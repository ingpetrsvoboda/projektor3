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
//                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemce");
	}

        protected function main°potomek°Stranka_AkceM°akceZAjemce()
        {
                $this->generujPolozkuSTlacitky();            
//                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemce");
        }
        
        public function main°potomek°Stranka_Zajemce°smaz()
        {
            $this->main°potomekNeni();  // po smazání vytvoří seznam zájemců
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
//                $this->novaPromenna("tlacitka", $tlacitka);
        }
	
	protected function prihlasovaci°potomek°Stranka_Zajemce°detail()
	{
//		$this->prihlasovaci°potomek°Stranka_Zajemce°prihlaseni();
                $this->generujPolozkuSTlacitky();
//                $this->novaPromenna("tlacitka", $tlacitka);
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
//                $this->novaPromenna("tlacitka", $tlacitka);
        }	

        protected function prihlaseni°potomek°Stranka_AkceM°akceUcastnika()
        {
                $this->generujPolozkuSTlacitky();            
//                $this->novaPromenna("tlacitka", $tlacitka);
        }


        /*
        *  ~~~~~~~~VHODNI NA POZICI~~~~~~~~~~
        */
	public function vhodniNaPozici($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("zajemci", $hlavickaTabulky); 
            return $this->vytvorStranku("vhodniNaPozici", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());
	}

	protected function vhodniNaPozici°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemci vhodní na pozici");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}
	
	protected function vhodniNaPozici°potomekNeni()
	{
                $iscoKod = $this->parametry["iscoKod"];
		$zajemci = Data_Zajemce::vypisVhodneNaPozici($iscoKod);
                $this->generujSeznamSTlacitkyPredpoklady($zajemci);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Zájemci vhodní na pozici");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}
	
	protected function vhodniNaPozici°potomek°Stranka_Zajemce°detail()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
        }	
        
/* ------------------------------------------------------------------------------------ */        
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
                            new Stranka_Element_Tlacitko("Test", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "test", "textDoNadpisuStranky" => "test", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav test", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "objektVlastnost" => "test", "textDoNadpisuStranky" => "test"))),
                            new Stranka_Element_Tlacitko("Akce", $this->cestaSem->generujUriDalsi("Stranka_AkceM.akceZajemce", array("id" => $zajemce->id))),
                            new Stranka_Element_Tlacitko("Smaž zájemce", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.smaz", array("id" => $zajemce->id))),
                            new Stranka_Element_Tlacitko("Souhlas", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.exportPDF", array("id" => $zajemce->id, "pdfDokument" => "AGPSouhlas")))
                        );
                        $this->pouzijHlavicku($zajemce, $hlavickaTabulky);
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
                        $zajemce->tlacitka = array
                        (
                                new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
//                                new Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $ucastnik->id)), "tlacitko")
                                new Stranka_Element_Tlacitko("Akce zájemce", $this->cestaSem->generujUriDalsi("Stranka_AkceM.akceZajemce", array("id" => $zajemce->id)))
                        );
                        $this->pouzijHlavicku($zajemce, $hlavickaTabulky);
                    }    
                    $this->novaPromenna("seznam", $zajemci);
                    $this->novaPromenna("zprava", "Celkem nalezeno:".  count($zajemci));
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
                $hlavickaTabulky->pridejSloupec("idCKancelarFK", "Kancelář", Data_Zajemce::ID_C_KANCELAR_FK, "Data_Ciselnik::vypisVse('kancelar', '', '', 'id_c_kancelar')", "Data_Ciselnik::najdiPodleId('kancelar', %ID%)", "text");                
                //sloupec pro zobrazení vlastnosti, která nemá odpovídající sloupec v db tabulce zajemce (byla vytvořena v konstruktoru Data_Zajemce)
                $hlavickaTabulky->pridejSloupec("celeJmeno", "Celé jméno");
                //sloupce pro zobrazení vlastností některého ObjektuVlastnosti (např. za_xxxx_flat_table) hlavního objektu (Zajemce)
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."vzdelani1", "Vzdělání1");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo1", "KZAM 1");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo2", "KZAM 2");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo3", "KZAM 3");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo4", "KZAM 4");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo5", "KZAM 5");
                $hlavickaTabulky->pridejSloupec("zamestnani_pozice1", "zaměstnání 1");
                
                
                return $hlavickaTabulky;
        }
        
        /*
         * funkce pro metodu vhodniNaPozici, používá metodu generujHlavickuTabulkyPredpoklady pro generování hlavičky 
         */
        private function generujSeznamSTlacitkyPredpoklady($zajemci)
        {
                if ($zajemci) 
                {                  
                    $hlavickaTabulky = $this->generujHlavickuTabulkyPredpoklady();
                    $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);  
                    
                    foreach($zajemci as $zajemce)
                    {
                        $zajemce->tlacitka = array
                        (
                                new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $zajemce->id, "zmraz" => 1)), "tlacitko"),
//                                new Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $ucastnik->id)), "tlacitko")
                        );
                        $this->pouzijHlavicku($zajemce, $hlavickaTabulky);
                    }    
                    $this->novaPromenna("seznam", $zajemci);
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }

        private function generujHlavickuTabulkyPredpoklady() 
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                //sloupce pro zobrazení vlastností odpovidajících sloupcům v db tabulce zajemce
                $hlavickaTabulky->pridejSloupec("id", "ID", Data_Zajemce::ID);
//                $hlavickaTabulky->pridejSloupec("identifikator", "Identifikátor", Data_Zajemce::IDENTIFIKATOR);
                //sloupce pro zobrazení vlastností odpovidajících těm sloupcům v db tabulce zajemce, které obsahují cizí klíče
                $hlavickaTabulky->pridejSloupec("idSBehProjektuFK", "Turnus", Data_Seznam_SBehProjektu::TEXT, "Data_Seznam_SBehProjektu::vypisVse()", "Data_Seznam_SBehProjektu::najdiPodleId(%ID%)", "text");
                $hlavickaTabulky->pridejSloupec("idCKancelarFK", "Kancelář", Data_Zajemce::ID_C_KANCELAR_FK, "Data_Ciselnik::vypisVse('kancelar', '', '', 'id_c_kancelar')", "Data_Ciselnik::najdiPodleId('kancelar', %ID%)", "text");                
                //sloupec pro zobrazení vlastnosti, která nemá odpovídající sloupec v db tabulce zajemce (byla vytvořena v konstruktoru Data_Zajemce)
                $hlavickaTabulky->pridejSloupec("celeJmeno", "Celé jméno");
                //sloupce pro zobrazení vlastností některého ObjektuVlastnosti (např. za_xxxx_flat_table) hlavního objektu (Zajemce)
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."vzdelani1", "Vzdělání1");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo1", "KZAM 1");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo2", "KZAM 2");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo3", "KZAM 3");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo4", "KZAM 4");
                $hlavickaTabulky->pridejSloupec("smlouva".self::SEPARATOR."KZAM_cislo5", "KZAM 5");
                $hlavickaTabulky->pridejSloupec("predpoklad", "Předpoklad");
                
                
                return $hlavickaTabulky;
        }                    
}