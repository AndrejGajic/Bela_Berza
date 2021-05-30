<?php
namespace App\Models;
use CodeIgniter\Model;

class CreditCard extends Model
{
    protected $table = 'creditcard';
    protected $primaryKey = 'CreditCardNumber';
    protected $returnType ='object';
    protected $allowedFields = ['BankAccountNumber','OwnerName','OwnerSurname','CVC','expirationDate'];

}