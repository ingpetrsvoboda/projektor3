<?php
/**
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_Seznam_SStavUcastnikAkce extends Data_Iterator
{
	public $id;
	public $text;
	public $plnyText;

	const TABULKA = "s_stav_ucastnik_akce";
	const ID = "id_s_stav_ucastnik_akce";
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

		return new Data_Seznam_SStavUcastnikAkce($radek[self::TEXT], $radek[self::PLNY_TEXT], $radek[self::ID]);
	}

	static function vypisVse()
	{
    $dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT ~1 FROM ~2 WHERE ".($filtr == "" ? "valid = 1" : "(valid = 1 AND ({$filtr}))");
		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[self::ID]);
		 
		return $vypis;
	}

	function save()
	{

	}

	function remove()
	{

	}

	/**
	 * Vrati pole vsech moznych nasledudjicich stavu pro stav.
	 * @param Data_Seznam_SStavAkce $sStavAkce Soucasny stav
	 * @return array Pole moznych nasledujicich stavu
	 */

	public function vypisNasledujici()
	{
		return Data_Seznam_SPrechodUcastnikAkce::vypisVse(Data_Seznam_SPrechodUcastnikAkce::ID_S_STAV_PRED_FK." = {$this->id}");
	}

}
?>
