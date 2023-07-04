<?php
// entete de toutes les pages

// on demarre la session
session_start();

// connexion à la base de données
require_once('requetesbdd/connect.php');

// ajout des classes
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
    <link href="../css/style.css" rel="stylesheet">

</head>

<body>

    <?php
    // message de confirmation ou d'erreur
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





        <!-- formulaire d'ajout de source -->

        <h2>Ajout de sources</h2>

        <form method="post" action="requetesbdd/insert.php">
            <label for="url">url client</label>
            <input type="text" name="url" id="url" class="form-control" placeholder="url" required autofocus>
            <label for="login">login</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="login" required autofocus>
            <label for="password">password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="password" required
                autofocus>
            <label for="logiciel">logiciel</label>

            <!-- liste deroulante des logiciels vers lesquels exporter les documents (de base sur selectionner) -->
            <select name="logiciel" id="logiciel" class="form-control">
                <option value="selectionner">selectionner</option>
                <option value="EBP">EBP</option>
                <option value="SAGE">SAGE</option>



            </select>

            <br>

            <button class=" btn btn-lg btn-primary btn-block" type="submit">ajouter</button>
        </form>




        <br>

        <!-- liste des sources -->

        <h2>Liste des sources</h2>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>url
                        <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="url de la source">
                            <title>information-circle</title>
                            <g id="Layer_2" data-name="Layer 2">
                                <g id="invisible_box" data-name="invisible box">
                                    <rect width="48" height="48" fill="none" />
                                </g>
                                <g id="icons_Q2" data-name="icons Q2">
                                    <path
                                        d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                    <path d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                    <circle cx="24" cy="14" r="2" />
                                </g>
                            </g>

                        </svg>
                    <th>logiciel
                        <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="logiciel vers lequel exporter les BAP">
                            <title>information-circle</title>
                            <g id="Layer_2" data-name="Layer 2">
                                <g id="invisible_box" data-name="invisible box">
                                    <rect width="48" height="48" fill="none" />
                                </g>
                                <g id="icons_Q2" data-name="icons Q2">
                                    <path
                                        d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                    <path d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                    <circle cx="24" cy="14" r="2" />
                                </g>
                            </g>

                        </svg>
                    </th>
                    <th>classeurs
                        <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="classeurs de la source">
                            <title>information-circle</title>
                            <g id="Layer_2" data-name="Layer 2">
                                <g id="invisible_box" data-name="invisible box">
                                    <rect width="48" height="48" fill="none" />
                                </g>
                                <g id="icons_Q2" data-name="icons Q2">
                                    <path
                                        d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                    <path d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                    <circle cx="24" cy="14" r="2" />
                                </g>
                            </g>

                        </svg>
                    </th>
                    <th>actions</th>

                </tr>
            </thead>
            <tbody>
                <?php

                // on recupere les sources dans la base de données
                $req = $bdd->query('SELECT * FROM CLIENT');

                // on affiche les sources
                foreach ($req as $donnees) {

                    $zeendoc = new Zeendoc($donnees['url_client']);
                    $co = $zeendoc->connect($donnees['login'], $donnees['mot_de_passe']);



                ?>
                <tr>
                    <td><?php echo $donnees['url_client']; ?></td>
                    <td><?php echo $donnees['logiciel']; ?></td>

                    <td>
                        <ul>
                            <?php
                                //on recupere les classeurs de la source avec un customIndex BAP
                                $classeurs = $zeendoc->getIndexBAP();



                                ?>
                            <table class="table table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>id classeur
                                            <svg width="20" height="20" viewBox="0 0 48 48"
                                                xmlns="http://www.w3.org/2000/svg" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="id des classeurs qui ont un index BAP">
                                                <title>information-circle</title>
                                                <g id="Layer_2" data-name="Layer 2">
                                                    <g id="invisible_box" data-name="invisible box">
                                                        <rect width="48" height="48" fill="none" />
                                                    </g>
                                                    <g id="icons_Q2" data-name="icons Q2">
                                                        <path
                                                            d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                                        <path
                                                            d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                                        <circle cx="24" cy="14" r="2" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </th>
                                        <th>index BAP
                                            <svg width="20" height="20" viewBox="0 0 48 48"
                                                xmlns="http://www.w3.org/2000/svg" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="index custom correspondant à BAP">
                                                <title>information-circle</title>
                                                <g id="Layer_2" data-name="Layer 2">
                                                    <g id="invisible_box" data-name="invisible box">
                                                        <rect width="48" height="48" fill="none" />
                                                    </g>
                                                    <g id="icons_Q2" data-name="icons Q2">
                                                        <path
                                                            d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                                        <path
                                                            d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                                        <circle cx="24" cy="14" r="2" />
                                                    </g>
                                                </g>
                                            </svg>
                                        <th>à exporter
                                            <svg width="20" height="20" viewBox="0 0 48 48"
                                                xmlns="http://www.w3.org/2000/svg" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="nombre de documents BAP à exporter">
                                                <title>information-circle</title>
                                                <g id="Layer_2" data-name="Layer 2">
                                                    <g id="invisible_box" data-name="invisible box">
                                                        <rect width="48" height="48" fill="none" />
                                                    </g>
                                                    <g id="icons_Q2" data-name="icons Q2">
                                                        <path
                                                            d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                                        <path
                                                            d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                                        <circle cx="24" cy="14" r="2" />
                                                    </g>
                                                </g>
                                            </svg>
                                        <th>actions
                                            <svg width="20" height="20" viewBox="0 0 48 48"
                                                xmlns="http://www.w3.org/2000/svg" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="actions possibles sur les BAP : exporter tout les documents BAP du classeur et copier l'url de la requete d'export (pour l'instalation sur zeendoc)">
                                                <title>information-circle</title>
                                                <g id="Layer_2" data-name="Layer 2">
                                                    <g id="invisible_box" data-name="invisible box">
                                                        <rect width="48" height="48" fill="none" />
                                                    </g>
                                                    <g id="icons_Q2" data-name="icons Q2">
                                                        <path
                                                            d="M24,2A22,22,0,1,0,46,24,21.9,21.9,0,0,0,24,2Zm0,40A18,18,0,1,1,42,24,18.1,18.1,0,0,1,24,42Z" />
                                                        <path
                                                            d="M24,20a2,2,0,0,0-2,2V34a2,2,0,0,0,4,0V22A2,2,0,0,0,24,20Z" />
                                                        <circle cx="24" cy="14" r="2" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // on affiche les classeurs
                                        foreach ($classeurs as $classeur) {
                                            echo '<tr>';
                                            echo '<td>' . $classeur['Coll_Id'] . '</td>';
                                            echo '<td>' . $classeur['Index_Id'] . '</td>';
                                            echo '<td>' . $zeendoc->getNbBAPDoc($classeur['Coll_Id'], $classeur['Index_Id']) . '</td>';
                                            $urlexport = '../requetesAPI/export.php?id=' . $classeur['Coll_Id'] . '&customIndex=' . $classeur['Index_Id'] . '&url=' . $donnees['url_client'];
                                        ?>
                                    <!-- boutton qui lance declanchement.php sans changer de page -->

                                    <td>
                                        <a href="<?php $urlexport ?>" class="btn btn-primary" title="exporter">
                                            <svg stroke="currentColor" alt="export" fill="none" stroke-width="2"
                                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
                                                class="h-4 w-4" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                                <polyline points="16 6 12 2 8 6"></polyline>
                                                <line x1="12" y1="2" x2="12" y2="15"></line>
                                            </svg>
                                        </a>


                                        <!-- boutton qui permet de copier l'url de la requete d'export -->
                                        <button class="btn btn-primary" title="copier l'url"
                                            onclick="copyToClipboard('<?php echo $urlexport ?>')"><svg fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2">
                                                </path>
                                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                            </svg></button>



                                    </td>


                </tr>
                <?php
                                        }
                ?>

            </tbody>
        </table>

        </td>



        <td>

            <!-- boutton qui permet de supprimer une source -->
            <a href="requetesbdd/suppr.php?id=<?php echo $donnees['id_client']; ?>" class="btn btn-danger">
                <svg stroke=" currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                    stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                    </path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </a>


        </td>
        </tr>
        <?php
                }

    ?>
        </tbody>
        </table>






    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
    // -----------copie de l' url de l'export---------------- //
    function copyToClipboard(text) {
        var
            dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute('value', text);
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
    }

    // -----------activation des tooltips---------------- //
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    </script>


</body>



</html>