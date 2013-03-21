<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Projektor_Stranka_Generator implements Projektor_Stranka_GeneratorInterface
{
    public $uzel;
    public  $stranka;


    /**
     * Konstanty pro třídy Projektor_Stranka
     */
    const TYP_SEZNAM = "seznam";  // stránka generuje seznam
    const TYP_DETAIL = "detail";  // stránka generuje detail
    const TYP_MENU = "menu";  // stránka generuje menu
    const TYP_SPECIFIC = "specific";  // strínka bez univerzální funkčnosti

    public function __construct(Projektor_Dispatcher_Uzel $uzel) {
        $this->uzel = $uzel;
    }

    public function generuj()
    {
        $trida = $this->uzel->trida;
        $this->stranka = new $trida($this->uzel);
    }

    public function volejMetoduVychozi()
    {
        $this->stranka->vychozi();
        return $this->stranka;
    }

    public function volejMetoduPotomkovskou(Projektor_Stranka_Base $strankaPotomek = null)
      //$uzelPotomek je potřeba v potomkovské metodě stránek, které pro potomek není generují seznam
      // a v potomkovské metodě generují položku. Pro generování položky je potřeba znát parametry potomka
    {
        $potomkovskaMetoda = "potomek°".$strankaPotomek->uzel->trida;
        $this->stranka->strankaPotomek = $strankaPotomek;
        $this->stranka->$potomkovskaMetoda();
        return $this->stranka;
    }

    public function volejMetoduPotomekNeni()
    {
        $this->stranka->potomekNeni();
        return $this->stranka;
    }

    public function volejMetoduVzdy()
    {
        /* volame privatni metodu, ktera nam generuje promenne pro obsah, ktery zobrazujeme vzdy, bez ohledu na potomka */
        $this->stranka->vzdy();
        return $this->stranka;
    }

//    abstract protected function generujStranku();

}
?>
