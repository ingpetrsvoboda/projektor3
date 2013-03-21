<?php
class Projektor_Data_Auto_STypAkceCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_STypAkceItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_STypAkceCollection typu Projektor_Data_Auto_STypAkceItem
     * @param Projektor_Data_Auto_STypAkceItem $object
     * @return \Projektor_Data_Auto_STypAkceItem
     */
    public function Item($id, Projektor_Data_Auto_STypAkceItem &$object=NULL){
        $object = new Projektor_Data_Auto_STypAkceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
