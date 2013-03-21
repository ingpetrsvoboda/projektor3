<?php
class Projektor_Stranka_Zajemce_Seznam extends Projektor_Stranka_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_ZajemceCollection";

    protected function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Zájemci");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Stranka_Element_Tlacitko("Nový zájemce", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Detail", array("objektVlastnost" => "smlouva")))
        );
        $this->novaPromenna("tlacitka", $tlacitka);
    }

    protected function potomek°Projektor_Stranka_Zajemce_Menu()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Zájemce vybraný ze seznamu");
    }

    protected function potomek°Projektor_Stranka_AkceM_AkceObjektu()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Akce zájemce");
    }

    protected function potomek°Projektor_Stranka_AkceM_Prihlasovaci()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Přihlášení zájemce na akci");
    }

    public function potomek°Projektor_Stranka_Zajemce_Smaz()
    {
        $this->potomekNeni();  // po smazání vytvoří seznam zájemců
    }

/* ------------------------------------------------------------------------------------ */


    protected function generujTlacitkaProSeznam($zajemce)
    {
        $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Menu", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
            );
        return $tlacitka;
    }

    protected function generujTlacitkaProPolozku($zajemce)
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
        //sloupce pro zobrazení vlastností odpovidajících sloupcům v db tabulce zajemce
//        $hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Data_Zajemce::ID);
        $hlavickaTabulky->pridejSloupec("dbField°identifikator", "Identifikátor");
        $hlavickaTabulky->pridejSloupec("id", "id");
        //sloupce pro zobrazení vlastností odpovidajících těm sloupcům v db tabulce zajemce, které obsahují cizí klíče
//TODO: příkazy generující pole referencovaných objektů a referencovaný objekt přidej do Helperu, do vlastností objektu dej jen název helperu a parametry, Helper použij až v pozijHlavicku
        $hlavickaTabulky->pridejSloupec("dbField°id_s_beh_projektu_FK", "Turnus");  //, "dbField°text"
        $hlavickaTabulky->pridejSloupec("dbField°id_c_kancelar_FK", "Kancelář");
        $hlavickaTabulky->pridejSloupec("smlouva->dbField°jmeno", "Jméno");
        $hlavickaTabulky->pridejSloupec("smlouva->dbField°prijmeni", "Příjmení");
        $hlavickaTabulky->pridejSloupec("test->dbField°id_s_isco_FK", "ISCO");

        return $hlavickaTabulky;
    }
}