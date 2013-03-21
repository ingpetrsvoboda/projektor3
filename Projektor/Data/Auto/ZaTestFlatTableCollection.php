<?php
/**
 * Description of ZaFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZaTestFlatTableCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZaTestFlatTableItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_ZaTestFlatTableCollection typu Projektor_Data_Auto_ZaTestFlatTableItem
     * @param Projektor_Data_Auto_ZaTestFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaTestFlatTableItem
     */
    public function Item($id, Projektor_Data_Auto_ZaTestFlatTableItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZaTestFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
