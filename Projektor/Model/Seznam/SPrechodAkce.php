<?php
/**
 * @author PHP_UML
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Projektor_Model_Seznam_SPrechodAkce extends Projektor_Model_Item
{
	public $id;
	public $text;
	public $plnyText;
	public $idSStavPredFK;
	public $idSStavPoFK;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_prechod_akce";
	const ID = "id_s_prechod_akce";
	const TEXT = "text";
	const PLNY_TEXT = "plny_text";
	const ID_S_STAV_PRED_FK = "id_s_stav_pred_FK";
	const ID_S_STAV_PO_FK = "id_s_stav_po_FK";

	public function __construct($text, $plnyText, $idSStavPredFK, $idSStavPoFK, $id = null)
	{
	 $this->id = $id;
	 $this->text = $text;
	 $this->plnyText = $plnyText;
	 $this->idSStavPredFK = $idSStavPredFK;
	 $this->idSStavPoFK = $idSStavPoFK;

         parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Projektor_Model_Seznam_SPrechodAkce($radek[self::TEXT], $radek[self::PLNY_TEXT],
		$radek[self::ID_S_STAV_PRED_FK], $radek[self::ID_S_STAV_PO_FK],
		$radek[self::ID]);
	}

	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "")
	{
		$dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
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
		$dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);

		if($this->id == null)
		{
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5) VALUES (:6, :7, :8, :9)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::TEXT, self::PLNY_TEXT, self::ID_S_STAV_PRED_FK, self::ID_S_STAV_PO_FK,
			$this->text, $this->plnyText, $this->idSStavPredFK, $this->idSStavPoFK
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7 WHERE ~8=:9";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::TEXT, $this->text,
			self::PLNY_TEXT, $this->plnyText,
			self::ID_S_STAV_PRED_FK, $this->idSStavPredFK,
			self::ID_S_STAV_PO_FK, $this->idSStavPoFK,
			self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($SPrechodAkce)
	{
		$dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $SPrechodAkce->id);
	}


	/**
	 * Overi jestli je mozne prejit z jednoho stavu do druheho.
	 * @param Projektor_Model_Seznam_SStavAkce $stavPred Stav Akce pred prechodem, tj.soucasny stav.
	 * @param Projektor_Model_Seznam_SStavAkce $stavPo Stav Akce po prechodu, tj budouci stav.
	 * @return boolean True pokud je mozny, false pokud ne.
	 */

	public static function jeMozny($stavPred, $stavPo)
	{
            $stavy = self::vypisVse(self::ID_S_STAV_PRED_FK.($stavPred ? " = \"{$stavPred->id}\"" : " IS NULL")." AND ".self::ID_S_STAV_PO_FK." = \"{$stavPo->id}\"");

            if(!$stavy)
                return false;
            else
                return true;
	}
}
?>