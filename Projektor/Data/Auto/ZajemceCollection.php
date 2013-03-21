<?php
/**
 * Description of ZajemceCollection
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZajemceCollection extends Projektor_Data_Collection {
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_ZajemceItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_ZajemceCollection typu Projektor_Data_Auto_ZajemceItem
     * @param Projektor_Data_Auto_ZajemceItem $object
     * @return \Projektor_Data_Auto_ZajemceItem
     */
    public function Item($id, Projektor_Data_Auto_ZajemceItem &$object=NULL){
        $object = new Projektor_Data_Auto_ZajemceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

   /**
    * Nstaví foltr ehere pro výběr akcí, na kterych je Item prihlasen.
    * @param $item Instance Item pro který se vyhledají akce
    * @return void
    */
    public function zajemciPrihlaseniNaAkci(Projektor_Data_Auto_AkceItem $item)
    {
        $ucastnikAkceCollection = new Projektor_Data_Auto_VzbUcastnikAkceCollection();
        $ucastnikAkceCollection->where("dbField°id_akce_FK", "=", $item->id);
        $in = array();
        foreach ($ucastnikAkceCollection as $ucastnikAkceItem)
        {
            $in[] = $ucastnikAkceItem->dbField°id_ucastnik_FK;
        }

//        $ua = new Projektor_Data_Auto_VzbUcastnikAkceItem;
//        $ua->dbField°id_ucastnik_FK;
//        $ua->dbField°id_akce_FK;
        $this->where("id", "IN", $in);
        return;
    }
}

?>
