<?php

namespace App\Controllers;
use App\Models\PrivilegedUserModel;
use App\Models\AdministratorModel;
use App\Models\UserModel;
use App\Models\RegistrationModel;
use App\Models\StockModel;
use App\Models\UserOwnsStockModel;
class Home extends BaseController
{
	public function index()
	{
	    //$this->session->destroy();
	    //$this->session->set('userType','guest');
		return view('index.php');
	}
}
