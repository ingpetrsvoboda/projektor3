<?php

ini_set('xdebug.show_exception_trace', '1');
ini_set('xdebug.collect_params', '4');

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
//$log = Projektor_Model_Auto_Autocode_Generator::generuj(Projektor_Model_Auto_Autocode_Generator::TEST);
$log = Projektor_Model_Auto_Autocode_Generator::generuj(Projektor_Model_Auto_Autocode_Generator::DEVELOPMENT);
//$log = Projektor_Model_Auto_Autocode_Generator::generuj(Projektor_Model_Auto_Autocode_Generator::PRODUCTION);
echo "Dokončeno generování autokódu, výpis logu:\n".$log."\n";
echo ("</pre>");
echo ('</body></html>');

?>
