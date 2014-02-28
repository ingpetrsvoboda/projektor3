 <?php
class Projektor_Controller_Page_Predpoklad_Menu extends Projektor_Controller_Page_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_SAkcePredpokladItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Předpoklad akce - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($predpoklad)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Detail", array("id" => $predpoklad->id, "zmraz" => 1))),
                new Projektor_Controller_Page_Element_Tlacitko("Upravit", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Detail", array("id" => $predpoklad->id))),
                new Projektor_Controller_Page_Element_Tlacitko("Smazat", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Detail", array("id" => $predpoklad->id, "smaz" => 1))),
            );
        return $tlacitka;
    }
}
