<?php
class Projektor2_PDF_Blok
{
    public $nadpis;
    public $text;
    public $barvaPisma;
    public $vyskaPismaNadpisu;
    public $vyskaPismaTextu;
    public $odsazeniZleva;
    public $predsazeni;
    public $odsazeniZprava;
    public $zarovnaniNadpisu;
    public $zarovnaniTextu;
    public $radkovani;
    public $mezeraPredNadpisem;
    public $mezeraPredOdstavcem;
    public $povoleniRozdeleniOdstavce;

    const NADPIS = false;
    const TEXT = false;
    const BARVA_PISMA = "0,0,0";
    const VYSKA_PISMA_NADPISU = 12;
    const VYSKA_PISMA_TEXTU = 10;
    const ODSAZENI_ZLEVA = 0;
    const PREDSAZENI = 0;
    const ODSAZENI_ZPRAVA = 0;
    const ZAROVNANI_NADPISU = "L";
    const ZAROVNANI_TEXTU = "L";
    const RADKOVANI = 1.5;
    const MEZERA_PRED_NADPISEM = 4;
    const MEZERA_PRED_ODSTAVCEM = 2;
    const POVOLENI_ROZDELENI_ODSTAVCE = TRUE;

    public function __construct($nadpis=self::NADPIS, $text=self::TEXT, $barvaPisma=self::BARVA_PISMA,
                                $vyskaPismaNadpisu=self::VYSKA_PISMA_NADPISU, $vyskaPismaTextu=self::VYSKA_PISMA_TEXTU,
                                $odsazeniZleva=self::ODSAZENI_ZLEVA, $predsazeni=self::PREDSAZENI, $odsazeniZprava=self::ODSAZENI_ZPRAVA,
                                $zarovnaniNadpisu=self::ZAROVNANI_NADPISU, $zarovnaniTextu=self::ZAROVNANI_TEXTU, $radkovani=self::RADKOVANI,
                                $mezeraPredNadpisem=self::MEZERA_PRED_NADPISEM, $mezeraPredOdstavcem=self::MEZERA_PRED_ODSTAVCEM,
                                $povoleniRozdeleniOdstavce=self::POVOLENI_ROZDELENI_ODSTAVCE)
    {
      $this->nadpis = $nadpis;
      $this->text = $text;
      $this->barvaPisma = $barvaPisma;
      $this->vyskaPismaNadpisu = $vyskaPismaNadpisu;
      $this->vyskaPismaTextu = $vyskaPismaTextu;
      $this->odsazeniZleva = $odsazeniZleva;
      $this->predsazeni = $predsazeni;
      $this->odsazeniZprava = $odsazeniZprava;
      $this->zarovnaniNadpisu = $zarovnaniNadpisu;
      $this->zarovnaniTextu = $zarovnaniTextu;
      $this->radkovani = $radkovani;
      $this->mezeraPredNadpisem = $mezeraPredNadpisem;
      $this->mezeraPredOdstavcem = $mezeraPredOdstavcem;
      $this->povoleniRozdeleniOdstavce = $povoleniRozdeleniOdstavce;
    }

    public function Nadpis($text) {
        $this->nadpis=$text;
    }

    public function Odstavec($text) {
        $this->text=$text;
    }

    public function PridejOdstavec($text) {
        if ($this->text) {
            $this->text .= chr(13).chr(10).$text;            
        } else {
            $this->text = $text;
        }
    }
    public function BarvaPisma($barvaPisma = self::BARVA_PISMA) {
        $this->barvaPisma = $barvaPisma;
    }

    public function VyskaPismaNadpisu($vyskaPismaNadpisu = VYSKA_PISMA_NADPISU) {
        $this->vyskaPismaNadpisu = $vyskaPismaNadpisu;
    }

    public function VyskaPismaTextu($vyskaPismaTextu = VYSKA_PISMA_TEXTU) {
        $this->vyskaPismaTextu = $vyskaPismaTextu;
    }

    public function OdsazeniZleva($OdsazeniZleva = self::ODSAZENI_ZLEVA) {
        $this->odsazeniZleva = $OdsazeniZleva;
    }

    public function Predsazeni($predsazeni = self::PREDSAZENI) {
        $this->predsazeni = $predsazeni;
    }

    public function OdsazeniZprava($OdsazeniZprava = self::ODSAZENI_ZPRAVA) {
        $this->odsazeniZprava = $OdsazeniZprava;
    }

    public function ZarovnaniNadpisu($ZarovnaniNadpisu = self::ZAROVNANI_NADPISU) {
        $this->zarovnaniNadpisu = $ZarovnaniNadpisu;
    }

    public function ZarovnaniTextu($ZarovnaniTextu = self::ZAROVNANI_TEXTU) {
        $this->zarovnaniTextu = $ZarovnaniTextu;
    }

    public function Radkovani($radkovani = self::RADKOVANI) {
        $this->radkovani = $radkovani;
    }
    public function MezeraPredNadpisem($mezeraPredNadpisem = self::MEZERA_PRED_NADPISEM) {
        $this->mezeraPredNadpisem = $mezeraPredNadpisem;
    }

    public function MezeraPredOdstavcem($mezeraPredOdstavcem = self::MEZERA_PRED_ODSTAVCEM) {
        $this->mezeraPredOdstavcem = $mezeraPredOdstavcem;
    }
    
    public function PovoleniRozdeleniOdstavce($povoleniRozdeleniOdstavce=self::POVOLENI_ROZDELENI_ODSTAVCE) {
        $this->povoleniRozdeleniOdstavce = $povoleniRozdeleniOdstavce;
    }
}
