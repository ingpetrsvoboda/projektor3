<?php
class Stranka_Ucastnici extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";
        const HLAVNI_OBJEKT = "Ucastnik";
	
        public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main($parametry = null)
	{
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("ucastnici", $hlavickaTabulky); 
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                
	}

	protected function main°potomekNeni()
	{
		$ucastnici = Data_Ucastnik::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($ucastnici);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastníci");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nový účastník", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("objektVlastnost" => "smlouva")))
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }

        
	protected function main°potomek°Stranka_Ucastnik°detail()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastník");
	}

        protected function main°potomek°Stranka_AkceM°akceUcastnika()
        {
                $this->generujPolozkuSTlacitky();            
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastník");
        }
        /**
        *  ~~~~~~~~PRIHLASOVACI~~~~~~~~~~
        */
	public function prihlasovaci($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("prihlasovaciZajemci", $hlavickaTabulky); 
            return $this->vytvorStranku("prihlasovaci", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());
        }

	protected function prihlasovaci°vzdy()
	{ 
                $this->novaPromenna("id", $this->nazev);                            
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Výběr účastníka");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			//new Tlacitko("Nový účastník", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail")),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }
	
	protected function prihlasovaci°potomekNeni()
	{
		$ucastnici = Data_Ucastnik::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($ucastnici);
        }
	
	protected function prihlasovaci°potomek°Stranka_Ucastnik°prihlaseni()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
        }
	
	protected function prihlasovaci°potomek°Stranka_Ucastnik°detail()
	{
//		$this->prihlasovaci°potomek°Stranka_Ucastnik°prihlaseni();
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
	}

        /*
        *  ~~~~~~~~PRIHLASENI~~~~~~~~~~
        */
	public function prihlaseni($parametry = null)
	{
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("prihlaseniZajemci", $hlavickaTabulky);             
		return $this->vytvorStranku("prihlaseni", self::SABLONA_MAIN, $parametry);
	}

	protected function prihlaseni°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Účastníci přihlášení na akci");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}
	
	protected function prihlaseni°potomekNeni()
	{
            $ucastnici = Data_Ucastnik::vypisPrihlaseneNaAkci($this->parametry["id_akce"]);
            $this->generujSeznamSTlacitky($ucastnici);
	}
	
	protected function prihlaseni°potomek°Stranka_Ucastnik°detail()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
        }	

        protected function prihlaseni°potomek°Stranka_AkceM°akceUcastnika()
        {
                $this->generujPolozkuSTlacitky();            
                $this->novaPromenna("tlacitka", $tlacitka);
        }

        private function generujPolozkuSTlacitky()
        {
        if($this->dalsi->parametry["id"])
		{
                    $ucastnik = Data_Ucastnik::najdiPodleId($this->dalsi->parametry["id"]);
                    if ($ucastnik)
                    {
                        $hlavickaTabulky = $this->generujHlavickuTabulky();
                        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky); 
                        
//                        $ucastnik->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id));
                        $ucastnik->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Smlouva", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav smlouvu", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva"))),
                            new Stranka_Element_Tlacitko("Dotazník", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav dotazník", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník"))),
                            new Stranka_Element_Tlacitko("Plán", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav plán", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán"))),
                            new Stranka_Element_Tlacitko("Doporučení rk", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "doporucenirk", "textDoNadpisuStranky" => "doporučení rekvalifikačního kurzu", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav doporučení rk", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "doporucenirk", "textDoNadpisuStranky" => "doporučení rekvalifikačního kurzu"))),
                            new Stranka_Element_Tlacitko("Ukončení", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "ukonceni", "textDoNadpisuStranky" => "ukončení", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav ukončení", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "ukonceni", "textDoNadpisuStranky" => "ukončení"))),
                            new Stranka_Element_Tlacitko("Test PC", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "testpc", "textDoNadpisuStranky" => "test PC", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav test PC", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "testpc", "textDoNadpisuStranky" => "test PC"))),
                            new Stranka_Element_Tlacitko("Zaměstnání", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav zaměstnání", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání"))),
                            new Stranka_Element_Tlacitko("Doplňující", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "doplnujici", "textDoNadpisuStranky" => "doplňující", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav doplňující", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "objektVlastnost" => "doplnujici", "textDoNadpisuStranky" => "doplňující"))),
                            new Stranka_Element_Tlacitko("Akce účastníka", $this->cestaSem->generujUriDalsi("Stranka_AkceM.akceUcastnika", array("id" => $ucastnik->id)))
                        );
                        $this->pouzijHlavicku($ucastnik, $hlavickaTabulky);
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
                        $ucastnik->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id));
                        $ucastnik->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id, "zmraz" => 1)), "tlacitko"),
//                            new Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_Ucastnik.detail", array("id" => $ucastnik->id)), "tlacitko")
                            new Stranka_Element_Tlacitko("Akce účastníka", $this->cestaSem->generujUriDalsi("Stranka_AkceM.akceUcastnika", array("id" => $ucastnik->id)))
                        );
                        $this->pouzijHlavicku($ucastnik, $hlavickaTabulky);
                    }
                    $this->novaPromenna("seznam", $ucastnici);
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }

        private function generujHlavickuTabulky() 
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                //sloupce pro zobrazení vlastností odpovidajících sloupcům v db tabulce zajemce
                $hlavickaTabulky->pridejSloupec("id", "ID", Data_Zajemce::ID);
                $hlavickaTabulky->pridejSloupec("identifikator", "Identifikátor", Data_Zajemce::IDENTIFIKATOR);
                //sloupce pro zobrazení vlastností odpovidajících těm sloupcům v db tabulce zajemce, které obsahují cizí klíče
                $hlavickaTabulky->pridejSloupec("idSBehProjektuFK", "Turnus", Data_Seznam_SBehProjektu::TEXT, "Data_Seznam_SBehProjektu::vypisVse()", "Data_Seznam_SBehProjektu::najdiPodleId(%ID%)", "text");
                $hlavickaTabulky->pridejSloupec("idCKancelarFK", "Kancelář", Data_Ucastnik::ID_C_KANCELAR_FK, "Data_Ciselnik::vypisVse('kancelar', '', '', 'id_c_kancelar')", "Data_Ciselnik::najdiPodleId('kancelar', %ID%)", "text");                
                //sloupec pro zobrazení vlastnosti, která nemá odpovídající sloupec v db tabulce zajemce (byla vytvořena v konstruktoru Data_Zajemce)
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