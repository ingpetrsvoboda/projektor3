<?php
/**
 * @author Petr Svoboda
 * @since Fri, 12 Oct 20011 19:01:55 +0200
 */

class Projektor_Data_Auto_SysUsersCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_SysUsersItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_SysUsersCollection typu Projektor_Data_Auto_SysUsersItem
     * @param Projektor_Data_Auto_SysUsersItem $object
     * @return \Projektor_Data_Auto_SysUsersItem
     */
    public function Item($id, Projektor_Data_Auto_SysUsersItem &$object=NULL){
        $object = new Projektor_Data_Auto_SysUsersItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
