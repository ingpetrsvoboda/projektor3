<?php

ob_start();
define("CLASS_PATH", "../");
require_once(CLASS_PATH . "Projektor/ProjektorAutoload.php");
require_once 'Twig/Autoloader.php';

Twig_Autoloader::register();
$loader   = new Twig_Loader_Filesystem('templates');
// in real life usage you should set up the cache directory!
$twig     = new Twig_Environment($loader);

//echo ('
//    <html>
//
//    <head>
//        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
//        <title>Projektor | test |</title>
//        <link rel="icon" type="image/gif" href="../favicon.gif"></link>
//        <link rel="stylesheet" type="text/css" href="../css/default.css" />
//        <link rel="stylesheet" type="text/css" href="../css/highlight.css" />
//    </head>
//
//    <body>
//        ');

$storage = new Projektor_App_Storage_Session('PROJEKTOR_STATUS');
$appStatus = new Projektor_App_Status($storage);
$app = new Projektor_App_Application($appStatus);
$app->run();
//echo ('</body></html>');
?>
