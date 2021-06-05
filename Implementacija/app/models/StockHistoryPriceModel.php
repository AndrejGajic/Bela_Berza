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
    

    public function clearTable() {
        $db = \Config\Database::connect();
        $sql = "delete from stockhistoryprice";
        $res = $db->query($sql);
    }
    
    public function getCoordinates(int $IdStock) {
        $db = \Config\Database::connect();
        $sql = "select timestamp, price from stockhistoryprice";
        $res = $db->query($sql, [$IdStock]);
        return $res;
    }
    
}
