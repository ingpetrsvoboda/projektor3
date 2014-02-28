<?php
/**
 * Description of Pravidlo
 *
 * @author Marek Petko
 */
class Projektor_Controller_Page_Element_Filtr_Pravidlo
{
    public $sloupec;
    public $porovnavani;
    public $hodnota;
    public $zlevaOtevrene;
    public $zpravaOtevrene;

    public function __construct($sloupec, $porovnavani, $hodnota, $zlevaOtevrene = false, $zpravaOtevrene = false)
    {
        $this->sloupec = $sloupec;
        $this->porovnavani = $porovnavani;
        $this->hodnota = $hodnota;
        $this->zlevaOtevrene = $zlevaOtevrene;
        $this->zpravaOtevrene = $zpravaOtevrene;
    }

    /**
     * Otevre nebo zavre vyhledavaci hodnotu.
     * @param boolean $otevrenost True pokud otevrene, false pokud uzavrene.
     */
    public function otevri($otevrenost = true)
    {
        $this->zlevaOtevrene = $otevrenost;
        $this->zpravaOtevrene = $otevrenost;
    }

    /**
     * Vygeneruje SQL kod pravidla.
     * @return
     */
    public function generujSQL()
    {
        return "(`{$this->sloupec}` {$this->porovnavani} \"" .($this->zlevaOtevrene ? "%":""). $this->hodnota .($this->zpravaOtevrene ? "%":""). "\")";
    }
}
?>
