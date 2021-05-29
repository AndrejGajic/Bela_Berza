<?php

namespace App\Controllers;


/** Kosta Matijevic 0034/2018 */

/**
 * PrivilegesController – klasa za prelazak korisnika u privilegovanog korisnika
 *
 * @version 1.0
 */

class PrivilegesController extends BaseController
{

    /**
     * Indeks funkcija koja u zavisnosti od tipa korisnika koji je ulogovan na razliciti nacin ucitava stranicu
     * za privilegije, a u slucaju gosta preusmerava ga na stranicu za Login
     */
    public function index()
    {
        $userType = $this->session->get('userType');
        if ($userType == null) {
            return redirect()->to('/home');
        }

        if ($userType == 'guest') {
            return redirect()->to('/login');
        }

        else if ($userType == 'standard') {
            $data = array('priv' => 0);
            return view('privileges.php',$data);
        }

        else if ($userType == 'privileged') {
            $data = array('priv' => 1);
            return view('privileges.php',$data);
        }
    }


    /**
     * Funkcija koja proverava da li korisnik ima dovoljno novca za prelazak u privilegovani rezim.
     * Ako ima korisniku se prikazuje modal za potvrdu kupovine, a ako nema ispisuje mu se poruka o neuspehu.
     */
    public function checkMoney()
    {

    }


    /**
     * Funkcija koja prikazuje modal za potvrdu kupovine privilegija
     */
    public function confirmation()
    {

    }


    /**
     * Funkcija koja azurira stanje u bazi u slucaju uspesne transakcije i obavestava korisnika o uspesnoj kupovini
     */
    public function grantPrivileges()
    {

    }
}
