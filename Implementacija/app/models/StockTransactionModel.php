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
    
    //type 0-buying, 1-selling
    
    public function getTransactionsByUserId($userId) {
        return $this->where("IdUser", $userId)->findAll();
    }
    
    public function getTransactionsByUserIdAndType($userId, $type) {
        $tempArray = [
          "IdUser" => $userId,
          "type" => $type
        ];
        return $this->where($tempArray)->findAll();
    }
    
}

