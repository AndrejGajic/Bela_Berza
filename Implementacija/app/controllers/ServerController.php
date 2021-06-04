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
    
    public function index() {
        return view('server.php');
    }
    
    public function getStockTimeData($stockName, $period, $outputSize) {
        
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
                        "x-rapidapi-key: 596939d60emsh17dede5c0ed3951p18cf6djsn7674766f4fde"
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
                        "x-rapidapi-key: 4c9b48580dmsh1474e734b15ec04p1bf687jsn1b7cacdeea16"
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
                        "x-rapidapi-key: 596939d60emsh17dede5c0ed3951p18cf6djsn7674766f4fde"
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

        $response = $this->getStockTimeData($stockName, "1day", "50");        
        $response = json_decode($response, true);

        $values = $response["values"];   
        
        $maxChange = 0;
        
        for ($i = 1; $i < 30; $i++) {
            $change = abs(floatval(floatval(($values[$i]["open"] - $values[$i - 1]["open"])) / floatval(($values[$i]["open"]  + $values[$i-1]["open"]))));
            if ($change > $maxChange) {
                $maxChange = $change;
            }
        }
        
        $response = $this->getStockInfo($stockName);
        $response = json_decode($response, true);
        $change_percent = floatval($response["percent_change"]);
        
        if (abs($change_percent) > $maxChange) {
            $maxChange = abs($change_percent);
        }
        
        return $maxChange;
    }
    
    
    public function updateStockPrices() {
        
        $stockModel = new \App\Models\StockModel();
        $stockNames = $stockModel->getAllStockNames();
        
        foreach ($stockNames as $stockName) {
            
            $response = $this->getStockCurrentPrice($stockName->companyName);
            $jsonObj = json_decode($response, true);
            $price = $jsonObj["price"];
            
            
            $stockInfo = $this->getStockInfo($stockName->companyName);
            $jsonObj2 = json_decode($stockInfo, true);
            $rate = $jsonObj2["percent_change"];
            
            
            $volatility = $this->getStockVolatility($stockName->companyName);
            $isVolatile = false;
            if ($volatility > 0.01) {
                $isVolatile = true;
            }
            
            $stockModel->updateStock($stockName->companyName, $price, $rate, $isVolatile);
        }
        
        return view('server.php');
    }
}
