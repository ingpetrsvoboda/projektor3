<?php
class Projektor_Stranka_Firma_Seznam extends Projektor_Stranka_Seznam
{
//	const NAZEV_FLAT_TABLE = "s_firma";
//        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "Firma";
//        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "Firmy";
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_SFirmaCollection";

    /*
        *  ~~~~~~~~MAIN~~~~~~~~~~
        */
    public function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Firmy");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Stranka_Element_Tlacitko("Nová firma", $this->uzel->potomekUri("Projektor_Stranka_Firma_Detail"))
        );
        $this->novaPromenna("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Stranka_Firma_Menu()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Detail položky seznamu firem");
    }
/* ------------------------------------------------------------------------------------ */


    protected function generujTlacitkaProSeznam($firma)
    {
        $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Firma_Menu", array("id" => $firma->id, "zmraz" => 1)), "tlacitko"),
            );
        return $tlacitka;
    }

    protected function generujTlacitkaProPolozku($firma)
    {
        $tlacitka = array
            (
            );
        return $tlacitka;    }

    protected function generujHlavickuTabulky($tridaData)
    {
            /* Hlavicka tabulky */
            $hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($tridaData, $this->uzel);
            $hlavickaTabulky->pridejSloupec("id", "ID");
            $hlavickaTabulky->pridejSloupec("ico", "IČO");
            $hlavickaTabulky->pridejSloupec("nazev_firmy", "Název firmy");
            $hlavickaTabulky->pridejSloupec("obec", "Obec");
            return $hlavickaTabulky;
    }

}