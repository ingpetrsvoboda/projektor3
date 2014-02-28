<?php
class Projektor_Model_Auto_SStavAkceCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SStavAkceItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SStavAkceItem, prvek kolekce Projektor_Model_Auto_SStavAkceCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SStavAkceItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SStavAkceItem
     */
    public function Item($id, Projektor_Model_Auto_SStavAkceItem &$object=NULL){
        $object = new Projektor_Model_Auto_SStavAkceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
