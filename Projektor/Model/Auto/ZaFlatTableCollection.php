<?php
/**
 * Description of ZaFlatTableCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_ZaFlatTableCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_ZaFlatTableItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_ZaFlatTableItem, prvek kolekce Projektor_Model_Auto_ZaFlatTableCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_ZaFlatTableItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_ZaFlatTableItem
     */
    public function Item($id, Projektor_Model_Auto_ZaFlatTableItem &$object=NULL){
        $object = new Projektor_Model_Auto_ZaFlatTableItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
