<?php
class Projektor_Data_Auto_VzbUcastnikAkceCollection extends Projektor_Data_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Data_Auto_VzbUcastnikAkceItem";

###START_AUTOCODE

    /**
     * Metoda vrací Item, prvek kolekce Projektor_Data_Auto_VzbUcastnikAkceCollection typu Projektor_Data_Auto_VzbUcastnikAkceItem
     * @param Projektor_Data_Auto_VzbUcastnikAkceItem $object
     * @return \Projektor_Data_Auto_VzbUcastnikAkceItem
     */
    public function Item($id, Projektor_Data_Auto_VzbUcastnikAkceItem &$object=NULL){
        $object = new Projektor_Data_Auto_VzbUcastnikAkceItem($id); //factory na Item
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
	 * @return array Pole stavu ucastnika akce (Projektor_Data_Seznam_SStavUcastnikAkce).
	 */

	public static function dejStavy($ucastnik, $akceUcastnika)
	{
		$stavyAkciUcastnika = array();
		$pocitadlo = 0;
		$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ~3 = :4 AND ~5 = :6";
                $preparedQuery = $dbh->prepare($query);
		foreach($akceUcastnika as $akce)
		{
			$radky = $preparedQuery->execute(Projektor_Data_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK, Projektor_Data_Auto_VzbUcastnikAkceCollection::TABULKA,
			Projektor_Data_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK, $ucastnik->id,
			Projektor_Data_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK, $akce->id
			)->fetch_assoc();

			$stavyAkciUcastnika[$pocitadlo++] = Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId($radky[Projektor_Data_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK]);
		}

		return $stavyAkciUcastnika;
	}
}