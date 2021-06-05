<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers;

/**
 * Description of ServerController
 *
 * @author Uros Stankovic 0270/2018
 */

class ServerController extends BaseController {

    static $volatileTreshold = 3;    
    static $trendDeviationTreshold = 0.03;
    static $keyCnt = 0;
    
    static $apiKeys = array("cec88fcae7mshf782fb49976a3e8p1d30a2jsn7ca40b240a2b");

    public function index() {
        return view('server.php');
    }
    
    public function getStockTimeData($stockName, $period, $outputSize) {
        
        //$apiKey = "x-rapidapi-key: " . self::$apiKeys[(self::$keyCnt++) % count(self::$apiKeys)];
        $index = rand(0, count(self::$apiKeys) - 1);
        $apiKey = "x-rapidapi-key: ".self::$apiKeys[$index];
        
        $apiKey = "x-rapidapi-key: " . self::$apiKeys[(self::$keyCnt++) % count(self::$apiKeys)];
              
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, [
                CURLOPT_URL => ("https://twelve-data1.p.rapidapi.com/time_series?symbol=" . $stockName . "&interval=" . $period . "&outputsize=" . $outputSize . "&format=json"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: twelve-data1.p.rapidapi.com",
                        $apiKey
                ],
        ]);
        
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {       
            return $response;
        }
    }
    
    public function getStockCurrentPrice($stockName) {
        
        //$apiKey = "x-rapidapi-key: " . self::$apiKeys[(self::$keyCnt++) % count(self::$apiKeys)];
        $index = rand(0, count(self::$apiKeys) - 1);
        $apiKey = "x-rapidapi-key: ".self::$apiKeys[$index];
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt_array($curl, [
                CURLOPT_URL => "https://twelve-data1.p.rapidapi.com/price?symbol=" . $stockName . "&format=json&outputsize=30",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: twelve-data1.p.rapidapi.com",
                        $apiKey
                ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
                return "cURL Error #:" . $err;
        } else {
                return $response;
        }
    }

    public function getStockInfo($stockName) {
        
        static $requestCnt = 0;
        //$apiKey = "x-rapidapi-key: " . self::$apiKeys[(self::$keyCnt++) % count(self::$apiKeys)];
        $index = rand(0, count(self::$apiKeys) - 1);
        $apiKey = "x-rapidapi-key: ".self::$apiKeys[$index];
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt_array($curl, [
                CURLOPT_URL => "https://twelve-data1.p.rapidapi.com/quote?symbol=" . $stockName . "&interval=1day&outputsize=30&format=json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                        "x-rapidapi-host: twelve-data1.p.rapidapi.com",
                        $apiKey
                ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
                return "cURL Error #:" . $err;
        } else {
                return $response;
        }
    }
            
    public function getStockVolatility($stockName) {

        $response = $this->getStockTimeData($stockName, "5min", "30");        
        $response = json_decode($response, true);

        $values = $response["values"];   
        
        $maxPrice = -1;
        $minPrice = -1;
        $totalValue = 0;
        
        for ($i = 0; $i < count($values); $i++) {
            
            $currPriceMin = floatval($values[$i]["low"]);
            $currPriceMax = floatval($values[$i]["high"]);
            $currPrice = floatval($values[$i]["open"]);

            $stockModel = new \App\Models\StockModel();
            $stockHistoryPriceModel = new \App\Models\StockHistoryPriceModel();
            $IdStock = $stockModel->getIdStockByCompanyName($stockName);
            $IdStock = $IdStock[0]->IdStock;
            $data = [
                "IdStock" => $IdStock, 
                "timestamp" => $values[$i]["datetime"], 
                "price" => $values[$i]["high"]
            ];
            //$stockHistoryPriceModel->clearTable();
            $stockHistoryPriceModel->insert($data); 
            
            $totalValue += $currPrice;
            
            if ($maxPrice == -1) {
                $maxPrice = $currPriceMax;
                $minPrice = $currPriceMin;
            } else {
                if ($currPriceMax > $maxPrice) {
                    $maxPrice = $currPriceMax;
                }
                if ($currPriceMin < $minPrice) {
                    $minPrice = $currPriceMin;
                }
            }
        }
        
        $maxChange = floatval((floatval($maxPrice - $minPrice)) / floatval($totalValue / count($values)));
        
        $stockModel = new \App\Models\StockModel();
        $change_percent = $stockModel->getStockRate($stockName)[0]->rate;

        if (abs($change_percent) > $maxChange) {
            $maxChange = abs($change_percent);
        }
        
        return $maxChange;
    }
    
    public function updateStockPrices(int $callCnt = 0) {  
        
        $stockModel = new \App\Models\StockModel();
        $stockNames = $stockModel->getAllStockNames();
        
        foreach ($stockNames as $stockName) {
            
            $response = $this->getStockCurrentPrice($stockName->companyName);       //first api call
            $jsonObj = json_decode($response, true);
            $price = $jsonObj["price"];
            
            $result = $this->immediateStockAction($stockName->companyName);         //second api call
            $action = $result['action'];
            $weight = $result['weight'];
            
            $isVolatile = true;
            
            
            if ($callCnt % 10 == 0) {
                
                $stockInfo = $this->getStockInfo($stockName->companyName);          //third api call
                $jsonObj2 = json_decode($stockInfo, true);
                $rate = $jsonObj2["percent_change"];


                $volatility = $this->getStockVolatility($stockName->companyName);   //fourth api call, total 48 calls (12 * 4)
                $isVolatile = false;
                if ($volatility > self::$volatileTreshold) {
                    $isVolatile = true;
                }

            }
            
            $stockModel->updateStock($stockName->companyName, $price, $rate, $isVolatile, $action, $weight);
        }
        echo "<script>window.close();</script>";
    }
    
    public function immediateStockAction($companyName) {
        
        $stockModel = new \App\Models\StockModel();
        $currentValue = $stockModel->getStockValue($companyName)[0]->value;
        
        $yArr = array();
        $xArr = array();
        
        $response = $this->getStockTimeData($companyName, "1min", "180");
        $response = json_decode($response, true);
        $values = $response["values"];   
        
        // x-axis goes from 0 to 30 (time)
        // y-axis represents stock value (price)
        $i = 0; 
        for (; $i < count($values); $i++) {
            array_push($xArr, intval($i));
            array_push($yArr, intval($values[$i]["close"]));
        }
     
        $linRegResult = $this->linearRegression($xArr, $yArr);
        $slope = $linRegResult['slope'];
        $intercept = $linRegResult['intercept'];
        
        // y = a * x + b
        $nextPredictedValue = $slope * $i + $intercept;
                
        // there must be a treshold (+= trendDeviationTreshold%) that should be ignored due to standard market noise (no action)
        $diff = floatval(abs($currentValue - $nextPredictedValue));
        $diffPercent = floatval($diff / $currentValue);
        
        
        $tradeStrength = 0;
        
        //found online
        if ($diffPercent > 0.1) {
            $tradeStrength = 5;
        } else if ($diffPercent > 0.06) {
            $tradeStrength = 4;
        } else if ($diffPercent > 0.04) {
            $tradeStrength = 3;
        } else if ($diffPercent > 0.025) {
            $tradeStrength = 2; 
        } else if ($diffPercent > 0.01) {
            $tradeStrength = 1;
        }
        
       
        // if the current price is lower than next predicted, stock should be bought
        // if the current price is higher than next predicted, stock should be sold
        if ($currentValue < $nextPredictedValue) {
            //return $currentValue . " BUY " . $nextPredictedValue;
            return array("action"=>"BUY", "weight"=>$tradeStrength);
        } else {
            //return $currentValue . "SELL " . $nextPredictedValue;
            return array("action"=>"SELL", "weight"=>$tradeStrength);
        }
    }
    
    function linearRegression($x, $y) {

        // calculate number points
        $n = count($x);

        // ensure both arrays of points are the same size
        if ($n != count($y)) {
          trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
        }

        // calculate sums
        $x_sum = array_sum($x);
        $y_sum = array_sum($y);

        $xx_sum = 0;
        $xy_sum = 0;

        for($i = 0; $i < $n; $i++) {
          $xy_sum+=($x[$i]*$y[$i]);
          $xx_sum+=($x[$i]*$x[$i]);
        }

        // calculate slope
        $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

        // calculate intercept
        $b = ($y_sum - ($m * $x_sum)) / $n;
        
        // return result
        return array("slope"=>$m, "intercept"=>$b);
    }
}
