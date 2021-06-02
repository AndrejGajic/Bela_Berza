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
     * ukoliko administrator nije prijavljen ova funkcija treba da uradi redirect na glavnu stranu
     */
    public function index()
    {
        if(!$this->session->get('adminId')){
            return redirect()->to('/home');
        }
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

    /**
     * Funkcija koja potvrdjuje registraciju na cekanju tako sto joj postavlja status potvrdjene
     * i kreira novog korisnika koji odgovara podacima iz registracije
     * @param string $username
     * @throws \ReflectionException
     */
    public function confirmRegistration($username)
    {
        $regModel = new RegistrationModel();
        $userModel = new UserModel();

        $reg = $regModel->where('username',$username)->find();


        $data = array('name'=>$reg[0]->name,'surname'=>$reg[0]->surname,'username'=>$reg[0]->username,
            'email'=>$reg[0]->email, 'balance'=>0.00);
        //ubaci novog korisnika u bazu sa podacima iz registracije
        $userModel->save($data);

        $reg[0]->status=1; //potvrdjena registracija
        $reg[0]->IdAdministrator = $this->session->get('adminId');
        $regModel->save($reg[0]);

        //obavesti korisnika mailom da je ubacen
        $email = \Config\Services::email();

        $email->setFrom('leko.matijevic@gmail.com', 'Bela berza Admin');
        $email->setTo('ruki.rukic@gmail.com');

        $email->setSubject('Potvrda registracije');
        $email->setMessage('Vasa registracija na sajt Bela Berza je potvrdjena. Sada mozete koristiti sve funkcionalnosti sajta.');

        $email->send();
        //return view('test',array('data'=>site_url('regconfirmation')));
        return redirect()->to(site_url('regconfirmation'));
    }


    /**
     * Funkcija koja odbacuje registraciju na cekanju tako sto joj postavlja status odbijene
     *
     * @param $username
     * @throws \ReflectionException
     */
    public function rejectRegistration($username)
    {
        $regModel = new RegistrationModel();
        $reg = $regModel->where('username',$username)->find();

        $reg[0]->status = 2;//odbijena registracija
        $reg[0]->IdAdministrator = $this->session->get('adminId');
        $regModel->save($reg[0]);

        //obavesti korisnika mailom da je odbijen
    }
}



