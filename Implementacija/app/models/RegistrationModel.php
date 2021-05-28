<?php
namespace App\Models;
use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table = 'registration';
    protected $primaryKey = 'IdRegistration';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['username','password','email','name','surname','ipAddres','status','IdAdministrator'];
}

