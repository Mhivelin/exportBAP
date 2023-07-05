<?php

// requetesbdd/export.php?id=coll_21&customIndex=custom_n7&url=deltic_demo

// initialisation des variables
include('../siteweb/requetesbdd/connect.php');
include('../classes/Zeendoc.php');



$id = $_GET['id'];
$url = $_GET['url'];
$customIndex = $_GET['customIndex'];


// récupération du client
$req = $bdd->query('SELECT * FROM CLIENT WHERE url_client = "' . $url . '"');
$donnees = $req->fetch();


// initialisation des variables
$url = $donnees['url_client'];
$login = $donnees['login'];
$password = $donnees['mot_de_passe'];

// création d'un objet Zeendoc
$zeendoc = new Zeendoc($url);

// connexion à Zeendoc
$co = $zeendoc->connect($login, $password);

// récupération les classeurs qui ont un index BAP
$docs = $zeendoc->searchBAPDoc($id, $customIndex);

// ------------------------- exportation des documents ------------------------- //

// ----------------------------------------------------------------------------- //

// changement de l'index BAP
foreach ($docs as $doc) {
    var_dump($doc);
    $zeendoc->changeBAP($id, $doc['Document']['Res_Id'], $customIndex);
    //$collId, $resId, $indexCustom
}

// retour a la page précédente
header('Location: ' . $_SERVER['HTTP_REFERER']);