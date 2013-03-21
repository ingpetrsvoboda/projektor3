<?php
class Projektor_Data_Auto_SFirmaCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_SFirmaItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_SFirmaCollection typu Projektor_Data_Auto_SFirmaItem
     * @param Projektor_Data_Auto_SFirmaItem $object
     * @return \Projektor_Data_Auto_SFirmaItem
     */
    public function Item($id, Projektor_Data_Auto_SFirmaItem &$object=NULL){
        $object = new Projektor_Data_Auto_SFirmaItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
