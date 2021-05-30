<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BankAccountModel;
use App\Models\CreditCardModel;
use App\Models\TransactionModel;

class Wallet extends BaseController
{
    public function index()
    {
        $username= $this->session->get('username');
        $userModel=new UserModel();
        $userBalance=$userModel->getUserBalance($username);
        return view('wallet.php',['userBalance'=>$userBalance]);
    }
    
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
            $this->session->setFlashdata('transactionError', 'Ne pokušavajte da unosite ne validne vrednosti ili da menjate HTML kod! Ovakvi pokušaji mogu biti sankcionisani!');
            return redirect()->to(site_url("Wallet")); 
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
            return redirect()->to(site_url("Wallet"));
        }
        

        if($user_data['cardOwnerName']!=$creditCard->OwnerName){
            $this->session->setFlashdata('transactionError', 'Uneta osoba nije vlasnik kartice-netačno ime!');
            return redirect()->to(site_url("Wallet"));
        }
        if($user_data['cardOwnerSurname']!=$creditCard->OwnerSurname){
            $this->session->setFlashdata('transactionError', 'Uneta osoba nije vlasnik kartice-netačno prezime!');
            return redirect()->to(site_url("Wallet"));
        }
        if($user_data['cardExpirationDate']!=$creditCard->expirationDate){
            $this->session->setFlashdata('transactionError', 'Netačan datum isteka kartice!');
            return redirect()->to(site_url("Wallet"));
        }
        if($user_data['CVC']!=$creditCard->CVC){
            $this->session->setFlashdata('transactionError', 'Netačan CVC kod!');
            return redirect()->to(site_url("Wallet"));
        }
                
        $db = \Config\Database::connect();
        $db->transBegin();
        
        $bankAccountModel=new BankAccountModel();
        $user_data['balance']=$bankAccountModel->getBankAccountBalance($creditCard->BankAccountNumber);
        if($user_data['balance']<$user_data['paymentAmount']){
            $db->transRollback();
            $this->session->setFlashdata('transactionError', 'Nemate dovoljno sredstava!');
            return redirect()->to(site_url("Wallet"));
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
        }
        
        return redirect()->to(site_url("Wallet"));
    }
    
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
            $this->session->setFlashdata('transactionError', 'Ne pokušavajte da unosite ne validne vrednosti ili da menjate HTML kod! Ovakvi pokušaji mogu biti sankcionisani!');
            return redirect()->to(site_url("Wallet")); 
        }
            
        $user_data['withdrawalAmount']=floatval($this->request->getVar('amountInputFieldWithdraw'));
        $user_data['name']=$this->request->getVar('nameInputFieldWithdraw');
        $user_data['surname']=$this->request->getVar('surnameInputFieldWithdraw');
        $user_data['bankAccountNumber']=str_replace("-","",$this->request->getVar('bankAccountNumberInput'));
                
        $bankAccountModel=new BankAccountModel();
        $bankAccount=$bankAccountModel->find($user_data['bankAccountNumber']);
        if($bankAccount==null){
            $this->session->setFlashdata('transactionError', 'Broj računa ne postoji u banci, molimo Vas proverite broj računa!');
            return redirect()->to(site_url("Wallet"));
        }
        
        $db = \Config\Database::connect();
        $db->transBegin();
        
        $userModel=new UserModel();
        $user=$userModel->getUserByUserName($this->session->get('username'));
        
        if($user->balance<$user_data['withdrawalAmount']){
            $db->transRollback();
            $this->session->setFlashdata('transactionError', 'Ne pokušavajte da isplatite više nego što imate novca na računu! Ovakvi pokušaji mogu biti sankcionisani!');
            return redirect()->to(site_url("Wallet")); 
        }
        
        $this->createTransaction($user, $bankAccount, $user_data['withdrawalAmount'], 1, $bankAccountModel, $userModel);
        
        if ($db->transStatus() === FALSE){
            $db->transRollback();
            $this->session->setFlashdata('transactionError', 'Došlo je do greške prilikom izvršavanja transakcije! Pokušajte ponovo.');
        }
        else{
            $db->transCommit();
            $this->session->setFlashdata('transactionSuccess', 'Transakcija je uspešno izvršena!');
        }
        return redirect()->to(site_url("Wallet"));        
    }
    
    public function createTransaction($user,$bankAccount,$amount,$type, $bankAccountModel,$userModel){      
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
}
