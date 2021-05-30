<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BankAccount;
use App\Models\CreditCard;
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
        
        $user_data['paymentAmount']=floatval($this->request->getVar('amountInputFieldPayment'));
        $user_data['cardOwnerName']=$this->request->getVar('nameInputFieldPayment');
        $user_data['cardOwnerSurname']=$this->request->getVar('surnameInputFieldPayment');
        $user_data['cardNumber']=str_replace("-","",$this->request->getVar('creditCardNumberInput'));
        $user_data['cardExpirationDate']=$this->request->getVar('expirationDateInput');
        $user_data['CVC']=$this->request->getVar('CVC');
        
        /*$user_data['error']=date("Y-m-d H:i:s");
        return view('test.php',['data'=>$user_data]);*/
        
        
        $creditCardModel=new CreditCard();
        $creditCard=$creditCardModel->find($user_data['cardNumber']);
        
        if($creditCard==null){            
            $this->session->setFlashdata('transactionError', 'Kartica ne postoji!');
            return redirect()->to(site_url("Wallet"));
        }
        
        $bankAccountModel=new BankAccount();
        $user_data['balance']=$bankAccountModel->getBankAccountBalance($creditCard->BankAccountNumber);
        if($user_data['balance']<$user_data['paymentAmount']){
            $this->session->setFlashdata('transactionError', 'Nemate dovoljno sredstava!');
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
        
        $user_data['error']="Sve je uspešno!";
        
        $db = \Config\Database::connect();
        
        $db->transBegin();
        
        $bankAccount=$bankAccountModel->find($creditCard->BankAccountNumber);
        $bankAccountModel->update($bankAccount->BankAccountNumber,['balance'=>$bankAccount->balance-$user_data['paymentAmount']]);
        
        $userModel=new UserModel();
        $user=$userModel->getUserByUserName($this->session->get('username'));
        $userModel->update($user->IdUser,['balance'=>$user->balance+$user_data['paymentAmount']]);
        
        $transactionModel=new TransactionModel();
        $transactionTimestamp=date("Y-m-d H:i:s");
        $transactionModel->insert([
            'timestamp'=>$transactionTimestamp,
            'amount'=>$user_data['paymentAmount'],
            'IdUser'=>$user->IdUser,
            'type'=>0
        ]);
        if ($db->transStatus() === FALSE)
        {
            $db->transRollback();
        }
        else
        {
            $db->transCommit();
        }
        
        return redirect()->to(site_url("Wallet"));
    }
    
    private function setTransactionError($error){
        
    }
}
