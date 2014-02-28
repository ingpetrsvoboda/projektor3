<?php
class Projektor_Controller_Page_Prezentace_Seznam extends Projektor_Controller_Page_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_SPrezentaceCollection";

    /*
        *  ~~~~~~~~MAIN~~~~~~~~~~
        */
    public function potomekNeni()
    {
        $tridaCollection = new Projektor_Model_Auto_SPrezentaceCollection();
        $tridaCollection
        Projektor_Model_Seznam_SPrezentace::nactiNazvy(array("Ucastnik", "Zajemce"));

        parent::potomekNeni();
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Prezentace");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Controller_Page_Element_Tlacitko("Nová prezentace", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Detail"))
        );
        $this->setViewContextValue("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Controller_Page_Firma_Menu()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Detail položky seznamu prezentace");
    }
/* ------------------------------------------------------------------------------------ */


    protected function generujTlacitkaProSeznam($prezentace)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Menu", array("id" => $prezentace->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Controller_Page_Element_Tlacitko("Duplikuj", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Menu", array("id" => $prezentace->id, "duplikuj" => 1))),
              );
        return $tlacitka;
    }

    protected function generujTlacitkaProPolozku($prezentace)
    {
        $tlacitka = array
            (
                new Projektor_Controller_Page_Element_Tlacitko("Duplikuj", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Menu", array("id" => $prezentace->id, "duplikuj" => 1))),
            );
        return $tlacitka;    }

    protected function generujHlavickuTabulky($tridaData)
    {
        /* Hlavicka tabulky */
        $hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
        $hlavickaTabulky->pridejSloupec("id","ID", Projektor_Model_Seznam_SPrezentace::ID);
        $hlavickaTabulky->pridejSloupec("dbField°hlavni_objekt","Hlavní objekt");
        $hlavickaTabulky->pridejSloupec("dbField°objekt_vlastnost","Objekt vlastnost");
        $hlavickaTabulky->pridejSloupec("dbField°nazev_sloupce","Název sloupce");
        $hlavickaTabulky->pridejSloupec("dbField°titulek","Titulek");
        $hlavickaTabulky->pridejSloupec("dbField°zobrazovat","Zobrazovat");
        $hlavickaTabulky->pridejSloupec("dbField°valid","Validní");
        return $hlavickaTabulky;
    }

}