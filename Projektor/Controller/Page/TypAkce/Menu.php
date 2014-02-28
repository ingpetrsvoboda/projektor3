 <?php
class Projektor_Controller_Page_TypAkce_Menu extends Projektor_Controller_Page_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_STypAkceItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Typ akce - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($typAkce)
    {
        $objektId = $this->vertex->params["id"];
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Detail", array("id" => $objektId, "zmraz" => 1))),
                new Projektor_Controller_Page_Element_Tlacitko("Upravit", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Detail", array("id" => $objektId))),
                new Projektor_Controller_Page_Element_Tlacitko("Smazat", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Detail", array("id" => $objektId, "smaz" => 1))),
                new Projektor_Controller_Page_Element_Tlacitko("Předpoklady", $this->vertex->childUri("Projektor_Controller_Page_Predpoklady_Detail", array("id_typ_akce" => $objektId))),
            );
        return $tlacitka;
    }
}
