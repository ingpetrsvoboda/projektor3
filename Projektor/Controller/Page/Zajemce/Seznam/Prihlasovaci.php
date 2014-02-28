<?php
class Projektor_Controller_Page_Zajemce_Seznam_Prihlasovaci extends Projektor_Controller_Page_Zajemce_Seznam
{
    const TYP_STRANKY = Projektor_Controller_Page_Generator::TYP_SEZNAM;
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_ZajemceCollection";

    protected function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Výběr zájemce pro přihlášení");
    }

    protected function potomek°Projektor_Controller_Page_Zajemce_Menu(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
    {
        $this->generujPolozku($uzelPotomek);
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Zájemce  - zobrazení a úprava vlastností");
    }
    protected function potomek°Projektor_Controller_Page_Zajemce_Prihlaska(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
    {
        $this->setViewContextValue("nadpis", "Přihlášení zájemce");
        $this->generujPolozku($uzelPotomek);
        $akce = Projektor_Model_Auto_AkceItem::najdiPodleId($this->vertex->params["id"]);

        try
        {
            //TODO: Nedodělek - pevně zadaný parametr 2!
                $akce->prihlas(Projektor_Model_Zajemce::najdiPodleId($uzelPotomek->params["id"]), Projektor_Model_Seznam_SStavUcastnikAkce::najdiPodleId(2), Projektor_Model_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));
                $this->setViewContextValue("hlaseni", "Prihlaseni bylo uspesne!");
        }
        catch(Exception $e)
        {
                $this->setViewContextValue("hlaseni", $e->getMessage());
        }

        parent::vzdy();
    }

    protected function generujTlacitkaProSeznam($zajemce)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Menu", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Přihlaš na akci", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Prihlaska", array("id" => $zajemce->id)), "tlacitko"),
            );
        return $tlacitka;
    }

    protected function generujTlacitkaProPolozku($zajemce)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Menu", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
            );
        return $tlacitka;
    }

}