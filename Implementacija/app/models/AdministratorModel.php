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
}
