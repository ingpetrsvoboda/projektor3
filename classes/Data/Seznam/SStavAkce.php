<?php
/**
 * @author PHP_UML
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_Seznam_SStavAkce extends Data_Iterator
{
	public $id;
	public $text;
	public $plnyText;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_stav_akce";
	const ID = "id_s_stav_akce";
	const TEXT = "text";
	const PLNY_TEXT = "plny_text";

	public function __construct($text, $plnyText, $id = null)
	{
	 $this->id = $id;
	 $this->text = $text;
	 $this->plnyText = $plnyText;
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_SStavAkce($radek[self::TEXT], $radek[self::PLNY_TEXT],
		$radek[self::ID]);

                 parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "")
	{
		$dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ".($filtr == "" ? "valid = 1" : "(valid = 1 AND {$filtr})");
		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[self::ID]);
		 
		return $vypis;
	}

	/**
	 * Ulozi parametry tridy jako radek do DB.
	 * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
	 */

	public function uloz()
	{
		$dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);

		if($this->id == null)
		{
			$query = "INSERT INTO ~1 (~2, ~3) VALUES (:4, :5)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::TEXT, self::PLNY_TEXT,
			$this->text, $this->plnyText
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5 WHERE ~6=:7";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::TEXT, $this->text,
			self::PLNY_TEXT, $this->plnyText,
			self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($SStavAkce)
	{
		$dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $SStavAkce->id);
	}


	/**
	 * Vrati pole vsech moznych nasledudjicich stavu pro stav.
	 * @param Data_Seznam_SStavAkce $sStavAkce Soucasny stav
	 * @return array Pole moznych nasledujicich stavu
	 */

	public function vypisNasledujici()
	{
		return Data_Seznam_SPrechodAkce::vypisVse(Data_Seznam_SPrechodAkce::ID_S_STAV_PRED_FK." = {$this->id}");
	}
}
?>