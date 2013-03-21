<?php
class Projektor_Data_Auto_SysAccUsrProjektCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_SysAccUsrProjektItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_SysAccUsrProjektCollection typu Projektor_Data_Auto_SysAccUsrProjektItem
     * @param Projektor_Data_Auto_SysAccUsrProjektItem $object
     * @return \Projektor_Data_Auto_SysAccUsrProjektItem
     */
    public function Item($id, Projektor_Data_Auto_SysAccUsrProjektItem &$object=NULL){
        $object = new Projektor_Data_Auto_SysAccUsrProjektItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}