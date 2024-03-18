<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonikBalanceController extends Controller
{
    //get current balance

    public function get_current_balance1()
    {
        $xml = '<env:Envelope xmlns:env="http://schemas.xmlsoap.org/soap/envelope/">
    <env:Header></env:Header>
    <env:Body>
        <ns2:getAccountBalanceResponse xmlns:ns2="http://konik.cgrate.com">
            <return>
                <responseCode>0</responseCode>
                <responseMessage>Successful</responseMessage>
                <balance>1629.00</balance>
            </return>
        </ns2:getAccountBalanceResponse>
    </env:Body>
</env:Envelope>';

// Convert XML to array
        $xmlObj = simplexml_load_string($xml);
        $json = json_encode($xmlObj);
        $array = json_decode($json, true);

// Convert array to JSON
        $jsonResponse = json_encode($array);

// Return the JSON response
        return response()->json($jsonResponse);

    }
    public function get_current_balance()
    {
        $konse_konse_url = env("KONSE_KONSE_URL_TEST");
        $konse_konse_username = env("KONSE_KONSE_USERNAME_TEST");
        $konse_konse_password = env("KONSE_KONSE_PASSWORD_TEST");

        $curl = curl_init();

        $xmlPayload = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:kon="http://konik.cgrate.com">
    <soapenv:Header>
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" soapenv:mustUnderstand="1">
            <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="' . $konse_konse_username . '">
                <wsse:Username>' . $konse_konse_username . '</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $konse_konse_password . '</wsse:Password>
            </wsse:UsernameToken>
        </wsse:Security>
    </soapenv:Header>
    <soapenv:Body>
        <ns2:getAccountBalance xmlns:ns2="http://konik.cgrate.com">
      </ns2:getAccountBalance>
    </soapenv:Body>
</soapenv:Envelope>';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $konse_konse_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/soap+xml,application/dime,multipart/related,text/*',
                'Content-Type: text/xml',
                // Specify the appropriate SOAPAction value if required by the service
                'SOAPAction: ""',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

// Convert SOAP response XML to array
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

        $xml->registerXPathNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns2', 'http://konik.cgrate.com');

// Convert array to JSON
        $jsonResponse = json_encode($xml);

// Return the JSON response
        return response()->json($jsonResponse);

    }
}
