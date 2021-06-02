<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers;

/**
 * Description of LogoutController
 *
 * @author Andrej
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
