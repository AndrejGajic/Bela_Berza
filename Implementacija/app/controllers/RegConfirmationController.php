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
        if(!$this->session->get('IdAdministrator')){
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
        $imgPath = $this->session->get('imagePath');

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

        //ubaci novog korisnika u bazu sa podacima iz registracije
        $data = array('name'=>$reg[0]->name,'surname'=>$reg[0]->surname,'username'=>$reg[0]->username,
            'email'=>$reg[0]->email, 'password'=>$reg[0]->password, 'balance'=>0.00);
        $userModel->save($data);

        //azuriraj status registracije na potvrdjen i postavi administratora koji je odobrio
        $reg[0]->status=1;
        $reg[0]->IdAdministrator = $this->session->get('IdAdministrator');
        $regModel->save($reg[0]);

        //obavesti korisnika mailom da je ubacen
        $email = \Config\Services::email();
        $email->setFrom('svpirotehnika@gmail.com','Bela berza admin');
        $email->setTo($reg[0]->email);
        $email->setSubject('Potvrda registracije');
        $email->setMessage('Vasa registracija na sajt Bela Berza je potvrdjena. Sada mozete koristiti sve funkcionalnosti sajta.');

        if($email->send()){
            return redirect()->to(site_url('regconfirmation'));
        }
        else{
            $dump = $email->printDebugger(['headers']);
            print_r($dump);
        }
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

        //azuriraj status registracije na odbijen postavi administratora koji je odobrio
        $reg[0]->status = 2;
        $reg[0]->IdAdministrator = $this->session->get('adminId');
        $regModel->save($reg[0]);

        //obavesti korisnika mailom da je odbijen
        $email = \Config\Services::email();
        $email->setFrom('svpirotehnika@gmail.com','Bela berza admin');
        $email->setTo($reg[0]->email);
        $email->setSubject('Odbijena registracija');
        $email->setMessage('Vasa registracija na sajt Bela Berza je odbijena. Ukoliko smatrate da ste greskom odbijeni kontaktirajte nas putem poruke.');

        if($email->send()){
            return redirect()->to(site_url('regconfirmation'));
        }
        else{
            $dump = $email->printDebugger(['headers']);
            print_r($dump);
        }
    }
}



