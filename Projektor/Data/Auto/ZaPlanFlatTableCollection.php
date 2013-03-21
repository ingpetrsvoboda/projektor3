<?php
/**
 * Description of Projektor_Data_Auto_ZaPlanFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZaPlanFlatTableCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZaPlanFlatTableItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_ZaPlanFlatTableCollection typu Projektor_Data_Auto_ZaPlanFlatTableItem
     * @param Projektor_Data_Auto_ZaPlanFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaPlanFlatTableItem
     */
    public function Item($id, Projektor_Data_Auto_ZaPlanFlatTableItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZaPlanFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
