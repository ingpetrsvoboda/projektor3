<?php
class Stranka_Firmy extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";
	const NAZEV_FLAT_TABLE = "s_firma";
	
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
            $filtrovaciFormular = $this->filtrovani("firmy", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $formularHTML);            
	}

	protected function main°vzdy()
	{

	}

	protected function main°potomekNeni()
	{
//		$firmy = Data_Seznam_SFirma::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
//      $jmenoTabulky, $id, $filtr = "", $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoSloupceIdHlavnihoObjektu=NULL
		$firmy = Data_Flat_FlatTable::vypisVse(self::NAZEV_FLAT_TABLE, $this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($firmy);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Firmy");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nová firma", $this->cestaSem->generujUriDalsi("Stranka_Firma.detail"))
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }

        
	protected function main°potomek°Stranka_Firma°detail()
	{
                $this->generujPolozkuSTlacitky();
                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Firma");
	}



        private function generujPolozkuSTlacitky()
        {
        if($this->dalsi->parametry["id"])
		{
//                    $firma = Data_Seznam_SFirma::najdiPodleId($this->dalsi->parametry["id"]);
                    $firma = Data_Flat_FlatTable::najdiPodleId(self::NAZEV_FLAT_TABLE, $this->dalsi->parametry["id"]);
                    if ($firma)
                    {
                        $hlavickaTabulky = $this->generujHlavickuTabulky();
                        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   

                        $firma->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Firma.detail", array("id" => $firma->id));
                        $firma->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Firma.detail", array("id" => $firma->id, "textDoNadpisuStranky" => "Detail firmy", "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_Firma.detail", array("id" => $firma->id, "textDoNadpisuStranky" => "Úprava údajů firmy"))),
                        );

                        $firma->odeberVsechnyVlastnosti();
                        foreach ($hlavickaTabulky->sloupce as $sloupec) {
                            $firma->pridejVlastnost($sloupec->nazevVlastnosti);
                        }
                        $this->novaPromenna("polozka", $firma);
                    }
                }
                $this->novaPromenna("skryjfiltr", TRUE);
        }                
        
        private function generujSeznamSTlacitky($firmy)
        {
                if ($firmy) 
                {                  
                    $hlavickaTabulky = $this->generujHlavickuTabulky();
                    $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);  
                    
                    foreach($firmy as $firma)
                    {
                        $firma->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Zajemce.detail", array("id" => $firma->id));
                        $firma->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Firma.detail", array("id" => $firma->id, "textDoNadpisuStranky" => "Detail firmy", "zmraz" => 1))),
                        );
                        $firma->odeberVsechnyVlastnosti();
                        foreach ($hlavickaTabulky->sloupce as $sloupec) {
                            $firma->pridejVlastnost($sloupec->nazevVlastnosti);
                        }
//                        $ucastnik->odeberVlastnost("cisloHlavnihoObjektu")->odeberVlastnost("idSBehProjektuFK")->odeberVlastnost("behCislo")->odeberVlastnost("idCProjektFK")->odeberVlastnost("idCKancelarFK")->odeberVlastnost("updated");
//                        $ucastnik->odeberVlastnost('_mapovaniObjektTabulka')->odeberVlastnost('_jmenoId')->odeberVlastnost('_prefix');
                    }    
                    $this->novaPromenna("seznam", $firmy);
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }

        private function generujHlavickuTabulky() 
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                $hlavickaTabulky->pridejSloupec("id", "ID", Data_Seznam_SFirma::ID);
                $hlavickaTabulky->pridejSloupec("ico", "IČO", Data_Seznam_SFirma::ICO);
                $hlavickaTabulky->pridejSloupec("nazev_firmy", "Název firmy", Data_Seznam_SFirma::NAZEV_FIRMY);
                $hlavickaTabulky->pridejSloupec("obec", "Obec", Data_Seznam_SFirma::OBEC);
                return $hlavickaTabulky;
        }
                    
}