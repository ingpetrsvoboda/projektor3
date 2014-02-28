<?php
class Projektor_Model_Auto_SysAccUsrProjektCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SysAccUsrProjektItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SysAccUsrProjektItem, prvek kolekce Projektor_Model_Auto_SysAccUsrProjektCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SysAccUsrProjektItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SysAccUsrProjektItem
     */
    public function Item($id, Projektor_Model_Auto_SysAccUsrProjektItem &$object=NULL){
        $object = new Projektor_Model_Auto_SysAccUsrProjektItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}