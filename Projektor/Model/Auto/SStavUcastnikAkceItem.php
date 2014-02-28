<?php
class Projektor_Model_Auto_SStavUcastnikAkceItem extends Projektor_Model_Item implements Projektor_Model_AutoItemInterface
{
    const DATABAZE = Framework_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "s_stav_ucastnik_akce";

    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°text";

###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku s_stav_ucastnik_akce
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku s_stav_ucastnik_akce a sloupec id_s_stav_ucastnik_akce. Vlatnosti sloupce: typ=int, sloupec je primární klíč a je auto_increment
     * je vygenerována public vlastnost se jménem $id
     */
    public $id;
    /**
     * Generovaná vlastnost pro tabulku s_stav_ucastnik_akce a sloupec text. Vlatnosti sloupce: typ=varchar, delka=200
     */
    public $dbField°text;
    /**
     * Generovaná vlastnost pro tabulku s_stav_ucastnik_akce a sloupec valid. Vlatnosti sloupce: typ=tinyint, default=1
     */
    public $dbField°valid;
    /**
     * Generovaná vlastnost pro tabulku s_stav_ucastnik_akce a sloupec plny_text. Vlatnosti sloupce: typ=varchar, delka=500
     */
    public $dbField°plny_text;

    public function reset()
    {
        unset($this->id);
        unset($this->dbField°text);
        unset($this->dbField°valid);
        unset($this->dbField°plny_text);
    }

###END_AUTOCODE
	/**
	 * Vrati pole vsech moznych nasledudjicich stavu pro stav.
	 * @param Projektor_Model_Seznam_SStavAkce $sStavAkce Soucasny stav
	 * @return array Pole moznych nasledujicich stavu
	 */

	public function vypisMozneNasledujiciStavy()
	{
		return Projektor_Model_Seznam_SPrechodUcastnikAkce::vypisVse(Projektor_Model_Seznam_SPrechodUcastnikAkce::ID_S_STAV_PRED_FK." = {$this->id}");
	}

}
?>
