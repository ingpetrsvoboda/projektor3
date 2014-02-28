 <?php
class Projektor_Controller_Page_Firma_Menu extends Projektor_Controller_Page_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_SFirmaItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Firma - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($firma)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Firma_Detail", array("id" => $firma->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Uprav", $this->vertex->childUri("Projektor_Controller_Page_Firma_Detail", array("id" => $firma->id)), "tlacitko"),
            );
        return $tlacitka;
    }
}
