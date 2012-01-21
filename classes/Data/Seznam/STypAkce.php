<?php
/**
 * Typ akce (radek v DB v seznamu s_typ_akce)
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_Seznam_STypAkce extends Data_Iterator
{
	public $id;
	public $nazev;
	public $trvaniDni;
	public $hodinyDen;
	public $minPocetUc;
	public $maxPocetUc;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_typ_akce";
	const ID = "id_s_typ_akce";
	const NAZEV = "nazev";
	const TRVANI_DNI = "trvani_dni";
	const HODINY_ZA_DEN = "hodiny/den";
	const MIN_POCET_UC = "min_pocet_uc";
	const MAX_POCET_UC = "max_pocet_uc";

	public function __construct($nazev, $trvaniDni, $hodinyDen, $minPocetUc, $maxPocetUc, $id = null)
	{
		$this->id = $id;
		$this->nazev = $nazev;
		$this->trvaniDni = $trvaniDni;
		$this->hodinyDen = $hodinyDen;
		$this->minPocetUc = $minPocetUc;
		$this->maxPocetUc = $maxPocetUc;

                parent::__construct(__CLASS__);
	}


	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQL();
		$query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_STypAkce($radek[self::NAZEV], $radek[self::TRVANI_DNI], $radek[self::HODINY_ZA_DEN],
		$radek[self::MIN_POCET_UC], $radek[self::MAX_POCET_UC], $radek[self::ID]);
	}


	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "", $orderBy = "", $order = "")
	{
		$dbh = App_Kontext::getDbMySQL();
		$query = "SELECT ~1 FROM ~2".
			($filtr == "" ? " WHERE (valid = 1)" : " WHERE (valid = 1 AND {$filtr})").
			($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;
		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
                    $vypis[] = self::najdiPodleId($radek[self::ID]);
		 
		return $vypis;
	}


	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($sTypAkce)
	{
		$dbh = App_Kontext::getDbMySQL();
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $sTypAkce->id);
	}


	/**
	 * Ulozi parametry tridy jako radek do DB.
	 * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
	 */

	public function uloz()
	{
		$dbh = App_Kontext::getDbMySQL();

		if($this->id == null)
		{
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6) VALUES (:7, :8, :9, :10, :11)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::NAZEV, self::TRVANI_DNI, self::HODINY_ZA_DEN, self::MIN_POCET_UC, self::MAX_POCET_UC,
			$this->nazev, $this->trvaniDni, $this->hodinyDen, $this->minPocetUc, $this->maxPocetUc
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11 WHERE ~12=:13";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::NAZEV, $this->nazev, self::TRVANI_DNI, $this->trvaniDni, self::HODINY_ZA_DEN,
			$this->hodinyDen, self::MIN_POCET_UC, $this->minPocetUc, self::MAX_POCET_UC, $this->maxPocetUc,
			self::ID, $this->id
			);
			return true;
		}
	}


}
?>
