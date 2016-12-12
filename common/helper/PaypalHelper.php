<?php

namespace common\helper;

/**
 * paypal Helper for paypal payment
 * Darshna Joshi 
 * 15th April 2016
 */

class PaypalHelper {
    
    /*
     * test host URL : https://api.sandbox.paypal.com
     * live host URL : https://api.paypal.com
     */
    public $host = 'https://api.sandbox.paypal.com'; 
    public $clientId = '';
    public $clientSecret = '';
    
    public $seller_id = ''; //  Business email ID
    
    /*
     * this url for payment using paypal account in web
     * test Paypal payment url : https://www.sandbox.paypal.com/cgi-bin/webscr 
     * live paypal payment url : https://www.paypal.com/cgi-bin/webscr
     */
    public $pay_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; 

    public $token = '';

    /*
     * curl request for get access tken using client id and client secret
     */
    public function get_access_token($url, $postdata) {
        
        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_POST, true); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
        curl_setopt($curl, CURLOPT_HEADER, false); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
        $response = curl_exec( $curl );
        
        if (empty($response)) {
            // some kind of an error happened
            die(curl_error($curl));
            curl_close($curl); // close cURL handler
        } else {
            $info = curl_getinfo($curl);
            
            curl_close($curl); // close cURL handler
        }

        // Convert the result from JSON format to a PHP array 
        $jsonResponse = json_decode( $response );
        return $jsonResponse->access_token;
    }

   /*
    * post call : curl request for payment
    */
    
    public function make_post_call($url, $postdata) {
        $token = $this->accessToken();
        
        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                'Authorization: Bearer '.$token,
                                'Accept: application/json',
                                'Content-Type: application/json'
                                ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
        
        $response = curl_exec( $curl );        
        $status   = curl_getinfo($curl, CURLINFO_HTTP_CODE);        
       
        if (empty($response)) {
            // some kind of an error happened
            die(curl_error($curl));
            curl_close($curl); // close cURL handler
        } else {
            $info = curl_getinfo($curl);
            
            curl_close($curl); // close cURL handler
        }

        // Convert the result from JSON format to a PHP array 
        $jsonResponse = json_decode($response, TRUE);
        
        $jsonResponse['http_status'] = $status;
        
        return $jsonResponse;
    }
    
    /**
     * get access token 
     */
    public function accessToken() {
          
        $url = $this->host.'/v1/oauth2/token';  
        $postArgs = 'grant_type=client_credentials';

        $token = $this->get_access_token($url,$postArgs);
        
        return $token;
    }
    
    /**
     * function for payment using credit card 
     * pass data : array with these fields 
     * credit_card_number, card_type, expire_mont,expire_year, cvv2,first_name,last_name,amount, currency
     * Darshna Joshi : 12th April 2016
     */
    
    public function PaymentByCard($post) {         
        $url = $this->host.'/v1/payments/payment';

        $payment = array(
                        'intent' => 'sale',
                        'payer' => array(
                                'payment_method' => 'credit_card',
                                'funding_instruments' => array (
                                        array(
                                            'credit_card' => array (
                                                'number' => $post['credit_card_number'],
                                                'type'   => $post['card_type'],
                                                'expire_month' => $post['expire_month'],
                                                'expire_year' => $post['expire_year'],
                                                'cvv2' => $post['cvv2'],
                                                'first_name' => $post['first_name'],
                                                'last_name' => $post['last_name'],
                                                )
                                            )
                                    )
                                ),
                                'transactions' => array (
                                        array(
                                            'amount' => array(
                                                    'total' => $post['amount'],
                                                    'currency' => $post['currency'],
                                                ),
                                            'description' => 'payment by a credit card using a test script'
                                        )
                                    )
                        );
        $json = json_encode($payment);
        $json_resp = $this->make_post_call($url, $json);
        
        $response = array();
        
        if($json_resp['http_status'] == 200 || $json_resp['http_status'] == 201){
            $response['status'] = 1;
            $response['message'] = "Payment Successfully done.";
            $response['data'] = $json_resp;
        } else {
            $response['status'] = '-1';
            $response['message'] = "Error!";
            $response['data'] = $json_resp;
        }
                
        $jsonResponse = json_encode($response, TRUE);
        return $jsonResponse;
    }
    
}
