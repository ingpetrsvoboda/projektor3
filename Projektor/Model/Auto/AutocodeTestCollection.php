<?php
/**
 * Testovací třída pro Projektor_Autocode_Generator. Třída s definicí class typu Collection pro testovací běh generátoru autocode.
 * Do této třídy doplňuje geberátor vygenerovaný autocode pokud je volán s parametrem $test=TRUE.
 *
 * @author pes2704
 */
class Projektor_Model_Auto_AutocodeTestCollection extends Projektor_Model_Collection {
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_ZajemceItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_ZajemceItem, prvek kolekce Projektor_Model_Auto_AutocodeTestCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_ZajemceItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_ZajemceItem
     */
    public function Item($id, Projektor_Model_Auto_ZajemceItem &$object=NULL){
        $object = new Projektor_Model_Auto_ZajemceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

}

?>
