<?php
/**
 * Description of CKancelarCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_CKancelarCollection extends Projektor_Data_CiselnikCollection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_CKancelarItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_CKancelarCollection typu Projektor_Data_Auto_CKancelarItem
     * @param Projektor_Data_Auto_CKancelarItem $object
     * @return \Projektor_Data_Auto_CKancelarItem
     */
    public function Item($id, Projektor_Data_Auto_CKancelarItem &$object=NULL){
        $object = new Projektor_Data_Auto_CKancelarItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

}

?>
