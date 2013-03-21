<?php
class Projektor_Pdf_Tabulka
{  
  var $nadpis;
  var $zahlavi;
  var $data;
  var $sirka;
  var $odsazeniZleva;
  var $vyskaPismaNadpisu;
  var $vyskaPismaZahlavi;
  var $vyskaPismaDat;
  
  const NADPIS = false;
  const ZAHLAVI = false;
  const DATA = false;
  const SIRKA = 80;
  const ODSAZENI_ZLEVA = 20;
  const VYSKA_PISMA_NADPISU = 14;
  const VYSKA_PISMA_ZAHLAVI = 12;
  const VYSKA_PISMA_DAT = 10;
  
  public function __construct($nadpis=self::NADPIS, $zahlavi=self::ZAHLAVI, $data=self::DATA, 
                              $sirka=self::SIRKA, $odsazeniZleva=self::ODSAZENI_ZLEVA, 
                              $vyskaPismaNadpisu=self::VYSKA_PISMA_NADPISU, $vyskaPismaZahlavi=self::VYSKA_PISMA_ZAHLAVI,
                              $vyskaPismaDat=self::VYSKA_PISMA_DAT)
  {
    $this->nazev=$nadpis;
    $this->zahlavi=$zahlavi;
    $this->data=$data;
    $this->sirka=$sirka;
    $this->odsazeniZleva=$odsazeniZleva;
    $this->vyskaPismaNadpisu=$vyskaPismaNadpisu;
    $this->vyskaPismaZahlavi=$vyskaPismaZahlavi;
    $this->vyskaPismaDat=$vyskaPismaDat;
  }
  
  function Nazev($nadpis)
  {
      $this->nadpis = iconv("UTF-8","windows-1250",$nadpis);
  }
  
  function Zahlavi($poleZahlavi)
  {
    foreach ($poleZahlavi as $bunka)
      $this->zahlavi[] = iconv("UTF-8","windows-1250",$bunka);
  }
  
  function Data($poleDat)
  {
    $i = 0;
    foreach ($poleDat as $radekDat)
    {
      foreach ($radekDat as $bunka)
      $this->data[$i][] = iconv("UTF-8","windows-1250",$bunka);
      $i++;
    }
  }
  
  function Sirka($sirka = self::SIRKA)
  {
    $this->sirka = $sirka;
  }
  
  function OdsazeniZleva($odsazeniZleva = self::ODSAZENI_ZLEVA)
  {
    $this->odsazeniZleva = $odsazeniZleva;
  }
    
  function VyskaPismaNadpisu($vyskaPismaNadpisu = self::VYSKA_PISMA_NADPISU)
  {
    $this->vyskaPismaNadpisu = $vyskaPismaNadpisu;
  }
    
  function VyskaPismaZahlavi($vyskaPismaZahlavi = self::VYSKA_PISMA_ZAHLAVI)
  {
    $this->vyskaPismaZahlavi = $vyskaPismaZahlavi;
  }
    
  function VyskaPismaDat($vyskaPismaDat = self::VYSKA_PISMA_DAT)
  {
    $this->vyskaPismaDat = $vyskaPismaDat;
  }       
}
