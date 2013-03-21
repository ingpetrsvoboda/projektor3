<?php
/**
 * Description of CKancelarCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_CProjektCollection extends Projektor_Data_CiselnikCollection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_CProjektItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_CProjektCollection typu Projektor_Data_Auto_CProjektItem
     * @param Projektor_Data_Auto_CProjektItem $object
     * @return \Projektor_Data_Auto_CProjektItem
     */
    public function Item($id, Projektor_Data_Auto_CProjektItem &$object=NULL){
        $object = new Projektor_Data_Auto_CProjektItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

}

?>
