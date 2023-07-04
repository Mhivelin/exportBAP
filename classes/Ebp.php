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
     * Obtention du jeton d'accès
     *
     * @return void
     */
    public function getAccessToken()
    {
        /* exemple de requête POST
        Post	url : https://api-login.ebp.com/connect/token 
        body (application/x-www-form-urlencoded): 
            client_id={clientId}
            &redirect_uri=https://localhost:3333/api/login/SigninRedirect
            &grant_type=authorization_code
            &code=swWZ-eNBDn8rpiTrvzuZVZ-ZwrdOplLPM6vaegaDOho
        // Paramètres de la requête POST pour obtenir le jeton d'accès
        */
        $url = $this->tokenEndpoint;
        $id_client = $this->id_client;
        $clientSecret = $this->clientSecret;
        $redirectUri = $this->redirectUri;
        $grant_type = 'authorization_code';
        $code = $this->code;

        // création de la requête
        $data = array(
            'client_id' => $id_client,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => $grant_type,
            'code' => $code
        );

        // création de la requête
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // récupération de la réponse
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);
    }





    /**
     * obtention du code d'autorisation
     * 
     * @return void
     */
    public function getCode()
    {
        if (isset($_GET['code'])) {
            $this->code = $_GET['code'];
        } else {

            // initialisation des variables
            $url = $this->authorizationEndpoint;
            $id_client = $this->id_client;
            $redirectUri = $this->redirectUri;
            $scope = 'openid profile offline_access';
            $state = '4e2a15864f564bd19375999c394baa01';
            $response_mode = 'query';
            $response_type = 'code';

            // création de la requête
            $url .= '?client_id=' . $id_client;
            $url .= '&redirect_uri=' . $redirectUri;
            $url .= '&response_type=' . $response_type;
            $url .= '&scope=' . $scope;
            $url .= '&state=' . $state;
            $url .= '&response_mode=' . $response_mode;

            // redirection vers l'url
            header('Location: ' . $url);
            exit();
        }
    }
}