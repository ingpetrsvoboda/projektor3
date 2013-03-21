<?php
class Projektor_Data_Auto_AkceDenItem extends Projektor_Data_Item
{
    const DATABAZE = Projektor_App_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "akce_den";
    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "id";    //TODO: u vazebních tabulek NAZEV_ZOBRAZOVANE_VLASTNOSTI nemá smysl ? upravit generator

###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku akce_den
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku akce_den a sloupec id_akce_den. Vlatnosti sloupce: typ=int, sloupec je primární klíč a je auto_increment
     * je vygenerována public vlastnost se jménem $id
     */
    public $id;
    /**
     * Generovaná vlastnost pro tabulku akce_den a sloupec datum. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°datum;
    /**
     * Generovaná vlastnost pro tabulku akce_den a sloupec id_akce_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky akce a sloupce id_akce
     */
    public $dbField°id_akce_FK;
    /**
     * Generovaná vlastnost pro tabulku akce_den a sloupec id_s_ucebny_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky s_ucebny a sloupce id_s_ucebny
     */
    public $dbField°id_s_ucebny_FK;
    /**
     * Generovaná vlastnost pro tabulku akce_den a sloupec id_s_personal_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky s_personal a sloupce id_s_personal
     */
    public $dbField°id_s_personal_FK;

    public function reset()
    {
        unset($this->id);
        unset($this->dbField°datum);
        unset($this->dbField°id_akce_FK);
        unset($this->dbField°id_s_ucebny_FK);
        unset($this->dbField°id_s_personal_FK);
    }

###END_AUTOCODE

	/**
	 * Vrati instanci Akce k tomuto Akcedni.
	 * @return Akce
	 */

	public function dejAkce()
	{
		return Projektor_Data_Auto_AkceItem::najdiPodleId($this->idAkceFK);
	}

	/**
	 * Vrati instanci SUcebna k tomuto Akcedni.
	 * @return Projektor_Data_Seznam_SUcebna
	 */

	public function dejSUcebna()
	{
		return Projektor_Data_Seznam_SUcebna::najdiPodleId($this->idSUcebnaFK);
	}

	/**
	 * Vrati instanci SPersonal k tomuto Akcedni.
	 * @return Projektor_Data_Seznam_SPersonal
	 */

	public function dejSPersonal()
	{
		return Projektor_Data_Seznam_SPersonal::najdiPodleId($this->idSPersonalFK);
	}

	/**
	 * Prihlasi ucastnika projektu k Akci (a i k jednotlivym AkceDnum)
	 * @param Ucastnik $ucastnik Instance ucastnika, ktereho prihlasujeme
	 * @param Projektor_Data_Seznam_SStavUcastnikAkce $sStavUcastnikAkce Pocatecni stav ucastnika po prihlaseni vzhledem k Akci
	 * @param Projektor_Data_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Pocatecni stav ucastnika po prihlaseni vzhledem k dnum Akce (AkceDen)
	 * @return unknown_type
	 */

	public function prihlas($ucastnik, $sStavUcastnikAkceDen)
	{
		if($this->stavUcastnika($ucastnik))
		throw new Exception("Ucastnik ID {$ucastnik->id} je jiz na AkceDen ID {$this->id} prihlasen.");

		if(!Projektor_Data_Seznam_SPrechodUcastnikAkceDen::jeMozny(NULL, $sStavUcastnikAkceDen))
		throw new Exception("Ucastnika AkceDne nelze prihlasit primo do stavu ID: {$sStavUcastnikAkceDen->id}");

                $dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "INSERT INTO ~1 (~2, ~3, ~4) VALUES (:5, :6, :7)";
		$dbh->prepare($query)->execute(Projektor_Data_Vzb_UcastnikAkceDen::TABULKA, Projektor_Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, Projektor_Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK,
		$ucastnik->id, $this->id, $sStavUcastnikAkceDen->id);
	}


	/**
	 * Zmeni stav ucastnika AkceDne
	 * @param Ucastnik $ucastnik Instance ucastnika, kteremu menime stav
	 * @param Projektor_Data_Seznam_SStavUcastnikAkceDen $sStavUcastnikAkceDen Stav ucastnika vzhledem k AkceDni
	 * @return unknown_type
	 */

	public function zmenStavUcastnika($ucastnik, $sStavUcastnikAkceDen)
	{
		if(!Projektor_Data_Seznam_SPrechodUcastnikAkceDen::jeMozny($this->stavUcastnika($ucastnik), $sStavUcastnikAkceDen))
		throw new Exception("Ucastnik AkceDne ve stavu ID: {$this->stavUcastnika($ucastnik)->id} nemuze prejit do stavu ID: {$sStavUcastnikAkceDen->id}");

                $dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "UPDATE ~1 SET ~2=:3 WHERE (~4 = :5 AND ~6 = :7)";
		$dbh->prepare($query)->execute(Projektor_Data_Vzb_UcastnikAkceDen::TABULKA,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK,
		$sStavUcastnikAkceDen->id,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK,
		$ucastnik->id,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK,
		$this->id);
	}

	/**
	 * Vrati stav ucastnika vzhledem k AkceDni nebo false pokud neni prihlasen.
	 * @param Ucastnik $ucastnik
	 * @return Projektor_Data_Seznam_SStavUcastnikAkceDen
	 */

	public function stavUcastnika($ucastnik)
	{
                $dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);

		$query = "SELECT ~1 FROM ~2 WHERE (~3=:4 AND ~5=:6)";
		$radek = $dbh->prepare($query)->execute(Projektor_Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK, Projektor_Data_Vzb_UcastnikAkceDen::TABULKA,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK, $ucastnik->id,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, $this->id
		)->fetch_assoc();

		if($radek)
		return Projektor_Data_Seznam_SStavUcastnikAkceDen::najdiPodleId($radek[Projektor_Data_Vzb_UcastnikAkceDen::ID_S_STAV_UCASTNIK_AKCE_DEN_FK]);
		else
		return false;
	}


	/**
	 * Najde a vrati vsechny Ucastniky prihlasene k AkceDne
	 * @return array() Pole instanci Ucastnik
	 */

	public function vsichniUcastnici()
	{
                $dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ~3=:4";
		$radky = $dbh->prepare($query)->execute(Projektor_Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK, Projektor_Data_Vzb_UcastnikAkceDen::TABULKA,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, $this->id)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = Projektor_Data_Ucastnik::najdiPodleId($radek[Projektor_Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK]);

		return $vypis;
	}


	/**
	 * Najde a vrati vsechny AkceDne na kterych je Ucasntik prihlasen.
	 * @param Ucastnik $ucastnik Instance ucastnika projektu
	 * @return array() Pole instanci AkceDne
	 */

	public static function vsechnyUcastnika($ucastnik)
	{
                $dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT ~1 FROM ~2 WHERE ~3=:4";
		$radky = $dbh->prepare($query)->execute(Projektor_Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK, Projektor_Data_Vzb_UcastnikAkceDen::TABULKA,
		Projektor_Data_Vzb_UcastnikAkceDen::ID_UCASTNIK_FK, $ucastnik->id)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[Projektor_Data_Vzb_UcastnikAkceDen::ID_AKCE_DEN_FK]);

		return $vypis;
	}

}
?>
