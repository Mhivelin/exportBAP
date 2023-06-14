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

    // on recupere les classeurs et les index BAP de la source

    $liste_classeur_BAP = $bdd->query('SELECT * FROM CLASSEUR WHERE id_client = ' . $donnees['id_client']);

    // on ajoute les nouveaux classeurs dans la base de données
    foreach ($liste_classeur_BAP as $classeur) {

        $Coll_Id = $classeur['id_classeur'];
        $Index_Id = $classeur['index_BAP'];

        // on ajoute les classeurs dans la base de données
        $req = $bdd->prepare('INSERT INTO CLASSEUR(id_classeur, index_BAP, id_client) VALUES(:id_classeur, :index_BAP, :id_client)');
        $req->execute(array(
            'id_classeur' => $Coll_Id,
            'index_BAP' => $Index_Id,
            'id_client' => $donnees['id_client']
        ));
    }
}

header('Location: siteweb/index.php');