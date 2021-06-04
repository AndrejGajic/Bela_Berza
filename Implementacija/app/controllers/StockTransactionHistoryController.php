<?php

namespace App\Controllers;

use App\Models\RegistrationModel;
use App\Models\StockTransactionModel;
use App\Models\UserModel;

/** Kosta Matijevic 0034/2018 */

/**
 * StocKTransactionHistoryController – klasa za pregled istorije kupovine i prodaja akcija od strane administratora
 *
 * @version 1.0
 */

class StocKTransactionHistoryController extends BaseController
{
    /**
     * indeks funkcija koja treba da pozove funkciju za ucitavanje stranice za pregled transakcija akcija
     * ukoliko administrator nije prijavljen ova funkcija treba da uradi redirect na glavnu stranu
     */
    public function index()
    {
        if(!$this->session->get('IdAdministrator')){
            return redirect()->to('/home');
        }
        return $this->showTransactionsPage();
    }

    /**
     * funkcija koja prikazuje stranicu za potvrdu registracija
     * sa ucitanim svim potrebnim podacima
     */
    public function showTransactionsPage()
    {

        $transactionType = $this->session->get('transactionType');
        //return view('test.php',array('data'=>$transactionType));
        $transactionInfos = (new StockTransactionModel())->getAllTransactionsInformation($transactionType);
        $error=null;
        if(count($transactionInfos)==0){
            $error='Nema nijedne transakcije u istoriji';
        }
        $username = $this->session->get('username');
        $imgPath = $this->session->get('imagePath');

        $data = array('infos'=>$transactionInfos, 'username'=>$username, 'imgPath'=>$imgPath, 'error'=>$error);
        return view('stockTransactions.php',$data);
    }


    /**
     * funkcija koja filtrira da li se prikazuju samo kupovine prodaje ili oba
     */
    public function filterTransactions()
    {
        //return view('test.php',array('data'=>1));
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("WalletController"));
        }
        $selectedType = $this->request->getVar("tipTransakcije");
        if($selectedType == "sve") $this->transactionType = -1;
        else if($selectedType == "kupovine") $this->transactionType = 0;
        else $this->transactionType = 1;
        $this->session->set("transactionType", $this->transactionType);
        return redirect()->to(site_url("StockTransactionHistoryController"));
    }

}




