<?php

namespace App\Controllers;
use App\Models\PrivilegedUserModel;
use App\Models\AdministratorModel;
use App\Models\UserModel;
use App\Models\RegistrationModel;
use App\Models\StockModel;
use App\Models\UserOwnsStockModel;

/** Kosta Matijevic 0034/2018 */

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
	    $this->session->set('userId',3);
        $this->session->set('adminId',1);
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

        return view('index.php',$data);
    }
}
