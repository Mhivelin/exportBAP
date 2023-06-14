<?php
// entete de toutes les pages

// on demarre la session
session_start();

require_once('requetesbdd/connect.php');

require_once('../classes/Zeendoc.php');


?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sources API BAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>

    <?php
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
        if ($message == 'ajouté') {
            echo '<div class="alert alert-success" role="alert">
            source ajoutée
            </div>';
        } else if ($message == 'erreurConnexion') {
            echo '<div class="alert alert-danger" role="alert">
            erreur de connexion
            </div>';
        } else if ($message == 'supprimé') {
            echo '<div class="alert alert-success" role="alert">
            source supprimée
            </div>';
        } else if ($message == 'erreursuppr') {
            echo '<div class="alert alert-danger" role="alert">
            erreur de suppression
            </div>';
        } else if ($message == 'sourceModifiee') {
            echo '<div class="alert alert-success" role="alert">
            source modifiée
            </div>';
        }
    }
    ?>
    <div class="container">




        <h2>Ajout de sources</h2>

        <form method="post" action="requetesbdd/insert.php">
            <label for="url">url client</label>
            <input type="text" name="url" id="url" class="form-control" placeholder="url" required autofocus>
            <label for="login">login</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="login" required autofocus>
            <label for="password">password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="password" required
                autofocus>

            <button class=" btn btn-lg btn-primary btn-block" type="submit">ajouter</button>
        </form>




        <br>



        <h2>Liste des sources</h2>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>url</th>
                    <th>login</th>
                    <th>classeurs</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $req = $bdd->query('SELECT * FROM CLIENT');


                foreach ($req as $donnees) {
                    $zeendoc = new Zeendoc($donnees['url_client']);
                    $co = $zeendoc->connect($donnees['login'], $donnees['mot_de_passe']);
                ?>
                <tr>
                    <td><?php echo $donnees['url_client']; ?></td>
                    <td><?php echo $donnees['login']; ?></td>

                    <td>
                        <ul>
                            <?php
                                $classeurs = $bdd->query('SELECT * FROM CLASSEUR WHERE id_client = ' . $donnees['id_client']);
                                ?>
                            <table class="table table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>id classeur</th>
                                        <th>index BAP</th>
                                        <th>à exporter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($classeurs as $classeur) {
                                            echo '<tr>';
                                            echo '<td>' . $classeur['id_classeur'] . '</td>';
                                            echo '<td>' . $classeur['index_BAP'] . '</td>';
                                            echo '<td>' . $zeendoc->getNbBAPDoc($classeur['id_classeur'], $classeur['index_BAP']) . '</td>';
                                        }
                                        ?>

                                </tbody>
                            </table>

                    </td>



                    <td>

                        <a href="requetesbdd/update.php?id=<?php echo $donnees['id_client']; ?>"
                            class="btn btn-primary">↺</a>
                        <a href="requetesbdd/suppr.php?id=<?php echo $donnees['id_client']; ?>"
                            class="btn btn-danger">x</a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>

        </table>

        <h3>Export des BAP : </h3>
        <!-- boutton qui lance declanchement.php en arriere plan avec curl -->
        <form method="post" action="../declanchement.php">
            <button class=" btn btn-lg btn-primary btn-block" type="submit">export</button>
        </form>