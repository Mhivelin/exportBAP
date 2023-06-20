<?php

/**
 * Classe ZeenDoc
 */
class ZeenDoc
{
    private $wsdl;               // URL du fichier WSDL
    private $service_location;   // URL de l'emplacement du service
    private $service_uri;        // URI du service
    public $client;             // Client SOAP

    /**
     * Constructeur de la classe ZeenDoc
     * @param string $UrlClient - URL du client
     */
    public function __construct(string $UrlClient = "deltic_demo")
    {
        $this->wsdl = "https://armoires.zeendoc.com/" . $UrlClient . "/ws/3_0/wsdl.php?WSDL";
        $this->service_location = "https://armoires.zeendoc.com/" . $UrlClient . "/ws/3_0/Zeendoc.php";
        $this->service_uri = "https://armoires.zeendoc.com/" . $UrlClient . "/ws/3_0/";
    }

    /**
     * Connecte l'utilisateur au service ZeenDoc
     * @param string $userLogin - Nom d'utilisateur
     * @param string $userCPassword - Mot de passe de l'utilisateur
     * @return mixed - Résultat de la connexion ou une exception SoapFault en cas d'erreur
     */
    public function connect(string $userLogin, string $userCPassword)
    {
        ini_set('soap.wsdl_cache_enabled', "0");

        $options = array(
            'location' => $this->service_location,
            'uri' => $this->service_uri,
            'trace' => true,
            'exceptions' => true,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS + SOAP_USE_XSI_ARRAY_TYPE
        );

        try {
            $this->client = new SoapClient($this->wsdl, $options);

            // Appel de la méthode 'login' du service SOAP
            $result = $this->client->__soapCall(
                'login',
                array(
                    'Login' => $userLogin,
                    'Password' => '',
                    'CPassword' => $userCPassword
                )
            );

            if (isset($result->Error_Msg)) {
                echo "<div class='alert alert-danger' role='alert'>Erreur : " . $result->Error_Msg . "</div>";
            } else {
            }
        } catch (SoapFault $fault) {
            return $fault;
        }
    }

    /**
     * Récupère le nombre de documents dans un classeur spécifié
     * @param string $collId - Identifiant de la collection
     * @return mixed - Résultat de la requête ou une exception SoapFault en cas d'erreur
     */
    public function getNBDocument(string $collId)
    {
        // fonction qui permet de récupérer le nombre de document d'une collection

        // Appel de la méthode 'getNbDoc' du service SOAP
        $result = $this->client->__soapCall(
            'getNbDoc',
            array(
                'Coll_Id' => $collId,                                           // Identifiant de la collection
                'IndexList' => new SoapParam('', 'IndexList'),                  // Liste vide d'index
                'StrictMode' => new SoapParam('', 'StrictMode'),                // Mode strict désactivé
                'Fuzzy' => new SoapParam('', 'Fuzzy'),                          // Recherche floue désactivée
                'Order_Col' => new SoapParam('', 'Order_Col'),                  // Aucune colonne de tri spécifiée
                'Order' => new SoapParam('', 'Order'),                          // Ordre de tri par défaut
                'Saved_Query_Id' => new SoapParam(240, 'Saved_Query_Id'),       // Identifiant de la requête sauvegardée non spécifié
                'Query_Operator' => new SoapParam('', 'Query_Operator')         // Opérateur de requête par défaut
            )
        );

        if (isset($result->Error_Msg)) {
            echo "<div class='alert alert-danger' role='alert'>Erreur : " . $result->Error_Msg . "</div>";
        } else {
            return $result;
        }
    }

    /**
     * Récupère les documents d'un classeur spécifié
     * @param string $collId - Identifiant de la collection
     * @param string $resId - Identifiant du document
     * @param string $Wanted_Columns - Colonnes souhaitées
     * @return mixed - Résultat de la requête ou une exception SoapFault en cas d'erreur
     */
    public function getDocument($collId, $resId, $Wanted_Columns = 'filename')
    {

        // fonction qui permet de récupérer les documents d'une collection
        // champs souhaités : Code journal;Date;N° de compte;Compte auxiliaire;Pièce;Document;Libellé;Débit;Crédit;Date de l'échéance;Moyen de paiement;N° de ligne pour les documents associés;Documents associés;N° de ligne pour les ventilations analytiques;Plan analytique;Poste analytique;Montant de la ventilation analytique;Notes;Intitulé du compte;Information libre 1;
        // champs souhaités (custom) : 
        $result = $this->client->__soapCall(
            "getDocument",
            array(
                'Coll_Id' => $collId,                                                           // Identifiant de la collection
                'Res_Id' => new SoapParam($resId, 'Res_Id'),                                    // Identifiant du document
                'Upload_Id' => new SoapParam('', 'Upload_Id'),                                  // Identifiant de l'upload
                'Comments' => new SoapParam('', 'Comments'),                                    // Commentaires
                'Lines_ConfigFileName' => new SoapParam('', 'Lines_ConfigFileName'),            // Nom du fichier de configuration des lignes
                'Wanted_Columns' => new SoapParam($Wanted_Columns, 'Wanted_Columns')      // Colonnes souhaitées
            )
        );

        if (isset($result->Error_Msg)) {
            echo "<div class='alert alert-danger' role='alert'>Erreur : " . $result->Error_Msg . "</div>";
        } else {
            return $result;
        }
    }

    public function getRights()
    {
        // fonction qui permet de récupérer toutes les informations de l'utilisateur connecté
        $result = $this->client->__soapCall(
            'getRights',
            array(
                'Get_ConfigSets' => 1
            )
        );

        $result = json_decode($result, true);

        if (isset($result['Error_Msg'])) {
            echo "<div class='alert alert-danger' role='alert'>Erreur : " . $result['Error_Msg'] . "</div>";
        } else {
            return $result;
        }
    }

    public function getInfoPerso()
    {
        //fonction qui permet de récupérer les informations de l'utilisateur connecté
        $result = $this->getRights();

        $infosUser = $result['User'];

        return $infosUser;
    }

    public function getClassList()
    {
        //fonction qui permet de récupérer la liste des classeurs de l'utilisateur connecté
        $result = $this->getRights();

        $collections = $result['Collections'];

        foreach ($collections as $key => $value) {
            $collList[] = $value['Coll_Id'];
        }
        return $collList;
    }

    public function getIndexBAP()
    {
        //fonction qui permet de récupérer la liste des index BAP
        $result = $this->getRights();

        $result = $result['Collections']/*['Index']*/;



        $indexBAP = array();
        foreach ($result as $classeur) {
            if (isset($classeur['Index'])) {
                foreach ($classeur['Index'] as $index) {
                    if ($index['Label'] == 'BAP') {
                        $indexBAP[] = [
                            'Coll_Id' => $classeur['Coll_Id'],
                            'Index_Id' => $index['Index_Id'],

                        ];
                    }
                }
            }
        }





        return $indexBAP;
    }

    public function getNbBAPDoc($collId, $indexCustom)

    {

        $liste_doc = $this->searchBAPDoc($collId, $indexCustom);
        return count($liste_doc);
    }





    private function searchDoc($collId, $indexList, $wantedColumns, $strictMode = 1, $orderCol = '', $order = '', $savedQueryId = '', $savedQueryName = '', $queryOperator = '', $from = '', $nbResults = '', $value1 = '', $makeUrlIndependentFromWebClientIP = '')
    {

        $param = array(
            'Coll_Id' => $collId,                       // Identifiant de la collection
            'IndexList' => $indexList,                  // Liste d'index
            'StrictMode' => $strictMode,                // Mode strict
            'Order_Col' => $orderCol,                   // Colonne de tri
            'Order' => $order,                          // Ordre de tri
            'Saved_Query_Id' => $savedQueryId,          // Identifiant de la requête sauvegardée
            'Saved_Query_Name' => $savedQueryName,      // Nom de la requête sauvegardée
            'Query_Operator' => $queryOperator,         // Opérateur de requête
            'From' => $from,                            // A partir de
            'Nb_Results' => $nbResults,                 // Nombre de résultats
            'Value_1' => $value1,                       // Valeur 1
            'Make_Url_Independant_From_WebClient_IP' => $makeUrlIndependentFromWebClientIP, // Indépendant de l'IP du client
            'Wanted_Columns' => $wantedColumns          // Colonnes souhaitées
        );

        // Appel de la méthode 'searchDoc' du service SOAP
        $result = $this->client->__soapCall(
            'searchDoc',
            $param
        );





        if (isset($result->Error_Msg)) {
            echo "<div class='alert alert-danger' role='alert'>Erreur : " . $result->Error_Msg . "</div>";
        } else {
            return $result;
        }
    }



    public function searchAllDoc()
    {

        $collId = '';
        $indexList = array();
        $wantedColumns = 'filename';

        // Appeler la méthode searchDoc avec les paramètres de recherche
        return $this->searchDoc($collId, $indexList, $wantedColumns);
    }



    public function searchBAPDoc($coll_Id, $indexCustom)
    {

        $indexList = array(
            array(
                'Id' => 10,
                'Label' => $indexCustom,
                'Value' => 1,
                'Operator' => 'EQUAL'
            )
        );

        $wantedColumns = 'filename;res_id;' . $indexCustom;


        $res = $this->searchDoc($coll_Id, $indexList, $wantedColumns);



        $res = json_decode($res, true);
        $docs = $res['Document'];

        //-------------------------------------------------------------------------------------
        // provisoire
        //-------------------------------------------------------------------------------------
        $resultat = array();
        foreach ($docs as $doc) {
            $document = $this->getDocument($coll_Id, $doc['Res_Id'], $wantedColumns);
            $document = json_decode($document, true);

            if ($document['Document']['Indexes'][$indexCustom][0] == 1) {
                $resultat[] = $document;
            }
        }

        return $resultat;
    }




    private function updateDoc($collId, $resId, $indexList, $mode = 'UpdateGiven')
    {
        $param = array(
            'Coll_Id' => $collId,           // Identifiant de la collection
            'Res_Id' => $resId,             // Identifiant du document
            'IndexList' => $indexList,      // Liste d'index
            'Mode' => $mode                 // Mode de mise à jour
        );



        // Appel de la méthode 'updateDoc' du service SOAP
        $result = $this->client->__soapCall(
            'updateDoc',
            $param
        );

        if (isset($result->Error_Msg)) {
            echo "<div class='alert alert-danger' role='alert'>Erreur : " . $result->Error_Msg . "</div>";
        } else {
            return $result;
        }
    }

    public function changeBAP($collId, $resId, $indexCustom)
    {
        $indexList = array(
            array(
                'Id' => 1,
                'Label' => $indexCustom,
                'Value' => 2
            )
        );

        return $this->updateDoc($collId, $resId, $indexList);
    }



    public function getSavedQueries($collId, $getNbResults = 1)
    {
        $param = array(
            'Coll_Id' => $collId,                           // Identifiant de la collection
            'Get_Nb_Results' => $getNbResults               // Identifiant du document
        );

        $result = $this->client->__soapCall(
            'getSavedQueries',
            $param
        );

        return $result;
    }
}