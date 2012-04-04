<?php
class Stranka_AkceM extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	/* main */
	public function main($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("akcem", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $formularHTML);  
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Akce");

		/* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nová akce", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail")),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function main°potomekNeni()
	{ 
		$akcem = Data_Akce::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($akcem);
        }

	protected function main°potomek°Stranka_AkceJ°detail()
	{ 
		$this->generujPolozkuSTlacitky("id");
	}
	
	protected function main°potomek°Stranka_Ucastnici°prihlasovaci()
	{ 
		$this->generujPolozkuSTlacitky("id_akce");
	}
	
	protected function main°potomek°Stranka_Ucastnici°prihlaseni()
	{
		$this->generujPolozkuSTlacitky("id_akce");
	}
	
	protected function main°potomek°Stranka_Zajemci°prihlasovaci()
	{ 
		$this->generujPolozkuSTlacitky("id_akce");
	}
	
	protected function main°potomek°Stranka_Zajemci°prihlaseni()
	{
		$this->generujPolozkuSTlacitky("id_akce");
	}
        
        /*
        *  ~~~~~~~~AKCE ÚČASTNÍKA~~~~~~~~~~
        */
	public function akceUcastnika($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("akcemAkceUcastnika", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            return $this->vytvorStranku("akceUcastnika", self::SABLONA_MAIN, $parametry, "", $formularHTML);  
	}

	protected function akceUcastnika°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
                    new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Akce účastníka");         }
	
	protected function akceUcastnika°potomekNeni()
	{
                $ucastnik = Data_Ucastnik::najdiPodleId($this->parametry["id"]);
                $akcem = Data_Akce::vsechnyAkceUcastnika($ucastnik);
                $this->generujSeznamSTlacitky($akcem);               
        }

        protected function akceUcastnika°potomek°Stranka_Ucastnici°prihlaseni() 
        {
		$this->generujPolozkuSTlacitky("id_akce");
        }

        protected function akceUcastnika°potomek°Stranka_Ucastnici°prihlasovaci() 
        {
		$this->generujPolozkuSTlacitky("id_akce");
        }
        
        /*
        *  ~~~~~~~~AKCE ZÁJEMCE~~~~~~~~~~
        */
	public function akceZajemce($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("akcemAkceUcastnika", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            return $this->vytvorStranku("akceZajemce", self::SABLONA_MAIN, $parametry, "", $formularHTML);  
	}

	protected function akceZajemce°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
                    new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Akce zájemce");         }
	
	protected function akceZajemce°potomekNeni()
	{
                $zajemce = Data_Zajemce::najdiPodleId($this->parametry["id"]);
                $akcem = Data_Akce::vsechnyAkceUcastnika($zajemce);
                $this->generujSeznamSTlacitky($akcem);               
        }

        protected function akceZajemce°potomek°Stranka_Zajemci°prihlaseni() 
        {
		$this->generujPolozkuSTlacitky("id_akce");
        }


        protected function akceZajemce°potomek°Stranka_Zajemci°prihlasovaci() 
        {
		$this->generujPolozkuSTlacitky("id_akce");
        }
                
        /**
        *  ~~~~~~~~PRIHLASOVACI~~~~~~~~~~
        */
	public function prihlasovaci($parametry = null)
	{ 
//TODO: dodělat filtrování do seznamů akcí
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("akcemPrihlasovaci", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            return $this->vytvorStranku("prihlasovaci", self::SABLONA_MAIN, $parametry, "", $formularHTML);  
        }

	protected function prihlasovaci°vzdy()
	{
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Výběr akce");
        }
	
	protected function prihlasovaci°potomekNeni()
	{
		$akcem = Data_Akce::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($akcem);
        }
	
	protected function prihlasovaci°potomek°Stranka_Ucastnik°prihlaseni()
	{
                $this->generujPolozkuSTlacitky();
//                $this->novaPromenna("tlacitka", $tlacitka);
        }
	
	protected function prihlasovaci°potomek°Stranka_Ucastnik°detail()
	{
                $this->generujPolozkuSTlacitky();
//                $this->novaPromenna("tlacitka", $tlacitka);
	}
        
//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------        
	private function generujPolozkuSTlacitky($nazevID)
	{
            if($this->dalsi->parametry[$nazevID])
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky); 
                    
                $akcej = Data_Akce::najdiPodleId($this->dalsi->parametry[$nazevID]);
                if ($akcej)
                {
                    $akcej->tlacitka = array
                    (
                        new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id, "zmraz" => 1))),
                        new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id))),
                        new Stranka_Element_Tlacitko("Zrušit", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id, "smaz" => 1, "zmraz" => 1))),
                        new Stranka_Element_Tlacitko("Přihlásit účastníka", $this->cestaSem->generujUriDalsi("Stranka_Ucastnici.prihlasovaci", array("id_akce" => $akcej->id))),
                        new Stranka_Element_Tlacitko("Seznam přihlášených", $this->cestaSem->generujUriDalsi("Stranka_Ucastnici.prihlaseni", array("id_akce" => $akcej->id)))
                    );
                    $this->pouzijHlavicku($akcej, $hlavickaTabulky);
                    $this->novaPromenna("polozka", $akcej);
                }
            }
	}
        
        private function generujSeznamSTlacitky($akcem)
        {
                if ($akcem) {
                    $hlavickaTabulky = $this->generujHlavickuTabulky();
                    $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky); 
                    
                    foreach($akcem as $akcej)
                    {
                        $akcej->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $akcej->id));
                        $akcej->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id, "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id))),
                            new Stranka_Element_Tlacitko("Zrušit", $this->cestaSem->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id, "smaz" => 1, "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Přihlásit účastníka", $this->cestaSem->generujUriDalsi("Stranka_Ucastnici.prihlasovaci", array("id_akce" => $akcej->id))),
                            new Stranka_Element_Tlacitko("Seznam přihlášených", $this->cestaSem->generujUriDalsi("Stranka_Ucastnici.prihlaseni", array("id_akce" => $akcej->id)))
                        );
                        $this->pouzijHlavicku($akcej, $hlavickaTabulky);
                    }
                    $this->novaPromenna("seznam", $akcem);
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }	

        private function generujHlavickuTabulky() 
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
		$hlavickaTabulky->pridejSloupec("id", "ID", Data_Akce::ID);
		$hlavickaTabulky->pridejSloupec("nazevHlavnihoObjektu", "Název hlavního objektu", Data_Akce::NAZEV_HLAVNIHO_OBJEKTU);                
		$hlavickaTabulky->pridejSloupec("nazev", "Název", Data_Akce::NAZEV);
		$hlavickaTabulky->pridejSloupec("popis", "Popis", Data_Akce::POPIS);
		$hlavickaTabulky->pridejSloupec("datumZacatek", "Datum začátku", Data_Akce::DATUM_ZACATEK);
		$hlavickaTabulky->pridejSloupec("datumKonec", "Datum konce", Data_Akce::DATUM_KONEC);
                return $hlavickaTabulky;
        }
              
}