<?php
class Projektor_Stranka_Zajemce_Seznam_Prihlasovaci extends Projektor_Stranka_Zajemce_Seznam
{
    const TYP_STRANKY = Projektor_Stranka_Generator::TYP_SEZNAM;
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_ZajemceCollection";

    protected function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Výběr zájemce pro přihlášení");
    }

    protected function potomek°Projektor_Stranka_Zajemce_Menu(Projektor_Dispatcher_Uzel $uzelPotomek = null)
    {
        $this->generujPolozku($uzelPotomek);
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Zájemce  - zobrazení a úprava vlastností");
    }
    protected function potomek°Projektor_Stranka_Zajemce_Prihlaska(Projektor_Dispatcher_Uzel $uzelPotomek = null)
    {
        $this->novaPromenna("nadpis", "Přihlášení zájemce");
        $this->generujPolozku($uzelPotomek);
        $akce = Projektor_Data_Auto_AkceItem::najdiPodleId($this->uzel->parametry["id"]);

        try
        {
            //TODO: Nedodělek - pevně zadaný parametr 2!
                $akce->prihlas(Projektor_Data_Zajemce::najdiPodleId($uzelPotomek->parametry["id"]), Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Projektor_Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));
                $this->novaPromenna("hlaseni", "Prihlaseni bylo uspesne!");
        }
        catch(Exception $e)
        {
                $this->novaPromenna("hlaseni", $e->getMessage());
        }

        parent::vzdy();
    }

    protected function generujTlacitkaProSeznam($zajemce)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Menu", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Přihlaš na akci", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Prihlaska", array("id" => $zajemce->id)), "tlacitko"),
            );
        return $tlacitka;
    }

    protected function generujTlacitkaProPolozku($zajemce)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Menu", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
            );
        return $tlacitka;
    }

}