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
	public function index()
	{
	    //$this->session->destroy();
	    $this->session->set('IdUser',1);
        //$this->session->destroy();
	   // $this->session->set('IdAdministrator',1);
        $this->session->set('username','ppan');
        $this->session->set('imagePath','https://pbs.twimg.com/profile_images/378800000072031509/d0790345cadb70017182dc27ca1b9ae1.png');
        $this->session->set('name','Petar');
        $this->session->set('surname','Pan');
        //$this->session->destroy();

        $adminId = $this->session->get('IdAdministrator');

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
}
