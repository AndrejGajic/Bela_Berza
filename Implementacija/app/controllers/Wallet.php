<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BankAccount;
use App\Models\CreditCard;

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
        
        $user_data['paymentAmount']=$this->request->getVar('amountInputFieldPayment');
        $user_data['cardOwnerName']=$this->request->getVar('nameInputFieldPayment');
        $user_data['cardOwnerSurname']=$this->request->getVar('surnameInputFieldPayment');
        $user_data['cardNumber']=str_replace("-","",$this->request->getVar('creditCardNumberInput'));
        $user_data['cardExpirationDate']=$this->request->getVar('expirationDateInput');
        $user_data['CVC']=$this->request->getVar('CVC');
        
        
        $creditCardModel=new CreditCard();
        $creditCard=$creditCardModel->find($user_data['cardNumber']);
        
        if($creditCard==null){
            $user_data['error']="Kartica ne postoji!";
            return view('test.php',['data'=>$user_data]);
        }
        
        $bankAccountModel=new BankAccount();
        $user_data['balance']=$bankAccountModel->getBankAccountBalance($creditCard->BankAccountNumber);
        if($user_data['balance']<$user_data['paymentAmount']){
            $user_data['error']="Nemate dovoljno sredstava!";
            return view('test.php',['data'=>$user_data]);
        }
        if($user_data['cardOwnerName']!=$creditCard->OwnerName){
            $user_data['error']="Uneta osoba nije vlasnik kartice-netačno ime!";
            return view('test.php',['data'=>$user_data]);
        }
        if($user_data['cardOwnerSurname']!=$creditCard->OwnerSurname){
            $user_data['error']="Uneta osoba nije vlasnik kartice-netačno prezime!";
            return view('test.php',['data'=>$user_data]);
        }
        if($user_data['cardExpirationDate']!=$creditCard->expirationDate){
            $user_data['error']="Netačan datum isteka!";
            return view('test.php',['data'=>$user_data]);
        }
        if($user_data['CVC']!=$creditCard->CVC){
            $user_data['error']="Netačan CVC kod!";
            return view('test.php',['data'=>$user_data]);
        }
        
        $user_data['error']="Sve je uspešno!";
        
        $db = \Config\Database::connect();
        
        $db->transBegin();
        
        $bankAccount=$bankAccountModel->find($creditCard->BankAccountNumber);
        $bankAccountModel->update($bankAccount->BankAccountNumber,['balance'=>$bankAccount->balance-$user_data['paymentAmount']]);
        
        $userModel=new UserModel();
        $user=$userModel->getUserByUserName($this->session->get('username'));
        $userModel->update($user->IdUser,['balance'=>$user->balance+$user_data['paymentAmount']]);
        
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
}
