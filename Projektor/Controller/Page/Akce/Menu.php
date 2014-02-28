 <?php
class Projektor_Controller_Page_Akce_Menu extends Projektor_Controller_Page_Menu
{
    const SABLONA = "menu.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_AkceItem";

        protected function vzdy()
        {
            /* Nadpis stranky */
            $this->setViewContextValue("nadpis", "Akce - zobrazení a úprava vlastností");
            $objektId = $this->vertex->params["id"];
            $akcej = new Projektor_Model_Auto_AkceItem($objektId);
            $prihlasovaciStranka = "Projektor_Controller_Page_".$akcej->dbField°nazev_hlavniho_objektu."_Seznam_Prihlasovaci";
            $prihlaseniStranka = "Projektor_Controller_Page_".$akcej->dbField°nazev_hlavniho_objektu."_Seznam_Prihlaseni";

            $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Akce_Detail", array("id" => $objektId, "zmraz" => 1)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Uprav", $this->vertex->childUri("Projektor_Controller_Page_Akce_Detail", array("id" => $objektId)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Zrušit", $this->vertex->childUri("Projektor_Controller_Page_Akce_Detail", array("id" => $objektId, "smaz" => 1, "zmraz" => 1)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Přihlásit na akci", $this->vertex->childUri($prihlasovaciStranka, array("id" => $akcej->id))),
                new Projektor_Controller_Page_Element_Tlacitko("Seznam přihlášených", $this->vertex->childUri($prihlaseniStranka, array("id" => $akcej->id)))
            );
            $this->setViewContextValue("tlacitka", $tlacitka);

            return parent::vzdy();
        }
}
