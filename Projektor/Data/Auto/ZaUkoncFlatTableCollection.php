<?php
/**
 * Description of ZaFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZaUkoncFlatTableCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZaUkoncFlatTableItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_ZaUkoncFlatTableCollection typu Projektor_Data_Auto_ZaUkoncFlatTableItem
     * @param Projektor_Data_Auto_ZaUkoncFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaUkoncFlatTableItem
     */
    public function Item($id, Projektor_Data_Auto_ZaUkoncFlatTableItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZaUkoncFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
