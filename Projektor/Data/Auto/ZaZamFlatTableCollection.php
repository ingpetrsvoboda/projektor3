<?php
/**
 * Description of ZaFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZaZamFlatTableCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZaZamFlatTableItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_ZaZamFlatTableCollection typu Projektor_Data_Auto_ZaZamFlatTableItem
     * @param Projektor_Data_Auto_ZaZamFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaZamFlatTableItem
     */
    public function Item($id, Projektor_Data_Auto_ZaZamFlatTableItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZaZamFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
