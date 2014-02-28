<?php
class Projektor_Model_Auto_SBehProjektuCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SBehProjektuItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SBehProjektuItem, prvek kolekce Projektor_Model_Auto_SBehProjektuCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SBehProjektuItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SBehProjektuItem
     */
    public function Item($id, Projektor_Model_Auto_SBehProjektuItem &$object=NULL){
        $object = new Projektor_Model_Auto_SBehProjektuItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
