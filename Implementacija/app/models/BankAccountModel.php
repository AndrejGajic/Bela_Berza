<?php
namespace App\Models;
use CodeIgniter\Model;

class BankAccountModel extends Model
{
    protected $table = 'bankaccount';
    protected $primaryKey = 'BankAccountNumber';
    protected $returnType ='object';
    protected $allowedFields = ['balance'];
    

    public function getBankAccountBalance($bankAccountNumber){
        $account=$this->where('BankAccountNumber',$bankAccountNumber)->first();//or findAll is same, result must be one row
        return $account->balance;
    }
}