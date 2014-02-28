<?php
class Projektor_Model_Auto_SFirmaItem extends Projektor_Model_Item implements Projektor_Model_AutoItemInterface
{
    const DATABAZE = Framework_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "s_firma";
    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°nazev_firmy";


###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku s_firma
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec id_s_firma. Vlatnosti sloupce: typ=int, sloupec je primární klíč a je auto_increment
     * je vygenerována public vlastnost se jménem $id
     */
    public $id;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec ico. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°ico;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec nazev_firmy. Vlatnosti sloupce: typ=varchar, delka=500
     */
    public $dbField°nazev_firmy;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec kod_obce. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°kod_obce;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec obec. Vlatnosti sloupce: typ=varchar, delka=100
     */
    public $dbField°obec;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec okres. Vlatnosti sloupce: typ=varchar, delka=100
     */
    public $dbField°okres;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec kod_okresu. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°kod_okresu;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec ulice_a_cislo. Vlatnosti sloupce: typ=varchar, delka=200
     */
    public $dbField°ulice_a_cislo;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec psc. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°psc;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec datum_akt. Vlatnosti sloupce: typ=date
     */
    public $dbField°datum_akt;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec datum_vzniku. Vlatnosti sloupce: typ=date
     */
    public $dbField°datum_vzniku;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec tel. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°tel;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec fax. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°fax;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec e_mail. Vlatnosti sloupce: typ=varchar, delka=100
     */
    public $dbField°e_mail;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec http. Vlatnosti sloupce: typ=varchar, delka=100
     */
    public $dbField°http;
    /**
     * Generovaná vlastnost pro tabulku s_firma a sloupec valid. Vlatnosti sloupce: typ=tinyint, default=1
     */
    public $dbField°valid;

    public function reset()
    {
        unset($this->id);
        unset($this->dbField°ico);
        unset($this->dbField°nazev_firmy);
        unset($this->dbField°kod_obce);
        unset($this->dbField°obec);
        unset($this->dbField°okres);
        unset($this->dbField°kod_okresu);
        unset($this->dbField°ulice_a_cislo);
        unset($this->dbField°psc);
        unset($this->dbField°datum_akt);
        unset($this->dbField°datum_vzniku);
        unset($this->dbField°tel);
        unset($this->dbField°fax);
        unset($this->dbField°e_mail);
        unset($this->dbField°http);
        unset($this->dbField°valid);
    }

###END_AUTOCODE
}
?>
