 <?php
class Projektor_Stranka_Prezentace_Menu extends Projektor_Stranka_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_SPrezentaceItem";

    protected function vzdy()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Prezentace - zobrazení a úprava vlastností");
        return parent::vzdy();
    }

    protected function generujTlacitkaMenu($prezentace)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Detail", array("id" => $prezentace->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Uprav", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Detail", array("id" => $prezentace->id))),
                new Projektor_Stranka_Element_Tlacitko("Smazat", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Detail", array("id" => $prezentace->id, "smaz" => 1))),
            );
        return $tlacitka;
    }
}
