<?php

define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();

echo ('
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Projektor | test akce |</title>
        <link rel="icon" type="image/gif" href="favicon.gif"></link>
        <link rel="stylesheet" type="text/css" href="css/default.css" />
        <link rel="stylesheet" type="text/css" href="css/highlight.css" />
    </head>

    <body>
        ');
echo("<pre>");

    $databaze = 'Projektor';
    $dbConfig = Framework_Config::najdiPolozkuPodleAtributu(Framework_Config::SEKCE_DB, Framework_Config::ATRIBUT_SEKCE_DATABAZE, $databaze);
    if (!$dbConfig)
        throw new Exception(__CLASS__." ".__METHOD__." Nenalezena položka v XML souboru s konfiguračními informacemi. Název sekce: ".
                            Framework_Config::SEKCE_DB.", název atributu: ".Framework_Config::ATRIBUT_SEKCE_DATABAZE.", atribut:".$databaze);
    if (!$dbConfig->user OR !$dbConfig->pass OR !$dbConfig->dbhost OR !$dbConfig->dbname OR !$dbConfig->dbtype)
        throw new Exception(__CLASS__." ".__METHOD__.
                            " Sekce s konfiguračními informacemi. Název sekce: ".
                            Framework_Config::SEKCE_DB.", název atributu: ".Framework_Config::ATRIBUT_SEKCE_DATABAZE.", hodnota atribut:".$databaze.
                            " neobsahuje všechny potřebné informace: user, pass, dbhost, dbname, dbtype");

    switch ($dbConfig->dbtype) {
        case Framework_Config::DB_TYPE_MYSQL :
            $dbh = new Framework_DBPDO_Mysql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;
            break;
        case Framework_Config::DB_TYPE_MSSQL :
            $dbh = new Framework_DBPDO_Mssql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;               
            break;
        default:
            throw new Exception(__CLASS__." ".__METHOD__." V konfigutaci (Framework_Config) neexistuje zadaný typ databáze: ".$dbConfig->dbtype);
    }
    $statement = 'SELECT * FROM c_titul_pred where id_c_titul_pred = 6';
    $stm = $dbh->prepare($statement);
//    $stm->setFetchMode(PDO::FETCH_ASSOC);
    $succ = $stm->execute();
    $res =  $stm->fetch();
    print_r($res);
    echo "***************************\n";
    $statement = 'SELECT * FROM c_titul_pred where id_c_titul_pred = :id';
    $stm = $dbh->prepare($statement);
    $id = 6;
    $stm->bindParam(':id', $id);
    $succ = $stm->execute();
    $stm->setFetchMode(PDO::FETCH_ASSOC);
    $res =  $stm->fetch();
    print_r($res);
    echo "Další příklady nefungují, vázání identifikátorů se děje pouze uvnitř objektu Projektor_Model_Sql\n";
    // TODO: upravit Projektor_Model_Sql - doplnit metodu bindIdentifikator
    echo "***************************\n";
    $statement = 'SELECT * FROM c_titul_pred where ~id = :id';
    $idName = 'id_c_titul_pred';
    $dbh->bindIdentificator('~id', $idName);
    $stm = $dbh->prepare($statement);
    echo "\nQuery string: ".$stm->queryString."\n";
    $idValue = 6;
    $stm->bindParam(':id', $idValue);
    $succ = $stm->execute();
    $res =  $stm->fetch(PDO::FETCH_ASSOC);
    print_r($res);
    echo "***************************\n";
    $statement = 'SELECT * FROM c_titul_pred where ~id <= :id';
    $idName = 'id_c_titul_pred';
    $dbh->bindIdentificator('~id', $idName);
    $stm = $dbh->prepare($statement);
    echo "\nQuery string: ".$stm->queryString."\n";
    $idValue = 6;
    $stm->bindParam(':id', $idValue);
    $succ = $stm->execute();
    $res =  $stm->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);
    echo "***************************\n";
    $statement = 'SELECT * FROM c_projekt where ~id IN (:id1,:id2,:id3,:id4)';
    $idName = 'id_c_projekt';
    $dbh->bindIdentificator('~id', $idName);
    $stm = $dbh->prepare($statement);
    echo "\nQuery string: ".$stm->queryString."\n";
    $stm->bindValue(':id1', 4);
    $stm->bindValue(':id2', 5);
    $stm->bindValue(':id3', 6);
    $stm->bindValue(':id4', 7);
    $succ = $stm->execute();
    $res =  $stm->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);
    echo "***************************\n";
    
echo("</pre>");

echo ('</body></html>');


?>
