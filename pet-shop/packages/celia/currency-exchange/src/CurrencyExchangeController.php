<?php

namespace Christine\CurrencyExchange;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CurrencyExchangeController extends Controller
{
    //
    public function index(Request $request ){

    $validate = \Validator::make($request->all(), [
        'amount'        => 'required|numeric|min:0',
        'currency_code' => 'required'
    ]);
            
            
    if ($validate->fails()) {
        $response = [
            'data' => $validate->errors(),
            'message' => 'Validation Failed!'
        ];
    
        return response()->json($response, 422);
    }


    // Base Currency will be Euro
    $baseCurrency = 'EUR';

    // Target Currency
    $targetCurrency = strtoupper($request->input('currency_code', 'EUR'));

    // Build the API URL
    $url = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";

    // Fetch the XML data from the API
    $xml = file_get_contents($url);

    // Parse the XML data into a SimpleXMLElement object
    $data = new \SimpleXMLElement($xml);
        
    // Find the exchange rate for the target currency
    $rate = null;

    foreach ($data->Cube->Cube->Cube as $currency) {
        if ($currency['currency'] == $targetCurrency) {
            $rate = (float) $currency['rate'];
            break;
        }
    }

    // Calculate the exchanged amount
    $exchangedAmount = $request->input('amount', 1) * $rate;

    return response()->json(['success'              => 'true' , 
                             'baseCurrency'         => $baseCurrency , 
                             'exchangeCurrency'     => $targetCurrency, 
                             'rate'                 => $rate ,
                             'exchangedAmount'      => $exchangedAmount]);

    }

    
}
