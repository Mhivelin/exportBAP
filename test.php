<?php

require_once('classes/Ebp.php');

// initialisation des variables
$id_client = 'jupiterwithoutpkce';
$clientSecret = '78f68eac-c4e2-4221-9836-d66db48a75f0';
$redirectUri = 'http://192.168.75.154/exportBAPDELTIC/test.php';

// création d'un objet EBP
$ebp = new EBP($id_client, $clientSecret, $redirectUri);

$ebp->getCode();


var_dump($ebp->code);

echo '<br><br>';

$ebp->getAccessToken();