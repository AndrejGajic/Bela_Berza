<?php

namespace App\Controllers;

use App\Models\UserOwnsStockModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\StockTransactionModel;

class CollectionController extends BaseController
{
    public function index()
    {
        return view('collection.php');
    }
    
    public function sellStock(){
        
        if(!$this->validate([
            'stockName'=>'required',
            'stock-quantity'=>'required',
            'stock-quantity'=>'is_natural_no_zero'
        ])){
            
           $this->session->setFlashdata('sellingStockError', 'Molimo Vas da ispoštujete format unosa i da ne menjate HTML kod jer takva radnja može biti sankcionicana!');
           return redirect()->to(site_url("CollectionController"));
        }        
        
        $user_data['stockName']=$this->request->getVar('stockName');
        $user_data['quantity']= intval($this->request->getVar('stock-quantity'));
                
        $db = \Config\Database::connect();
        $db->transBegin();
        
        $userModel=new UserModel();
        $user=$userModel->getUserByUserName($this->session->get('username'));
        
        $stockModel=new StockModel();
        $stock=$stockModel->getStockByCompanyName($user_data['stockName']);
        
        if($stock==null){
            $db->transRollback();
            $this->session->setFlashdata('sellingStockError', 'Željena akcija trenutno nije u vašoj kolekciji! Molimo Vas da ne pokušavate nasilnu prodaju kroz promenu HTML koda jer takva radnja može biti sankcionicana!');
            return redirect()->to(site_url("CollectionController"));
        }
        
        $income= floatval($stock->value)*$user_data['quantity'];
        
        $userOwnsStockModel=new UserOwnsStockModel();
        $userOwnsStock=$userOwnsStockModel->find(['IdUser'=>$user->IdUser, 'IdStock'=>$stock->IdStock]);
        
        if($userOwnsStock==null ||intval($userOwnsStock->quantity)<$user_data['quantity'])
        {
            $db->transRollback();
            $this->session->setFlashdata('sellingStockError', 'Ne posedujete dovoljan broj akcija za prodaju!');
            return redirect()->to(site_url("CollectionController"));
        }
        
        $newQuantity=intval($userOwnsStock->quantity)-$user_data['quantity'];
        if($newQuantity==0){//delete
            $userOwnsStockModel->where(['IdUser'=> $user->IdUser,'IdStock'=>$stock->IdStock])->delete();
        }
        else{//just update
            $userOwnsStockModel->updateQuantity($user->IdUser, $stock->IdStock, $newQuantity);
        }
        
        $userModel->update($user->IdUser,['balance'=>$user->balance+$income]);
        

        //change available qty 
        $stockModel->update($stock->IdStock,['availableQty'=>$stock->availableQty+$user_data['quantity']]);
        
        //insert stock transaction data
        $stockTransactionModel=new StockTransactionModel();
        $stockTransactionModel->insert([
            'IdUser'=>$user->IdUser,
            'IdStock'=>$stock->IdStock,
            'totalPrice'=>$income,
            'quantity'=>$user_data['quantity'],
            'type'=>1,
            'timestamp'=>date("Y-m-d H:i:s")
        ]);
        
        
        if ($db->transStatus() === FALSE){
            $db->transRollback();
            $this->session->setFlashdata('sellingStockError', 'Prodaja nije uspela, molimo Vas pokušajte ponovo!');
            return redirect()->to(site_url("CollectionController"));
        }
        else{
            $db->transCommit();
        }
                
        $this->session->setFlashdata('sellingStockSuccess', 'Prodaja je uspešno okončana i sredstva na vašem računu su ažurirana!');
        return redirect()->to(site_url("CollectionController"));
    }
    
}
