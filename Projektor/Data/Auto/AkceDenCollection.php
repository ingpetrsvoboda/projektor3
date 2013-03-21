<?php
class Projektor_Data_Auto_AkceDenCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_AkceDenItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_AkceDenCollection typu Projektor_Data_Auto_AkceDenItem
     * @param Projektor_Data_Auto_AkceDenItem $object
     * @return \Projektor_Data_Auto_AkceDenItem
     */
    public function Item($id, Projektor_Data_Auto_AkceDenItem &$object=NULL){
        $object = new Projektor_Data_Auto_AkceDenItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
