<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>connect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet">

</head>

<body>
    <?php
    // récupération des erreurs
    if (isset($_GET['erreur'])) {
        $err = $_GET['erreur'];
        if ($err == 1) {
            echo "<div class='alert alert-danger'>Utilisateur ou mot de passe incorrect</div>";
        } else if ($err == 2) {
            echo "<div class='alert alert-danger'>Veuillez vous connecter pour accéder à cette page</div>";
        } else if ($err == 3) {
            echo "<div class='alert alert-danger'>Identifiant incorrect</div>";
        } else if ($err == 4) {
            echo "<div class='alert alert-danger'>Session expirée. Veuillez vous connecter à nouveau</div>";
        }
    }
    ?>

    <div class="container">

        <h1>Connexion à l'API BAP</h1>

        <form action="verif_session.php" method="post">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" name="login" placeholder="Login" class="form-control" id="login" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" placeholder="Mot de passe" class="form-control" id="password"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

    </div>

</body>

</html>