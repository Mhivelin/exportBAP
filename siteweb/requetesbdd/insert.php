<?php

// connexion à la base de données
require_once('connect.php');

// inclusion de la classe Zeendoc
include('../../classes/Zeendoc.php');

// verification des variables
if (isset($_POST['url']) && isset($_POST['login']) && isset($_POST['password'])) {

    try {
        // création d'un objet Zeendoc
        $zeendoc = new Zeendoc($_POST['url']);

        // connexion à Zeendoc
        $co = $zeendoc->connect($_POST['login'], $_POST['password']);

        // si la connexion échoue, on redirige vers la page d'accueil avec un message d'erreur
        if (isset($co->faultstring)) {
            header('Location: ../index.php?message=erreurConnexion');
            exit();
        }
        // si la connexion réussit, on ajoute la source dans la base de données
        else {
            $url = $_POST['url'];
            $login = $_POST['login'];
            $password = $_POST['password'];



            $req = $bdd->prepare('INSERT INTO CLIENT(url_client, login, mot_de_passe) VALUES(:url_client, :login, :mot_de_passe)');
            $req->execute(array(
                'url_client' => $url,
                'login' => $login,
                'mot_de_passe' => $password
            ));


            // récupération des classeurs qui ont un index BAP
            $liste_classeur_BAP = $zeendoc->getIndexBAP();

            // récupération de l'id du client ajouté
            $id_client = $bdd->lastInsertId();

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


            header('Location: ../index.php?message=ajouté');
            exit();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}