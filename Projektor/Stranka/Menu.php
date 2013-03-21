<?php
/**
 * Description of Seznam
 *
 * @author pes2704
 */
abstract class Projektor_Stranka_Menu extends Projektor_Stranka_Base
{
    /**
     * Metoda smaže všechny potomky mimo posledního -> zavře všechny předtím vytvořené potomky (stránky-potomky)
     */
    protected function vychozi()
    {
        // smaže všechny potomky mimo posledního -> zavře všechny předtím vytvořené potomky
        // bez toho by uživatel musel zavírat ručně tlačítkem Zpět a ve strínve typu je to nepraktické
        if ($this->uzel->uzlyPotomci)
        {
            $posledniUzelPotomek = end($this->uzel->uzlyPotomci);
            unset($this->uzel->uzlyPotomci);
            $this->uzel->uzlyPotomci[] = $posledniUzelPotomek;
        }
        $item = $this->dejItem($this->uzel->parametry["id"]);
        $this->generujMenu($item);
    }

    protected function generujMenu(Projektor_Data_Item $item)
    {
        $tlacitka = $this->generujTlacitkaMenu($item);  //TODO: dodělat funkci generujTlacitkaMenu (podle objektových vlastností hlavního objektu)
        $this->novaPromenna("tlacitka", $tlacitka);
    }

//    protected function generujTlacitkaMenu(Projektor_Data_Item $item) {}

    /**
     * metoda vrací data Item pro stránku, může být přetížená metodou dejItem ve stránce, která je potomkem této třídy a taková metoda vrací
     * item například s vhodným fitrem where (pracuje jen s některými Item) nebo item s vlastnostmi, které neodpovídají sloupcům db tabulky
     */
    public function dejItem($id)
    {
            $tridaItem = static::TRIDA_DATA_ITEM;
            return new $tridaItem($id);
    }
}
?>
