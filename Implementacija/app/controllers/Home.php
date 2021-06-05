<?php

namespace App\Controllers;
use App\Models\PrivilegedUserModel;
use App\Models\AdministratorModel;
use App\Models\StockTransactionModel;
use App\Models\UserModel;
use App\Models\RegistrationModel;
use App\Models\StockModel;
use App\Models\StockHistoryPriceModel;
use App\Models\UserOwnsStockModel;

/** 
 * Kosta Matijevic 0034/2018 
 * Luka Tomanović 0410/2018 ->buy stock

 * Uros Stankovic 0270/2018 -> displaying stock prices & api calls

*/

/**
 * Home – klasa za kontroler glavne stranice
 *
 * @version 1.0
 */


class Home extends BaseController
{
    
    static $apiKeys = array("4c9b48580dmsh1474e734b15ec04p1bf687jsn1b7cacdeea16", "596939d60emsh17dede5c0ed3951p18cf6djsn7674766f4fde", "25161a4a2fmsh56563b0f98b3758p197dd6jsn6b84d98ba669", "f1e65fcca9mshafcbddf30bc160fp1a7e14jsna37a277cf664", "11df2299eemshed10c079dedf5a7p1d29e6jsn059e2f0c2060");
    
    /**
     * indeks funkcija koja prepoznaje tip prijavljenog korisnika
     * prosledjuje ga funkciji za prikaz pocetne stranice
     * Ovde se korisnik preusmerava nakon Logina
     */
    public function index($IdStock = 1)   
    {        
	    //$this->session->destroy();
        //$this->session->destroy();
        //$this->session->set('IdAdministrator',1);

        $adminId = $this->session->get('IdAdministrator');

        //administrator je prijavljen
        if($adminId){
            return $this->showHomePage('admin', $IdStock);
        }

        $userId = $this->session->get('IdUser');

        //nijedan korisnik nije prijavljen
        if(!$userId){
            return $this->showHomePage('guest', $IdStock);
        }

        $privUserId = (new PrivilegedUserModel())->find($userId);

        //prijavljen je privilegovani korisnik
        if($privUserId){
            return $this->showHomePage('privileged', $IdStock);
        }

        //prijavljen je obican korisnik
        return $this->showHomePage('standard', $IdStock);
	}

	/**
	 * funkcija koja prikazuje glavnu stranicu sajta na razlicite nacine u zavisnosti
     * od tipa prijavljenog korisnika
     *
     * @param string $userType
	 */
    public function showHomePage($userType, $IdStock = 1)
    {
        /*
         * showPromo oznacava da li ce biti prikazana reklamno dugme za privilegovanog korisnika
         * menu se koristi za razlicit prikaz navigacije za svaki tip korisnika (standard, priv, guest, admin)
         * assistantCLass oznacava da li ce biti omogucen asistent pri trgovini (locked i unlocked)
         * username ce sluziti za ispis u navigaciji, a u slucaju guesta je null
         */

        $imgPath = $this->session->get('imagePath');
        $name = $this->session->get('name');
        $surname = $this->session->get('surname');

        if($userType=='standard'){
            $data=array('showPromo'=>true,'menu'=>'standard','assistantClass'=>'locked-assistant','name'=>$name,'surname'=>$surname,'imgPath'=>$imgPath);
        }

        else if($userType=='privileged'){
            $data=array('showPromo'=>false,'menu'=>'privileged','assistantClass'=>'unlocked-assistant','name'=>$name,'surname'=>$surname,'imgPath'=>$imgPath);
        }

        else if($userType=='guest'){
            $data=array('showPromo'=>true,'menu'=>'guest','assistantClass'=>'locked-assistant','name'=>$name,'surname'=>$surname,'imgPath'=>$imgPath);
        }

        else if($userType=='admin'){
            $data=array('showPromo'=>false,'menu'=>'admin','assistantClass'=>'admin-assistant','name'=>$name,'surname'=>$surname,'imgPath'=>$imgPath);
        }
        
        
        $stockModel = new StockModel();
        
        $stockValues = array('MSFT'=>$stockModel->getStockValue("MSFT")[0]->value, 
                'AAPL'=>$stockModel->getStockValue("AAPL")[0]->value,
                'AMZN'=>$stockModel->getStockValue("AMZN")[0]->value,
                'GOOGL'=>$stockModel->getStockValue("GOOGL")[0]->value,
                'FB'=>$stockModel->getStockValue("FB")[0]->value,
                'UBER'=>$stockModel->getStockValue("UBER")[0]->value,
                'INTC'=>$stockModel->getStockValue("INTC")[0]->value,
                'TSLA'=>$stockModel->getStockValue("TSLA")[0]->value,
                'BAMXF'=>$stockModel->getStockValue("BAMXF")[0]->value,
                'MCD'=>$stockModel->getStockValue("MCD")[0]->value,
                'SSNLF'=>$stockModel->getStockValue("SSNLF")[0]->value,
                'XIACF'=>$stockModel->getStockValue("XIACF")[0]->value);
        
        $stockRates = array('MSFTR'=>$stockModel->getStockRate("MSFT")[0]->rate, 
                'AAPLR'=>$stockModel->getStockRate("AAPL")[0]->rate,
                'AMZNR'=>$stockModel->getStockRate("AMZN")[0]->rate,
                'GOOGLR'=>$stockModel->getStockRate("GOOGL")[0]->rate,
                'FBR'=>$stockModel->getStockRate("FB")[0]->rate,
                'UBERR'=>$stockModel->getStockRate("UBER")[0]->rate,
                'INTCR'=>$stockModel->getStockRate("INTC")[0]->rate,
                'TSLAR'=>$stockModel->getStockRate("TSLA")[0]->rate,
                'BAMXFR'=>$stockModel->getStockRate("BAMXF")[0]->rate,
                'MCDR'=>$stockModel->getStockRate("MCD")[0]->rate,
                'SSNLFR'=>$stockModel->getStockRate("SSNLF")[0]->rate,
                'XIACFR'=>$stockModel->getStockRate("XIACF")[0]->rate);
        
        $wrapper = array('volatileStocks'=>$volatileStocks = $stockModel->getVolatileStocks());
        
        /*$stockHistoryPriceModel = new StockHistoryPriceModel();
        $coordinatesResult = $stockHistoryPriceModel->getCoordinates($IdStock);
        $coordinates = array();
        
        $res = var_dump($coordinatesResult);
        
        echo "<script type='text/javascript'>alert('$res');</script>";
           
        $cnt = 0;
        
        foreach($coordinatesResult as $coordinateXY) {
            echo "<script type='text/javascript'>alert('$coordinateXY->price');</script>";
            $coordinates["x" . $cnt] = $coordinateXY->timestamp;
            $coordinates["y" . $cnt] = $coordinateXY->timestamp;
        }*/
        
        $data = array_merge($data, $stockValues);
        $data = array_merge($data, $stockRates);
        $data = array_merge($data, $wrapper);
        /*$data["coordinates"] = $coordinates;*/

        return view('index.php',$data);
    }
    
    /**
	* buyStock - funkcija koja se koristi za funkcionalnost kupovine akcija
        * 
     */
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
        
        //akcije vise nisu dostupne za prodaju
        $stockModel->update($stock->IdStock,['availableQty'=>$stock->availableQty-$user_data['quantity']]);
        
        
        //insert stock transaction data
        $stockTransactionModel=new StockTransactionModel();
        $stockTransactionModel->insert([
            'IdUser'=>$user->IdUser,
            'IdStock'=>$stock->IdStock,
            'totalPrice'=>$stockPrice,
            'quantity'=>$user_data['quantity'],
            'type'=>0,
            'timestamp'=>date("Y-m-d H:i:s")
        ]);

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            $this->session->setFlashdata('buyingStockError', 'Kupovina akcija nije uspela, molimo Vas pokušajte ponovo!');
            return redirect()->to(site_url("Home"));
        } else {
            $db->transCommit();
        }

        $this->session->setFlashdata('buyingStockSuccess', 'Kupovina je uspešno okončana, akcije su dodate u kolekciju i sredstva na vašem računu su ažurirana!');
        return redirect()->to(site_url("Home"));
    }
        

    
    public function setChartTarget($IdStock) {
        return redirect()->to(site_url("Home/index/$IdStock"));
    }

}
