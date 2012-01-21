<?php
class PDF_Paticka
{
    var $text;
    var $zarovnani;
    var $vyskaPisma;
    var $barvaPisma;
    var $barvaPozadi;
    var $barvaRamecku;
    var $odsazeni;
    var $cislovani;
    var $cisloStranky;
    
    
    const TEXT = "";
    const ZAROVNANI = "C";
    const VYSKA_PISMA = 10;
    const BARVA_PISMA = "0,0,0";
    const BARVA_POZADI = "255,255,255";
    const BARVA_RAMECKU = "255,255,255";
    const ODSAZENI = 25;
    const CISLOVANI = false;
    const CISLO_STRANKY = 0;
    
    public function __construct(  $text=self::TEXT, $zarovnani=self::ZAROVNANI, 
                                  $vyskaPisma=self::VYSKA_PISMA, $barvaPisma=self::BARVA_PISMA,
                                  $barvaPozadi=self::BARVA_POZADI, $barvaRamecku=self::BARVA_RAMECKU,
                                  $odsazeni=self::ODSAZENI, $cislovani=self::CISLOVANI, $cisloStranky=self::CISLO_STRANKY)
    {
      $this->text = $text;
      $this->zarovnani = $zarovnani;
      $this->vyskaPisma = $vyskaPisma;
      $this->barvaPisma = $barvaPisma;
      $this->barvaPozadi = $barvaPozadi;
      $this->barvaRamecku = $barvaRamecku;
      $this->odsazeni = $odsazeni;
      $this->cislovani = $cislovani;
      $this->cisloStranky = $cisloStranky;
    }
    
    function Text($text)
    {
        $this->text=iconv("UTF-8","windows-1250",$text);
    }
    
    function Zarovnani($zarovnani)
    {
        $this->zarovnani=$zarovnani;
    }
    
    function VyskaPisma($vyskaPisma)
    {
        $this->vyskaPisma=$vyskaPisma;
    }

    function Odsazeni($odsazeni)
    {
        $this->Odsazeni=$odsazeni;
    }

    function BarvaPisma($barvaPisma)
    {
        $this->barvaPisma=$barvaPisma;
    }

    function BarvaPozadi($barvaPozadi)
    {
        $this->barvaPozadi=$barvaPozadi;
    }
    
    function BarvaRamecku($barvaRamecku)
    {
        $this->barvaRamecku=$barvaRamecku;
    }
}
