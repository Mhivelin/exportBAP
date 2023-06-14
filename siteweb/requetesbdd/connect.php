<?php

$host = 'localhost';
$login = 'delticAPI';
$password = 'delticAPI';

$bdd = new PDO('mysql:host=' . $host . ';dbname=deltic;charset=utf8', $login, $password);