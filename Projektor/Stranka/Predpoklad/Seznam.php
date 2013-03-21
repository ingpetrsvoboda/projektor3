<?php
class Projektor_Stranka_Predpoklad_Seznam extends Projektor_Stranka_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_SAkcePredpokladCollection";

    public function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Předpoklady");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Stranka_Element_Tlacitko("Nový předpoklad", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Detail"))
        );
        $this->novaPromenna("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Stranka_Predpoklad_Menu()
    {
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Detail položky seznamu předpokladů");
    }
/* ------------------------------------------------------------------------------------ */
    protected function generujTlacitkaProSeznam($predpoklad)
    {
        $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Menu", array("id" => $predpoklad->id, "zmraz" => 1)), "tlacitko"),
            );
        return $tlacitka;
    }
    protected function generujTlacitkaProPolozku($predpoklad)
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
        $hlavickaTabulky->pridejSloupec("dbField°text", "Název");
        $hlavickaTabulky->pridejSloupec("dbField°full_text", "Popis");
        $hlavickaTabulky->pridejSloupec("dbField°id_s_typ_akce_FK", "Typ akce");
        $hlavickaTabulky->pridejSloupec("dbField°id_s_typ_akce_pred_FK", "Typ akce předcházející");
        $hlavickaTabulky->pridejSloupec("dbField°id_s_stav_ucastnik_akce_pred_FK", "Stav účastníka před akcí");

        return $hlavickaTabulky;
    }


        /*
         *  ~~~~~~~~PRO TYP AKCE~~~~~~~~
         */
        public function proTypAkce()
	{
            /* Vygenerovani filtrovaciho formulare */
            $this->autofiltr();
	}

	protected function proTypAkce°vzdy()
	{
            $this->novaPromenna("id", $this->nazev);
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Předpoklady pro typ akce");

            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Zpět", $this->uzel->zpetUri()),
                //TODO: tohle určitě nefunguje pro typ akce
                    new Projektor_Stranka_Element_Tlacitko("Nový předpoklad", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad")),
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function proTypAkce°potomekNeni()
	{
            $predpoklady = Projektor_Data_Seznam_SAkcePredpoklad::vypisPro
            (
                Projektor_Data_Seznam_STypAkce::najdiPodleId($this->parametry["id_typ_akce"]),
                $this->parametry["razeniPodle"],
                $this->parametry["razeni"]
            );
            $this->generujSeznamSTlacitky($predpoklady);
	}

}