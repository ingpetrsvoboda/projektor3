<?php
/**
 * Objekt hlavičky dokumentu pdf
 * @author Martin, Kovář, Petr Svoboda
 *
 */
class PDF_Hlavicka
{
    
    var $text;
    
    var $obrazekSoubor;
    var $obrazekX;
    var $obrazekY;
    var $obrazekW;
    var $obrazekH;
    var $obrazekTyp;
            
    var $zarovnani;
    var $vyskaPisma;
    var $barvaPisma;
    var $barvaPozadi;
    var $barvaRamecku;
    var $odsazeni;
    
    const TEXT = "";
    const ZAROVNANI = "C";
    const VYSKA_PISMA = 10;
    const BARVA_PISMA = "0,0,0";
    const BARVA_POZADI = "255,255,255";
    const BARVA_RAMECKU = "255,255,255";
    const ODSAZENI = 25;
    
    /**
     * @param string $text
     * @param character $zarovnani, default "C"
     * @param integer $vyskaPisma, default "12"
     * @param string $barvaPisma, default "0,0,0"
     * @param string $barvaPozadi, default "255,255,255"
     * @param string $barvaRamecku, default "255,255,255"
     * @param unknown_type $odsazeni, default "25"
     * @return 
     */
    public function __construct(  $text=self::TEXT, $zarovnani=self::ZAROVNANI, 
                                  $vyskaPisma=self::VYSKA_PISMA, $barvaPisma=self::BARVA_PISMA,
                                  $barvaPozadi=self::BARVA_POZADI, $barvaRamecku=self::BARVA_RAMECKU,
                                  $odsazeni=self::ODSAZENI)
    {
      $this->text = $text;
      $this->zarovnani = $zarovnani;
      $this->vyskaPisma = $vyskaPisma;
      $this->barvaPisma = $barvaPisma;
      $this->barvaPozadi = $barvaPozadi;
      $this->barvaRamecku = $barvaRamecku;
      $this->odsazeni = $odsazeni;
    }
    
    /**
     * Nastaví objektu PDF_Hlavicka vlastnost text
     * @param string $text - text hlavičky v UTF-8
     * @return 
     */
    function Text($text)
    {
        $this->text=iconv("UTF-8","windows-1250",$text);
    }
    
	function Obrazek($obrazekSoubor, $obrazekX=null, $obrazekY=null, $obrazekW=0, $obrazekH=0, $obrazekTyp='')
	{
		$this->obrazekSoubor  = $obrazekSoubor;
    	$this->obrazekX = $obrazekX;
    	$this->obrazekY = $obrazekY;
    	$this->obrazekW = $obrazekW;
    	$this->obrazekH = $obrazekH;
    	$this->obrazekTyp = $obrazekTyp;
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
