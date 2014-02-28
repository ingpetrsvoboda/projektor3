<?php
ob_start();

define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();

require 'test_Projektor_Model_Auto_FUNKCE_PRO_TESTY.php';

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

//Vypis

echo "<h2>Item, který má reference na jiné Collection - Item z db tabulky s cizími klíči.</h2>";
    echo "<h3>Výpis Item, serializovaného Item a Item po deserializaci:</h3>";
    $test = function (){  
        echo '<p>Item</p>';
        $item = new Projektor_Model_Auto_CKancelar3Item(10);
        echo '<p>item id: '.$item->id;
        echo '<pre style="font-size: 1.2em">';
        print_r($item);
        echo '</pre>';
        vypisItem($item); 
        echo '<p>item id: '.$item->id;
        
        $serialized = serialize($item);
        echo '<p>Serializovaný Item:</p>';
        echo '<p>'.$serialized.'<p>';
        
        echo '<p>Deserializovaný Item:</p>';
        $item2 = unserialize($serialized);

        echo '<p>item2 id: '.$item2->id;
        echo '<pre style="font-size: 1.2em">';
        print_r($item2);
        echo '</pre>';
        vypisItem($item2); 
        echo '<p>item2 id: '.$item2->id;
        };
    runTestFunction($test);
    
    
echo "<h2>Item a Collection bez referencí na jiné Collection.</h2>";
    $test = function (){
        $item = new Projektor_Model_Auto_CProjektItem(7);
        vypisItem($item);
                echo '<pre style="font-size: 1.2em">';
        print_r($item);
        echo '</pre>';
        vypisItem($item); 
        
        $serialized = serialize($item);
        echo '<p>Serializovaný Item:</p>';
        echo '<p>'.$serialized.'<p>';
        
        echo '<p>Deserializovaný Item:</p>';
        $item2 = unserialize($serialized);
        echo '<pre style="font-size: 1.2em">';
        print_r($item2);
        echo '</pre>';
        vypisItem($item2);
    };
    runTestFunction($test);


?>