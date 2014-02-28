<?php
/**
 * Description of SIscoCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_SIscoCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SIscoItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SIscoItem, prvek kolekce Projektor_Model_Auto_SIscoCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SIscoItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SIscoItem
     */
    public function Item($id, Projektor_Model_Auto_SIscoItem &$object=NULL){
        $object = new Projektor_Model_Auto_SIscoItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
