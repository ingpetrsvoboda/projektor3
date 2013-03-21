<?php
/**
 * Testovací třída pro Projektor_Autocode_Generator. Třída s definicí class typu Collection pro testovací běh generátoru autocode.
 * Do této třídy doplňuje geberátor vygenerovaný autocode pokud je volán s parametrem $test=TRUE.
 *
 * @author pes2704
 */
class Projektor_Data_Auto_AutocodeTestCollection extends Projektor_Data_Collection {
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZajemceItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_AutocodeTestCollection typu Projektor_Data_Auto_ZajemceItem
     * @param Projektor_Data_Auto_ZajemceItem $object
     * @return \Projektor_Data_Auto_ZajemceItem
     */
    public function Item($id, Projektor_Data_Auto_ZajemceItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZajemceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

}

?>
