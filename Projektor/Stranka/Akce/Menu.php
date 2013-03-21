 <?php
class Projektor_Stranka_Akce_Menu extends Projektor_Stranka_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_AkceItem";

        protected function vzdy()
        {
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Akce - zobrazení a úprava vlastností");
            $objektId = $this->uzel->parametry["id"];
            $akcej = new Projektor_Data_Auto_AkceItem($objektId);
            $prihlasovaciStranka = "Projektor_Stranka_".$akcej->dbField°nazev_hlavniho_objektu."_Seznam_Prihlasovaci";
            $prihlaseniStranka = "Projektor_Stranka_".$akcej->dbField°nazev_hlavniho_objektu."_Seznam_Prihlaseni";

            $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Akce_Detail", array("id" => $objektId, "zmraz" => 1)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Uprav", $this->uzel->potomekUri("Projektor_Stranka_Akce_Detail", array("id" => $objektId)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Zrušit", $this->uzel->potomekUri("Projektor_Stranka_Akce_Detail", array("id" => $objektId, "smaz" => 1, "zmraz" => 1)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Přihlásit na akci", $this->uzel->potomekUri($prihlasovaciStranka, array("id" => $akcej->id))),
                new Projektor_Stranka_Element_Tlacitko("Seznam přihlášených", $this->uzel->potomekUri($prihlaseniStranka, array("id" => $akcej->id)))
            );
            $this->novaPromenna("tlacitka", $tlacitka);

            return parent::vzdy();
        }
}
