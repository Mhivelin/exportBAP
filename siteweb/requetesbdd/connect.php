<?php
$host = 'dm435171-001.eu.clouddb.ovh.net';
$user = 'API-deltic';
$password = 'APIdeltic123';
$dbname = 'API';
$port = '35219';
try {
    $bdd = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // Effectuez vos opérations sur la base de données ici
} catch (PDOException $e) {
    // Gérez les erreurs de connexion ici
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}