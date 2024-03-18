<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZraController extends Controller

{
    function generatePaymentReferenceNumber()
    {
        $prefix = 'TRN'; // Prefix for the account number
        $suffix = time(); // Suffix for the account number (UNIX timestamp)

        // Generate a random number between 1000 and 9999
        $random = rand(1000000000, 9999999999);

        // Combine the prefix, random number, and suffix to form the account number
        $raw_payment_reference_number = $prefix . $random . $suffix;

        $payment_reference_number = substr($raw_payment_reference_number, 0, 24);

        // Check if the payment reference number already exists in the database
        if (DB::table('consumer_transactions')->where('payment_reference_number', $payment_reference_number)->exists()) {
            // If the payment reference number already exists, generate a new one recursively
            return $this->generatePaymentReferenceNumber();
        }

        return $payment_reference_number;
    }

    function generatePrnNumber(){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'WSDL: https://543.cgrate.co.zm/Konik/KonikWs?wsdl=null',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:kon="http://konik.cgrate.com">
        <soapenv:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" soapenv:mustUnderstand="1">
                    <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="1685371630964">
                    <wsse:Username>1685371630964</wsse:Username>
                    <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">6ne4hGZU</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
        <soapenv:Body>
            <ns2:getPRNData xmlns:ns2="http://konik.cgrate.com">
                <!--Optional:-->
                <prn>?</prn>
            </ns2:getPRNData>
        </soapenv:Body>
        </soapenv:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/xml'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


    }
}
