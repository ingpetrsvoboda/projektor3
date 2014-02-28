<?php
class Framework_Chyby {
    static $chyby_text = array(
        503 => "musí být zadána",
        305 => "není platné datum",
        108 => "nenalezen příslušný sloupec v databázi",
        110 => "by měla být číslo",
        111 => "by měla být celé číslo",
        112 => "by měla být kladné číslo",
        120 => "je moc dlouhá"
    );
    public $pocet=0;
    public $promnenna=array();
    public $hodnota=array();
    public $chyba_cislo=array();
    public $chyba_text=array();
    
    public function write($promnenna,$hodnota,$chyba_cislo) {
        
        $this->promnenna[$this->pocet]=$promnenna;
        $this->hodnota[$this->pocet]=$hodnota;
        $this->chyba_cislo[$this->pocet]=$chyba_cislo;
        if (array_key_exists($chyba_cislo, self::$chyby_text)) $this->chyba_text[$this->pocet]= self::$chyby_text[$chyba_cislo];
        $this->pocet++;
    }
}

?>