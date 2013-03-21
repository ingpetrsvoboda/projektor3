<?php
/**
 * Description of SysAccUsrKancelarCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_SysAccUsrKancelarCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_SysAccUsrKancelarItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_SysAccUsrKancelarCollection typu Projektor_Data_Auto_SysAccUsrKancelarItem
     * @param Projektor_Data_Auto_SysAccUsrKancelarItem $object
     * @return \Projektor_Data_Auto_SysAccUsrKancelarItem
     */
    public function Item($id, Projektor_Data_Auto_SysAccUsrKancelarItem &$object=NULL){
        $object = new Projektor_Data_Auto_SysAccUsrKancelarItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}

?>
