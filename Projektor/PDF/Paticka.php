<?php
class Projektor2_PDF_Paticka
{
    public $text;
    public $zarovnani;
    public $vyskaPisma;
    public $barvaPisma;
    public $barvaPozadi;
    public $barvaRamecku;
    public $cislovani;
    public $cisloStranky;
    public $odsazeniNahore;
    public $odsazeniDole;
    public $radkovani;


    const TEXT = "";
    const ZAROVNANI = "C";
    const VYSKA_PISMA = 10;
    const BARVA_PISMA = "128,128,128";
    const BARVA_POZADI = "255,255,255";
    const BARVA_RAMECKU = "255,255,255";
    const ODSAZENI_NAHORE = 7;
    const ODSAZENI_DOLE = 7;
    const RADKOVANI = 1.5;  // vzdálenost mezi účařími v násobcích výšky písma
    const CISLOVANI = false;
    const CISLO_STRANKY = 0;

    public function __construct(  $text=self::TEXT, $zarovnani=self::ZAROVNANI,
                                  $vyskaPisma=self::VYSKA_PISMA, $barvaPisma=self::BARVA_PISMA,
                                  $barvaPozadi=self::BARVA_POZADI, $barvaRamecku=self::BARVA_RAMECKU,
                                  $odsazeniNahore=self::ODSAZENI_NAHORE, $odsazeniDole=self::ODSAZENI_DOLE, $radkovani=self::RADKOVANI,
                                  $cislovani=self::CISLOVANI, $cisloStranky=self::CISLO_STRANKY)
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
      $this->cislovani = $cislovani;
      $this->cisloStranky = $cisloStranky;
    }

    public function Odstavec($text)
    {
        $this->text=iconv("UTF-8","windows-1250",$text);
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
