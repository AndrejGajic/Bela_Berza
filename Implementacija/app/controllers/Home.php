<?php

namespace App\Controllers;
use App\Models\PrivilegedUserModel;
use App\Models\AdministratorModel;
use App\Models\UserModel;
use App\Models\RegistrationModel;
use App\Models\StockModel;
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
    /**
     * indeks funkcija koja prepoznaje tip prijavljenog korisnika
     * prosledjuje ga funkciji za prikaz pocetne stranice
     * Ovde se korisnik preusmerava nakon Logina
     */
    public function index($IdStock = 1)   
    {        
	    //$this->session->destroy();
	    //$this->session->set('userId',1);
        $this->session->set('username','petarpan');
        $this->session->set('img','https://pbs.twimg.com/profile_images/378800000072031509/d0790345cadb70017182dc27ca1b9ae1.png');
        //$this->session->destroy();

        $adminId = $this->session->get('adminId');

        //administrator je prijavljen
        if($adminId){
            return $this->showHomePage('admin');
        }

        $userId = $this->session->get('userId');

        //nijedan korisnik nije prijavljen
        if(!$userId){
            return $this->showHomePage('guest');
        }

        $privUserId = (new PrivilegedUserModel())->find($userId);

        //prijavljen je privilegovani korisnik
        if($privUserId){
            return $this->showHomePage('privileged');
        }

        //prijavljen je obican korisnik
        return $this->showHomePage('standard');
	}

	/**
	 * funkcija koja prikazuje glavnu stranicu sajta na razlicite nacine u zavisnosti
     * od tipa prijavljenog korisnika
     *
     * @param string $userType
	 */
    public function showHomePage($userType)
    {
        /*
         * showPromo oznacava da li ce biti prikazana reklamno dugme za privilegovanog korisnika
         * menu se koristi za razlicit prikaz navigacije za svaki tip korisnika (standard, priv, guest, admin)
         * assistantCLass oznacava da li ce biti omogucen asistent pri trgovini (locked i unlocked)
         * username ce sluziti za ispis u navigaciji, a u slucaju guesta je null
         */

        $username = $this->session->get('username');
        $imgPath = $this->session->get('img');

        if($userType=='standard'){
            $data=array('showPromo'=>true,'menu'=>'standard','assistantClass'=>'locked-assistant','username'=>$username,'imgPath'=>$imgPath);
        }

        else if($userType=='privileged'){
            $data=array('showPromo'=>false,'menu'=>'privileged','assistantClass'=>'unlocked-assistant','username'=>$username,'imgPath'=>$imgPath);
        }

        else if($userType=='guest'){
            $data=array('showPromo'=>true,'menu'=>'guest','assistantClass'=>'locked-assistant','username'=>$username,'imgPath'=>$imgPath);
        }

        else if($userType=='admin'){
            $data=array('showPromo'=>false,'menu'=>'admin','assistantClass'=>'admin-assistant','username'=>$username,'imgPath'=>$imgPath);
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
        
        
        $data = array_merge($data, $stockValues);
        $data = array_merge($data, $stockRates);
        $data = array_merge($data, $wrapper);

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
    
  
    public function setStockPrice(){
        //$stockName=$this->request->getVar('stockName');
        
        $db = \Config\Database::connect();
        $db->transBegin();
        
        //$stockModel=new StockModel();
        //$stock=$stockModel->getStockByCompanyName($stockName);
        
        /*if($stock==null){
            $db->transRollback();
            $this->session->setFlashdata('buyingStockError', 'Željena akcija trenutno nije u ponudi! Molimo Vas da ne pokušavate nasilnu kupovinu kroz promenu HTML koda jer takva radnja može biti sankcionicana!');
            return redirect()->to(site_url("Home"));
        }*/
        
        /*$curl = curl_init();
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://twelve-data1.p.rapidapi.com/quote?symbol=AMZN&interval=1day&outputsize=30&format=json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: twelve-data1.p.rapidapi.com",
                    "x-rapidapi-key: 4c9b48580dmsh1474e734b15ec04p1bf687jsn1b7cacdeea16"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
                echo "cURL Error #:" . $err;
        } else {
                echo $response;
        }
        */
        
        
        $stockName = "MSFT";
        
        $res = $this->getStockVolatility($stockName);
        
        echo "<script type='text/javascript'>alert('$res');</script>";

        //$this->session->setFlashdata('stockPriceFetched', 'Amazon costs ' + $response);
    }    
    
        
    public function getStockVolatility($stockName) {   

        $response = $this->getStockTimeData($stockName, "1day", "50");        
        $response = json_decode($response, true);

        $values = $response["values"];   
        
        $maxChange = 0;
        
        for ($i = 1; $i < 30; $i++) {
            $change = abs(floatval(floatval(($values[$i]["open"] - $values[$i - 1]["open"])) / floatval(($values[$i]["open"]  + $values[$i-1]["open"]))));
            if ($change > $maxChange) {
                $maxChange = $change;
            }
        }
        
        $response = $this->getStockInfo($stockName);
        $response = json_decode($response, true);
        $change_percent = floatval($response["percent_change"]);
        
        if (abs($change_percent) > $maxChange) {
            $maxChange = abs($change_percent);
        }
        
        return $maxChange;
    }
    
    public function getStockInfo($stockName) {
        
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt_array($curl, [
                CURLOPT_URL => "https://twelve-data1.p.rapidapi.com/quote?symbol=" . $stockName . "&interval=1day&outputsize=30&format=json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: twelve-data1.p.rapidapi.com",
                        "x-rapidapi-key: 596939d60emsh17dede5c0ed3951p18cf6djsn7674766f4fde"
                ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
                return "cURL Error #:" . $err;
        } else {
                return $response;
        }
    }
    
    public function getStockTimeData($stockName, $period, $outputSize) {
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt_array($curl, [
                CURLOPT_URL => ("https://twelve-data1.p.rapidapi.com/time_series?symbol=" . $stockName . "&interval=" . $period . "&outputsize=" . $outputSize . "&format=json"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: twelve-data1.p.rapidapi.com",
                        "x-rapidapi-key: 596939d60emsh17dede5c0ed3951p18cf6djsn7674766f4fde"
                ],
        ]);
        
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
                return $err;
        } else {
            
                return $response;
        }
    }
    
    public function setChartTarget($IdStock) {
        return redirect()->to(site_url("Home/index/$IdStock"));
    }
}
