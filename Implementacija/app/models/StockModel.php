<?php
namespace App\Models;
use CodeIgniter\Model;

/*

    
 * Uros Stankovic 0270/2018
 * 
 *  */

class StockModel extends Model
{
    protected $table = 'stock';
    protected $primaryKey = 'IdStock';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['companyName','value','rate','imagePath','availableQty','isVolatile'];
    
    public function getStockByCompanyName($companyName){
        return $this->where('companyName',$companyName)->first();
    }
    
    public function getAllStockNames() {

        $db = \Config\Database::connect();
        $sql = "select companyName from stock";
        $query = $db->query($sql);
        return $query->getResultObject();
        
    }
    
    public function getStockValues() {

        $db = \Config\Database::connect();
        $sql = "select value from stock";
        $query = $db->query($sql);
        return $query->getResultObject();
        
    }
    
    public function getStockValue($companyName) {

        $db = \Config\Database::connect();
        $sql = "select value from stock where companyName=?";
        $query = $db->query($sql, [$companyName]);
        return $query->getResultObject();
        
    }
    
    public function getStockRate($companyName) {

        $db = \Config\Database::connect();
        $sql = "select rate from stock where companyName=?";
        $query = $db->query($sql, [$companyName]);
        return $query->getResultObject();
        
    }
    
    public function updateStockPrice(string $companyName, float $value) {
        
        $db = \Config\Database::connect();
        $sql = "update stock set value=? where companyName=?";
        $res = $db->query($sql,[$value, $companyName]);
        
    }
    
    public function updateStockRate(string $companyName, float $rate) {
        
        $db = \Config\Database::connect();
        $sql = "update stock set rate=? where companyName=?";
        $res = $db->query($sql,[$rate, $companyName]);
        
    }
    
    public function updateStockVolatility(string $companyName, bool $isVolatile) {
        
        $db = \Config\Database::connect();
        $sql = "update stock set isVolatile=? where companyName=?";
        $res = $db->query($sql,[$isVolatile, $companyName]);
        
    }
    
    public function updateStock(String $companyName, float $value, float $rate, bool $isVolatile) {
        
        $db = \Config\Database::connect();
        $sql = "update stock set value=?, rate=?, isVolatile=? where companyName=?";
        $res = $db->query($sql,[$value, $rate, $isVolatile, $companyName]);
        
    }
    
    public function getVolatileStocks() {
        
        $db = \Config\Database::connect();
        $sql = "select * from stock where isVolatile = true";
        $query = $db->query($sql);
        return $query->getResultObject();
        
    }
}

