    <?php
class Projektor2_PDF_Tabulka {
    public $nadpis;
    public $zahlavi;
    public $data;
    public $sirka;
    public $odsazeniZleva;
    public $vyskaPismaNadpisu;
    public $vyskaPismaZahlavi;
    public $vyskaPismaDat;

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
                                $vyskaPismaDat=self::VYSKA_PISMA_DAT) {
        $this->nazev=$nadpis;
        $this->zahlavi=$zahlavi;
        $this->data=$data;
        $this->sirka=$sirka;
        $this->odsazeniZleva=$odsazeniZleva;
        $this->vyskaPismaNadpisu=$vyskaPismaNadpisu;
        $this->vyskaPismaZahlavi=$vyskaPismaZahlavi;
        $this->vyskaPismaDat=$vyskaPismaDat;
    }

    public function Nazev($nadpis)
    {
        $this->nadpis = iconv("UTF-8","windows-1250",$nadpis);
    }

    public function Zahlavi($poleZahlavi)
    {
        foreach ($poleZahlavi as $bunka)
        $this->zahlavi[] = iconv("UTF-8","windows-1250",$bunka);
    }

    public function Data($poleDat)
    {
        $i = 0;
        foreach ($poleDat as $radekDat) {
            foreach ($radekDat as $bunka)
                $this->data[$i][] = iconv("UTF-8","windows-1250",$bunka);
                $i++;
            }
    }

    public function Sirka($sirka = self::SIRKA) {
        $this->sirka = $sirka;
    }

    public function OdsazeniZleva($odsazeniZleva = self::ODSAZENI_ZLEVA) {
        $this->odsazeniZleva = $odsazeniZleva;
    }

    public function VyskaPismaNadpisu($vyskaPismaNadpisu = self::VYSKA_PISMA_NADPISU) {
        $this->vyskaPismaNadpisu = $vyskaPismaNadpisu;
    }

    public function VyskaPismaZahlavi($vyskaPismaZahlavi = self::VYSKA_PISMA_ZAHLAVI) {
        $this->vyskaPismaZahlavi = $vyskaPismaZahlavi;
    }

    public function VyskaPismaDat($vyskaPismaDat = self::VYSKA_PISMA_DAT) {
        $this->vyskaPismaDat = $vyskaPismaDat;
    }
}
