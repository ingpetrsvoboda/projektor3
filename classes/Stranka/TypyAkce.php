<?php
class Stranka_TypyAkce extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	/*
         *  ~~~~~~~~MAIN~~~~~~~~
         */
	public function main($parametry = null)
	{ 
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani("typyakce", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();

            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $formularHTML);            
	}

	protected function main°vzdy()
	{
            $this->novaPromenna("id", $this->nazev);                            
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Typy akce");

            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
                    new Stranka_Element_Tlacitko("Nový typ akce", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail")),
            );
            $this->novaPromenna("tlacitka", $tlacitka);

            /* Hlavicka tabulky */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
	}

	protected function main°potomekNeni()
	{
            $typyakce = Data_Seznam_STypAkce::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
            $this->generujSeznamSTlacitky($typyakce);
	}

	protected function main°potomek°Stranka_TypAkce°detail()
	{ 
		$this->generujPolozkuSTlacitky("id");
	}

        protected function main°potomek°Stranka_Predpoklady°proTypAkce()
	{
		$this->generujPolozkuSTlacitky("id_typ_akce");
	}
	
//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------        
	private function generujPolozkuSTlacitky($nazevID)
	{
            if($this->dalsi->parametry[$nazevID])
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky); 
                    
                $typakce = Data_Seznam_STypAkce::najdiPodleId($this->dalsi->parametry[$nazevID]);
                if ($typakce)
                {
                    $typakce->tlacitka = array
                    (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id))),
                            new Stranka_Element_Tlacitko("Smazat", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "smaz" => 1))),
                            new Stranka_Element_Tlacitko("Předpoklady", $this->cestaSem->generujUriDalsi("Stranka_Predpoklady.proTypAkce", array("id_typ_akce" => $typakce->id))),
                    );
                    $this->pouzijHlavicku($typakce, $hlavickaTabulky);
                    $this->novaPromenna("polozka", $typakce);
                }
            }
	}
        
        private function generujSeznamSTlacitky($typyakce)
        {
             if ($typyakce) {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);            
		foreach($typyakce as $typakce)
		{
                    $typakce->tlacitka = array
                    (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id))),
                            new Stranka_Element_Tlacitko("Smazat", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "smaz" => 1))),
                            new Stranka_Element_Tlacitko("Předpoklady", $this->cestaSem->generujUriDalsi("Stranka_Predpoklady.proTypAkce", array("id_typ_akce" => $typakce->id))),
                    );
                    $this->pouzijHlavicku($typakce, $hlavickaTabulky);
		}
                $this->novaPromenna("seznam", $typyakce);            
            } else {
            $this->novaPromenna("zprava", "Nic nenalezeno!");
            }
        }

        private function generujHlavickuTabulky()
        {
            
            /* Hlavicka tabulky */
            $hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
            $hlavickaTabulky->pridejSloupec("id", "ID", Data_Seznam_STypAkce::ID);
            $hlavickaTabulky->pridejSloupec("nazev", "Název", Data_Seznam_STypAkce::NAZEV);
            $hlavickaTabulky->pridejSloupec("trvaniDni", "Trvání dní", Data_Seznam_STypAkce::TRVANI_DNI);
            $hlavickaTabulky->pridejSloupec("hodinyDen", "Hodiny za den", Data_Seznam_STypAkce::HODINY_ZA_DEN);
            $hlavickaTabulky->pridejSloupec("minPocetUc", "Minimální počet účastníků", Data_Seznam_STypAkce::MIN_POCET_UC);
            $hlavickaTabulky->pridejSloupec("maxPocetUc", "Maximální počet účastníků", Data_Seznam_STypAkce::MAX_POCET_UC);

            return $hlavickaTabulky;
        }
	
	

}