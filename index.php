<?php 
//error_reporting(E_ALL);
//ini_set('xdebug.show_exception_trace', '1');
//ini_set('xdebug.collect_params', '4');
ini_set('xdebug.profiler_enable', '1');
ob_start();

// zajištění autoload pro Framework
require_once 'Framework/Autoloader.php';
Framework_Autoloader::register();
// zajištění autoload pro Projektor
require_once 'Projektor/Autoloader.php';
Projektor_Autoloader::register();
// zajištění autoload pro Twig
require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();

/**
 * Storage pro Framework_Status - ze storage se načítá status aplikace, který tam byl uložen před odesláním minulého response. 
 * Jedná se tedy o uložení stavu mezi jednotlivými requesty. Proto se používá Framework_Storage_Session, storage drží uložená data
 * do konce existence session.
 */
//$appStatusStorage = Projektor_Container::getStorageSession();  //název session napevno v Containeru

/**
 * Status aplikace pro Framework_Application. Status je načítán a ukládán do injektované storage.
 */
//$appStatus = new Framework_Status($appStatusStorage);

/**
 * Request a response objekt pro router. 
 */
//$request = new Framework_Request_Request();
//$response = new Framework_Response_Response();
//$router = new Projektor_Router_Projektor($request, $response);
//$app = new Projektor_Application($appStatus, $router);
//$app->run();

$app = new Projektor_Application();
$app->run();
?>