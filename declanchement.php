<?php
// initialisation des variables

include('siteweb/requetesbdd/connect.php');
include('classes/Zeendoc.php');

// récupération des données de la base de données
$req = $bdd->query('SELECT * FROM CLIENT');

// pour chaque client
foreach ($req as $donnees) {

    // initialisation des variables
    $url = $donnees['url_client'];
    $login = $donnees['login'];
    $password = $donnees['mot_de_passe'];

    // création d'un objet Zeendoc
    $zeendoc = new Zeendoc($url);

    // connexion à Zeendoc
    $co = $zeendoc->connect($login, $password);

    // récupération les classeurs qui ont un index BAP
    $classeurs = $zeendoc->getIndexBAP();



    // pour chaque classeur
    foreach ($classeurs as $classeur) {

        // récupération des variables
        $Coll_Id = $classeur['Coll_Id'];
        $Index_Id = $classeur['Index_Id'];


        // récupération des documents du classeur
        $Wanted_Columns = 'filename;' . $Index_Id;
        $docs = $zeendoc->searchBAPDoc($Coll_Id, $Index_Id);


        // pour chaque document
        foreach ($docs as $doc) {

            // récupération du document
            $document = $zeendoc->getDocument($Coll_Id, $doc["Document"]['Res_Id'], $Wanted_Columns);
            $zeendoc->changeBAP($Coll_Id, $doc["Document"]['Res_Id'], $Index_Id);
        }
    }
}