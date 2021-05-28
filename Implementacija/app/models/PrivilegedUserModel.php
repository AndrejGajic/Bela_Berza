<?php
namespace App\Models;
use CodeIgniter\Model;

class PrivilegedUserModel extends Model
{
    protected $table = 'privilegeduser';
    protected $primaryKey = 'IdUser';
    protected $useAutoIncrement = false;
    protected $returnType ='object';
    protected $allowedFields = ['startDate','endDate'];
}