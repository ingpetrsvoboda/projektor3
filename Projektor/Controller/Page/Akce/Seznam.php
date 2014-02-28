<?php
class Projektor_Controller_Page_Akce_Seznam extends Projektor_Controller_Page_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_AkceCollection";

	protected function potomekNeni()
	{
            parent::potomekNeni();
            /* Nadpis stranky */
            $this->setViewContextValue("nadpis", "Seznam akcí");
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Nová akce", $this->vertex->childUri("Projektor_Controller_Page_Akce_Detail")),
            );
            $this->setViewContextValue("tlacitka", $tlacitka);
	}

	protected function potomek°Projektor_Controller_Page_Akce_Detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Položka ze seznamu akcí");
	}

	protected function potomek°Projektor_Controller_Page_Ucastnici_Prihlasovaci(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
		$this->generujPolozku($uzelPotomek);
	}

	protected function potomek°Projektor_Controller_Page_Ucastnici_Prihlaseni(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
		$this->generujPolozku($uzelPotomek);
	}

	protected function potomek°Projektor_Controller_Page_Zajemci_Prihlasovaci(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{

		$this->generujPolozku($uzelPotomek);
	}

	protected function potomek°Projektor_Controller_Page_Zajemci_Prihlaseni(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Akce vybraná ze seznamu");
                $this->generujPolozku($uzelPotomek);
	}

//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------

        protected function generujTlacitkaProSeznam(Projektor_Model_Auto_AkceItem $akcej)
        {
            $tlacitka = array
                (
                            new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Akce_Menu", array("id" => $akcej->id, "zmraz" => 1))),
                );
            return $tlacitka;
        }

	protected function generujTlacitkaProPolozku($akcej)
        {
            $tlacitka = array
                (
                );
            return $tlacitka;
        }

//        private functi

        protected function generujHlavickuTabulky($tridaData)
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
//		$hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Model_Akce::ID);
		$hlavickaTabulky->pridejSloupec("dbField°nazev_hlavniho_objektu", "Název hlavního objektu");
		$hlavickaTabulky->pridejSloupec("dbField°nazev", "Název");
		$hlavickaTabulky->pridejSloupec("dbField°popis", "Popis");
		$hlavickaTabulky->pridejSloupec("dbField°datum_zacatek", "Datum začátku");
		$hlavickaTabulky->pridejSloupec("dbField°datum_konec", "Datum konce");
                return $hlavickaTabulky;
        }

}