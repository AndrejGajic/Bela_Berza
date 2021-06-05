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
    

    public function find($id = null)
    {
        $db = \Config\Database::connect();
        $sql = "select IdStock, IdUser, quantity from userownsstock where IdUser=? and IdStock=?";
        $query = $db->query($sql,[$id['IdUser'],$id['IdStock']]);
        return $query->getRow();
    }
    
    public function updateQuantity(int $userId, int $stockId, int $newQuantity) {
        $db = \Config\Database::connect();
        $sql = "update userownsstock set quantity=? where IdUser=? and IdStock=?";
        return $db->query($sql,[$newQuantity,$userId,$stockId]);
    }
    
    public function isUserOwningStockQuantity(int $userId, string $idStock) {
        $db = \Config\Database::connect();
        $sql = "select quantity from userownsstock where IdUser=? and IdStock=?";
        $query = $db->query($sql,[$userId, $idStock]);
        return $query->getRow();
    }

    /**
     * Funkcija koja vraca listu svih akcija i kolicina koje zadati korisnik poseduje
     * @param int $IdUser
     * @return array|object[]
     */
    public function findStocksForUser($IdUser){
        $db = \Config\Database::connect();
        $sql = "select IdStock, quantity from userownsstock where IdUser=?";
        $query = $db->query($sql,$IdUser);
        return $query->getResultObject();
    }

    public function find($id = null)
    {
        $db = \Config\Database::connect();
        $sql = "select IdStock, IdUser, quantity from userownsstock where IdUser=? and IdStock=?";
        $query = $db->query($sql,[$id['IdUser'],$id['IdStock']]);
        return $query->getRow();
    }
    
    public function updateQuantity(int $userId, int $stockId, int $newQuantity) {
        $db = \Config\Database::connect();
        $sql = "update userownsstock set quantity=? where IdUser=? and IdStock=?";
        return $db->query($sql,[$newQuantity,$userId,$stockId]);
    }

}
