<?php
ob_start();

// zajištění autoload pro Projektor
require_once 'Projektor/Autoloader.php';
Projektor_Autoloader::register();

require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();

$storage = new Projektor_App_Storage_Session('PROJEKTOR_STATUS');
$appStatus = new Projektor_App_Status($storage);
$response = new Projektor_App_Response_Response();
$router = new Projektor_App_Router_Base($response);
$app = new Projektor_App_Application($appStatus, $router);
$app->run();


?>