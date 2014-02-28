<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VZORclassProAutocode
 *
 * @author pes2704
 */
    //vzor kódu pro collection
class Projektor_Model_Auto_TabulkaCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_TabulkaItem";
###START_AUTOCODE
###END_AUTOCODE
}


    //vzor kódu pro item
class Projektor_Model_Auto_TabulkaItem extends Projektor_Model_Item implements Projektor_Model_AutoItemInterface
{
    const DATABAZE = Framework_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "nazev_tabulky_v_db";

###START_AUTOCODE
public function reset(){}
###END_AUTOCODE
}
    //vzor kódu pro item číselník
class Projektor_Model_Auto_CTabulkaItem extends Projektor_Model_CiselnikItem
{
    const DATABAZE = Framework_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "nazev_tabulky_v_db";

###START_AUTOCODE
public function reset(){}
###END_AUTOCODE
}
?>
