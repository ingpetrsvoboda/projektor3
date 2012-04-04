<?php
abstract class Stranka_FlatTableM extends Stranka implements Stranka_Interface
{
        
        protected $nazev_flattable;
        protected $nazev_jednotne;
        protected $nazev_mnozne;
        protected $vsechny_radky;
        protected $databaze;
        
	const SABLONA_MAIN = "seznam.xhtml";

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main($parametry = null, $formular = null, $filtrovaciForm = null)
	{
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, $formular, $filtrovaciForm);
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                
	}

	protected function main°potomekNeni()
	{
		$poleFlatTable = Data_Flat_FlatTable::vypisVse($this->databaze, $this->nazev_flattable, $this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"], FALSE, "", NULL, "", "", "", $this->vsechny_radky);
                $this->generujSeznamSTlacitky($poleFlatTable);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", $this->nazev_mnozne);
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nová ".strtolower($this->nazev_jednotne), $this->cestaSem->generujUriDalsi("Stranka_".$this->nazev_jednotne.".detail"))
		);
                $this->novaPromenna("tlacitka", $tlacitka);
        }

        
	protected function main°potomek°Stranka_FlatTableJ°detail()
	{
                $this->generujPolozkuSTlacitky();
//                $this->novaPromenna("tlacitka", $tlacitka);
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", $this->nazev_jednotne);
	}



        private function generujPolozkuSTlacitky()
        {
        if($this->dalsi->parametry["id"])
		{
//                    $firma = Data_Seznam_SFirma::najdiPodleId($this->dalsi->parametry["id"]);
                    $polozka = Data_Flat_FlatTable::najdiPodleId($this->databaze, $this->nazev_flattable, $this->dalsi->parametry["id"], FALSE, "", NULL, $this->vsechny_radky);
                    if ($polozka)
                    {
                        $hlavickaTabulky = $this->generujHlavickuTabulky();
                        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   

                        $polozka->odkaz = $this->cestaSem->generujUriDalsi("Stranka_".$this->nazev_jednotne.".detail", array("id" => $polozka->id));
                        $polozka->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_".$this->nazev_jednotne.".detail", array("id" => $polozka->id, "textDoNadpisuStranky" => "Detail ".strtolower($this->nazev_mnozne), "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_".$this->nazev_jednotne.".detail", array("id" => $polozka->id, "textDoNadpisuStranky" => "Úprava údajů ".strtolower($this->nazev_mnozne)))),
                        );
                        $this->pouzijHlavicku($polozka, $hlavickaTabulky);
                        $this->novaPromenna("polozka", $polozka);
                    }
                }
                $this->novaPromenna("skryjfiltr", TRUE);
        }                
        
        private function generujSeznamSTlacitky($poleFlatTable)
        {
                if ($poleFlatTable) 
                {                  
                    $hlavickaTabulky = $this->generujHlavickuTabulky();
                    $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);  
                    
                    foreach($poleFlatTable as $polozkaFlatTable)
                    {
                        $polozkaFlatTable->odkaz = $this->cestaSem->generujUriDalsi("Stranka_".$this->nazev_jednotne.".detail", array("id" => $polozkaFlatTable->id));
                        $polozkaFlatTable->tlacitka = array
                        (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_".$this->nazev_jednotne.".detail", array("id" => $polozkaFlatTable->id, "textDoNadpisuStranky" => "Detail ".strtolower($this->nazev_mnozne), "zmraz" => 1))),
                        );
                        $this->pouzijHlavicku($polozkaFlatTable, $hlavickaTabulky);
                    }    
                    $this->novaPromenna("seznam", $poleFlatTable);
                } else {
                $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
        }           
}