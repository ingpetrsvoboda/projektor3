 <?php
class Projektor_Controller_Page_Prezentace_Menu extends Projektor_Controller_Page_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_SPrezentaceItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Prezentace - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($prezentace)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Detail", array("id" => $prezentace->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Uprav", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Detail", array("id" => $prezentace->id))),
                new Projektor_Controller_Page_Element_Tlacitko("Smazat", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Detail", array("id" => $prezentace->id, "smaz" => 1))),
            );
        return $tlacitka;
    }
}
