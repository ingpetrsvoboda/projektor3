<?php
/**
 * @author PHP_UML
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_Seznam_SFirma extends Data_Iterator
{
	public $id;
	public $ico;
	public $nazevFirmy;
	public $kodObce;
	public $obec;
	public $okres;
	public $kodOkresu;
	public $uliceACislo;
	public $psc;
	public $datumAkt;
	public $datumVzniku;
	public $tel;
	public $fax;
	public $email;
	public $http;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_firma";
	const ID = "id_s_firma";
	const ICO = "ico";
	const NAZEV_FIRMY = "nazev_firmy";
	const KOD_OBCE = "kod_obce";
	const OBEC = "obec";
	const OKRES = "okres";
	const KOD_OKRESU = "kod_okresu";
	const ULICE_A_CISLO = "ulice_a_cislo";
	const PSC = "psc";
	const DATUM_AKT = "datum_akt";
	const DATUM_VZNIKU = "datum_vzniku";
	const TEL = "tel";
	const FAX = "fax";
	const E_MAIL = "e_mail";
	const HTTP = "http";

	public function __construct($ico, $nazevFirmy, $kodObce, $obec, $okres, $kodOkresu, $uliceACislo, $psc, $datumAkt, $datumVzniku, $tel, $fax, $email, $http, $id = null)
	{
	 $this->id = $id;
	 $this->ico = $ico;
	 $this->nazevFirmy = $nazevFirmy;
	 $this->kodObce = $kodObce;
	 $this->obec = $obec;
	 $this->okres = $okres;
	 $this->kodOkresu = $kodOkresu;
	 $this->uliceACislo = $uliceACislo;
	 $this->psc = $psc;
	 $this->datumAkt = $datumAkt;
	 $this->datumVzniku = $datumVzniku;
	 $this->tel = $tel;
	 $this->fax = $fax;
	 $this->email = $email;
	 $this->http = $http;

         parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_SFirma($radek[self::ICO], $radek[self::NAZEV_FIRMY], $radek[self::KOD_OBCE], $radek[self::OBEC],
		$radek[self::OKRES], $radek[self::KOD_OKRESU], $radek[self::ULICE_A_CISLO], $radek[self::PSC],
		$radek[self::DATUM_AKT], $radek[self::DATUM_VZNIKU], $radek[self::TEL], $radek[self::FAX],
		$radek[self::E_MAIL], $radek[self::HTTP], $radek[self::ID]);
	}

	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "")
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
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
		$dbh = App_Kontext::getDbMySQLProjektor();

		if($this->id == null)
		{
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7, ~8, ~9, ~10, ~11, ~12, ~13, ~14, ~15) VALUES (:16, :17, :18, :19, :20, :21, :22, :23, :24, :25, :26, :27, :28, :29)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::ICO, self::NAZEV_FIRMY, self::KOD_OBCE, self::OBEC, self::OKRES, self::KOD_OKRESU, self::ULICE_A_CISLO, self::PSC, self::DATUM_AKT, self::DATUM_VZNIKU, self::TEL, self::FAX, self::E_MAIL, self::HTTP,
			$this->ico, $this->nazevFirmy, $this->kodObce, $this->obec, $this->okres, $this->kodOkresu, $this->uliceACislo, $this->psc, $this->datumAkt, $this->datumVzniku, $this->tel, $this->fax, $this->email, $this->http
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11, ~12=:13, ~14=:15, ~16=:17, ~18=:19, ~20=:21, ~22=:23, ~24=:25, ~26=:27, ~28=:29, WHERE ~30=:31";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::ICO, $this->ico,
			self::NAZEV_FIRMY, $this->nazevFirmy,
			self::KOD_OBCE, $this->kodObce,
			self::OBEC, $this->obec,
			self::OKRES, $this->okres,
			self::KOD_OKRESU, $this->kodOkresu,
			self::ULICE_A_CISLO, $this->uliceACislo,
			self::PSC, $this->psc,
			self::DATUM_AKT, $this->datumAkt,
			self::DATUM_VZNIKU, $this->datumVzniku,
			self::TEL, $this->tel,
			self::FAX, $this->fax,
			self::E_MAIL, $this->email,
			self::HTTP, $this->http,
			self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($sFirma)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $sFirma->id);
	}

}
?>
