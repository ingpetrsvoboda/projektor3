<?php
class Projektor_Stranka_Ucastnici extends Projektor_Stranka_Base implements Projektor_Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";
        const HLAVNI_OBJEKT = "Ucastnik";

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main()
	{
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("ucastnici", $hlavickaTabulky);
            $this->novaPromenna("filtrovaciFormular", $filtrovaciFormular->toHtml());
	}

	protected function main°vzdy()
	{
            $this->novaPromenna("id", $this->nazev);
            $this->novaPromenna("navigace", $this->uzel->drobeckovaNavigace());
	}

	protected function main°potomekNeni()
	{
		$ucastnici = Projektor_Data_Ucastnik::vypisVse($this->filtr->generujSQL(), $this->uzel->parametry["razeniPodle"], $this->uzel->parametry["razeni"]);
                $this->generujSeznamSTlacitky($ucastnici);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastníci");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Stranka_Element_Tlacitko("Zpět", $this->uzel->zpetUri()),
			new Projektor_Stranka_Element_Tlacitko("Nový účastník", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("objektVlastnost" => "smlouva")))
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }


	protected function main°potomek°Projektor_Stranka_Ucastnik°detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozkuSTlacitky($uzelPotomek);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastník");
	}

        protected function main°potomek°Projektor_Stranka_AkceM°akceObjektu(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
                $this->generujPolozkuSTlacitky($uzelPotomek);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastník");
        }
        /**
        *  ~~~~~~~~PRIHLASOVACI~~~~~~~~~~
        */
	public function prihlasovaci()
	{
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("prihlasovaciUcastnici", $hlavickaTabulky);
            $this->novaPromenna("filtrovaciFormular", $filtrovaciFormular->toHtml());
        }

	protected function prihlasovaci°vzdy()
	{
//                $this->novaPromenna("id", $this->nazev);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Výběr účastníka");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Stranka_Element_Tlacitko("Zpět", $this->uzel->zpetUri()),
			//new Tlacitko("Nový účastník", $this->uzelSem->generujUriDalsi("Projektor_Stranka_Ucastnik")),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }

	protected function prihlasovaci°potomekNeni()
	{
		$ucastnici = Projektor_Data_Ucastnik::vypisVse($this->filtr->generujSQL(), $this->uzel->parametry["razeniPodle"], $this->uzel->parametry["razeni"]);
                $this->generujSeznamSTlacitky($ucastnici);
        }

	protected function prihlasovaci°potomek°Projektor_Stranka_Ucastnik°prihlaseni(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozkuSTlacitky($uzelPotomek);
        }

	protected function prihlasovaci°potomek°Projektor_Stranka_Ucastnik°detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
//		$this->prihlasovaci°potomek°Projektor_Stranka_Ucastnik°prihlaseni();
                $this->generujPolozkuSTlacitky($uzelPotomek);
	}

        /*
        *  ~~~~~~~~PRIHLASENI~~~~~~~~~~
        */
	public function prihlaseni()
	{
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("prihlaseniZajemci", $hlavickaTabulky);
            $this->novaPromenna("filtrovaciFormular", $filtrovaciFormular->toHtml());
	}

	protected function prihlaseni°vzdy()
	{
//                $this->novaPromenna("id", $this->nazev);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastníci přihlášení na akci");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Stranka_Element_Tlacitko("Zpět", $this->uzel->zpetUri()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function prihlaseni°potomekNeni()
	{
            $ucastnici = Projektor_Data_Ucastnik::vypisPrihlaseneNaAkci($this->parametry["id_akce"]);
            $this->generujSeznamSTlacitky($ucastnici);
	}

	protected function prihlaseni°potomek°Projektor_Stranka_Ucastnik°detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozkuSTlacitky($uzelPotomek);
        }

        protected function prihlaseni°potomek°Projektor_Stranka_AkceM°akceObjektu(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
                $this->generujPolozkuSTlacitky($uzelPotomek);
        }

        private function generujPolozkuSTlacitky(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
        if($uzelPotomek->parametry["id"])
		{
                    $ucastnik = Projektor_Data_Ucastnik::najdiPodleId($uzelPotomek->parametry["id"]);
                    if ($ucastnik)
                    {
                        $hlavickaTabulky = $this->generujHlavickuTabulky();
                        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
                        $this->dejSeznamItemZHlavicky($ucastnik, $hlavickaTabulky);
//                        $ucastnik->odkaz = $this->uzelSem->generujUriDalsi("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id));
                        $ucastnik->tlacitka = array
                        (
                            new Projektor_Stranka_Element_Tlacitko("Smlouva", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav smlouvu", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva"))),
                            new Projektor_Stranka_Element_Tlacitko("Dotazník", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav dotazník", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník"))),
                            new Projektor_Stranka_Element_Tlacitko("Plán", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav plán", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán"))),
                            new Projektor_Stranka_Element_Tlacitko("Doporučení rk", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doporucenirk", "textDoNadpisuStranky" => "doporučení rekvalifikačního kurzu", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav doporučení rk", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doporucenirk", "textDoNadpisuStranky" => "doporučení rekvalifikačního kurzu"))),
                            new Projektor_Stranka_Element_Tlacitko("Ukončení", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "ukonceni", "textDoNadpisuStranky" => "ukončení", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav ukončení", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "ukonceni", "textDoNadpisuStranky" => "ukončení"))),
                            new Projektor_Stranka_Element_Tlacitko("Test PC", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "testpc", "textDoNadpisuStranky" => "test PC", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav test PC", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "testpc", "textDoNadpisuStranky" => "test PC"))),
                            new Projektor_Stranka_Element_Tlacitko("Zaměstnání", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav zaměstnání", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání"))),
                            new Projektor_Stranka_Element_Tlacitko("Doplňující", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doplnujici", "textDoNadpisuStranky" => "doplňující", "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Uprav doplňující", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doplnujici", "textDoNadpisuStranky" => "doplňující"))),
                            new Projektor_Stranka_Element_Tlacitko("Akce účastníka", $this->uzel->potomekUri("Projektor_Stranka_AkceM_AkceObjektu", array("id" => $ucastnik->id)))
                        );
                        $this->novaPromenna("polozka", $ucastnik);
                    }
                }
                $this->novaPromenna("skryjfiltr", TRUE);
        }

        private function generujSeznamSTlacitky($ucastnici)
        {
                if ($ucastnici) {
                    $hlavickaTabulky = $this->generujHlavickuTabulky();
                    $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);

                    foreach($ucastnici as $ucastnik)
                    {
                        $this->dejSeznamItemZHlavicky($ucastnik, $hlavickaTabulky);
                        $ucastnik->tlacitka = array
                        (
                            new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id, "zmraz" => 1)), "tlacitko"),
//                            new Tlacitko("Uprav", $this->uzelSem->generujUriDalsi("Projektor_Stranka_Ucastnik", array("id" => $ucastnik->id)), "tlacitko")
                            new Projektor_Stranka_Element_Tlacitko("Akce účastníka", $this->uzel->potomekUri("Projektor_Stranka_AkceM_AkceObjektu", array("id" => $ucastnik->id)))
                        );
                    }
                    $this->novaPromenna("seznam", $ucastnici);
                    $this->novaPromenna("zprava", "Celkem nalezeno:".  count($ucastnici));
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }

        private function generujHlavickuTabulky()
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($this->uzel);
                //sloupce pro zobrazení vlastností odpovidajících sloupcům v db tabulce zajemce
                $hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Data_Ucastnik::ID);
                $hlavickaTabulky->pridejSloupec("identifikator", "Identifikátor", Projektor_Data_Ucastnik::IDENTIFIKATOR);
                //sloupce pro zobrazení vlastností odpovidajících těm sloupcům v db tabulce zajemce, které obsahují cizí klíče
                $hlavickaTabulky->pridejSloupec("idSBehProjektuFK", "Turnus", Projektor_Data_Seznam_SBehProjektu::TEXT, "Projektor_Data_Seznam_SBehProjektu::vypisVse()", "Projektor_Data_Seznam_SBehProjektu::najdiPodleId(".Projektor_Stranka_Base::SLOT_PRO_ID.")", "text");  //v Projektor_Stranka_Base bylo const SLOT_PRO_ID = "%ID%";
                $hlavickaTabulky->pridejSloupec("idCKancelarFK", "Kancelář", Projektor_Data_Ucastnik::ID_C_KANCELAR_FK, "Projektor_Data_Ciselnik::vypisVse(Projektor_App_Config::DATABAZE_PROJEKTOR, 'kancelar', '', '', 'id_c_kancelar')", "Projektor_Data_Ciselnik::najdiPodleId(Projektor_App_Config::DATABAZE_PROJEKTOR, 'kancelar', ".Projektor_Stranka_Base::SLOT_PRO_ID.")", "text");
                //sloupec pro zobrazení vlastnosti, která nemá odpovídající sloupec v db tabulce zajemce (byla vytvořena v konstruktoru Projektor_Data_Zajemce)
                $hlavickaTabulky->pridejSloupec("celeJmeno", "Celé jméno");
                //sloupce pro zobrazení vlastností některého ObjektuVlastnosti (např. za_xxxx_flat_table) hlavního objektu (Zajemce)
                $hlavickaTabulky->pridejSloupec("dotaznik".self::SEPARATOR."vzdelani1", "Vzdělání1");
                $hlavickaTabulky->pridejSloupec("dotaznik".self::SEPARATOR."KZAM_cislo1", "KZAM 1");
                $hlavickaTabulky->pridejSloupec("dotaznik".self::SEPARATOR."KZAM_cislo2", "KZAM 2");
                $hlavickaTabulky->pridejSloupec("dotaznik".self::SEPARATOR."KZAM_cislo3", "KZAM 3");
                $hlavickaTabulky->pridejSloupec("dotaznik".self::SEPARATOR."KZAM_cislo4", "KZAM 4");
                $hlavickaTabulky->pridejSloupec("dotaznik".self::SEPARATOR."KZAM_cislo5", "KZAM 5");


                return $hlavickaTabulky;

        }
}