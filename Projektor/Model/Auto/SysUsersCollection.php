<?php
/**
 * @author Petr Svoboda
 * @since Fri, 12 Oct 20011 19:01:55 +0200
 */

class Projektor_Model_Auto_SysUsersCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SysUsersItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SysUsersItem, prvek kolekce Projektor_Model_Auto_SysUsersCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SysUsersItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SysUsersItem
     */
    public function Item($id, Projektor_Model_Auto_SysUsersItem &$object=NULL){
        $object = new Projektor_Model_Auto_SysUsersItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
