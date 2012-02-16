<?php
class Stranka_ISCOM extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "iscom.xhtml";

        protected $vyhledanaIsco;
        
	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	/*
         *  ~~~~~~~~MAIN~~~~~~~~
         */
	public function main($parametry = null)
	{ 
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry);
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("jsFilePath1", "js/ajax_request.js");                
                $this->novaPromenna("jsFilePath2", "js/isco_vyhledani.js");                
                $this->novaPromenna("id", $this->nazev);                
                        
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Seznam ISCO");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
                        new Stranka_Element_Tlacitko("Vyhledání ISCO", $this->cestaSem->generujUriDalsi("Stranka_ISCOVyhledani.main"))
                    
		);
                $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function main°potomekNeni()
	{
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   
                $seznamisco = Data_Seznam_SISCO::vypisVse("LENGTH(".Data_Seznam_SISCO::KOD.")=1", $this->parametry["razeniPodle"], $this->parametry["razeni"]);
		foreach($seznamisco as $jednoisco)
		{
			$jednoisco->odkaz = $this->cestaSem->generujUriDalsi("Stranka_ISCOM.ISCO2", array("id" => $jednoisco->id, "prefixKodu" => substr($jednoisco->kod, 0, 1)));
			$jednoisco->tlacitka = array
			(
				new Stranka_Element_Tlacitko("Úroveň 2", $jednoisco->odkaz),
			);
                        $this->pouzijHlavicku($jednoisco, $hlavickaTabulky);
                }

                $this->novaPromenna("seznam", $seznamisco);
	}
        
        public function main°potomek°Stranka_ISCOM°ISCO2() 
        {
            $this->generujPolozkuSTlacitky("id");
        }
        
        public function main°potomek°Stranka_ISCOJ°detail() 
	{ 
		$this->generujPolozkuSTlacitky("id");
	}

        public function ISCO2($parametry = null)
	{ 
		return $this->vytvorStranku("ISCO2", self::SABLONA_MAIN, $parametry);
	}
        
        protected function ISCO2°vzdy()
	{
            $this->main°vzdy();
        }   
        
	protected function ISCO2°potomekNeni()
	{       
            $this->generujSeznamSTlacitky();
        }   

        public function ISCO2°potomek°Stranka_ISCOM°ISCO3() 
        {
            $this->generujPolozkuSTlacitky("id");            
        }
        
        public function ISCO2°potomek°Stranka_ISCOJ°detail() 
	{ 
		$this->generujPolozkuSTlacitky("id");
	}

        public function ISCO3($parametry = null)
	{ 
		return $this->vytvorStranku("ISCO3", self::SABLONA_MAIN, $parametry);
	}
        
        protected function ISCO3°vzdy()
	{
            $this->main°vzdy();
        }         
        
	protected function ISCO3°potomekNeni()
	{           
            $this->generujSeznamSTlacitky();
	}          

        public function ISCO3°potomek°Stranka_ISCOM°ISCO4() 
        {
            $this->generujPolozkuSTlacitky("id");            
        }
        
        public function ISCO3°potomek°Stranka_ISCOJ°detail() 
	{ 
		$this->generujPolozkuSTlacitky("id");
	}

        public function ISCO4($parametry = null)
	{ 
		return $this->vytvorStranku("ISCO4", self::SABLONA_MAIN, $parametry);
	}
        
        protected function ISCO4°vzdy()
	{
            $this->main°vzdy();
        } 
        
	protected function ISCO4°potomekNeni()
	{            
            $this->generujSeznamSTlacitky();
        }    
        protected function ISCO4°potomek°Stranka_ISCOM°ISCO5() 
        {
            $this->generujPolozkuSTlacitky("id");
        }
        
        public function ISCO4°potomek°Stranka_ISCOJ°detail() 
	{ 
		$this->generujPolozkuSTlacitky("id");
	}

        public function ISCO5($parametry = null)
	{ 
		return $this->vytvorStranku("ISCO5", self::SABLONA_MAIN, $parametry);
	}
        
        protected function ISCO5°vzdy()
	{
            $this->main°vzdy();
        } 
        
	protected function ISCO5°potomekNeni()
	{            
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   
                $pref = $this->parametry["prefixKodu"];
		$seznamisco = Data_Seznam_SISCO::vypisVse("LENGTH(`".Data_Seznam_SISCO::KOD."`)=5 AND LEFT(`".Data_Seznam_SISCO::KOD."`, 4)='".$pref."'", $this->parametry["razeniPodle"], $this->parametry["razeni"]);
		if ($seznamisco)
                {
                    foreach($seznamisco as $jednoisco)
                    {
                        $jednoisco->odkaz = $this->cestaSem->generujUriDalsi("Stranka_ISCOJ.detail", array("id" => $jednoisco->id, "zmraz" => 1));
                        $jednoisco->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $jednoisco->odkaz),
                        );
                        $this->pouzijHlavicku($jednoisco, $hlavickaTabulky);
                        }
                    $this->novaPromenna("seznam", $seznamisco);
                } else {
                    $this->novaPromenna("zprava", "Nic nenalezeno!");
                }                
	}    
        
	protected function ISCO5°potomek°Stranka_ISCOJ°detail()
	{ 
		$this->generujPolozkuSTlacitky("id");
	}

        protected function main°potomek°Stranka_ISCOVyhledani°main()
	{ 
	}
        
	private function generujPolozkuSTlacitky($nazevID)
	{
            if($this->dalsi->parametry[$nazevID])
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   
                $seznamisco[] = Data_Seznam_SISCO::najdiPodleId($this->dalsi->parametry[$nazevID]);
                $seznamisco[0]->tlacitka = array
                (
                        new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_ISCOJ.detail", array("id" => $seznamisco[0]->id, "zmraz" => 1))),
                );
                $this->pouzijHlavicku($seznamisco[0], $hlavickaTabulky);
                $this->novaPromenna("seznam", $seznamisco);
            }
        }
	
	private function generujSeznamSTlacitky()
        {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   
                $prefix = $this->parametry["prefixKodu"];
                // vyhledává všechny kódy začínající na prefix a mající kód o jeden znak delší než prefix
                $delkaPrefixu = strlen($prefix);
                $delkaKodu = $delkaPrefixu + 1;
                // odkaz na tlčítku je vytvořen tak, že se volá metoda s názvem "ISCOx", kde x je číslo o jednu větší než $delkaKodu
                // nadpis na tlačítku je vytvořen z textu Úroveň a čísla $delkaKodu
		$seznamisco = Data_Seznam_SISCO::vypisVse("LENGTH(`".Data_Seznam_SISCO::KOD."`)=".$delkaKodu." AND LEFT(`".Data_Seznam_SISCO::KOD."`, ".$delkaPrefixu.")='".$prefix."'", $this->parametry["razeniPodle"], $this->parametry["razeni"]);
		if ($seznamisco)
                {
                    foreach($seznamisco as $jednoisco)
                    {
                        $jednoisco->odkaz = $this->cestaSem->generujUriDalsi("Stranka_ISCOM.ISCO".($delkaKodu+1), array("id" => $jednoisco->id, "prefixKodu" => substr($jednoisco->kod, 0, 4)));
                        $jednoisco->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Úroveň ".($delkaKodu+1), $jednoisco->odkaz),
                        );
                        $this->pouzijHlavicku($jednoisco, $hlavickaTabulky);
                    }
                    $this->novaPromenna("seznam", $seznamisco);
                } else {
                    $this->novaPromenna("zprava", "Nic nenalezeno!");
                }                 
        }
        

        private function generujHlavickuTabulky() 
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
		$hlavickaTabulky->pridejSloupec("id", "ID", Data_Seznam_SISCO::ID);
		$hlavickaTabulky->pridejSloupec("kod", "kód", Data_Seznam_SISCO::KOD);
                $hlavickaTabulky->pridejSloupec("nazev", "Název", Data_Seznam_SISCO::NAZEV);                
                
                return $hlavickaTabulky;
        }        
        
}