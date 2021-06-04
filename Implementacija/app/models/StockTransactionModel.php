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

    /*
     * Vraca sve transakcije ali sa imenom korisnika i imenom stocka umesto IdUser i IdStock
     */
    public function getAllTransactionsInformation($type=-1){
        if($type!=-1){
           $transactions = $this->where('type',$type)->findAll();
        }
        else {
            $transactions = $this->findAll();
        }

        $transactionsInfos = array();

        $userModel = new UserModel();
        $stockModel = new StockModel();

        foreach($transactions as $transaction){
            $username = $userModel->find($transaction->IdUser)->username;
            $stockName = $stockModel->find($transaction->IdStock)->companyName;
            $totalPrice = $transaction->totalPrice;
            $quantity =$transaction->quantity;
            $type = $transaction->type;
            $timestamp = $transaction->timestamp;

            $transactionInfo = ['username'=>$username,'stockName'=>$stockName,'totalPrice'=>$totalPrice,
                'quantity'=>$quantity,'type'=>$type,'timestamp'=>$timestamp];

            array_push($transactionsInfos,$transactionInfo);
        }

        return $transactionsInfos;
    }

}
