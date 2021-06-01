<?php
namespace App\Models;
use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table = 'registration';
    protected $primaryKey = 'IdRegistration';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['username', 'date', 'password','email','name','surname','ipAddres','status','IdAdministrator'];

    public function getActiveRegistrations()
    {
        $db = \Config\Database::connect();
        $sql = "select username, date, email, ipAddress from registration where status=0";
        $query = $db->query($sql);
        return $query->getResultObject();
    }
}



