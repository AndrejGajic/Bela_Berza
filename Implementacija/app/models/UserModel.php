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

}