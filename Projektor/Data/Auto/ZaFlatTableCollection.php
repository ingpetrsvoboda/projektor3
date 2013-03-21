<?php
/**
 * Description of ZaFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZaFlatTableCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZaFlatTableItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_ZaFlatTableCollection typu Projektor_Data_Auto_ZaFlatTableItem
     * @param Projektor_Data_Auto_ZaFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaFlatTableItem
     */
    public function Item($id, Projektor_Data_Auto_ZaFlatTableItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZaFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
