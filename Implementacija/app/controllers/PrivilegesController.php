<?php

namespace App\Controllers;


use App\Models\PrivilegedUserModel;
use App\Models\UserModel;

/** Kosta Matijevic 0034/2018 */

/**
 * PrivilegesController – klasa za prelazak korisnika u privilegovanog korisnika
 *
 * @version 1.1
 */

class PrivilegesController extends BaseController
{

    /**
     * Konstanta koja oznacava cenu prelaska u privilegovanog korisnika
     */
    const PRIVILEGES_PRICE = 30.00;

    /**
     * indeks funkcija koja prepoznaje tip prijavljenog korisnika
     * prosledjuje ga funkciji za prikaz stranice za privilegije
     */
    public function index()
    {
        //$this->removeExpiredPrivileges();

        $userId = $this->session->get('userId');

        //nijedan korisnik nije prijavljen
        if(!$userId){
            return $this->showPrivilegesPage('guest');
        }

        $privUserId = (new PrivilegedUserModel())->find($userId);

        //prijavljen je privilegovani korisnik
        if($privUserId){
            return $this->showPrivilegesPage('privileged');
        }

        //prijavljen je obican korisnik
        return $this->showPrivilegesPage('standard');

    }

    /**
     *
     * Funkcija koja u zavisnosti od tipa korisnika koji je ulogovan na razliciti nacin ucitava stranicu
     * za privilegije, a u slucaju gosta preusmerava ga na stranicu za Login.
     *
     * @param string $userType
     *
     */

    public function showPrivilegesPage($userType)
    {
        //admin - preusmeren na pocetnu stranu (ne bi trebalo da moze da se desi da admin dodje ovde)
        if($userType=='admin'){
            return redirect()->to('/home');
        }

        //gost - preusmeren na login stranicu
        if($userType=='guest') {
            return redirect()->to('/login');
        }

        else if($userType=='standard'){
            $class = 'no-priv-status';
            $msg = 'TRENUTNO NEMATE PRIVILEGIJE';
            $showBtn = true;
            $menu='standard';


            //ima dovoljno para za privilegije
            if($this->checkMoney()) {
                $modal = '#paymentModal';
            }

            //nema dovoljno para za privilegije
            else{
                $modal = '#error';
            }
        }

        else if($userType=='privileged'){
            $class = 'yes-priv-status';
            $showBtn = false;
            $modal = '';
            $menu='privileged';

            //novi privilegovani korisnik
            if($this->session->getFlashdata('newPriv')==1){
                $msg = 'CESTITAMO, POSTALI STE PRIVILEGOVANI KORISNIK';
            }

            //stari privilegovani korisnik
            else {
                $msg = 'VEC STE PRIVILEGOVANI KORISNIK';
            }
        }

        $username = $this->session->get('username');
        $imgPath = $this->session->get('imagePath');
        $name = $this->session->get('name');
        $surname = $this->session->get('surname');

        $data = array('class'=>$class,'msg'=>$msg,'showBtn'=>$showBtn,
            'modal'=>$modal, 'menu'=>$menu, 'username'=>$username,'imgPath'=>$imgPath,'name'=>$name,'surname'=>$surname);
        return view('privileges.php',$data);
    }


    /**
     * Funkcija koja proverava da li korisnik ima dovoljno novca za prelazak u privilegovani rezim
     */
    public function checkMoney()
    {
        $balance = (new UserModel())->find($this->session->get('userId'))->balance;
        if($balance<self::PRIVILEGES_PRICE){
            return false;
        }
        return true;
    }


    /**
     * Funkcija koja azurira stanje u bazi u slucaju uspesne transakcije
     * i obavestava korisnika da je upravo dobio privilegije
     */
    public function grantPrivileges()
    {
        $userId = $this->session->get('userId');
        $privModel = new PrivilegedUserModel();

        //racuna datum pocetka i kraja privilegije
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+1 year'));

        //upisuje korisnika u privilegovane korisnike
        $data = array('startDate' => $startDate, 'endDate'=>$endDate, 'IdUser'=>$userId);
        $privModel->save($data);

        //azurira stanje na racunu korisnika
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $user->balance-=30.00;
        $userModel->save($user);

        //vraca korisnika na pocetnu stranicu za privilegije sa specijalnim statusom da je tek dobio privilegije
        $this->session->setFlashdata('newPriv',1);
        return redirect()->to(site_url('privilegescontroller'));
    }


    /**
     * Funkcija koja proverava datume u bazi i oduzima privilegije svim korisnicima kojima je istekla pretplata.
     * Ovu funkciju server treba da izvrsava svakog dana u 00.00h
     */
    private function removeExpiredPrivileges(){
        $privModel = new PrivilegedUserModel();
        $privUsers = $privModel->findAll();

        foreach($privUsers as $user){
            if($user->endDate < date('Y-m-d')){
                $privModel->delete($user->IdUser);
            }
        }
    }
}
