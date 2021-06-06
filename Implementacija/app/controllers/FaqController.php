<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers;

use App\Models\PrivilegedUserModel;

/**
 * Andrej Gajic 2018/0303
 */

/**
 * FAQController - klasa kontrolera za rad sa stranicom FAQ (frequently asked questions)
 *
 * @version 1.0
 */
class FaqController extends BaseController {
    
    /**
     * Indeks funkcija za prikaz stranice FAQ
     */
    public function index() {
        $userId = $this->session->get("IdUser");
        if(!$userId){
            return redirect()->to("/login");
        }
        if($this->session->get('IdAdministrator')){
             return redirect()->to("/home");
        }
        $privUserId = (new PrivilegedUserModel())->find($userId);

        //prijavljen je privilegovani korisnik
        if($privUserId){
            $menu= "privileged";
        }
        else{
            $menu= "standard";
        }
        $img = $this->session->get("imagePath");
        $name = $this->session->get("name");
        $surname = $this->session->get("surname");
        $username = $this->session->get("username");
        
        return view("faq.php",["menu" => $menu, "imgPath" => $img, "name" => $name, "surname" => $surname, "username" => $username]);
    }
    
}
