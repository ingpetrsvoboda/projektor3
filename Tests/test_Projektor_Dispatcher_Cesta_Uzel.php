<?php

define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();
    if ($_GET['cesta'])
    {
        $cesta_ser = $_GET['cesta'];
        $cesta = unserialize($cesta_ser);
    }


    $k = new Projektor_Dispatcher_TreeDispatcher_Vertex(null, null);
    $pci1 = $k->addChildVertex("Prvni_Trida", array(par1=>"raz", par2=>"dva"));
    $pci2 = $k->addChildVertex("Druha_Trida", array(par1=>"raz", par2=>"dva"));
    $pci11 = $pci1->addChildVertex("Prvni_potomek_prvni_tridy", array(par1=>"raz", par2=>"dva"));
    $pci12 = $pci1->addChildVertex("Druhy_potomek_prvni_tridy", array(par1=>"raz", par2=>"dva"));
    $pci21 = $pci2->addChildVertex("Prvni_potomek_druhe_tridy", array(par1=>"raz", par2=>"dva"));

    $k_ser = serialize($k);
    echo $k_ser;
    $k_novy = unserialize($k_ser);



?>
