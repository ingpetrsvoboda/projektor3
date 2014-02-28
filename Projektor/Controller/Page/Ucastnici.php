<?php
class Projektor_Controller_Page_Ucastnici extends Projektor_Controller_Page_AbstractPage implements Projektor_Controller_Page_Interface
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
            $this->setViewContextValue("filtrovaciFormular", $filtrovaciFormular->toHtml());
	}

	protected function main°vzdy()
	{
            $this->setViewContextValue("id", $this->controllerName);
            $this->setViewContextValue("navigace", $this->vertex->breadcrumbNavigation());
	}

	protected function main°potomekNeni()
	{
		$ucastnici = Projektor_Model_Ucastnik::vypisVse($this->filtr->generujSQL(), $this->vertex->params["razeniPodle"], $this->vertex->params["razeni"]);
                $this->generujSeznamSTlacitky($ucastnici);
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Účastníci");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->vertex->backUri()),
			new Projektor_Controller_Page_Element_Tlacitko("Nový účastník", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("objektVlastnost" => "smlouva")))
		);
                $this->setViewContextValue("tlacitka", $tlacitka);
        }


	protected function main°potomek°Projektor_Controller_Page_Ucastnik°detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozkuSTlacitky($uzelPotomek);
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Účastník");
	}

        protected function main°potomek°Projektor_Controller_Page_AkceM°akceObjektu(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
                $this->generujPolozkuSTlacitky($uzelPotomek);
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Účastník");
        }
        /**
        *  ~~~~~~~~PRIHLASOVACI~~~~~~~~~~
        */
	public function prihlasovaci()
	{
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("prihlasovaciUcastnici", $hlavickaTabulky);
            $this->setViewContextValue("filtrovaciFormular", $filtrovaciFormular->toHtml());
        }

	protected function prihlasovaci°vzdy()
	{
//                $this->novaPromenna("id", $this->nazev);
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Výběr účastníka");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->vertex->backUri()),
			//new Tlacitko("Nový účastník", $this->uzelSem->generujUriDalsi("Projektor_Controller_Page_Ucastnik")),
		);
                $this->setViewContextValue("tlacitka", $tlacitka);
        }

	protected function prihlasovaci°potomekNeni()
	{
		$ucastnici = Projektor_Model_Ucastnik::vypisVse($this->filtr->generujSQL(), $this->vertex->params["razeniPodle"], $this->vertex->params["razeni"]);
                $this->generujSeznamSTlacitky($ucastnici);
        }

	protected function prihlasovaci°potomek°Projektor_Controller_Page_Ucastnik°prihlaseni(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozkuSTlacitky($uzelPotomek);
        }

	protected function prihlasovaci°potomek°Projektor_Controller_Page_Ucastnik°detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
//		$this->prihlasovaci°potomek°Projektor_Controller_Page_Ucastnik°prihlaseni();
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
            $this->setViewContextValue("filtrovaciFormular", $filtrovaciFormular->toHtml());
	}

	protected function prihlaseni°vzdy()
	{
//                $this->novaPromenna("id", $this->nazev);
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Účastníci přihlášení na akci");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->vertex->backUri()),
		);
                $this->setViewContextValue("tlacitka", $tlacitka);
	}

	protected function prihlaseni°potomekNeni()
	{
            $ucastnici = Projektor_Model_Ucastnik::vypisPrihlaseneNaAkci($this->parametry["id_akce"]);
            $this->generujSeznamSTlacitky($ucastnici);
	}

	protected function prihlaseni°potomek°Projektor_Controller_Page_Ucastnik°detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozkuSTlacitky($uzelPotomek);
        }

        protected function prihlaseni°potomek°Projektor_Controller_Page_AkceM°akceObjektu(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
                $this->generujPolozkuSTlacitky($uzelPotomek);
        }

        private function generujPolozkuSTlacitky(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
        if($uzelPotomek->params["id"])
		{
                    $ucastnik = Projektor_Model_Ucastnik::najdiPodleId($uzelPotomek->params["id"]);
                    if ($ucastnik)
                    {
                        $hlavickaTabulky = $this->generujHlavickuTabulky();
                        $this->setViewContextValue("hlavickaTabulky", $hlavickaTabulky);
                        $this->dejSeznamItemZHlavicky($ucastnik, $hlavickaTabulky);
//                        $ucastnik->odkaz = $this->uzelSem->generujUriDalsi("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id));
                        $ucastnik->tlacitka = array
                        (
                            new Projektor_Controller_Page_Element_Tlacitko("Smlouva", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav smlouvu", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Dotazník", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav dotazník", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Plán", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav plán", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Doporučení rk", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doporucenirk", "textDoNadpisuStranky" => "doporučení rekvalifikačního kurzu", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav doporučení rk", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doporucenirk", "textDoNadpisuStranky" => "doporučení rekvalifikačního kurzu"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Ukončení", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "ukonceni", "textDoNadpisuStranky" => "ukončení", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav ukončení", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "ukonceni", "textDoNadpisuStranky" => "ukončení"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Test PC", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "testpc", "textDoNadpisuStranky" => "test PC", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav test PC", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "testpc", "textDoNadpisuStranky" => "test PC"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Zaměstnání", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav zaměstnání", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Doplňující", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doplnujici", "textDoNadpisuStranky" => "doplňující", "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Uprav doplňující", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "objektVlastnost" => "doplnujici", "textDoNadpisuStranky" => "doplňující"))),
                            new Projektor_Controller_Page_Element_Tlacitko("Akce účastníka", $this->vertex->childUri("Projektor_Controller_Page_AkceM_AkceObjektu", array("id" => $ucastnik->id)))
                        );
                        $this->setViewContextValue("polozka", $ucastnik);
                    }
                }
                $this->setViewContextValue("skryjfiltr", TRUE);
        }

        private function generujSeznamSTlacitky($ucastnici)
        {
                if ($ucastnici) {
                    $hlavickaTabulky = $this->generujHlavickuTabulky();
                    $this->setViewContextValue("hlavickaTabulky", $hlavickaTabulky);

                    foreach($ucastnici as $ucastnik)
                    {
                        $this->dejSeznamItemZHlavicky($ucastnik, $hlavickaTabulky);
                        $ucastnik->tlacitka = array
                        (
                            new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id, "zmraz" => 1)), "tlacitko"),
//                            new Tlacitko("Uprav", $this->uzelSem->generujUriDalsi("Projektor_Controller_Page_Ucastnik", array("id" => $ucastnik->id)), "tlacitko")
                            new Projektor_Controller_Page_Element_Tlacitko("Akce účastníka", $this->vertex->childUri("Projektor_Controller_Page_AkceM_AkceObjektu", array("id" => $ucastnik->id)))
                        );
                    }
                    $this->setViewContextValue("seznam", $ucastnici);
                    $this->setViewContextValue("zprava", "Celkem nalezeno:".  count($ucastnici));
                } else {
                $this->setViewContextValue("zprava", "Nic nenalezeno!");
                }
        }

        private function generujHlavickuTabulky($tridaData)
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
                //sloupce pro zobrazení vlastností odpovidajících sloupcům v db tabulce zajemce
                $hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Model_Ucastnik::ID);
                $hlavickaTabulky->pridejSloupec("identifikator", "Identifikátor", Projektor_Model_Ucastnik::IDENTIFIKATOR);
                //sloupce pro zobrazení vlastností odpovidajících těm sloupcům v db tabulce zajemce, které obsahují cizí klíče
                $hlavickaTabulky->pridejSloupec("idSBehProjektuFK", "Turnus", Projektor_Model_Seznam_SBehProjektu::TEXT, "Projektor_Model_Seznam_SBehProjektu::vypisVse()", "Projektor_Model_Seznam_SBehProjektu::najdiPodleId(".Projektor_Controller_Page_AbstractPage::SLOT_PRO_ID.")", "text");  //v Projektor_Controller_Page_Base bylo const SLOT_PRO_ID = "%ID%";
                $hlavickaTabulky->pridejSloupec("idCKancelarFK", "Kancelář", Projektor_Model_Ucastnik::ID_C_KANCELAR_FK, "Projektor_Model_Ciselnik::vypisVse(Framework_Config::DATABAZE_PROJEKTOR, 'kancelar', '', '', 'id_c_kancelar')", "Projektor_Model_Ciselnik::najdiPodleId(Framework_Config::DATABAZE_PROJEKTOR, 'kancelar', ".Projektor_Controller_Page_AbstractPage::SLOT_PRO_ID.")", "text");
                //sloupec pro zobrazení vlastnosti, která nemá odpovídající sloupec v db tabulce zajemce (byla vytvořena v konstruktoru Projektor_Model_Zajemce)
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