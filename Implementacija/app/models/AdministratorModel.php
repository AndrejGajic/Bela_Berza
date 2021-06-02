<?php
namespace App\Models;
use CodeIgniter\Model;

class AdministratorModel extends Model
{
    protected $table = 'administrator';
    protected $primaryKey = 'IdAdministrator';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['username','password','email'];
    
    public function getUserByEmail($email) {
        return $this->where("email", $email)->first();
    }
    
    public function getUserByUserName($username) {
        return $this->where("username", $username)->first();
    }
}

