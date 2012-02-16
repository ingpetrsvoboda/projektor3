<?php
class Data_Vzb_UcastnikAkce
{
	const TABULKA = "vzb_ucastnik_akce";
	const ID = "id_vzb_ucastnik_akce";
	const ID_UCASTNIK_FK = "id_ucastnik_FK";
	const ID_AKCE_FK = "id_akce_FK";
	const ID_S_STAV_UCASTNIK_AKCE_FK = "id_s_stav_ucastnik_akce_FK";

	/**
	 * Vraci stavy Ucastnika vzhledem k vybranym Akcim.
	 * @param UcastnikB $ucastnik Ucastnik jehoz stav vzhledem k Akcim nas zajima.
	 * @param array $akceUcastnika Pole instanci Akce, pro ktere hledame stavy.
	 * @return array Pole stavu ucastnika akce (Data_Seznam_SStavUcastnikAkce).
	 */

	public static function dejStavy($ucastnik, $akceUcastnika)
	{
		$stavyAkciUcastnika = array();
		$pocitadlo = 0;
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT ~1 FROM ~2 WHERE ~3 = :4 AND ~5 = :6";
                $preparedQuery = $dbh->prepare($query);
		foreach($akceUcastnika as $akce)
		{
			$radky = $preparedQuery->execute(Data_Vzb_UcastnikAkce::ID_S_STAV_UCASTNIK_AKCE_FK, Data_Vzb_UcastnikAkce::TABULKA,
			Data_Vzb_UcastnikAkce::ID_UCASTNIK_FK, $ucastnik->id,
			Data_Vzb_UcastnikAkce::ID_AKCE_FK, $akce->id
			)->fetch_assoc();

			$stavyAkciUcastnika[$pocitadlo++] = Data_Seznam_SStavUcastnikAkce::najdiPodleId($radky[Data_Vzb_UcastnikAkce::ID_S_STAV_UCASTNIK_AKCE_FK]);
		}

		return $stavyAkciUcastnika;
	}
}