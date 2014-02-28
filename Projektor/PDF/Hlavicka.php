<?php
/**
 * Objekt hlavičky dokumentu pdf
 * @author Martin, Kovář, Petr Svoboda
 *
 */
class Projektor2_PDF_Hlavicka
{

    public $text;

    public $obrazekSoubor;
    public $obrazekX;
    public $obrazekY;
    public $obrazekW;
    public $obrazekH;
    public $obrazekTyp;

    public $zarovnani;
    public $vyskaPisma;
    public $barvaPisma;
    public $barvaPozadi;
    public $barvaRamecku;
    public $odsazeniNahore;
    public $odsazeniDole;
    public $radkovani;

    const TEXT = "";
    const ZAROVNANI = "C";
    const VYSKA_PISMA = 10;
    const BARVA_PISMA = "0,0,0";
    const BARVA_POZADI = "255,255,255";
    const BARVA_RAMECKU = "255,255,255";
    const ODSAZENI_NAHORE = 0;
    const ODSAZENI_DOLE = 10;
    const RADKOVANI = 1.5;  // vzdálenost mezi účařími v násobcích výšky písma

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
                                  $odsazeniNahore=self::ODSAZENI_NAHORE, $odsazeniDole=self::ODSAZENI_DOLE, $radkovani=self::RADKOVANI)
    {
      $this->text = $text;
      $this->zarovnani = $zarovnani;
      $this->vyskaPisma = $vyskaPisma;
      $this->barvaPisma = $barvaPisma;
      $this->barvaPozadi = $barvaPozadi;
      $this->barvaRamecku = $barvaRamecku;
      $this->odsazeniNahore = $odsazeniNahore;
      $this->odsazeniDole = $odsazeniDole;
      $this->radkovani = $radkovani;
    }

    /**
     * Nastaví objektu PDF_Hlavicka vlastnost text v kódování windows-1250
     * @param string $text - text hlavičky v UTF-8
     * @return
     */
    public function Odstavec($text)
    {
        $this->text=iconv("UTF-8","windows-1250",$text);
    }

    public function Obrazek($obrazekSoubor, $obrazekX=null, $obrazekY=null, $obrazekW=0, $obrazekH=0, $obrazekTyp='')
    {
        $this->obrazekSoubor  = $obrazekSoubor;
        $this->obrazekX = $obrazekX;
        $this->obrazekY = $obrazekY;
        $this->obrazekW = $obrazekW;
        $this->obrazekH = $obrazekH;
        $this->obrazekTyp = $obrazekTyp;
    }

    public function Zarovnani($zarovnani)
    {
        $this->zarovnani=$zarovnani;
    }

    public function VyskaPisma($vyskaPisma)
    {
        $this->vyskaPisma=$vyskaPisma;
    }

    public function OdsazeniNahore($odsazeni)
    {
        $this->odsazeniNahore=$odsazeni;
    }

    public function OdsazeniDole($odsazeni)
    {
        $this->odsazeniDole=$odsazeni;
    }

    public function Radkovani($radkovani)
    {
        $this->radkovani=$radkovani;
    }
    
    public function BarvaPisma($barvaPisma)
    {
        $this->barvaPisma=$barvaPisma;
    }

    public function BarvaPozadi($barvaPozadi)
    {
        $this->barvaPozadi=$barvaPozadi;
    }

    public function BarvaRamecku($barvaRamecku)
    {
        $this->barvaRamecku=$barvaRamecku;
    }
}
