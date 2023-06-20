<?php
// initialisation des variables

include('siteweb/requetesbdd/connect.php');
include('classes/Zeendoc.php');

$req = $bdd->query('SELECT * FROM CLIENT');

foreach ($req as $donnees) {

    $url = $donnees['url_client'];
    $login = $donnees['login'];
    $password = $donnees['mot_de_passe'];

    // création d'un objet Zeendoc
    $zeendoc = new Zeendoc($url);

    // connexion à Zeendoc
    $co = $zeendoc->connect($login, $password);

    $classeurs = $bdd->query('SELECT * FROM CLASSEUR WHERE id_client = ' . $donnees['id_client']);

    foreach ($classeurs as $classeur) {
        $zeendoc->changeBAP($classeur['id_classeur'], $classeur['index_BAP']
    }

    
}

// header('Location: siteweb/index.php');