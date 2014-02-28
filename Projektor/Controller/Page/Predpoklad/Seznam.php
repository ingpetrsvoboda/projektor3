<?php
class Projektor_Controller_Page_Predpoklad_Seznam extends Projektor_Controller_Page_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_SAkcePredpokladCollection";

    public function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Předpoklady");
        /* Ovladaci tlacitka stranky */
        $tlacitka = array
        (
            new Projektor_Controller_Page_Element_Tlacitko("Nový předpoklad", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Detail"))
        );
        $this->setViewContextValue("tlacitka", $tlacitka);
    }

    public function potomek°Projektor_Controller_Page_Predpoklad_Menu()
    {
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Detail položky seznamu předpokladů");
    }
/* ------------------------------------------------------------------------------------ */
    protected function generujTlacitkaProSeznam($predpoklad)
    {
        $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Menu", array("id" => $predpoklad->id, "zmraz" => 1)), "tlacitko"),
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
        $hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
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
            $this->setViewContextValue("id", $this->controllerName);
            /* Nadpis stranky */
            $this->setViewContextValue("nadpis", "Předpoklady pro typ akce");

            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->vertex->backUri()),
                //TODO: tohle určitě nefunguje pro typ akce
                    new Projektor_Controller_Page_Element_Tlacitko("Nový předpoklad", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad")),
            );
            $this->setViewContextValue("tlacitka", $tlacitka);
	}

	protected function proTypAkce°potomekNeni()
	{
            $predpoklady = Projektor_Model_Seznam_SAkcePredpoklad::vypisPro
            (
                Projektor_Model_Seznam_STypAkce::najdiPodleId($this->parametry["id_typ_akce"]),
                $this->parametry["razeniPodle"],
                $this->parametry["razeni"]
            );
            $this->generujSeznamSTlacitky($predpoklady);
	}

}