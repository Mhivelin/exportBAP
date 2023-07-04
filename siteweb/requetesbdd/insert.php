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
            $logiciel = $_POST['logiciel'];



            $req = $bdd->prepare('INSERT INTO CLIENT(url_client, login, mot_de_passe, logiciel) VALUES(:url_client, :login, :mot_de_passe, :logiciel)');
            $req->execute(array(
                'url_client' => $url,
                'login' => $login,
                'mot_de_passe' => $password,
                'logiciel' => $logiciel
            ));






            header('Location: ../index.php?message=ajouté');
            exit();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}