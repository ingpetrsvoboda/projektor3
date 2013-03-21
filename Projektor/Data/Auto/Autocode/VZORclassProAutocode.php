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
class Projektor_Data_Auto_TabulkaCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_TabulkaItem";
}
    //vzor kódu pro item
class Projektor_Data_Auto_TabulkaItem extends Projektor_Data_Item
{
    const DATABAZE = Projektor_App_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "nazev_tabulky_v_db";

###START_AUTOCODE
public function reset(){}
###END_AUTOCODE
}
    //vzor kódu pro item číselník
class Projektor_Data_Auto_CTabulkaItem extends Projektor_Data_CiselnikItem
{
    const DATABAZE = Projektor_App_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "nazev_tabulky_v_db";

###START_AUTOCODE
public function reset(){}
###END_AUTOCODE
}
?>
