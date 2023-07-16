<?php
namespace App\GalaxPay;

class GalaxPay {
    public $baseUrl = "https://api.sandbox.cloud.galaxpay.com.br/v2";
    private $galaxId;
    private $galaxHash;
    private $galaxToken;
    private const BODY_TOKEN = [
        "grant_type"    =>  "authorization_code",
        "scope"         =>  "customers.read customers.write plans.read plans.write transactions.read transactions.write webhooks.write cards.read cards.write card-brands.read subscriptions.read subscriptions.write charges.read charges.write boletos.read"
    ];

    public function __construct($galaxId, $galaxHash)
    {
        $this->galaxId = $galaxId;
        $this->galaxHash = $galaxHash;
        $this->galaxToken = $this->setToken();
    }

    private function setToken(){
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL             =>  $this->baseUrl."/token",
            CURLOPT_POSTFIELDS      =>  json_encode(self::BODY_TOKEN),
            CURLOPT_RETURNTRANSFER  =>  true,
            CURLOPT_HTTPHEADER      =>  $this->getHeaders()
        ]);

        $response = curl_exec($curl);

        return json_decode($response, true);
    }

    private function getHeaders() {
        return ['Authorization: Basic '.base64_encode( $this->galaxId . ':' . $this->galaxHash)];
    }
    
    public function getToken() {
        return $this->galaxToken;
    }

    public function sendRequest($endpoint, $params = [], $method = 'GET') {
        $curl = curl_init();

        switch (mb_strtoupper($method, 'UTF-8')) {
            case 'GET':
                //Configura request
                curl_setopt_array($curl, [
                    CURLOPT_URL            => $this->baseUrl.$endpoint.'?'.http_build_query($params),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST  => $method,
                    CURLOPT_HTTPHEADER     => $this->getRequestHeaders()
                ]);
                break;
                //Configura o request
            case 'POST':
            case 'PUT':
                curl_setopt_array($curl,[
                    CURLOPT_URL             =>  $this->baseUrl.$endpoint,
                    CURLOPT_RETURNTRANSFER  =>  true,
                    CURLOPT_CUSTOMREQUEST   =>  $method,
                    CURLOPT_HTTPHEADER      =>  $this->getRequestHeaders(),
                    CURLOPT_POSTFIELDS      =>  json_encode($params)
                ]);
                break;
            case 'DELETE':
                //configura o request
                curl_setopt_array($curl,[
                    CURLOPT_URL             =>  $this->baseUrl.$endpoint,
                    CURLOPT_RETURNTRANSFER  =>  true,
                    CURLOPT_CUSTOMREQUEST   =>  $method,
                    CURLOPT_HTTPHEADER      =>  $this->getRequestHeaders()
                ]);
                break;
            default:
                # code...
                break;
        }

        $response = curl_exec($curl);
        //fecha a conexão
        curl_close($curl);

        return json_decode($response, true);
    }

    private function getRequestHeaders(){
        return [
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->galaxToken['access_token']
        ];
    }
}