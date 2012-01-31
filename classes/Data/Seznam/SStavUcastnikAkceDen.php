<?php
/**
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_Seznam_SStavUcastnikAkceDen extends Data_Iterator
{
	public $id;
	public $text;
	public $plnyText;

	const TABULKA = "s_stav_ucastnik_akce_den";
	const ID = "id_s_stav_ucastnik_akce_den";
	const TEXT = "text";
	const PLNY_TEXT = "plny_text";

	public function __construct($text, $plnyText, $id = NULL)
	{
		$this->id = $id;
		$this->text = $text;
		$this->plnyText = $plnyText;

                 parent::__construct(__CLASS__);
	}

	static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_SStavUcastnikAkceDen($radek[self::TEXT], $radek[self::PLNY_TEXT], $radek[self::ID]);
	}

	/**
	 * Vrati pole vsech moznych nasledudjicich stavu pro stav.
	 * @param Data_Seznam_SStavAkce $sStavAkce Soucasny stav
	 * @return array Pole moznych nasledujicich stavu
	 */

	public function vypisNasledujici()
	{
		return Data_Seznam_SPrechodUcastnikAkceDen::vypisVse(Data_Seznam_SPrechodUcastnikAkceDen::ID_S_STAV_PRED_FK." = {$this->id}");
	}

}
?>
