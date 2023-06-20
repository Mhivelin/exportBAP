<?php

require_once 'Zeendoc.php';

$zeendoc = new Zeendoc();
$zeendoc->connect('marius.hivelin@gmail.com', 'X?BSh:R92EmyDKi');

$classeurs = $zeendoc->getIndexBAP();

echo '<br>classseurs : ';
var_dump($classeurs);

echo '<br><br>';

foreach ($classeurs as $classeur) {
    $Coll_Id = $classeur['Coll_Id'];
    echo 'Coll_Id : ' . $Coll_Id . '<br>';
    $Index_Id = $classeur['Index_Id'];
    echo 'Index_Id : ' . $Index_Id . '<br>';

    echo 'nb docs : ' . $zeendoc->getNbBAPDoc($Coll_Id, $Index_Id) . '<br>';

    //$docs = $zeendoc->searchAllDoc(); // string(344) "{"Result":0,"Nb_Docs":4,"Stats":[],"Document":[{"Res_Id":4,"Coll_Id":"coll_21","Version":0,"Properties":[],"Indexes":[]},{"Res_Id":1,"Coll_Id":"coll_23","Version":0,"Properties":[],"Indexes":[]},{"Res_Id":2,"Coll_Id":"coll_21","Version":0,"Properties":[],"Indexes":[]},{"Res_Id":1,"Coll_Id":"coll_21","Version":0,"Properties":[],"Indexes":[]}]}"
    //$docs = json_decode($docs, true);

    $docs = $zeendoc->searchBAPDoc($Coll_Id, $Index_Id);

    echo 'docs : <br>';

    var_dump($docs);

    echo '<br><br>';
}