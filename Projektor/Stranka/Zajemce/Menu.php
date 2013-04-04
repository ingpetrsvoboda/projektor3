 <?php
class Projektor_Stranka_Zajemce_Menu extends Projektor_Stranka_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_ZajemceItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Zájemce - zobrazení a úprava vlastností");
        $objektId = $this->uzel->parametry["id"];
        $tlacitka = array
        (
            new Projektor_Stranka_Element_Tlacitko("Smlouva", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva", "zmraz" => 1))),
            new Projektor_Stranka_Element_Tlacitko("Uprav smlouvu", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva"))),
            new Projektor_Stranka_Element_Tlacitko("Dotazník", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník", "zmraz" => 1))),
            new Projektor_Stranka_Element_Tlacitko("Uprav dotazník", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník"))),
            new Projektor_Stranka_Element_Tlacitko("Plán", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán", "zmraz" => 1))),
            new Projektor_Stranka_Element_Tlacitko("Uprav plán", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán"))),
            new Projektor_Stranka_Element_Tlacitko("Zaměstnání", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání", "zmraz" => 1))),
            new Projektor_Stranka_Element_Tlacitko("Uprav zaměstnání", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání"))),
            new Projektor_Stranka_Element_Tlacitko("Test", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "test", "textDoNadpisuStranky" => "test", "zmraz" => 1))),
            new Projektor_Stranka_Element_Tlacitko("Uprav test", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId, "objektVlastnost" => "test", "textDoNadpisuStranky" => "test"))),
            new Projektor_Stranka_Element_Tlacitko("Akce zájemce", $this->uzel->potomekUri("Projektor_Stranka_Akce_AkceObjektu", array("id" => $objektId))),
            new Projektor_Stranka_Element_Tlacitko("Smaž zájemce", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("id" => $objektId))),
            new Projektor_Stranka_Element_Tlacitko("Souhlas", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_ExportPDF", array("id" => $objektId, "pdfDokument" => "AGPSouhlas")))
        );
        $this->novaPromenna("tlacitka", $tlacitka);

        return parent::vzdy();
    }
}
