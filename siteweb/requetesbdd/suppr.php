<?php

require_once('connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // on supprime les classeurs
    $req = $bdd->prepare('DELETE FROM CLASSEUR WHERE id_client = :id_client');
    $req->execute(array(
        'id_client' => $id
    ));

    // on supprime le client
    $req = $bdd->prepare('DELETE FROM CLIENT WHERE id_client = :id_client');
    $req->execute(array(
        'id_client' => $id
    ));









    header('Location: ../index.php?message=supprimé');
    exit();
} else {
    header('Location: ../index.php?message=erreursuppr');
    exit();
}