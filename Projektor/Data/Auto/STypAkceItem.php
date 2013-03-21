<?php
class Projektor_Data_Auto_STypAkceItem extends Projektor_Data_Item
{
    const DATABAZE = Projektor_App_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "s_typ_akce";
    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°nazev";

###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku s_typ_akce
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec id_s_typ_akce. Vlatnosti sloupce: typ=int, sloupec je primární klíč a není autoicrement
     * je vygenerována standardní vlastnost
     */
    public $dbField°id_s_typ_akce;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec zkratka. Vlatnosti sloupce: typ=varchar, delka=45
     */
    public $dbField°zkratka;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec nazev. Vlatnosti sloupce: typ=varchar, delka=200
     */
    public $dbField°nazev;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec trvani_dni. Vlatnosti sloupce: typ=int
     */
    public $dbField°trvani_dni;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec hodiny_za_den. Vlatnosti sloupce: typ=int
     */
    public $dbField°hodiny_za_den;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec min_pocet_uc. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°min_pocet_uc;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec max_pocet_uc. Vlatnosti sloupce: typ=varchar, delka=20
     */
    public $dbField°max_pocet_uc;
    /**
     * Generovaná vlastnost pro tabulku s_typ_akce a sloupec valid. Vlatnosti sloupce: typ=tinyint, default=1
     */
    public $dbField°valid;

    public function reset()
    {
        unset($this->dbField°id_s_typ_akce);
        unset($this->dbField°zkratka);
        unset($this->dbField°nazev);
        unset($this->dbField°trvani_dni);
        unset($this->dbField°hodiny_za_den);
        unset($this->dbField°min_pocet_uc);
        unset($this->dbField°max_pocet_uc);
        unset($this->dbField°valid);
    }

###END_AUTOCODE

//    protected function nastavItem()
//    {
//        $this->databaze = self::DATABAZE;
//        $this->tabulka = self::TABULKA;
//    }

}
?>
