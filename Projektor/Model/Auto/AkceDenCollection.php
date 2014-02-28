<?php
class Projektor_Model_Auto_AkceDenCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_AkceDenItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_AkceDenItem, prvek kolekce Projektor_Model_Auto_AkceDenCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_AkceDenItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_AkceDenItem
     */
    public function Item($id, Projektor_Model_Auto_AkceDenItem &$object=NULL){
        $object = new Projektor_Model_Auto_AkceDenItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
