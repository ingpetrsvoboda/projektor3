<?php
class Projektor_Controller_Page_TypAkce_Seznam extends Projektor_Controller_Page_Seznam
{
    const TYP_STRANKY = Projektor_Controller_Page_Generator::TYP_SEZNAM;
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_STypAkceCollection";

    public function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Typy akce");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Controller_Page_Element_Tlacitko("Nový typ akce", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Detail"))
        );
        $this->setViewContextValue("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Controller_Page_TypAkce_Menu()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Detail položky seznamu typů akce");
    }
/* ------------------------------------------------------------------------------------ */
    protected function generujTlacitkaProSeznam($typAkce)
    {
        $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Menu", array("id" => $typAkce->id, "zmraz" => 1)), "tlacitko"),
            );
        return $tlacitka;
    }
    protected function generujTlacitkaProPolozku($typAkce)
    {
        $tlacitka = array
            (
            );
        return $tlacitka;
    }

    protected function generujHlavickuTabulky($tridaData)
    {
            /* Hlavicka tabulky */
            $hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
            $hlavickaTabulky->pridejSloupec("id", "ID");
            $hlavickaTabulky->pridejSloupec("dbField°nazev", "Název");
            $hlavickaTabulky->pridejSloupec("dbField°zkratka", "Zkratka");
            $hlavickaTabulky->pridejSloupec("dbField°trvani_dni", "Trvání dní");
            $hlavickaTabulky->pridejSloupec("dbField°hodiny_za_den", "Hodiny za den");
            $hlavickaTabulky->pridejSloupec("dbField°min_pocet_uc", "Minimální počet účastníků");
            $hlavickaTabulky->pridejSloupec("dbField°max_pocet_uc", "Maximální počet účastníků");
            return $hlavickaTabulky;
    }
}