<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'IdUser';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['username','password','email','name','surname','imagePath','balance'];
    
    public function getUserBalance($username){
        $user=$this->where('username',$username)->first();//or findAll is same, result must be one row
        return $user->balance;
    }
    
    public function getUserByUserName($username){
        return $this->where('username',$username)->first();
    }
    
    public function getUserByEmail($email) {
        return $this->where("email", $email)->first(); // mora samo jedan da bude svejedno
    }

}