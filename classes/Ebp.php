<?php


/**
 * Classe EBP
 */
class EBP
{
    private $id_client;     // id du client
    private $clientSecret;  // clé secrète
    private $redirectUri;   // url de redirection après connexion
    public $code;          // code d'autorisation
    private $accessToken;   // jeton d'accès
    private $refreshToken;  // jeton de rafraichissement
    public $tokenEndpoint = 'https://api-login.ebp.com/connect/token'; // url de l'endpoint de jeton
    public $authorizationEndpoint = 'https://api-login.ebp.com/connect/authorize'; // url de l'endpoint d'autorisation

    /**
     * Constructeur
     *
     * @param string $id_client
     * @param string $clientSecret
     * @param string $redirectUri
     */
    public function __construct($id_client, $clientSecret, $redirectUri)
    {
        $this->id_client = $id_client;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }


    /**
     * Récupère le code d'autorisation
     *
     * @return string
     */
    public function getCode()
    {
        $params = [
            'client_id' => $this->id_client,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => 'openid profile',
            'code_challenge_method' => 'S256',
            'code_challenge' => $this->generateCodeVerifier()
        ];

        $url = $this->authorizationEndpoint . '?' . http_build_query($params);

        header('Location: ' . $url);
        exit;
    }

    /**
     * Récupère le jeton d'accès
     *
     * @return string
     */
    public function getAccessToken()
    {
        $this->code = $_GET['code'];

        $data = [
            'client_id' => $this->id_client,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $this->code,
            'code_verifier' => $_COOKIE['code_verifier'],
            'redirect_uri' => $this->redirectUri
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($this->tokenEndpoint, false, $context);
        $token = json_decode($response, true);

        $this->accessToken = $token['access_token'];
        $this->refreshToken = $token['refresh_token'];

        return $this->accessToken;
    }

    /**
     * Génère un code aléatoire
     *
     * @return string
     */
    private function generateCodeVerifier()
    {
        $code_verifier = bin2hex(random_bytes(32));
        setcookie('code_verifier', $code_verifier, time() + 3600);
        return $code_verifier;
    }
}