<?php

namespace App\Controllers;

use App\Models\UserOwnsStockModel;
use App\Models\UserModel;
use App\Models\StockModel;

class Home extends BaseController
{
    public function index()
    {
	return view('index.php');
    }
    
    public function buyStock(){
        if(!$this->validate([
            'stockName'=>'required',
            'stock-quantity'=>'required',
            'stock-quantity'=>'is_natural_no_zero'
        ])){
           $this->session->setFlashdata('buyingStockError', 'Molimo Vas da ispoštujete format unosa i da ne menjate HTML kod jer takva radnja može biti sankcionicana!');
           return redirect()->to(site_url("Home"));
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
            $this->session->setFlashdata('buyingStockError', 'Željena akcija trenutno nije u ponudi! Molimo Vas da ne pokušavate nasilnu kupovinu kroz promenu HTML koda jer takva radnja može biti sankcionicana!');
            return redirect()->to(site_url("Home"));
        }
        
        if(intval($stock->availableQty)< $user_data['quantity']){
            $db->transRollback();
            $this->session->setFlashdata('buyingStockError', 'Nažalost, nije moguće kupiti zahtevani broj akcija. Proverite njihovu trenutnu dostupnost i pokušajte ponovo.');
            return redirect()->to(site_url("Home"));
        }
        
        $stockPrice= floatval($stock->value)*$user_data['quantity'];
        
        if(floatval($user->balance)<$stockPrice){
            $db->transRollback();
            $this->session->setFlashdata('buyingStockError', 'Nemate dovoljno sredstava na računu!');
            return redirect()->to(site_url("Home"));
        }
        
        //ubacivanje u kolekciju akcija
        $userOwnsStockModel=new UserOwnsStockModel();
        $userOwnsStock=$userOwnsStockModel->find(['IdUser'=>$user->IdUser,
                                     'IdStock'=>$stock->IdStock]);
        if($userOwnsStock==null){
            $userOwnsStockModel->insert(['IdUser'=>$user->IdUser,
                                         'IdStock'=>$stock->IdStock,
                                         'quantity'=>$user_data['quantity']]);
        }else{
            $newQuantity=intval($userOwnsStock->quantity)+$user_data['quantity'];
            $userOwnsStockModel->updateQuantity($user->IdUser, $stock->IdStock, $newQuantity);
        }
        
        //skidanje para sa racuna
        $userModel->update($user->IdUser,['balance'=>$user->balance-$stockPrice]);
        
        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            $this->session->setFlashdata('buyingStockError', 'Kupovina akcija nije uspela, molimo Vas pokušajte ponovo!');
            return redirect()->to(site_url("Home"));
        } else {
            $db->transCommit();
        }
        
        //akcije vise nisu dostupne za prodaju
        $stockModel->update($stock->IdStock,['availableQty'=>$stock->availableQty-$user_data['quantity']]);
        
        $this->session->setFlashdata('buyingStockSuccess', 'Kupovina je uspešno okončana, akcije su dodate u kolekciju i sredstva na vašem računu su ažurirana!');
        return redirect()->to(site_url("Home"));
    }
}
