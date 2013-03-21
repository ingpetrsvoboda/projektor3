<?php
class Projektor_Data_Auto_Cache_StrukturaTabulky
{
    public $tabulka;                 // název tabulky
    public $sloupce = array();         // pole objektů se strukturami řádků
    public $primaryKeyFieldName;     //jméno sloupce tabulky s primárním klíčem

    public function __construct($tabulka, $sloupce, $primaryKeyFieldName = NULL)
    {
        $this->tabulka  = $tabulka;
        $this->sloupce = $sloupce;
        $this->primaryKeyFieldName = $primaryKeyFieldName;
    }
}
?>
