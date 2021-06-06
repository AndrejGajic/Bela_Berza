<?php

namespace App\Controllers;

use App\Models\PrivilegedUserModel;
use App\Models\UserModel;

/**
 * Kosta Matijevic 0034/2018 - index
 * Andrej Gajic 0303/2018 - 
 */


/**
 *  ProfileController - klasa kontrolera za rad sa izmenom korisnickih informacija
 */
class ProfileController extends BaseController
{
    public function index()
    {
        $userId = $this->session->get('IdUser');
        if(!$userId){
            return redirect()->to('/login');
        }
        if($this->session->get('IdAdministrator')){
            return redirect()->to('/home');
        }
        $privUserId = (new PrivilegedUserModel())->find($userId);

        //prijavljen je privilegovani korisnik
        if($privUserId){
            $menu='privileged';
        }
        else{
            $menu='standard';
        }
        $img = $this->session->get('imagePath');
        $name = $this->session->get('name');
        $surname = $this->session->get('surname');
        $username= $this->session->get('username');
        $userModel=new UserModel();

        return view('profile.php',['menu'=>$menu,'imgPath'=>$img,'name'=>$name,'surname'=>$surname]);
    }
    
    /**
     * Funkcija za promenu e-mail adrese korisnika.  
     */
    public function changeEmail() {
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("ProfileController"));
        }
        if(!$this->validate([
            "newEmail" => "required|valid_email",
            "passwordConfirmation" => "required"
        ])) {
            $this->session->setFlashData("emailChangeRefused", "Promena odbijena - niste uneli podatke u odgovarajucem formatu!");
            return redirect()->to(site_url("ProfileController"));
        }
        $newEmail = $this->request->getVar("newEmail");
        $passwordConfirmation = $this->request->getVar("passwordConfirmation");
        $userModel = new UserModel();
        $user = $userModel->find($this->session->get("IdUser"));
        if($passwordConfirmation != $user->password) {
            $this->session->setFlashData("emailChangeRefused", "Promena odbijena - uneta lozinka se ne poklapa sa vasom lozinkom!");
            return redirect()->to(site_url("ProfileController"));
        }
        $data = [
            "email" => $newEmail
        ];
        $userModel->update($user->IdUser, $data);
        $this->session->setFlashData("emailChangeAccepted", "Promena prihvacena - vasa e-mail adresa je uspesno promenjena!");
        $this->session->set("email", $newEmail);
        return redirect()->to(site_url("ProfileController"));
    }
    
    /**
     * Funckija za promenu lozinke korisnika.
     */
    public function changePassword() {
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("ProfileController"));
        }
        if(!$this->validate([
            "oldPassword" => "required",
            "newPassword" => "required",
            "newPasswordConfirmation" => "required"
        ])) {
            $this->session->setFlashData("passwordChangeRefused", "Promena odbijena - niste uneli podatke u odgovarajucem formatu!");
            return redirect()->to(site_url("ProfileController"));
        }
        if(!$this->validateNewPassword()) {
            return redirect()->to(site_url("ProfileController"));
        }
        $oldPassword = $this->request->getVar("oldPassword");
        $newPassword = $this->request->getVar("newPassword");
        $newPasswordConfirmation = $this->request->getVar("newPasswordConfirmation");
        $userModel = new UserModel();
        $user = $userModel->find($this->session->get("IdUser"));
        if($oldPassword != $user->password) {
            $this->session->setFlashdata("passwordChangeRefused", "Promena odbijena - uneta lozinka se ne poklapa sa vasom lozinkom!");
            return redirect()->to(site_url("ProfileController"));
        }
        if($newPassword != $newPasswordConfirmation) {
            $this->session->setFlashdata("passwordChangeRefused", "Promena odbijena - nova lozinka i potvrda nove lozinke se razlikuju!");
            return redirect()->to(site_url("ProfileController"));
        }
        $data = [
          "password" => $newPassword  
        ];
        $userModel->update($user->IdUser, $data);
        $this->session->setFlashdata("passwordChangeAccepted", "Promena prihvacena - vasa lozinka je uspesno promenjena!");
        return redirect()->to(site_url("ProfileController"));
        
    }
    /**
     * Funkcija za promenu profilne slike korisnika.
     */
    public function changeImage() {
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("ProfileController"));
        }
        $newImage = $this->request->getVar("newImage");
        $userModel = new UserModel();
        $user = $userModel->find($this->session->get("IdUser"));
        $data = [
            "imagePath" =>"../assets/images/$newImage"
        ];
        $userModel->update($user->IdUser, $data);
        $this->session->set("imagePath", "../assets/images/$newImage");
        return redirect()->to(site_url("ProfileController"));
    }
    
    
    /**
     * Pomocna funkcija za validaciju nove lozinke.
     * @return boolean
     */
    
    private function validateNewPassword() {
        if(!$this->validate([
            "newPassword" => "min_length[6]|max_length[100]"
        ])) {
            $this->session->setFlashData("passwordChangeRefused", "Promena odbijena - nova lozinka mora imati izmedju 6 i 10 karaktera!");
            $ret = false;
        }
        else {
            $password = $this->request->getVar("newPassword");
            if(!preg_match("/[a-z]/", $password)) {
                $this->session->setFlashData("passwordChangeRefused", "Promena odbijena - nova lozinka mora sadrzati bar jedno malo slovo!");
                $ret = false;
            }
            else if(!preg_match("/[A-Z]/", $password)) {
                $this->session->setFlashData("passwordChangeRefused", "Promena odbijena - nova lozinka mora sadrzati bar jedno veliko slovo!");
                $ret = false;
            }
            else if(!preg_match("/[0-9]/", $password)) {
                $this->session->setFlashData("passwordChangeRefused", "Promena odbijena - nova lozinka mora sadrzati bar jedan broj!");
                $ret = false;
            }
        }
        return $ret;
    }
    
    
    
}
