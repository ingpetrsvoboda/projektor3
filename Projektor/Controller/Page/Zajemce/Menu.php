<?php
class Projektor_Controller_Page_Zajemce_Menu extends Projektor_Controller_Page_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_ZajemceItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Zájemce - zobrazení a úprava vlastností");
        $objektId = $this->vertex->params["id"];
        $tlacitka = array
        (
            new Projektor_Controller_Page_Element_Tlacitko("Smlouva", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva", "zmraz" => 1))),
            new Projektor_Controller_Page_Element_Tlacitko("Uprav smlouvu", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva"))),
            new Projektor_Controller_Page_Element_Tlacitko("Dotazník", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník", "zmraz" => 1))),
            new Projektor_Controller_Page_Element_Tlacitko("Uprav dotazník", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník"))),
            new Projektor_Controller_Page_Element_Tlacitko("Plán", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán", "zmraz" => 1))),
            new Projektor_Controller_Page_Element_Tlacitko("Uprav plán", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán"))),
            new Projektor_Controller_Page_Element_Tlacitko("Zaměstnání", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání", "zmraz" => 1))),
            new Projektor_Controller_Page_Element_Tlacitko("Uprav zaměstnání", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání"))),
            new Projektor_Controller_Page_Element_Tlacitko("Test", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "test", "textDoNadpisuStranky" => "test", "zmraz" => 1))),
            new Projektor_Controller_Page_Element_Tlacitko("Uprav test", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "test", "textDoNadpisuStranky" => "test"))),
            new Projektor_Controller_Page_Element_Tlacitko("Akce zájemce", $this->vertex->childUri("Projektor_Controller_Page_Akce_AkceObjektu", array("id" => $objektId))),
            new Projektor_Controller_Page_Element_Tlacitko("Smaž zájemce", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Detail", array("id" => $objektId))),
            new Projektor_Controller_Page_Element_Tlacitko("Souhlas", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_ExportPDF", array("id" => $objektId, "pdfDokument" => "AGPSouhlas")))
        );
        $this->setViewContextValue("tlacitka", $tlacitka);

        return parent::vzdy();
    }
}
