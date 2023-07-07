<?php

/**
 * Genere un code verifier aleatoire
 *
 * @return string
 */
function generateCodeVerifier()
{
    $bytes = random_bytes(32);
    return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
}
/**
 * Attend le retour de l'authentification
 *
 * @param integer $timeoutInSeconds
 * @return string|null
 */
function waitForCallback($timeoutInSeconds = 15)
{
    $start = time();
    $result = null;

    while ($result === null && time() - $start < $timeoutInSeconds) {
        usleep(100000); // 100ms
        $result = $_GET['code'] ?? null;
    }

    return $result;
}

/**
 * Recupere le token d'acces
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param string $code
 * @param string $codeVerifier
 * @param string $redirectUri
 * @return string
 */
function getAccessToken($clientId, $clientSecret, $code, $codeVerifier, $redirectUri)
{
    $data = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'code_verifier' => $codeVerifier,
        'redirect_uri' => $redirectUri
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents('https://api-login.ebp.com/connect/token', false, $context);
    $token = json_decode($response, true);

    return $token['access_token'];
}

/**
 * Effectue une requete vers l'API
 *
 * @param string $accessToken
 * @param string $subscriptionKey
 * @param string $url
 * @return string|false
 */
function makeApiRequest($accessToken, $subscriptionKey, $url)
{
    $options = [
        'http' => [
            'header' => "Authorization: Bearer $accessToken\r\n" .
                "ebp-subscription-key: $subscriptionKey\r\n",
            'method' => 'GET',
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    return $response;
}

// VARIABLES A RENSEIGNER
$clientId = 'jupiterwithoutpkce';                       // Client ID
$clientSecret = '78f68eac-c4e2-4221-9836-d66db48a75f0'; // Client Secret
$subscriptionKey = 'ded59b2d14d44e24b6bd1ae64ca45d6d';  // Subscription Key

// lien de redirection vers l'application
$redirectUri = 'http://192.168.75.154/exportBAP/ebptest2.php';

// Generer le code challenge
$codeVerifier = generateCodeVerifier();
$codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

// Construire l'URL de redirection vers l'authentification
//  https://api-login.ebp.com/connect/token body (application/x-www-form-urlencoded): client_id={clientId}&redirect_uri=https://localhost:3333/api/login/SigninRedirect&grant_type=authorization_code&code=swWZ-eNBDn8rpiTrvzuZVZ-ZwrdOplLPM6vaegaDOho
$url = 'https://api-login.ebp.com/connect/authorize' .
    '?client_id=' . $clientId .
    '&redirect_uri=' . $redirectUri .
    '&response_type=code' .
    '&scope=openid' .
    '&code_challenge=' . $codeChallenge .
    '&code_challenge_method=S256';

// Rediriger l'utilisateur vers l'URL
header("Location: $url");

// Attendre le retour de l'authentification
$code = waitForCallback();

$data = [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'code_verifier' => $codeVerifier
];
var_dump($data);

$accessToken = getAccessToken($clientId, $clientSecret, $code, $codeVerifier, $redirectUri);

$apiUrl = 'https://api-developpeurs.ebp.com/gescom/api/v1/Folders';

$response = makeApiRequest($accessToken, $subscriptionKey, $apiUrl);

if ($response !== false) {
    echo "\n\n";
    echo $response;
} else {
    echo "Erreur\n\n";
}