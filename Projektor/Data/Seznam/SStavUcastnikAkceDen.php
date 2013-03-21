<?php
/**
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Projektor_Data_Seznam_SStavUcastnikAkceDen extends Projektor_Data_Item
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
		$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Projektor_Data_Seznam_SStavUcastnikAkceDen($radek[self::TEXT], $radek[self::PLNY_TEXT], $radek[self::ID]);
	}

	/**
	 * Vrati pole vsech moznych nasledudjicich stavu pro stav.
	 * @param Projektor_Data_Seznam_SStavAkce $sStavAkce Soucasny stav
	 * @return array Pole moznych nasledujicich stavu
	 */

	public function vypisMozneNasledujiciStavy()
	{
		return Projektor_Data_Seznam_SPrechodUcastnikAkceDen::vypisVse(Projektor_Data_Seznam_SPrechodUcastnikAkceDen::ID_S_STAV_PRED_FK." = {$this->id}");
	}

}
?>
