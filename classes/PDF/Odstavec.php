<?php
class PDF_Odstavec
{
    var $nadpis;
    var $text;
    var $barvaPisma;
    var $vyskaPismaNadpisu;
    var $vyskaPismaTextu;
    var $odsazeniZleva;
    var $predsazeni; 
    var $odsazeniZprava;
    var $zarovnaniNadpisu;
    var $zarovnaniTextu;
    
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
    
    public function __construct($nadpis=self::NADPIS, $text=self::TEXT, $barvaPisma=self::BARVA_PISMA,
                                $vyskaPismaNadpisu=self::VYSKA_PISMA_NADPISU, $vyskaPismaTextu=self::VYSKA_PISMA_TEXTU,
                                $odsazeniZleva=self::ODSAZENI_ZLEVA, $predsazeni=self::PREDSAZENI, $odsazeniZprava=self::ODSAZENI_ZPRAVA, 
                                $zarovnaniNadpisu=self::ZAROVNANI_NADPISU, $zarovnaniTextu=self::ZAROVNANI_TEXTU)
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
    }
         
    function Nadpis($text)
    {
        $this->nadpis=$text;
    }
  
    function Text($text)
    {
        $this->text=$text;
    }
  
    function BarvaPisma($barvaPisma = self::BARVA_PISMA)
    {
        $this->barvaPisma = $barvaPisma;
    }
    
    function VyskaPismaNadpisu($vyskaPismaNadpisu = VYSKA_PISMA_NADPISU)
    {
        $this->vyskaPismaNadpisu = $vyskaPismaNadpisu;
    }
    
    function VyskaPismaTextu($vyskaPismaTextu = VYSKA_PISMA_TEXTU)
    {
        $this->vyskaPismaTextu = $vyskaPismaTextu;
    }
    
    function OdsazeniZleva($OdsazeniZleva = self::ODSAZENI_ZLEVA)
    {
        $this->odsazeniZleva = $OdsazeniZleva;
    }
    
    function Predsazeni($predsazeni = self::PREDSAZENI)
    {
        $this->predsazeni = $predsazeni;
    }
    
    function OdsazeniZprava($OdsazeniZprava = self::ODSAZENI_ZPRAVA)
    {
        $this->odsazeniZprava = $OdsazeniZprava;
    }
        
    function ZarovnaniNadpisu($ZarovnaniNadpisu = self::ZAROVNANI_NADPISU)
    {
        $this->zarovnaniNadpisu = $ZarovnaniNadpisu;
    }
        
    function ZarovnaniTextu($ZarovnaniTextu = self::ZAROVNANI_TEXTU)
    {
        $this->zarovnaniTextu = $ZarovnaniTextu;
    }
}    
