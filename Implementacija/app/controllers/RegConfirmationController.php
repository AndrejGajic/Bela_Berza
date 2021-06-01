<?php

namespace App\Controllers;

use App\Models\RegistrationModel;
use App\Models\UserModel;

/** Kosta Matijevic 0034/2018 */

/**
 * RegConfirmationControllerController – klasa za potvrdjivanje registracija od strane administratora
 *
 * @version 1.0
 */

class RegConfirmationController extends BaseController
{
    /**
     * indeks funkcija koja treba da pozove funkciju za ucitavanje stranice za potvrdu registracija
     */
    public function index()
    {
        return $this->showRegConfirmPage();
    }

    /**
     * funkcija koja prikazuje stranicu za potvrdu registracija
     * sa ucitanim svim potrebnim podacima
     */
    public function showRegConfirmPage()
    {
        $pendingRegs = (new RegistrationModel())->getActiveRegistrations();
        $error=null;
        if(count($pendingRegs)==0){
            $error='Nema nijedne registracije na cekanju';
        }
        $username = $this->session->get('username');
        $imgPath = $this->session->get('img');

        $data = array('regs'=>$pendingRegs, 'username'=>$username, 'imgPath'=>$imgPath, 'error'=>$error);
        return view('confirmation.php',$data);
    }

    public function confirmRegistration($username)
    {
        $regModel = new RegistrationModel();
        $userModel = new UserModel();

        $reg = $regModel->where('username',$username)->find();


        $data = array('name'=>$reg[0]->name,'surname'=>$reg[0]->surname,'username'=>$reg[0]->username,
            'email'=>$reg[0]->email, 'balance'=>0.00);
        //ubaci novog korisnika u bazu
        $userModel->save($data);

        $reg[0]->status=1;
        $reg[0]->IdAdministrator = $this->session->get('adminId');
        //azuriraj registraciju kao uspesnu
        $regModel->save($reg[0]);

        //obavesti ga mailom da je ubacen
    }
}



