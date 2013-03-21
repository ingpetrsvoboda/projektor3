<?php
class Projektor_Data_Auto_SBehProjektuCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_SBehProjektuItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_SBehProjektuCollection typu Projektor_Data_Auto_SBehProjektuItem
     * @param Projektor_Data_Auto_SBehProjektuItem $object
     * @return \Projektor_Data_Auto_SBehProjektuItem
     */
    public function Item($id, Projektor_Data_Auto_SBehProjektuItem &$object=NULL){
        $object = new Projektor_Data_Auto_SBehProjektuItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
}
?>
