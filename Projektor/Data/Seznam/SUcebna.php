<?php
/**
 * @author PHP_UML
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Projektor_Data_Seznam_SUcebna extends Projektor_Data_Item
{
	public $id;
	public $popis;
	public $infoPublic;
	public $infoManager;
	public $idSFirmaFK;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_ucebny";
	const ID = "id_s_ucebny";
	const POPIS = "popis";
	const INFO_PUBLIC = "info_public";
	const INFO_MANAGER = "info_manager";
	const ID_S_FIRMA_FK = "id_s_firma_FK";

	public function __construct($popis, $infoPublic, $infoManager, $idSFirmaFK, $id = null)
	{
	 $this->id = $id;
	 $this->popis = $popis;
	 $this->infoPublic = $infoPublic;
	 $this->infoManager = $infoManager;
	 $this->idSFirmaFK = $idSFirmaFK;

         parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Projektor_Data_Seznam_SUcebna($radek[self::POPIS], $radek[self::INFO_PUBLIC],
		$radek[self::INFO_MANAGER], $radek[self::ID_S_FIRMA_FK],
		$radek[self::ID]);
	}

	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "")
	{
		$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
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
		$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);

		if($this->id == null)
		{
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5) VALUES (:6, :7, :8, :9)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::POPIS, self::INFO_PUBLIC, self::INFO_MANAGER, self::ID_S_FIRMA_FK,
			$this->popis, $this->infoPublic, $this->infoManager, $this->idSFirmaFK
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, WHERE ~10=:11";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::POPIS, $this->popis,
			self::INFO_PUBLIC, $this->infoPublic,
			self::INFO_MANAGER, $this->infoManager,
			self::ID_S_FIRMA_FK, $this->idSFirmaFK,
			self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($sUcebna)
	{
		$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $sUcebna->id);
	}


}
?>
