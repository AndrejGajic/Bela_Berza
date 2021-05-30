<?php
namespace App\Models;
use CodeIgniter\Model;

class UserOwnsStockModel extends Model
{
    protected $table = 'userownsstock';
    //nema $primary key jer je kompozitni kljuc
    protected $returnType ='object';
    protected $allowedFields = ['IdStock','IdUser','quantity'];

    //ne moze se koristiti podrazumevani findAll jer nema primary key-a
    public function findAll(int $limit = 0, int $offset = 0)
    {
        $db = \Config\Database::connect();
        $sql = "select IdStock, IdUser, quantity from userownsstock";
        $query = $db->query($sql);
        return $query->getResultObject();
    }

}