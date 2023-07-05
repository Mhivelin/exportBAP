<?php

// ---------- page de vérification de la session ---------------- //

session_start();

if (isset($_SESSION['login']) && isset($_SESSION['password'])) {
    $login = $_SESSION['login'];
    if ($login == 'admin') {
        $password = $_SESSION['password'];
        $password = md5($password);
        if ($password != 'e6ee2495719bda6a3e76087a45a5d7cb') {
            header('Location: connection.php?erreur=1');
            exit();
        } else {
            // retour à la page précédente
            header('Location: ..//index.php');
            exit();
        }
    } else {
        header('Location: connection.php?erreur=3');
        exit();
    }
} else if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    if ($login == 'admin') {
        $password = $_POST['password'];
        $password = md5($password);
        if ($password != '676049a04381dbd6577b25b739212e11') {
            header('Location: connection.php?erreur=2');
            exit();
        } else {
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;

            header('Location: ../index.php');
            exit();
        }
    } else {
        header('Location: connection.php?erreur=3');
        exit();
    }
} else {
    header('Location: connection.php?erreur=4');
    exit();
}