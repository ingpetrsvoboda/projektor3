<?php
class Projektor_Stranka_Prezentace_Seznam extends Projektor_Stranka_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_SPrezentaceCollection";

    /*
        *  ~~~~~~~~MAIN~~~~~~~~~~
        */
    public function potomekNeni()
    {
        $tridaCollection = new Projektor_Data_Auto_SPrezentaceCollection();
        $tridaCollection
        Projektor_Data_Seznam_SPrezentace::nactiNazvy(array("Ucastnik", "Zajemce"));

        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Prezentace");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Stranka_Element_Tlacitko("Nová prezentace", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Detail"))
        );
        $this->novaPromenna("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Stranka_Firma_Menu()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Detail položky seznamu prezentace");
    }
/* ------------------------------------------------------------------------------------ */


    protected function generujTlacitkaProSeznam($prezentace)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Menu", array("id" => $prezentace->id, "zmraz" => 1)), "tlacitko"),
                new Projektor_Stranka_Element_Tlacitko("Duplikuj", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Menu", array("id" => $prezentace->id, "duplikuj" => 1))),
              );
        return $tlacitka;
    }

    protected function generujTlacitkaProPolozku($prezentace)
    {
        $tlacitka = array
            (
                new Projektor_Stranka_Element_Tlacitko("Duplikuj", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Menu", array("id" => $prezentace->id, "duplikuj" => 1))),
            );
        return $tlacitka;    }

    protected function generujHlavickuTabulky($tridaData)
    {
        /* Hlavicka tabulky */
        $hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($tridaData, $this->uzel);
        $hlavickaTabulky->pridejSloupec("id","ID", Projektor_Data_Seznam_SPrezentace::ID);
        $hlavickaTabulky->pridejSloupec("dbField°hlavni_objekt","Hlavní objekt");
        $hlavickaTabulky->pridejSloupec("dbField°objekt_vlastnost","Objekt vlastnost");
        $hlavickaTabulky->pridejSloupec("dbField°nazev_sloupce","Název sloupce");
        $hlavickaTabulky->pridejSloupec("dbField°titulek","Titulek");
        $hlavickaTabulky->pridejSloupec("dbField°zobrazovat","Zobrazovat");
        $hlavickaTabulky->pridejSloupec("dbField°valid","Validní");
        return $hlavickaTabulky;
    }

}