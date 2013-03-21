<?php
class Projektor_Data_Auto_SBehProjektuItem extends Projektor_Data_Item
{
    const DATABAZE = Projektor_App_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "s_beh_projektu";

    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°text";

###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku s_beh_projektu
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec id_s_beh_projektu. Vlatnosti sloupce: typ=int, sloupec je primární klíč a je auto_increment
     * je vygenerována public vlastnost se jménem $id
     */
    public $id;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec beh_cislo. Vlatnosti sloupce: typ=int
     */
    public $dbField°beh_cislo;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec oznaceni_turnusu. Vlatnosti sloupce: typ=int
     */
    public $dbField°oznaceni_turnusu;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec id_c_projekt. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky c_projekt a sloupce id_c_projekt
     */
    public $dbField°id_c_projekt;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec text. Vlatnosti sloupce: typ=varchar, delka=50, default=""
     */
    public $dbField°text;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec zacatek. Vlatnosti sloupce: typ=date
     */
    public $dbField°zacatek;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec konec. Vlatnosti sloupce: typ=date
     */
    public $dbField°konec;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec closed. Vlatnosti sloupce: typ=tinyint
     */
    public $dbField°closed;
    /**
     * Generovaná vlastnost pro tabulku s_beh_projektu a sloupec valid. Vlatnosti sloupce: typ=tinyint, default=1
     */
    public $dbField°valid;

    public function reset()
    {
        unset($this->id);
        unset($this->dbField°beh_cislo);
        unset($this->dbField°oznaceni_turnusu);
        unset($this->dbField°id_c_projekt);
        unset($this->dbField°text);
        unset($this->dbField°zacatek);
        unset($this->dbField°konec);
        unset($this->dbField°closed);
        unset($this->dbField°valid);
    }

###END_AUTOCODE

}
?>
