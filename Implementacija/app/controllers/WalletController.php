<?php

namespace App\Controllers;


use App\Models\PrivilegedUserModel;
use App\Models\StockTransactionModel;
use App\Models\UserModel;
use App\Models\BankAccountModel;
use App\Models\CreditCardModel;
use App\Models\TransactionModel;

/** 
 * 
 * Andrej Gajic 0303/2018 -> index, getTransactions, filter, getUserActions, filterActions
*/


/**
* WalletController – klasa kontrolera za rad sa transakcijama
*
* @version 1.0
* @author Luka Tomanović 0410/2018 ->index,payment,withdraw,createTransaction
*/

class WalletController extends BaseController
{

    
    private $transactionType = 0; // koristi se za ispis uplata i isplata (ako je 0 ispisuju se i uplate i isplate; ako je 1 samo uplate; ako je 2 samo isplate
    private $actionType = 0; // koristi se za ispis kupljenih i prodatih akcija (ako je 0 sve se ispisuje, ako je 1 ispisuju se kupljene akcije, ako je 2 ispisuju se prodate akcije
    
    /**
    * index funkcija koja se koristi pri prikazu stranice moj novčanik
    */
    public function index()
    {
        if($this->session->get("transactionType")) {
            $this->transactionType = $this->session->get("transactionType");
        }
        else {
            $this->transactionType = 0;
        }
        if($this->session->get("actionType")) {
            $this->actionType = $this->session->get("actionType");
        }
        else {
            $this->actionType = 0;
        }

        $userId = $this->session->get('IdUser');
        if(!$userId){
            return redirect()->to('/login');
        }
        if($this->session->get('IdAdministrator')){
            return redirect()->to('/home');
        }
        $privUserId = (new PrivilegedUserModel())->find($userId);

        //prijavljen je privilegovani korisnik
        if($privUserId){
            $menu='privileged';
        }
        else{
            $menu='standard';
        }
        $img = $this->session->get('imagePath');
        $name = $this->session->get('name');
        $surname = $this->session->get('surname');
        $username= $this->session->get('username');
        $userModel=new UserModel();
        $transactions=$this->getTransactions();
        $actions = $this->getUserActions();

        $userBalance=$userModel->getUserBalance($username);
        return view('wallet.php',['userBalance'=>$userBalance,'menu'=>$menu,'imgPath'=>$img,'name'=>$name,'surname'=>$surname,
            'transactions'=>$transactions, 'actions'=>$actions]);
    }
    
    /**
     * payment funkcija koja se koristi pri uplati novca na račun korisnika veb aplikacije
     * @author Luka Tomanović
    */
    public function payment(){
        if(!$this->validate([
            'amountInputFieldPayment'=>'required',
            'amountInputFieldPayment'=>'decimal',
            'amountInputFieldPayment'=>'greater_than[0]',
            'nameInputFieldPayment'=>'required',
            'surnameInputFieldPayment'=>'required',
            'creditCardNumberInput'=>'required',
            'creditCardNumberInput'=>'regex_match[/[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}/]',
            'expirationDateInput'=>'valid_date[Y-m]',
            'CVC'=>'required',
            'CVC'=>'regex_match[/[0-9]{3}/]'
        ])){
            $this->session->setFlashdata('transactionError', 'Ne pokušavajte da unosite nevalidne vrednosti ili da menjate HTML kod! Ovakvi pokušaji mogu biti sankcionisani!');
            return redirect()->to(site_url("WalletController")); 
        }
                
        $user_data['paymentAmount']=floatval($this->request->getVar('amountInputFieldPayment'));
        $user_data['cardOwnerName']=$this->request->getVar('nameInputFieldPayment');
        $user_data['cardOwnerSurname']=$this->request->getVar('surnameInputFieldPayment');
        $user_data['cardNumber']=str_replace("-","",$this->request->getVar('creditCardNumberInput'));
        $user_data['cardExpirationDate']=$this->request->getVar('expirationDateInput');
        $user_data['CVC']=$this->request->getVar('CVC');
                
        $creditCardModel=new CreditCardModel();
        $creditCard=$creditCardModel->find($user_data['cardNumber']);
        
        if($creditCard==null){            
            $this->session->setFlashdata('transactionError', 'Kartica ne postoji!');
            return redirect()->to(site_url("WalletController"));
        }
        

        if($user_data['cardOwnerName']!=$creditCard->OwnerName){
            $this->session->setFlashdata('transactionError', 'Uneta osoba nije vlasnik kartice-netačno ime!');
            return redirect()->to(site_url("WalletController"));
        }
        if($user_data['cardOwnerSurname']!=$creditCard->OwnerSurname){
            $this->session->setFlashdata('transactionError', 'Uneta osoba nije vlasnik kartice-netačno prezime!');
            return redirect()->to(site_url("WalletController"));
        }
        if($user_data['cardExpirationDate']!=$creditCard->expirationDate){
            $this->session->setFlashdata('transactionError', 'Netačan datum isteka kartice!');
            return redirect()->to(site_url("WalletController"));
        }
        if($user_data['CVC']!=$creditCard->CVC){
            $this->session->setFlashdata('transactionError', 'Netačan CVC kod!');
            return redirect()->to(site_url("WalletController"));
        }
                
        $db = \Config\Database::connect();
        $db->transBegin();
        
        $bankAccountModel=new BankAccountModel();
        $user_data['balance']=$bankAccountModel->getBankAccountBalance($creditCard->BankAccountNumber);
        if($user_data['balance']<$user_data['paymentAmount']){
            $db->transRollback();
            $this->session->setFlashdata('transactionError', 'Nemate dovoljno sredstava!');
            return redirect()->to(site_url("WalletController"));
        }
        
        $bankAccount=$bankAccountModel->find($creditCard->BankAccountNumber);
        $userModel=new UserModel();

        $user=$userModel->getUserByUserName($this->session->get('username'));
        
        $this->createTransaction($user, $bankAccount, $user_data['paymentAmount'], 0, $bankAccountModel, $userModel);
        
        if ($db->transStatus() === FALSE){
            $db->transRollback();
        }
        else{
            $db->transCommit();
            $this->session->setFlashdata('transactionSuccess', 'Uplata je uspešno izvršena!');
        }
        
        return redirect()->to(site_url("WalletController"));
    }
    
    /**
     * withdraw funkcija koja se koristi pri isplati novca na račun korisnika veb aplikacije
     * @author Luka Tomanović
    */
    public function withdraw(){
        
        if(!$this->validate([
            'amountInputFieldWithdraw'=>'required',
            'amountInputFieldWithdraw'=>'decimal',
            'amountInputFieldWithdraw'=>'greater_than[0]',
            'nameInputFieldWithdraw'=>'required',
            'surnameInputFieldWithdraw'=>'required',
            'bankAccountNumberInput'=>'required',
            'bankAccountNumberInput'=>'regex_match[/[0-9]{3}-[0-9]{13}-[0-9]{2}/]'
        ])){
            $this->session->setFlashdata('transactionError', 'Ne pokušavajte da unosite nevalidne vrednosti ili da menjate HTML kod! Ovakvi pokušaji mogu biti sankcionisani!');
            return redirect()->to(site_url("WalletController")); 
        }
            
        $user_data['withdrawalAmount']=floatval($this->request->getVar('amountInputFieldWithdraw'));
        $user_data['name']=$this->request->getVar('nameInputFieldWithdraw');
        $user_data['surname']=$this->request->getVar('surnameInputFieldWithdraw');
        $user_data['bankAccountNumber']=str_replace("-","",$this->request->getVar('bankAccountNumberInput'));
                
        $bankAccountModel=new BankAccountModel();
        $bankAccount=$bankAccountModel->find($user_data['bankAccountNumber']);
        if($bankAccount==null){
            $this->session->setFlashdata('transactionError', 'Broj računa ne postoji u banci, molimo Vas proverite broj računa!');
            return redirect()->to(site_url("WalletController"));
        }
        
        $db = \Config\Database::connect();
        $db->transBegin();
        
        $userModel=new UserModel();
        $user=$userModel->getUserByUserName($this->session->get('username'));
        
        if($user->balance<$user_data['withdrawalAmount']){
            $db->transRollback();
            $this->session->setFlashdata('transactionError', 'Ne pokušavajte da isplatite više nego što imate novca na računu! Ovakvi pokušaji mogu biti sankcionisani!');
            return redirect()->to(site_url("WalletController")); 
        }
        
        $this->createTransaction($user, $bankAccount, $user_data['withdrawalAmount'], 1, $bankAccountModel, $userModel);
        
        if ($db->transStatus() === FALSE){
            $db->transRollback();
            $this->session->setFlashdata('transactionError', 'Došlo je do greške prilikom izvršavanja transakcije! Pokušajte ponovo.');
        }
        else{
            $db->transCommit();
            $this->session->setFlashdata('transactionSuccess', 'Isplata je uspešno izvršena!');
        }
        return redirect()->to(site_url("WalletController"));        
    }
    
    /**
     * createTransaction funkcija koja koristi interno za evidentiranje transakcije u bazi
     *
     * @param User $user 
     * @param BankAccount $bankAccount 
     * @param float $amount 
     * @param int $type 
     * @param BankAccountModel $bankAccountModel 
     * @param UserModel $user 
     * 
     * @author Luka Tomanović 
    */
    private function createTransaction($user,$bankAccount,$amount,$type, $bankAccountModel,$userModel){      
        if($type==1){
            $amount=$amount*(-1);
        }
        $bankAccountModel->update($bankAccount->BankAccountNumber,['balance'=>$bankAccount->balance-$amount]);
        $userModel->update($user->IdUser,['balance'=>$user->balance+$amount]);
        
        
        if($type==1){
            $amount=$amount*(-1);//reverse
        }
        $transactionModel=new TransactionModel();
        $transactionTimestamp=date("Y-m-d H:i:s");
        $transactionModel->insert([
            'timestamp'=>$transactionTimestamp,
            'amount'=>$amount,
            'IdUser'=>$user->IdUser,
            'type'=>$type
        ]);
    }

    /**
     * Funckija za prikaz uplata na racun i isplata sa racuna korisnika
     */
    private function getTransactions() {
        $transactionModel = new TransactionModel();
        if($this->transactionType == 0) {
            $transactions = $transactionModel->getTransactionsByUserId($this->session->get("IdUser"));
        }
        else if($this->transactionType == 1) {
            $transactions = $transactionModel->getTransactionsByUserIdAndType($this->session->get("IdUser"), 0);
        }
        else {
            $transactions = $transactionModel->getTransactionsByUserIdAndType($this->session->get("IdUser"), 1);
        }
        return $transactions;
    }

    /**
     * Funkcija za filtriranje prikaza uplata i isplata korisnika
     */
    public function filter() {
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("WalletController"));
        }
        $selectedType = $this->request->getVar("tip");
        if($selectedType == "sve") $this->transactionType = 0;
        else if($selectedType == "uplate") $this->transactionType = 1;
        else $this->transactionType = 2;
        $this->session->set("transactionType", $this->transactionType);
        return redirect()->to(site_url("WalletController"));
    }


    /**
     * Funckija za dohvatanje svih akcija koje je korisnik kupio, odnosno prodao na svom nalogu
     */
    private function getUserActions() {
        $stockTransactionModel = new StockTransactionModel();
        if($this->actionType == 0) {
            $actions = $stockTransactionModel->getTransactionsByUserId($this->session->get("IdUser"));
        }
        else if($this->actionType == 1) {
            $actions = $stockTransactionModel->getTransactionsByUserIdAndType($this->session->get("IdUser"), 0);
        }
        else {
            $actions = $stockTransactionModel->getTransactionsByUserIdAndType($this->session->get("IdUser"), 1);
        }
        return $actions;
    }

    /**
     * Funkcija za filtriranje prikaza kupljenih, odnosno prodatih akcija korisnika
     */
    public function filterActions() {
        
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("WalletController"));
        }
        $selectedType = $this->request->getVar("tipAkcije");
        if($selectedType == "sve") $this->actionType = 0;
        else if($selectedType == "kupovine") $this->actionType = 1;
        else $this->actionType = 2;
        $this->session->set("actionType", $this->actionType);
        return redirect()->to(site_url("WalletController"));

    }
}
