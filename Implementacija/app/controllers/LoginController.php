<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegistrationModel;
use App\Models\AdministratorModel;
/**
 * Andrej Gajic 0303/2018 -> index, login, registration
 */

/**
 * LoginContoller - klasa kontrolera za rad sa logovanjem i registracijom korisnika na sistem
 * 
 * @version 1.1
 */

class LoginController extends BaseController
{
    /**
     * Index funkcija koja se koristi pri prikazu forme za logovanje/registraciju korisnika
     */
    public function index()
    {
        return view('login.php');
    }
    /**
     * 
     * Funkcija za obradu logovanja korisnika na sistem
     */
    public function login() {
        helper(["form"]);
        $this->session->remove("IdAdministrator");
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("LoginController"));
        }
        if(!$this->validate([
            "emailLogin" => "required|min_length[3]|max_length[100]",
            "passwordLogin" => "required|min_length[6]|max_length[100]"
        ])) {
            $this->session->setFlashData("loginError", "Niste uneli odgovarajuce podatke!");
            return redirect()->to(site_url("LoginController"));
        }
        $email = $this->request->getVar("emailLogin");
        $password = $this->request->getVar("passwordLogin");
        $checker = "@";
        $userModel = new UserModel();
        $administratorModel = new AdministratorModel();
        if(strpos($email, $checker) == true) {
            // email unet
            $user = $administratorModel->getUserByEmail($email);
            if($user == null) {
                $user = $userModel->getUserByEmail($email);
                if($user == null) {
                    $this->session->setFlashData("loginError", "Pogresna e-mail adresa!");
                    return redirect()->to(site_url("LoginController"));
                }
                // nadjen obican korisnik
                if($password != $user->password) {
                    $this->session->setFlashData("loginError", "Pogresna lozinka!");
                    return redirect()->to(site_url("LoginController"));
                }
                
                if($user->imagePath==null)$user->imagePath='../assets/images/user.png';
                $this->session->set("username", $user->username);
                $this->session->set("IdUser", $user->IdUser);
                $this->session->set("name", $user->name);
                $this->session->set("surname", $user->surname);
                $this->session->set("email", $user->email);
                $this->session->set("img", $user->imagePath);
            }
            else {
                // nadjen administrator
                if($password != $user->password) {
                    $this->session->setFlashData("loginError", "Pogresna lozinka!");
                    return redirect()->to(site_url("LoginController"));
                }
                $this->session->set("IdAdministrator", $user->IdAdministrator);
                $this->session->set("username", $user->username);
                
            }
        }
        else {
            $username = $email;
            $user = $administratorModel->getUserByUserName($username);
            if($user == null) {
                $user = $userModel->getUserByUserName($username);
                if($user == null) {
                    $this->session->setFlashData("loginError", "Pogresno korisnicno ime!");
                    return redirect()->to(site_url("LoginController"));
                }
                // nadjen obican korisnik
                if($password != $user->password) {
                    $this->session->setFlashData("loginError", "Pogresna lozinka!");
                    return redirect()->to(site_url("LoginController"));
                }
                
                if($user->imagePath==null)$user->imagePath='../assets/images/user.png';
                
                $this->session->set("username", $user->username);
                $this->session->set("IdUser", $user->IdUser);
                $this->session->set("name", $user->name);
                $this->session->set("surname", $user->surname);
                $this->session->set("email", $user->email);
                $this->session->set("img", $user->imagePath);
            }
            else {
                // nadjen administrator
                if($password != $user->password) {
                    $this->session->setFlashData("loginError", "Pogresna lozinka!");
                    return redirect()->to(site_url("LoginController"));
                }
                $this->session->set("IdAdministrator", $user->IdAdministrator);
                $this->session->set("username", $user->username);
            }
        }
      
        return redirect()->to(site_url("Home"));
        
    }
    /**
     * 
     * Funkcija za obradu registracije korisnika na sistem.
     */
    public function registration() {
        
        if($this->request->getMethod() == "get") {
            return redirect()->to(site_url("LoginController"));
        }
        
        
        if(!$this->validateRegistration()) {
            return redirect()->to(site_url("LoginController"));
        }
        
        $email = $this->request->getVar("emailReg");
        $username = $this->request->getVar("usernameReg");
        $userModel = new UserModel();
        $registrationModel = new RegistrationModel();
        $administratorModel = new AdministratorModel();
        
        if($userModel->getUserByEmail($email) != null || $registrationModel->getUserByEmail($email) || $administratorModel->getUserByEmail($email)) {
            $this->session->setFlashData("registrationError", "Vec ste registrovani sa emailom $email!");
            return redirect()->to(site_url("LoginController"));
        }
        
        if($userModel->getUserByUserName($username) || $registrationModel->getUserByUserName($username) || $administratorModel->getUserByUserName($username)) {
            $this->session->setFlashData("registrationError", "Vec ste registrovani sa korisnickim imenom $username");
            return redirect()->to(site_url("LoginController"));
        }
        $startDate = date('Y-m-d');
        $data = [
            "name" => $this->request->getVar("nameReg"),
            "surname" => $this->request->getVar("surnameReg"),
            "email" => $this->request->getVar("emailReg"),
            "password" => $this->request->getVar("passwordReg"),
            "username" => $this->request->getVar("usernameReg"),
            "date" => $startDate,
            "status" => 0
        ];
        
        $registrationModel = new RegistrationModel();
        $registrationModel->insert($data);
        
        $this->session->setFlashData("registrationSuccess", "Vas zahtev za registraciju je poslat!");
        return redirect()->to(site_url("LoginController")); 
    }
    
    /**
     * Pomocna funkcija za validaciju unetih podataka pri registraciji korisnika na sistem.
     * @return boolean
     */
    
    private function validateRegistration() {
        helper(["form"]);
        $ret = true;
        if(!$this->validate([
            "nameReg" => "required|max_length[50]",
            "surnameReg" => "required|max_length[50]",
            "usernameReg" => "required",
            "emailReg" => "required",
            "passwordReg" => "required"
        ])) {
            $this->session->setFlashData("registrationError", "Registracija odbijena - morate popuniti sva polja!");
            $ret = false;
        }
        else if(!$this->validate([
            "usernameReg" => "min_length[3]|max_length[50]"
        ])) {
            $this->session->setFlashData("registrationError", "Registracija odbijena - korisnicko ime mora sadrzati izmedju 3 i 50 karaktera!");
            $ret = false;
        }
        else if(!$this->validate([
            "emailReg" => "valid_email"
        ])) {
            $this->session->setFlashData("registrationError", "Registracija odbijena - neispravan format e-mail adrese!");
            $ret = false;
        }
        else if(!$this->validate([
            "passwordReg" => "min_length[6]|max_length[100]"
        ])) {
            $this->session->setFlashData("registrationError", "Registracija odbijena - lozinka mora sadrzati izmedju 6 i 100 karaktera!");
            $ret = false;
        }
        else {
            $password = $this->request->getVar("passwordReg");
            if(!preg_match("/[a-z]/", $password)) {
                $this->session->setFlashData("registrationError", "Registracija odbijena - lozinka mora sadrzati bar jedno malo slovo!");
                $ret = false;
            }
            else if(!preg_match("/[A-Z]/", $password)) {
                $this->session->setFlashData("registrationError", "Registracija odbijena - lozinka mora sadrzati bar jedno veliko slovo!");
                $ret = false;
            }
            else if(!preg_match("/[0-9]/", $password)) {
                $this->session->setFlashData("registrationError", "Registracija odbijena - lozinka mora sadrzati bar jedan broj!");
                $ret = false;
            }
        }
        return $ret;
    }
    
}
