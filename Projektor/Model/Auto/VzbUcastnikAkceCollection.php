<?php
class Projektor_Model_Auto_VzbUcastnikAkceCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_VzbUcastnikAkceItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_VzbUcastnikAkceItem, prvek kolekce Projektor_Model_Auto_VzbUcastnikAkceCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_VzbUcastnikAkceItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_VzbUcastnikAkceItem
     */
    public function Item($id, Projektor_Model_Auto_VzbUcastnikAkceItem &$object=NULL){
        $object = new Projektor_Model_Auto_VzbUcastnikAkceItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

//	const TABULKA = "vzb_ucastnik_akce";
//	const ID = "id_vzb_ucastnik_akce";
//	const ID_UCASTNIK_FK = "id_ucastnik_FK";
//	const ID_AKCE_FK = "id_akce_FK";
//	const ID_S_STAV_UCASTNIK_AKCE_FK = "id_s_stav_ucastnik_akce_FK";

	/**
	 * Vraci stavy Ucastnika vzhledem k vybranym Akcim.
	 * @param UcastnikB $ucastnik Ucastnik jehoz stav vzhledem k Akcim nas zajima.
	 * @param array $akceUcastnika Pole instanci Akce, pro ktere hledame stavy.
	 * @return array Pole stavu ucastnika akce (Projektor_Model_Seznam_SStavUcastnikAkce).
	 */

	public static function dejStavy($ucastnik, $akceUcastnika)
	{
		$stavyAkciUcastnika = array();
		$pocitadlo = 0;
		$dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ~3 = :4 AND ~5 = :6";
                $dbh->bindIdentificator('~1', Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK);
                $dbh->bindIdentificator('~2', Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA);
                $dbh->bindIdentificator('~3', Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK);
                $dbh->bindIdentificator('~5', Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK);
                $stm = $dbh->prepare($query);
                $stm->bindParam(':4', $ucastnik->id);                
                
		foreach($akceUcastnika as $akce)
		{
                    $stm->bindParam(':6', $akce->id);  
                    $stm->execute();
                    $radky = $stm->fetch(PDO::FETCH_ASSOC); 
//			$radky = $preparedQuery->execute(Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK, Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA,
//			Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK, $ucastnik->id,
//			Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK, $akce->id
//			)->fetch_assoc();

			$stavyAkciUcastnika[$pocitadlo++] = Projektor_Model_Seznam_SStavUcastnikAkce::najdiPodleId($radky[Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK]);
		}

		return $stavyAkciUcastnika;
	}
}