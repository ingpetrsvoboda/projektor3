 <?php
class Projektor_Stranka_TypAkce_Menu extends Projektor_Stranka_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_STypAkceItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Typ akce - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($typAkce)
    {
        $objektId = $this->uzel->parametry["id"];
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Detail", array("id" => $objektId, "zmraz" => 1))),
                new Projektor_Stranka_Element_Tlacitko("Upravit", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Detail", array("id" => $objektId))),
                new Projektor_Stranka_Element_Tlacitko("Smazat", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Detail", array("id" => $objektId, "smaz" => 1))),
                new Projektor_Stranka_Element_Tlacitko("Předpoklady", $this->uzel->potomekUri("Projektor_Stranka_Predpoklady_Detail", array("id_typ_akce" => $objektId))),
            );
        return $tlacitka;
    }
}
