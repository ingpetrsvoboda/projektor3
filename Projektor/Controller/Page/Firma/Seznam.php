<?php
class Projektor_Controller_Page_Firma_Seznam extends Projektor_Controller_Page_Seznam
{
//	const NAZEV_FLAT_TABLE = "s_firma";
//        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "Firma";
//        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "Firmy";
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_SFirmaCollection";

    /*
        *  ~~~~~~~~MAIN~~~~~~~~~~
        */
    public function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Firmy");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Controller_Page_Element_Tlacitko("Nová firma", $this->vertex->childUri("Projektor_Controller_Page_Firma_Detail"))
        );
        $this->setViewContextValue("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Controller_Page_Firma_Menu()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Detail položky seznamu firem");
    }
/* ------------------------------------------------------------------------------------ */


    protected function generujTlacitkaProSeznam($firma)
    {
        $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Firma_Menu", array("id" => $firma->id, "zmraz" => 1)), "tlacitko"),
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
            $hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
            $hlavickaTabulky->pridejSloupec("id", "ID");
            $hlavickaTabulky->pridejSloupec("ico", "IČO");
            $hlavickaTabulky->pridejSloupec("nazev_firmy", "Název firmy");
            $hlavickaTabulky->pridejSloupec("obec", "Obec");
            return $hlavickaTabulky;
    }

}