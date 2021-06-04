<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers;
/**
 * Andrej Gajic 0303/2018 -> logout
 */

/**
 * LogoutController - klasa za odjavljivanje korisnika
 *
 * @version 1.0
 */
class LogoutController extends BaseController {
    
    
    /**
     * Funkcija za odjavu korisnika sa sistema.
     * 
     */
    public function logout() {
        $this->session->destroy(); // iz nekog razloga ne brise nista ova metoda
       /* $this->session->remove("adminId");
        $this->session->remove("userId");
        $this->session->remove("email");
        $this->session->remove("username");
        $this->session->remove("name");
        $this->session->remove("surname");
        $this->session->remove("img"); */
        
        return redirect()->to(site_url("LoginController"));
    }
    
    
}
