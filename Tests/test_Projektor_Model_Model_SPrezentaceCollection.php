<?php
ob_start();

require 'test_Projektor_Model_Auto_FUNKCE_PRO_TESTY.php';
define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();
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
        echo Projektor_Dispatcher_TreeDispatcher::getContent($_GET['cesta']);
    } else {
//        $koren = new Projektor_Router_Uzel("Projektor_Stranka_AkceJ_Menu_Detail", null, array("id" => 5), FALSE);
!!        $koren = new Projektor_Dispatcher_TreeDispatcher_Vertex("Projektor_Stranka_PrezentaceJ_Detail", null, array("id" => 5), FALSE);
upravit předávání parametru - asi z instance aplikace -> request->params
        $controller = new Projektor_Controller_Page_PrezentaceJ_Menu_Detail();
        $koren = new Projektor_Dispatcher_TreeDispatcher_Vertex($controller);
        $cesta_serialized = serialize($koren);
        echo Projektor_Dispatcher_TreeDispatcher::getContent($cesta_serialized);
    }

        echo "<p >".interval()."</p>";

    exit();
echo("<h2>Akce</h2>");
    echo ("<h3>Volání Projektor_Model_Auto_SPrezentaceCollection:</h3>");
    $prezentaceCollection = new Projektor_Model_Auto_SPrezentaceCollection();
    vypisCollection($prezentaceCollection);
    echo "<p >".interval()."</p>";
    $prezentaceCollection = new Projektor_Model_Auto_SPrezentaceCollection();
    $prezentaceCollection->vsechnyRadky();
    vypisCollection($prezentaceCollection);

    echo ('</body></html>');

?>
