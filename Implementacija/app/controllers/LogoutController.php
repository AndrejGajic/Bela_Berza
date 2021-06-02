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
        $this->session->destroy();
        return redirect()->to(site_url("LoginController"));
    }
    
    
}
