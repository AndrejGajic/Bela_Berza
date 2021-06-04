<?php

namespace App\Controllers;

use App\Models\PrivilegedUserModel;
use App\Models\UserModel;

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
}
