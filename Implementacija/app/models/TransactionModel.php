<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'IdTransaction';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['timestamp','amount','idUser','type'];
}


