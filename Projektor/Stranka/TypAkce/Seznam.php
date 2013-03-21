<?php
class Projektor_Stranka_TypAkce_Seznam extends Projektor_Stranka_Seznam
{
    const TYP_STRANKY = Projektor_Stranka_Generator::TYP_SEZNAM;
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_STypAkceCollection";

    public function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Typy akce");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Stranka_Element_Tlacitko("Nový typ akce", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Detail"))
        );
        $this->novaPromenna("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Stranka_TypAkce_Menu()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Detail položky seznamu typů akce");
    }
/* ------------------------------------------------------------------------------------ */
    protected function generujTlacitkaProSeznam($typAkce)
    {
        $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Menu", array("id" => $typAkce->id, "zmraz" => 1)), "tlacitko"),
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
            $hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($tridaData, $this->uzel);
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