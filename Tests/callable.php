<?php

ini_set('xdebug.show_exception_trace', '1');
ini_set('xdebug.collect_params', '4');

define ("CLASS_PATH", "../");
require_once(CLASS_PATH."Projektor/ProjektorAutoload.php");

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
var_dump(is_callable(array("Projektor_Data_Auto_AkceCollection", "__construct")));
$c = defined(Projektor_Data_Auto_AkceCollection::NAZEV_TRIDY_ITEM);
var_dump($c );
//$b = new Projektor_Data_Auto_AkceCollection();
//$r = new ReflectionObject($b);
//echo $r->getConstant('NAZEV_TRIDY_ITEM');
echo ('</body></html>');

?>
