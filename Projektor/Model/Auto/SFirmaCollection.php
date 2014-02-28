<?php
class Projektor_Model_Auto_SFirmaCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SFirmaItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SFirmaItem, prvek kolekce Projektor_Model_Auto_SFirmaCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SFirmaItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SFirmaItem
     */
    public function Item($id, Projektor_Model_Auto_SFirmaItem &$object=NULL){
        $object = new Projektor_Model_Auto_SFirmaItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
