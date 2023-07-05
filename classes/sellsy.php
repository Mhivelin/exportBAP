<?php

/**
 * Classe Sellsy
 */
class Sellsy
{
    private static $api_url = "{{url_api}}";
    private static $oauth_access_token = "{{user_token}}";
    private static $oauth_access_token_secret = "{{user_secret}}";
    private static $oauth_consumer_key = "{{consumer_token}}";
    private static $oauth_consumer_secret = "{{consumer_secret}}";
    private static $instance;
    private $header;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        $encoded_key = rawurlencode(self::$oauth_consumer_secret) . '&' . rawurlencode(self::$oauth_access_token_secret);
        $oauth_params = array(
            'oauth_consumer_key' => self::$oauth_consumer_key,
            'oauth_token' => self::$oauth_access_token,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_timestamp' => time(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => $encoded_key
        );

        $this->header = array(
            'Content-Type: application/json',
            'oauth_consumer_key: ' . $oauth_params['oauth_consumer_key'],
            'oauth_token: ' . $oauth_params['oauth_token'],
            'oauth_nonce: ' . $oauth_params['oauth_nonce'],
            'oauth_timestamp: ' . $oauth_params['oauth_timestamp'],
            'oauth_signature_method: ' . $oauth_params['oauth_signature_method'],
            'oauth_version: ' . $oauth_params['oauth_version'],
            'oauth_signature: ' . $oauth_params['oauth_signature']
        );
    }

    private function requestApi($request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => $this->header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function createPurchase($ident, $thirdID, $doctype, $row_type, $row_id, $row_qt, $row_unitAmount, $row_taxID, $paymediumID, $payAmount, $payCurrencyID, $custom)
    {
        $request = array(
            'method' => 'Purchase.create',
            'params' => array(
                'purchase'  => array(                           // correspondances zeedoc
                    'ident'     => $ident,                      // Identifier
                    'thirdid'   => $thirdID,
                    'doctype'   => $doctype
                ),
                'rows'  => array(
                    0 => array(
                        'type'          => $row_type,
                        'id'            => $row_id,
                        'qt'            => $row_qt,
                        'unitAmount'    => $row_unitAmount,
                        'taxid'         => $row_taxID
                    )
                ),
                'payments' => array(
                    0 => array(
                        'mediumid'  => $paymediumID,
                        'amount'    => $payAmount,
                        'currency'  => $payCurrencyID
                    )
                ),
                'paydate' => array(
                    'custom'    => $custom
                )
            )
        );

        $response = $this->requestApi($request);

        // Traiter la réponse ici si nécessaire

        return $response;
    }
}

$test = new Sellsy();
var_dump($test);