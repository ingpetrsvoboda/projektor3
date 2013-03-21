 <?php
class Projektor_Stranka_Firma_Menu extends Projektor_Stranka_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_SFirmaItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Firma - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($firma)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Firma_Detail", array("id" => $firma->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Uprav", $this->uzel->potomekUri("Projektor_Stranka_Firma_Detail", array("id" => $firma->id)), "tlacitko"),
            );
        return $tlacitka;
    }
}
