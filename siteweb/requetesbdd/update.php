<?php

// connexion à la base de données
require_once('connect.php');

// inclusion de la classe Zeendoc
include('../../classes/Zeendoc.php');


if (isset($_GET['id'])) {


    $id_client = $_GET['id'];

    $req = $bdd->prepare('SELECT * FROM CLIENT WHERE id_client = :id_client');

    $req->execute(array(
        'id_client' => $id_client
    ));

    $donnees = $req->fetch();



    $url = $donnees['url_client'];
    $login = $donnees['login'];
    $password = $donnees['mot_de_passe'];






    try {
        // création d'un objet Zeendoc
        $zeendoc = new Zeendoc($url);

        // connexion à Zeendoc
        $co = $zeendoc->connect($login, $password);





        // on recupere les classeurs et les index BAP de la source

        $liste_classeur_BAP = $zeendoc->getIndexBAP();

        // on ajoute les nouveaux classeurs dans la base de données
        foreach ($liste_classeur_BAP as $classeur) {

            $Coll_Id = $classeur['Coll_Id'];
            $Index_Id = $classeur['Index_Id'];

            // on ajoute les classeurs dans la base de données
            $req = $bdd->prepare('INSERT INTO CLASSEUR(id_classeur, index_BAP, id_client) VALUES(:id_classeur, :index_BAP, :id_client)');
            $req->execute(array(
                'id_classeur' => $Coll_Id,
                'index_BAP' => $Index_Id,
                'id_client' => $id_client
            ));
        }

        // on supprime les classeurs qui ne sont plus dans la source
        $req = $bdd->prepare('SELECT * FROM CLASSEUR WHERE id_client = :id_client');
        $req->execute(array(
            'id_client' => $id_client
        ));

        $liste_classeur = $req->fetchAll();

        foreach ($liste_classeur as $classeur) {
            $id_classeur = $classeur['id_classeur'];
            $index_BAP = $classeur['index_BAP'];

            $trouve = false;

            foreach ($liste_classeur_BAP as $classeur_BAP) {
                if ($classeur_BAP['Coll_Id'] == $id_classeur) {
                    $trouve = true;
                }
            }

            if ($trouve == false) {
                $req = $bdd->prepare('DELETE FROM CLASSEUR WHERE id_classeur = :id_classeur');
                $req->execute(array(
                    'id_classeur' => $id_classeur
                ));
            }
        }

        // redirection vers la page d'accueil
        header('Location: ../index.php?message=sourceModifiee');
        exit();
    } catch (Exception $e) {
        header('Location: ../index.php?message=erreurConnexion');
        exit();
    }
}