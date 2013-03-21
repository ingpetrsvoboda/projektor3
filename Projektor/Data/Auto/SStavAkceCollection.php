<?php
class Projektor_Data_Auto_SStavAkceCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_SStavAkceItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_SStavAkceCollection typu Projektor_Data_Auto_SStavAkceItem
     * @param Projektor_Data_Auto_SStavAkceItem $object
     * @return \Projektor_Data_Auto_SStavAkceItem
     */
    public function Item($id, Projektor_Data_Auto_SStavAkceItem &$object=NULL){
        $object = new Projektor_Data_Auto_SStavAkceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
