<?php
class Projektor_Stranka_Akce_Seznam extends Projektor_Stranka_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_AkceCollection";

	protected function potomekNeni()
	{
            parent::potomekNeni();
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Seznam akcí");
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Nová akce", $this->uzel->potomekUri("Projektor_Stranka_Akce_Detail")),
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function potomek°Projektor_Stranka_Akce_Detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Položka ze seznamu akcí");
	}

	protected function potomek°Projektor_Stranka_Ucastnici_Prihlasovaci(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
		$this->generujPolozku($uzelPotomek);
	}

	protected function potomek°Projektor_Stranka_Ucastnici_Prihlaseni(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
		$this->generujPolozku($uzelPotomek);
	}

	protected function potomek°Projektor_Stranka_Zajemci_Prihlasovaci(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{

		$this->generujPolozku($uzelPotomek);
	}

	protected function potomek°Projektor_Stranka_Zajemci_Prihlaseni(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Akce vybraná ze seznamu");
                $this->generujPolozku($uzelPotomek);
	}

//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------

        protected function generujTlacitkaProSeznam(Projektor_Data_Auto_AkceItem $akcej)
        {
            $tlacitka = array
                (
                            new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Akce_Menu", array("id" => $akcej->id, "zmraz" => 1))),
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
		$hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($tridaData, $this->uzel);
//		$hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Data_Akce::ID);
		$hlavickaTabulky->pridejSloupec("dbField°nazev_hlavniho_objektu", "Název hlavního objektu");
		$hlavickaTabulky->pridejSloupec("dbField°nazev", "Název");
		$hlavickaTabulky->pridejSloupec("dbField°popis", "Popis");
		$hlavickaTabulky->pridejSloupec("dbField°datum_zacatek", "Datum začátku");
		$hlavickaTabulky->pridejSloupec("dbField°datum_konec", "Datum konce");
                return $hlavickaTabulky;
        }

}