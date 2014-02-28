<?php
class Projektor_Model_Auto_AkceItem extends Projektor_Model_Item implements Projektor_Model_AutoItemInterface
{
    const DATABAZE = Framework_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "akce";
    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°nazev";


###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku akce
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku akce a sloupec id_akce. Vlatnosti sloupce: typ=int, sloupec je primární klíč a je auto_increment
     * je vygenerována public vlastnost se jménem $id
     */
    public $id;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec nazev_hlavniho_objektu. Vlatnosti sloupce: typ=varchar, delka=45
     */
    public $dbField°nazev_hlavniho_objektu;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec datum_zacatek. Vlatnosti sloupce: typ=date
     */
    public $dbField°datum_zacatek;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec datum_konec. Vlatnosti sloupce: typ=date
     */
    public $dbField°datum_konec;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec nazev. Vlatnosti sloupce: typ=varchar, delka=100
     */
    public $dbField°nazev;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec popis. Vlatnosti sloupce: typ=varchar, delka=500
     */
    public $dbField°popis;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec id_s_stav_akce_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky s_stav_akce a sloupce id_s_stav_akce
     */
    public $dbField°id_s_stav_akce_FK;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec id_s_typ_akce_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky s_typ_akce a sloupce id_s_typ_akce
     */
    public $dbField°id_s_typ_akce_FK;
    /**
     * Generovaná vlastnost pro tabulku akce a sloupec valid. Vlatnosti sloupce: typ=tinyint, default=1
     */
    public $dbField°valid;

    public function reset()
    {
        unset($this->id);
        unset($this->dbField°nazev_hlavniho_objektu);
        unset($this->dbField°datum_zacatek);
        unset($this->dbField°datum_konec);
        unset($this->dbField°nazev);
        unset($this->dbField°popis);
        unset($this->dbField°id_s_stav_akce_FK);
        unset($this->dbField°id_s_typ_akce_FK);
        unset($this->dbField°valid);
    }

###END_AUTOCODE

//    protected function nastavItem()
//    {
//        $this->databaze = self::DATABAZE;
//        $this->tabulka = self::TABULKA;
//    }


//	public function __construct($nazevHlavnihoObjektu, $datumZacatek, $datumKonec, $nazev, $popis, $idSStavAkceFK, $idSTypAkceFK, $id = null)
//	{
//		$this->id = $id;
//                $this->nazevHlavnihoObjektu = $nazevHlavnihoObjektu;
//		$this->datumZacatek = $datumZacatek;
//                $this->datumKonec = $datumKonec;
//		$this->nazev = $nazev;
//		$this->popis = $popis;
//		$this->idSStavAkceFK = $idSStavAkceFK;
//		$this->idSTypAkceFK = $idSTypAkceFK;
//
//                parent::__construct(__CLASS__);
//	}

//    /**
//	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
//	 * @param int $id Identifikator radku tabulky
//	 * @return Projektor_Model_Akce Instance tridy obsahujici data z radku v tabulce
//	 */
//
//	public static function najdiPodleId($id)
//	{
//                $dbh = Framework_Kontext::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
//		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
//		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();
//
//		if(!$radek)
//                    return false;
//
//		return new Projektor_Model_Akce($radek[self::NAZEV_HLAVNIHO_OBJEKTU], $radek[self::DATUM_ZACATEK], $radek[self::DATUM_KONEC], $radek[self::NAZEV], $radek[self::POPIS], $radek[self::ID_S_STAV_AKCE_FK], $radek[self::ID_S_TYP_AKCE_FK], $radek[self::ID]);
//	}
//
//
//	/**
//	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
//	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
//	 * @return array() Pole instanci tridy odpovidajici radkum v DB
//	 */
//
//	public static function vypisVse($filtr = "", $orderBy = "", $order = "")
//	{
//                $dbh = Framework_Kontext::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
//		$query = "SELECT ~1 FROM ~2".
//			($filtr == "" ? "" : " WHERE ({$filtr})").
//			($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;
//
//		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();
//
//		foreach($radky as $radek)
//		$vypis[] = self::najdiPodleId($radek[self::ID]);
//
//		return $vypis;
//	}


	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVseProObjekt($nazevHlavnihoObjektu, $filtr = "", $orderBy = "", $order = "")
	{
                $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2".
			($filtr == "" ? " WHERE ~3 = :4" : " WHERE ~3 = :4 AND ({$filtr})").
			($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;

		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA, self::NAZEV_HLAVNIHO_OBJEKTU, $nazevHlavnihoObjektu)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[self::ID], $radek[self::NAZEV_HLAVNIHO_OBJEKTU]);

		return $vypis;
	}

//	/**
//	 * Nastavi v radku v databaze odpovidajici parametru $id tridy hodnotu valid = 0
//	 * @return unknown_type
//	 */
//
//	public static function smaz()
//	{
//                $dbh = Framework_Kontext::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
//		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
//		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $this->id);
//	}


//	/**
//	 * Ulozi parametry tridy jako radek do DB.
//	 * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
//	 */
//
//	public function uloz()
//	{
//                $dbh = Framework_Kontext::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
//
//		if($this->id == null)
//		{
//			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7, ~8) VALUES (:9, :10, :11, :12, :13, :14, :15)";
//			return $dbh->prepare($query)->execute(
//			self::TABULKA, self::NAZEV_HLAVNIHO_OBJEKTU, self::DATUM_ZACATEK, self::DATUM_KONEC, self::NAZEV, self::POPIS, self::ID_S_STAV_AKCE_FK, self::ID_S_TYP_AKCE_FK,
//                        $this->nazevHlavnihoObjektu, $this->datumZacatek, $this->datumKonec, $this->nazev, $this->popis, $this->idSStavAkceFK, $this->idSTypAkceFK
//			)->last_insert_id();
//		}
//		else
//		{
//			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11, ~12=:13, ~14=:15 WHERE ~16=:17";
//			$dbh->prepare($query)->execute(
//			self::TABULKA,
//                        self::NAZEV_HLAVNIHO_OBJEKTU, $this->nazevHlavnihoObjektu,
//                        self::DATUM_ZACATEK, $this->datumZacatek,
//                        self::DATUM_KONEC, $this->datumKonec,
//                        self::NAZEV, $this->nazev,
//                        self::POPIS, $this->popis,
//                        self::ID_S_STAV_AKCE_FK, $this->idSStavAkceFK,
//                        self::ID_S_TYP_AKCE_FK, $this->idSTypAkceFK,
//                        self::ID, $this->id
//			);
//			return true;
//		}
//	}

	/**
	 * Vrati instanci typu teto akce.
	 * @return Projektor_Model_Seznam_STypAkce
	 */

	public function dejSTypAkce()
	{
		return Projektor_Model_Seznam_STypAkce::najdiPodleId($this->idSTypAkceFK);
	}

	/**
	 * Vrati instanci stavu teto akce.
	 * @return Projektor_Model_Seznam_SStavAkce
	 */

	public function dejSStavAkce()
	{
		return Projektor_Model_Seznam_SStavAkce::najdiPodleId($this->idSStavAkceFK);
	}


	/**
	 * Prihlasi ucastnika projektu k Akci (a i k jednotlivym AkceDnum)
	 * @param Ucastnik $ucastnik Instance ucastnika, ktereho prihlasujeme
	 * @param Projektor_Model_Seznam_SStavUcastnikAkce $sStavUcastnikAkce Pocatecni stav ucastnika po prihlaseni vzhledem k Akci
	 * @param Projektor_Model_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Pocatecni stav ucastnika po prihlaseni vzhledem k dnum Akce (AkceDen)
	 * @return unknown_type
	 */

	public function prihlas($ucastnik, $sStavUcastnikAkce, $sStavUcastnikAkceDen)
	{
		if($this->stavUcastnika($ucastnik))
			throw new Exception("Ucastnik ID {$ucastnik->id} je jiz na Akci ID {$this->id} prihlasen.");

                $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "INSERT INTO ~1 (~2, ~3, ~4) VALUES (:5, :6, :7)";
		$dbh->prepare($query)->execute(Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA, Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK,
		Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK, Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK,
		$ucastnik->id, $this->id, $sStavUcastnikAkce->id);

		$this->prihlasNaVsechnyDny($ucastnik, $sStavUcastnikAkceDen);
	}

	/**
	 * Prihlasi Ucastnika na jednotlive AkceDny
	 * @param Ucastnik $ucastnik Instance ucastnika projektu
	 * @return unknown_type
	 */

	private function prihlasNaVsechnyDny($ucastnik, $sStavUcastnikAkceDen)
	{
		$akceDny = $this->vsechnyDny();
		if(!$akceDny)
			throw new Exception("Akce ID: {$this->id} nema zadne AkceDny.");

		foreach($akceDny as $akceDen)
		$akceDen->prihlas($ucastnik, $sStavUcastnikAkceDen);
	}


	/**
	 * Zmeni stav ucastnika Akce (a i jeho stav u jednotlivych AkceDnu)
	 * @param Ucastnik $ucastnik Instance ucastnika, kteremu menime stav
	 * @param Projektor_Model_Seznam_SStavUcastnikAkce $sStavUcastnikAkce Stav ucastnika vzhledem k Akci
	 * @param Projektor_Model_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Stav ucastnika vzhledm k dnum Akce (AkceDen) - volitelne
	 * @return unknown_type
	 */

	public function zmenStavUcastnika($ucastnik, $sStavUcastnikAkce, $sStavUcastnikAkceDen = null)
	{
		if(!Projektor_Model_Seznam_SPrechodUcastnikAkce::jeMozny($this->stavUcastnika($ucastnik), $sStavUcastnikAkce))
			throw new Exception("Ucastnik Akce ve stavu ID: {$this->stavUcastnika($ucastnik)->id} nemuze prejit do stavu ID: {$sStavUcastnikAkce->id}");

                $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET ~2=:3 WHERE (~4 = :5 AND ~6 = :7)";
		$dbh->prepare($query)->execute(Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA,
		Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK,
		$sStavUcastnikAkce->id,
		Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK,
		$ucastnik->id,
		Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK,
		$this->id);

		if($sStavUcastnikAkceDen)
			$this->zmenStavUcastnikaNaVsechDnech($ucastnik, $sStavUcastnikAkceDen);
	}

	/**
	 * Vrati stav ucastnika vzhledem k AkceDni nebo false pokud neni prihlasen.
	 * @param Ucastnik $ucastnik
	 * @return Projektor_Model_Seznam_SStavUcastnikAkceDen
	 */

	public function stavUcastnika($ucastnik)
	{
                $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);

		$query = "SELECT ~1 FROM ~2 WHERE (~3=:4 AND ~5=:6)";
                $dbh->bindIdentificator('~1', Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK);
                $dbh->bindIdentificator('~2', Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA);
                $dbh->bindIdentificator('~3', Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK);
                $dbh->bindIdentificator('~5', Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK);
                $stm = $dbh->prepare($query);
                $stm->bindParam(':4', $ucastnik->id);
                $stm->bindParam(':6', $this->id);
                $radek = $stm->fetch(PDO::FETCH_ASSOC);
                
//		$radek = $dbh->prepare($query)->execute(Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK, Projektor_Model_Auto_VzbUcastnikAkceCollection::TABULKA,
//		Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_UCASTNIK_FK, $ucastnik->id,
//		Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_AKCE_FK, $this->id
//		)->fetch_assoc();

		if($radek)
			return Projektor_Model_Seznam_SStavUcastnikAkce::najdiPodleId($radek[Projektor_Model_Auto_VzbUcastnikAkceCollection::ID_S_STAV_UCASTNIK_AKCE_FK]);
		else
			return false;
	}

	/**
	 * Zmeni stav ucastnika u vsech AkceDnu
	 * @param Ucastnik $ucastnik Instance ucastnika, kteremu menime stav
	 * @param Projektor_Model_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Stav ucastnika vzhledem k dnum Akce (AkceDen)
	 * @return unknown_type
	 */

	private function zmenStavUcastnikaNaVsechDnech($ucastnik, $sStavUcastnikAkceDen)
	{
		$akceDny = $this->vsechnyDny();
		if(!$akceDny)
			throw new Exception("Akce ID: {$this->id} nema zadne AkceDny.");

		foreach($akceDny as $akceDen)
			if(!$akceDen->stavUcastnika($ucastnik))
				throw new Exception("Ucastnik ID: {$ucastnik->id} neni na AkceDni ID: {$akceDen->id} prihlasen.");
		else
			$akceDen->zmenStavUcastnika($ucastnik, $sStavUcastnikAkceDen);
	}


	/**
	 * Zmeni stav Akce (tzn. vsem prihlasenym ucastnikum zmeni stav)
	 * @param Projektor_Model_Seznam_SStavUcastnikAkce $sStavUcastnikAkce Stav ucastniku vzhledem k Akci
	 * @param Projektor_Model_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Stav ucastniku vzhledm k dnum Akce (AkceDen) - volitelne
	 * @return unknown_type
	 */

	public function zmenStav($sStavAkce, $sStavUcastnikAkce = null, $sStavUcastnikAkceDen = null)
	{
		if(!Projektor_Model_Seznam_SPrechodAkce::jeMozny($this->dejSStavAkce(), $sStavAkce))
			throw new Exception("Prechod ze stavu Akce ID: {$this->dejSStavAkce()->id} do stavu ID: {$sStavAkce->id} neni mozny.");

                $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET ~2=:3 WHERE ~4=:5";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID_S_STAV_AKCE_FK, $sStavAkce->id, self::ID, $this->id);

		if($sStavUcastnikAkce)
		{
                    $ucastniciAkce = $this->vsichniUcastnici();
                    foreach($ucastniciAkce as $ucastnikAkce)
                        $this->zmenStavUcastnika($ucastnikAkce, $sStavUcastnikAkce, $sStavUcastnikAkceDen);
		}
	}


	/**
	 * Automaticky vytvori AkceDny k Akci. Pocet dnu je stanoven podle hodnoty startDatum Akce.
	 * @param $sUcebna Ucebna ve ktere by mely AkceDny probihat.
	 * @param $sPersonal Personal, ktery bude akci zajistovat.
	 * @return void
	 */

	public function vytvorDny($sUcebna, $sPersonal)
	{
		$datum = new DateTime($this->startDatum);
		for($i = $this->dejSTypAkce()->trvaniDni; $i > 0; $i--)
		{
			$akceDen = new Projektor_Model_AkceDen($datum->format("Y-m-d"), $this->id, $sUcebna->id, $sPersonal->id);
			$akceDen->uloz();
			$datum->modify("+1 day");
		}

	}


	/**
	 * Najde a vrati vsechny AkceDny teto Akce.
	 * @param Ucastnik $ucastnik Instance ucastnika projektu
	 * @return array() Pole instanci Akce
	 */

	public function vsechnyDny()
	{
		return Projektor_Model_AkceDen::vypisVse(Projektor_Model_AkceDen::ID_AKCE_FK."={$this->id}");
	}
}
?>
