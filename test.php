<?php
/*
$password = 'API-deltic123';
$password = md5($password);
echo $password;*/

require_once 'classes/Zeendoc.php';

require_once 'siteweb/requetesbdd/connect.php';


$req = $bdd->query('SELECT * FROM CLIENT');


foreach ($req as $donnees) {
    $zeendoc = new Zeendoc($donnees['url_client']);
    $co = $zeendoc->connect($donnees['login'], $donnees['mot_de_passe']);
    $rights = $zeendoc->getRights();
    $coll = $rights['Collections'];

    foreach ($coll as $key => $value) {
        $collList[] = $value['Coll_Id'];

        $test = $zeendoc->getDocSelly($value['Coll_Id']);

        foreach ($test as $key => $value) {
            echo $value['Label'] . '<br>';
        }
    }
}