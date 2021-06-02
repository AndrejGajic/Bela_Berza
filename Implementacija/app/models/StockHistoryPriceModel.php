<?php

namespace App\Models;
use CodeIgniter\Model;

class StockHistoryPriceModel extends Model
{
    protected $table = 'stockhistoryprice';
    protected $primaryKey = 'IdStockHistoryPrice';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['IdStock','timestamp','price'];
    
}

