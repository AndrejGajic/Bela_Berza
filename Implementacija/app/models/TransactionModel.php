<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'IdTransaction';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['timestamp','amount','IdUser','type'];
    
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




