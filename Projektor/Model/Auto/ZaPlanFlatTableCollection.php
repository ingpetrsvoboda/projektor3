<?php
/**
 * Description of Projektor_Model_Auto_ZaPlanFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_ZaPlanFlatTableCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_ZaPlanFlatTableItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_ZaPlanFlatTableItem, prvek kolekce Projektor_Model_Auto_ZaPlanFlatTableCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_ZaPlanFlatTableItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_ZaPlanFlatTableItem
     */
    public function Item($id, Projektor_Model_Auto_ZaPlanFlatTableItem &$object=NULL){
        $object = new Projektor_Model_Auto_ZaPlanFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
