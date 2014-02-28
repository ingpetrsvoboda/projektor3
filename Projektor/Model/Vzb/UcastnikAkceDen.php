<?php
class Projektor_Model_Vzb_UcastnikAkceDen
{
	const TABULKA = "vzb_ucastnik_akce_den";
	const ID = "id_vzb_ucastnik_akce_den";
	const ID_AKCE_DEN_FK = "id_akce_den_FK";
	const ID_UCASTNIK_FK = "id_ucastnik_FK";
	const ID_S_STAV_UCASTNIK_AKCE_DEN_FK = "id_s_stav_ucastnik_akce_den_FK";

	/**
	 * Vraci stavy Ucastnika vzhledem k vybranym Akcim.
	 * @param UcastnikB $ucastnik Ucastnik jehoz stav vzhledem k Akcim nas zajima.
	 * @param array $akceUcastnika Pole instanci Akce, pro ktere hledame stavy.
	 * @return array Pole stavu ucastnika akce (Projektor_Model_Seznam_SStavUcastnikAkce).
	 */

	/*public static function dejStavy($ucastnik, $akceUcastnika)
	 {
		$stavyAkciUcastnika = array();
		$pocitadlo = 0;
		$dbh = Projektor_AppContext::getDB();
		foreach($akceUcastnika as $akce)
		{
		$query = "SELECT ~1 FROM ~2 WHERE ~3 = :4 AND ~5 = :6";
		$radky = $dbh->prepare($query)->execute(Projektor_Model_Vzb_UcastnikAkce::ID_S_STAV_UCASTNIK_AKCE_FK, Projektor_Model_Vzb_UcastnikAkce::TABULKA,
		Projektor_Model_Vzb_UcastnikAkce::ID_UCASTNIK_FK, $ucastnik->id,
		Projektor_Model_Vzb_UcastnikAkce::ID_AKCE_FK, $akce->id
		)->fetch_assoc();

		$stavyAkciUcastnika[$pocitadlo++] = Projektor_Model_Seznam_SStavUcastnikAkce::najdiPodleId($radky[Projektor_Model_Vzb_UcastnikAkce::ID_S_STAV_UCASTNIK_AKCE_FK]);
		}

		return $stavyAkciUcastnika;
		}*/
}