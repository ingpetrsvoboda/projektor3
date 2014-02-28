<?php
/**
 * Description of Seznam
 *
 * @author pes2704
 */
abstract class Projektor_Controller_Page_Menu extends Projektor_Controller_Page_AbstractPage
{
    /**
     * Metoda smaže všechny potomky mimo posledního -> zavře všechny předtím vytvořené potomky (stránky-potomky)
     */
    protected function vychozi()
    {
        // smaže všechny potomky mimo posledního -> zavře všechny předtím vytvořené potomky
        // bez toho by uživatel musel zavírat ručně tlačítkem Zpět a ve strínve typu je to nepraktické
        if ($this->vertex->ChildVertexDispatchers)
        {
            $posledniUzelPotomek = end($this->vertex->ChildVertexDispatchers);
            unset($this->vertex->ChildVertexDispatchers);
            $this->vertex->ChildVertexDispatchers[] = $posledniUzelPotomek;
        }
        $item = $this->dejItem($this->vertex->params["id"]);
        $this->generujMenu($item);
    }
    
    protected function potomekNeni() {}
    
    protected function generujMenu(Projektor_Model_Item $item)
    {
        $tlacitka = $this->generujTlacitkaMenu($item);  //TODO: dodělat funkci generujTlacitkaMenu (podle objektových vlastností hlavního objektu)
        $this->setViewContextValue("tlacitka", $tlacitka);
    }

//    protected function generujTlacitkaMenu(Projektor_Model_Item $item) {}

    /**
     * metoda vrací data Item pro stránku, může být přetížená metodou dejItem ve stránce, která je potomkem této třídy a taková metoda vrací
     * item například s vhodným fitrem where (pracuje jen s některými Item) nebo item s vlastnostmi, které neodpovídají sloupcům db tabulky
     */
    public function dejItem($id)
    {
            $tridaItem = static::TRIDA_Model_ITEM;
            return new $tridaItem($id);
    }
}
?>
