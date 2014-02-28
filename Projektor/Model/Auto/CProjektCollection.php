<?php
/**
 * Description of CKancelarCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_CProjektCollection extends Projektor_Model_CiselnikCollection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_CProjektItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_CProjektItem, prvek kolekce Projektor_Model_Auto_CProjektCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_CProjektItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_CProjektItem
     */
    public function Item($id, Projektor_Model_Auto_CProjektItem &$object=NULL){
        $object = new Projektor_Model_Auto_CProjektItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

}

?>
