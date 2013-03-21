 <?php
class Projektor_Stranka_Predpoklad_Menu extends Projektor_Stranka_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_SAkcePredpokladItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Předpoklad akce - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($predpoklad)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Detail", array("id" => $predpoklad->id, "zmraz" => 1))),
                new Projektor_Stranka_Element_Tlacitko("Upravit", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Detail", array("id" => $predpoklad->id))),
                new Projektor_Stranka_Element_Tlacitko("Smazat", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Detail", array("id" => $predpoklad->id, "smaz" => 1))),
            );
        return $tlacitka;
    }
}
