<?php
class Projektor_Data_Auto_Cache_StrukturaSloupce
{
    public $nazev;         //názvy sloupců tabulky
    public $default;       //default hodnoty sloupců tabulky
    public $typ;          //datové typy sloupců tabulky
    public $delka;         //délky datových typů sloupců tabulky typu char, varchar atd.
    public $klic;         //údaj, zda sloupec je klíč
    public $extra;         //řetězec 'auto_increment', pokud je sloupec primární klíč a je autoincrement
    public $referencovanaTabulka;  //jméno tabulky, ze které je cizí klíč
    public $referencovanySloupec;  //jméno sloupce referencované tabulky odpovídající cizímu klíči
    public $titulek;

    public function __construct($nazev, $default, $typ, $delka, $klic, $extra, $ReferencovanaTabulka, $ReferencovanySloupec)
    {
        $this->nazev = $nazev;
        $this->default = $default;
        $this->typ = $typ;
        $this->delka = $delka;
        $this->klic = $klic;
        $this->extra = $extra;
        $this->referencovanaTabulka = $ReferencovanaTabulka;
        $this->referencovanySloupec = $ReferencovanySloupec;
    }
}
?>
