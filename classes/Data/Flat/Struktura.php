<?php
class Data_Flat_Struktura
{
    public $nazevTabulky;
    // vlastnosti databázové tabulky vracené příkazem SHOW COLUMNS
    public $nazvy = array();         //názvy sloupců tabulky
    public $typy = array();          //datové typy sloupců tabulky
    public $delky = array();   //délky datových typů sloupců tabulky typu char, varchar atd.
    public $pk = array();           //TRUE pokud slopupec je primární klíč
    public $primaryKeyFieldName; //jméno sloupce tabulky s primárním klíčem
    
    public function __construct($nazevTabulky, $nazvy, $typy, $delky, $pk, $primaryKeyFieldName) {
        $this->nazevTabulky  = $nazevTabulky;
        $this->nazvy = $nazvy;
        $this->typy = $typy;
        $this->delky = $delky;
        $this->pk = $pk;
        $this->primaryKeyFieldName = $primaryKeyFieldName;
    }
}
?>
