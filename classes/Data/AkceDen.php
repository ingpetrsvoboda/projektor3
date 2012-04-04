<?php
/**
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_AkceDen extends Data_Iterator
{

	public $id;
	public $datum;
	public $idAkceFK;
	public $idSUcebnaFK;
	public $idSPersonalFK;

	const TABULKA = "akce_den";
	const ID = "id_akce_den";
	const DATUM = "datum";
	const ID_AKCE_FK = "id_akce_FK";
	const ID_S_UCEBNY_FK = "id_s_ucebny_FK";
	const ID_S_PERSONAL_FK = "id_s_personal_FK";

	public function __construct($datum, $idAkceFK, $idSUcebnaFK, $idSPersonalFK, $id = null)
	{
		$this->id = $id;
		$this->datum = $datum;
		$this->idAkceFK = $idAkceFK;
		$this->idSUcebnaFK = $idSUcebnaFK;
		$this->idSPersonalFK = $idSPersonalFK;

                parent::__construct(__CLASS__);
	}


	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_AkceDen($radek[self::DATUM], $radek[self::ID_AKCE_FK], $radek[self::ID_S_UCEBNY_FK], $radek[self::ID_S_PERSONAL_FK], $radek[self::ID]);
	}


	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "")
	{
                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2".($filtr == "" ? "" : " WHERE ({$filtr})");
		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[self::ID]);
		 
		return $vypis;
	}


	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($akce)
	{
                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $akce->id);
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
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5) VALUES (:6, :7, :8, :9)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::DATUM, self::ID_AKCE_FK, self::ID_S_UCEBNY_FK, self::ID_S_PERSONAL_FK,
			$this->datum, $this->idAkceFK, $this->idSUcebnaFK, $this->idSPersonalFK
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9 WHERE ~10=:11";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::DATUM, $this->datum, self::ID_AKCE_FK, $this->idAkceFK, self::ID_S_UCEBNY_FK,
			$this->idSUcebnaFK, self::ID_S_PERSONAL_FK, $this->idSPersonalFK, self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vrati instanci Akce k tomuto Akcedni.
	 * @return Akce
	 */

	public function dejAkce()
	{
		return Data_Akce::najdiPodleId($this->idAkceFK);
	}

	/**
	 * Vrati instanci SUcebna k tomuto Akcedni.
	 * @return Data_Seznam_SUcebna
	 */

	public function dejSUcebna()
	{
		return Data_Seznam_SUcebna::najdiPodleId($this->idSUcebnaFK);
	}

	/**
	 * Vrati instanci SPersonal k tomuto Akcedni.
	 * @return Data_Seznam_SPersonal
	 */

	public function dejSPersonal()
	{
		return Data_Seznam_SPersonal::najdiPodleId($this->idSPersonalFK);
	}

	/**
	 * Prihlasi ucastnika projektu k Akci (a i k jednotlivym AkceDnum)
	 * @param Ucastnik $ucastnik Instance ucastnika, ktereho prihlasujeme
	 * @param Data_Seznam_SStavUcastnikAkce $sStavUcastnikAkce Pocatecni stav ucastnika po prihlaseni vzhledem k Akci
	 * @param Data_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Pocatecni stav ucastnika po prihlaseni vzhledem k dnum Akce (AkceDen)
	 * @return unknown_type
	 */

	public function prihlas($ucastnik, $sStavUcastnikAkceDen)
	{
		if($this->stavUcastnika($ucastnik))
		throw new Exception("Ucastnik ID {$ucastnik->id} je jiz na AkceDen ID {$this->id} prihlasen.");
			
		if(!Data_Seznam_SPrechodUcastnikAkceDen::jeMozny(NULL, $sStavUcastnikAkceDen))
		throw new Exception("Ucastnika AkceDne nelze prihlasit primo do stavu ID: {$sStavUcastnikAkceDen->id}");

                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "INSERT INTO ~1 (~2, ~3, ~4) VALUES (:5, :6, :7)";
		$dbh->prepare($query)->execute(Data_Vzb_UcastnikAkceDen::TABULKA, Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK,
		Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK,
		$ucastnik->id, $this->id, $sStavUcastnikAkceDen->id);
	}


	/**
	 * Zmeni stav ucastnika AkceDne
	 * @param Ucastnik $ucastnik Instance ucastnika, kteremu menime stav
	 * @param Data_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Stav ucastnika vzhledem k AkceDni
	 * @return unknown_type
	 */

	public function zmenStavUcastnika($ucastnik, $sStavUcastnikAkceDen)
	{
		if(!Data_Seznam_SPrechodUcastnikAkceDen::jeMozny($this->stavUcastnika($ucastnik), $sStavUcastnikAkceDen))
		throw new Exception("Ucastnik AkceDne ve stavu ID: {$this->stavUcastnika($ucastnik)->id} nemuze prejit do stavu ID: {$sStavUcastnikAkceDen->id}");

                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET ~2=:3 WHERE (~4 = :5 AND ~6 = :7)";
		$dbh->prepare($query)->execute(Data_Vzb_UcastnikAkceDen::TABULKA,
		Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK,
		$sStavUcastnikAkceDen->id,
		Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK,
		$ucastnik->id,
		Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK,
		$this->id);
	}

	/**
	 * Vrati stav ucastnika vzhledem k AkceDni nebo false pokud neni prihlasen.
	 * @param Ucastnik $ucastnik
	 * @return Data_Seznam_SStavUcastnikAkceDen
	 */

	public function stavUcastnika($ucastnik)
	{
                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);

		$query = "SELECT ~1 FROM ~2 WHERE (~3=:4 AND ~5=:6)";
		$radek = $dbh->prepare($query)->execute(Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK, Data_Vzb_UcastnikAkceDen::TABULKA,
		Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK, $ucastnik->id,
		Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, $this->id
		)->fetch_assoc();

		if($radek)
		return Data_Seznam_SStavUcastnikAkceDen::najdiPodleId($radek[Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK]);
		else
		return false;
	}


	/**
	 * Najde a vrati vsechny Ucastniky prihlasene k AkceDne
	 * @return array() Pole instanci Ucastnik
	 */

	public function vsichniUcastnici()
	{
                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ~3=:4";
		$radky = $dbh->prepare($query)->execute(Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK, Data_Vzb_UcastnikAkceDen::TABULKA,
		Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, $this->id)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = Data_Ucastnik::najdiPodleId($radek[Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK]);
		 
		return $vypis;
	}


	/**
	 * Najde a vrati vsechny AkceDne na kterych je Ucasntik prihlasen.
	 * @param Ucastnik $ucastnik Instance ucastnika projektu
	 * @return array() Pole instanci AkceDne
	 */

	public static function vsechnyUcastnika($ucastnik)
	{
                $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ~3=:4";
		$radky = $dbh->prepare($query)->execute(Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, Data_Vzb_UcastnikAkceDen::TABULKA,
		Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK, $ucastnik->id)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK]);
		 
		return $vypis;
	}

}
?>
