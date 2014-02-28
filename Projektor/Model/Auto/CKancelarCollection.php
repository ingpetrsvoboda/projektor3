<?php
/**
 * Description of CKancelarCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_CKancelarCollection extends Projektor_Model_CiselnikCollection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_CKancelarItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_CKancelarItem, prvek kolekce Projektor_Model_Auto_CKancelarCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_CKancelarItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_CKancelarItem
     */
    public function Item($id, Projektor_Model_Auto_CKancelarItem &$object=NULL){
        $object = new Projektor_Model_Auto_CKancelarItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

}

?>
