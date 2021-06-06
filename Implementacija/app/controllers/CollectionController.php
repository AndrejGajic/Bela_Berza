<?php

namespace App\Controllers;
/**
 * @author Luka Tomanović 0410/2018
 * Kosta Matijevic 0034/2018
 *
 * CollectionController - klasa za prikaz riznice akcija i njihovu prodaju
 */

use App\Models\PrivilegedUserModel;
use App\Models\StockTransactionModel;
use App\Models\UserOwnsStockModel;
use App\Models\UserModel;
use App\Models\StockModel;


class CollectionController extends BaseController
{
    /**
     * Indeks funkcija sluzi da spreci neprijavljenog korisnika ili administratora da pristupe stranici
     * i da pozove funkciju za prikaz riznice za prijavljenog korisnika
     */
    public function index()
    {
        $IdUser = $this->session->get('IdUser');
        if(!$IdUser){
            return redirect()->to('/login');
        }
        if($this->session->get('IdAdministrator')){
            return redirect()->to('/home');
        }
        return $this->showCollectionPage($IdUser);
    }


    /**
     * funkcija za prikaz riznice prijavljenog korisnika
     * @param int $IdUser
     *
     */
    public function showCollectionPage($IdUser)
    {

        $imgPath = $this->session->get('imagePath');
        $name = $this->session->get('name');
        $surname = $this->session->get('surname');

        if((new PrivilegedUserModel())->find($IdUser)){
            $menu='privileged';
        }else{
            $menu='standard';
        }

        //dohvata samo IdStock i kolicinu koju korisnik poseduje
        $myStocksList = (new UserOwnsStockModel())->findStocksForUser($IdUser);

        $stockModel = new StockModel();

        $stocks = array();
        foreach($myStocksList as $myStock){
            $stock =  $stockModel->find($myStock->IdStock);
            $stock->availableQty = $myStock->quantity;

            //u stock se nalaze sve informacije o akciji + kolicina koju korisnik poseduje
            array_push($stocks,$stock);
        }

        //stranici ce biti prosledjeni neophodni podaci za ispis navigacije kao i lista svih akcija korisnika
        $data = array('name'=>$name,'surname'=>$surname,'imgPath'=>$imgPath,  'menu'=>$menu, 'stocks'=>$stocks,);

        return view('collection.php',$data);
    }

    /**
     * funkcija za kupovinu akcija na berzi
     * 
     * @author Luka Tomanović
    */
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
