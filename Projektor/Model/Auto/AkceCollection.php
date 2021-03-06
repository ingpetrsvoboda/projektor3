<?php
class Projektor_Model_Auto_AkceCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_AkceItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_AkceItem, prvek kolekce Projektor_Model_Auto_AkceCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_AkceItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_AkceItem
     */
    public function Item($id, Projektor_Model_Auto_AkceItem &$object=NULL){
        $object = new Projektor_Model_Auto_AkceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE


   /**
    * Nstaví filtr where pro výběr akcí, na kterych je Item prihlášen.
    * @param $item Instance Item pro který se vyhledají akce
    * @return void
    */
    public function vyberAkceObjektu($item)
    {
        $ucastnikAkceCollection = new Projektor_Model_Auto_VzbUcastnikAkceCollection();
        $ucastnikAkceCollection->where("dbField°id_ucastnik_FK", "=", $item->id);
        $in = array();
        foreach ($ucastnikAkceCollection as $ucastnikAkceItem)
        {
            $in[] = $ucastnikAkceItem->dbField°id_akce_FK;
        }

//        $ua = new Projektor_Model_Auto_VzbUcastnikAkceItem;
//        $ua->dbField°id_ucastnik_FK;
//        $ua->dbField°id_akce_FK;
        $this->where("id", "IN", $in);
        return;
//            $this->where($nazevVlastnosti, $podminka, $hodnota)
//            $dbh = Framework_Kontext::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
//            $query = "SELECT ~1 FROM ~2 WHERE ~3=:4";
//            $radky = $dbh->prepare($query)->execute(Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK, Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA,
//            Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK, $item->id)->fetchall_assoc();
//
//            foreach($radky as $radek)
//                    $vypis[] = self::najdiPodleId($radek[Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK]);
//
//            return $vypis;
    }
}
?>
