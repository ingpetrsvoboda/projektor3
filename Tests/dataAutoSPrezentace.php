<?php
ob_start();

require 'dataAutoFunkceProTest.php';
define("CLASS_PATH", "../");
require_once(CLASS_PATH . "Projektor/ProjektorAutoload.php");
echo ('
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Projektor | test |</title>
        <link rel="icon" type="image/gif" href="../favicon.gif"></link>
        <link rel="stylesheet" type="text/css" href="../css/default.css" />
        <link rel="stylesheet" type="text/css" href="../css/highlight.css" />
    </head>

    <body>
        ');

echo interval();
    if (isset($_GET['cesta']))
    {
        echo Projektor_Dispatcher_Cesta::getContent($_GET['cesta']);
    } else {
//        $koren = new Projektor_Router_Uzel("Projektor_Stranka_AkceJ_Menu_Detail", null, array("id" => 5), FALSE);
        $koren = new Projektor_Dispatcher_Uzel("Projektor_Stranka_PrezentaceJ_Detail", null, array("id" => 5), FALSE);
        $cesta_serialized = serialize($koren);
        echo Projektor_Dispatcher_Cesta::getContent($cesta_serialized);
    }

        echo "<p >".interval()."</p>";

    exit();
echo("<h2>Akce</h2>");
    echo ("<h3>Volání Projektor_Data_Auto_SPrezentaceCollection:</h3>");
    $prezentaceCollection = new Projektor_Data_Auto_SPrezentaceCollection();
    vypisTabulku($prezentaceCollection);
    echo "<p >".interval()."</p>";
    $prezentaceCollection = new Projektor_Data_Auto_SPrezentaceCollection();
    $prezentaceCollection->vsechnyRadky();
    vypisTabulku($prezentaceCollection);

    echo ('</body></html>');

?>
