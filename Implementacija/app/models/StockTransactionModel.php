<?php

namespace App\Models;
use CodeIgniter\Model;

class StockTransactionModel extends Model
{
    protected $table = 'stocktransaction';
    protected $primaryKey = 'IdStockTransaction';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['IdUser','IdStock','totalPrice','quantity','type','timestamp'];
    
}

