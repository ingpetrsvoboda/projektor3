<?php
class Stranka_Zajemci extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";
        const HLAVNI_OBJEKT = "Zajemce";
	
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
            $filtrovaciFormular = $this->filtrovani("zajemci", $hlavickaTabulky); 
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                
	}

	protected function main°potomekNeni()
	{
		$zajemci = Data_Zajemce::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($zajemci);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemci");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nový zájemce", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("objektVlastnost" => "smlouva")))
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }

        
	protected function main°potomek°Stranka_Zajemce°detail()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemce");
	}

        protected function main°potomek°Stranka_AkceM°akceUcastnika()
        {
                $this->generujPolozkuSTlacitky();            
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemce");
        }
        /**
        *  ~~~~~~~~PRIHLASOVACI~~~~~~~~~~
        */
	public function prihlasovaci($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("zajemci", $hlavickaTabulky);            

            return $this->vytvorStranku("prihlasovaci", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());
        }

	protected function prihlasovaci°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Výběr zájemce");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			//new Tlacitko("Nový účastník", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail")),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }
	
	protected function prihlasovaci°potomekNeni()
	{
		$zajemci = Data_Zajemce::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($zajemci);
        }
	
	protected function prihlasovaci°potomek°Stranka_Zajemce°prihlaseni()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
        }
	
	protected function prihlasovaci°potomek°Stranka_Zajemce°detail()
	{
//		$this->prihlasovaci°potomek°Stranka_Zajemce°prihlaseni();
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
	}

        /*
        *  ~~~~~~~~PRIHLASENI~~~~~~~~~~
        */
	public function prihlaseni($parametry = null)
	{ 
		return $this->vytvorStranku("prihlaseni", self::SABLONA_MAIN, $parametry);
	}

	protected function prihlaseni°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemci přihlášení na akci");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}
	
	protected function prihlaseni°potomekNeni()
	{
            $zajemci = Data_Zajemce::vypisPrihlaseneNaAkci($this->parametry["id_akce"]);
            $this->generujSeznamSTlacitky($zajemci);
	}
	
	protected function prihlaseni°potomek°Stranka_Zajemce°detail()
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
                    $zajemce = Data_Zajemce::najdiPodleId($this->dalsi->parametry["id"]);
                    if ($zajemce)
                    {
                        $hlavickaTabulky = $this->generujHlavickuTabulky();
                        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   

                        $zajemce->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id));
                        $zajemce->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Smlouva", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav smlouvu", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "smlouva", "textDoNadpisuStranky" => "smlouva"))),
                            new Stranka_Element_Tlacitko("Dotazník", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav dotazník", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "dotaznik", "textDoNadpisuStranky" => "dotazník"))),
                            new Stranka_Element_Tlacitko("Plán", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav plán", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "plan", "textDoNadpisuStranky" => "plán"))),
                            new Stranka_Element_Tlacitko("Zaměstnání", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav zaměstnání", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "zamestnani", "textDoNadpisuStranky" => "zaměstnání"))),
                            new Stranka_Element_Tlacitko("Akce", $this->cestaSem->generujUriDalsi("Stranka_AkceM.akceUcastnika", array("id" => $zajemce->id)))
                        );

                        $zajemce->odeberVsechnyVlastnosti();
                        foreach ($hlavickaTabulky->sloupce as $sloupec) {
                            $zajemce->pridejVlastnost($sloupec->nazevVlastnosti);
                        }
                        $this->novaPromenna("polozka", $zajemce);
                    }
                }
                $this->novaPromenna("skryjfiltr", TRUE);
        }                
        
        private function generujSeznamSTlacitky($zajemci)
        {
                if ($zajemci) 
                {                  
                    $hlavickaTabulky = $this->generujHlavickuTabulky();
                    $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);  
                    
                    foreach($zajemci as $zajemce)
                    {
                        $zajemce->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id));
                        $zajemce->tlacitka = array
                        (
                                new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
//                                new Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $ucastnik->id)), "tlacitko")
                                new Stranka_Element_Tlacitko("Přihlaš", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.prihlaseni", array("id" => $zajemce->id)))
                        );
                        $zajemce->odeberVsechnyVlastnosti();
                        foreach ($hlavickaTabulky->sloupce as $sloupec) {
                            $zajemce->pridejVlastnost($sloupec->nazevVlastnosti);
                        }
//                        $ucastnik->odeberVlastnost("cisloHlavnihoObjektu")->odeberVlastnost("idSBehProjektuFK")->odeberVlastnost("behCislo")->odeberVlastnost("idCProjektFK")->odeberVlastnost("idCKancelarFK")->odeberVlastnost("updated");
//                        $ucastnik->odeberVlastnost('_mapovaniObjektTabulka')->odeberVlastnost('_jmenoId')->odeberVlastnost('_prefix');
                    }    
                    $this->novaPromenna("seznam", $zajemci);
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }

        private function generujHlavickuTabulky() 
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                $hlavickaTabulky->pridejSloupec("id", "ID", Data_Zajemce::ID);
                $hlavickaTabulky->pridejSloupec("identifikator", "Identifikátor", Data_Zajemce::IDENTIFIKATOR);
                $hlavickaTabulky->pridejSloupec("turnusText", "Turnus", Data_Seznam_SBehProjektu::TEXT, "Data_Seznam_SBehProjektu::vypisVse()", "text");
                $hlavickaTabulky->pridejSloupec("kancelarText", "Kancelář", Data_Zajemce::ID_C_KANCELAR_FK, "Data_Ciselnik::vypisVse('kancelar', '', '', 'id_c_kancelar')","text");                
                $hlavickaTabulky->pridejSloupec("celeJmeno", "Celé jméno");
                
                return $hlavickaTabulky;
        }
                    
}