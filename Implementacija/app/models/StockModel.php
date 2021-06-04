<?php
namespace App\Models;
use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'stock';
    protected $primaryKey = 'IdStock';
    protected $useAutoIncrement = true;
    protected $returnType ='object';
    protected $allowedFields = ['companyName','value','rate','imagePath','availableQty','isVolatile','weight','action'];
    
    
    public function getStockByCompanyName($companyName){
        return $this->where('companyName',$companyName)->first();
    }
}

