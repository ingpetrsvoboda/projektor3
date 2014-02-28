<?php
/**
 * Description of ZajemceCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_ZajemceCollection extends Projektor_Model_Collection {
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_ZajemceItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_ZajemceItem, prvek kolekce Projektor_Model_Auto_ZajemceCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_ZajemceItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_ZajemceItem
     */
    public function Item($id, Projektor_Model_Auto_ZajemceItem &$object=NULL){
        $object = new Projektor_Model_Auto_ZajemceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

   /**
    * Nstaví filtr where pro výběr akcí, na kterych je Item prihlasen.
    * @param $item Instance Item pro který se vyhledají akce
    * @return void
    */
    public function zajemciPrihlaseniNaAkci(Projektor_Model_Auto_AkceItem $item)
    {
        $ucastnikAkceCollection = new Projektor_Model_Auto_VzbUcastnikAkceCollection();
        $ucastnikAkceCollection->where("dbField°id_akce_FK", "=", $item->id);
        $in = array();
        foreach ($ucastnikAkceCollection as $ucastnikAkceItem)
        {
            $in[] = $ucastnikAkceItem->dbField°id_ucastnik_FK;
        }

//        $ua = new Projektor_Model_Auto_VzbUcastnikAkceItem;
//        $ua->dbField°id_ucastnik_FK;
//        $ua->dbField°id_akce_FK;
        $this->where("id", "IN", $in);
        return;
    }
}

?>
