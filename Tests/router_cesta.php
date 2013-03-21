<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../ProjektorAutoload.php");
    if ($_GET['cesta'])
    {
        $cesta_ser = $_GET['cesta'];
        $cesta = unserialize($cesta_ser);
    }


    $k = new Projektor_Dispatcher_Uzel(null, null, null);
    $pci1 = $k->pridejPotomka("Prvni_Trida", array(par1=>"raz", par2=>"dva"));
    $pci2 = $k->pridejPotomka("Druha_Trida", array(par1=>"raz", par2=>"dva"));
    $pci11 = $pci1->pridejPotomka("Prvni_potomek_prvni_tridy", array(par1=>"raz", par2=>"dva"));
    $pci12 = $pci1->pridejPotomka("Druhy_potomek_prvni_tridy", array(par1=>"raz", par2=>"dva"));
    $pci21 = $pci2->pridejPotomka("Prvni_potomek_druhe_tridy", array(par1=>"raz", par2=>"dva"));

    $k_ser = serialize($k);
    echo $k_ser;
    $k_novy = unserialize($k_ser);



?>
