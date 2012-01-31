<?php
class Stranka_Predpoklady extends Stranka implements Stranka_Interface
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
            $filtrovaciFormular = $this->filtrovani("predpoklady", $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            // cvicna uprava pro renderer tableless
//            $formularHTML = str_replace("<ol>", "<ul>", $formularHTML);
//            $formularHTML = str_replace("</ol>", "</ul>", $formularHTML);

            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $formularHTML);
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                            
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Předpoklady");

            /* Ovladaci tlacitka stranky */
            $tlacitka = $this->tlacitka(true);
            $this->novaPromenna("tlacitka", $tlacitka);

            /* Hlavicka tabulky */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
	}

	protected function main°potomekNeni()
	{
            $predpoklady = Data_Seznam_SAkcePredpoklad::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);

            if($predpoklady)
            {
                $seznam = $this->dalsiSloupce($predpoklady);
                $this->novaPromenna("seznam", $seznam);
            }
            else
                $this->novaPromenna("zprava", "Nic nenalezeno!");
	}

	protected function main°potomek°Stranka_Predpoklad°detail()
	{ 
            if($this->dalsi->parametry["id"])
            {
                $predpoklady[] = Data_Seznam_SAkcePredpoklad::najdiPodleId($this->dalsi->parametry["id"]);
                $this->dalsiSloupce($predpoklady);
                $this->novaPromenna("seznam", $predpoklady);
            }
	}

        
        /*
         *  ~~~~~~~~PRO TYP AKCE~~~~~~~~
         */
        public function proTypAkce($parametry = null)
	{
            return $this->vytvorStranku("proTypAkce", self::SABLONA_MAIN, $parametry);
	}

	protected function proTypAkce°vzdy()
	{
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Předpoklady pro typ akce");

            /* Ovladaci tlacitka stranky */
            $tlacitka = $this->tlacitka(false);
            $this->novaPromenna("tlacitka", $tlacitka);

            /* Hlavicka tabulky */
            $hlavickaTabulky = $this->hlavickaTabulky();
            $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
	}

	protected function proTypAkce°potomekNeni()
	{
            $data = Data_Seznam_SAkcePredpoklad::vypisPro
            (
                Data_Seznam_STypAkce::najdiPodleId($this->parametry["id_typ_akce"]),
                $this->parametry["razeniPodle"],
                $this->parametry["razeni"]
            );

            if(!$data)
            {
                $this->novaPromenna("zprava", "Tento typ akce nemá žádné předpoklady!");
                return 1;
            }

            $predpoklady = $this->dalsiSloupce($data);
            $this->novaPromenna("seznam", $predpoklady);
	}

	protected function proTypAkce°potomek°Stranka_Predpoklad°detail()
	{
            if($this->dalsi->parametry["id"])
            {
                $predpoklady[] = Data_Seznam_SAkcePredpoklad::najdiPodleId($this->dalsi->parametry["id"]);
                $this->dalsiSloupce($predpoklady);
                $this->novaPromenna("seznam", $predpoklady);
            }
	}


         /*
         *  ~~~~~~~~PRIVATNI FUNKCE~~~~~~~~
         */
        private function dalsiSloupce($data)
        {
            foreach($data as $predpoklad)
            {
                $predpoklad->odkaz = $this->cestaSem->generujUriDalsi("Stranka_Predpoklad.detail", array("id" => $predpoklad->id, "zmraz" => 1));
                $predpoklad->tlacitka = array
                (
                    new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_Predpoklad.detail", array("id" => $predpoklad->id, "zmraz" => 1))),
                    new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_Predpoklad.detail", array("id" => $predpoklad->id))),
                    new Stranka_Element_Tlacitko("Smazat", $this->cestaSem->generujUriDalsi("Stranka_Predpoklad.detail", array("id" => $predpoklad->id, "smaz" => 1))),
                );
                $predpoklad->sTypAkceFK = Data_Seznam_STypAkce::najdiPodleId($predpoklad->idSTypAkceFK)->nazev;
                $predpoklad->sTypAkcePredFK = Data_Seznam_STypAkce::najdiPodleId($predpoklad->idSTypAkcePredFK)->nazev;
                $predpoklad->sStavUcastnikAkcePredFK = Data_Seznam_SStavUcastnikAkce::najdiPodleId($predpoklad->idSStavUcastnikAkcePredFK)->text;
                $predpoklad->odeberVlastnost("idSTypAkceFK")->odeberVlastnost("idSTypAkcePredFK")->odeberVlastnost("idSStavUcastnikAkcePredFK");
                $predpoklad->pridejVlastnost("sTypAkceFK")->pridejVlastnost("sTypAkcePredFK")->pridejVlastnost("sStavUcastnikAkcePredFK");
            }
            
           return $data;
        }

        private function generujHlavickuTabulky()
        {
            $hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
            $hlavickaTabulky->pridejSloupec("id","ID", Data_Seznam_SAkcePredpoklad::ID);
            $hlavickaTabulky->pridejSloupec("text","Název", Data_Seznam_SAkcePredpoklad::TEXT);
            $hlavickaTabulky->pridejSloupec("fullText","Popis", Data_Seznam_SAkcePredpoklad::FULL_TEXT);
            $hlavickaTabulky->pridejSloupec("idSTypAkceFK","Typ akce", Data_Seznam_SAkcePredpoklad::ID_S_TYP_AKCE_FK, "Data_Seznam_STypAkce::vypisVse()", "nazev");
            $hlavickaTabulky->pridejSloupec("idSTypAkcePredFK","Typ akce před", Data_Seznam_SAkcePredpoklad::ID_S_TYP_AKCE_PRED_FK, "Data_Seznam_STypAkce::vypisVse()", "nazev");
            $hlavickaTabulky->pridejSloupec("idSStavUcastnikAkcePredFK","Stav účastníka před", Data_Seznam_SAkcePredpoklad::ID_S_STAV_UCASTNIK_AKCE_PRED_FK, "Data_Seznam_SStavUcastnikAkce::vypisVse()", "text");

            return $hlavickaTabulky;
        }

        private function tlacitka($tlacitkoNovy)
        {
            $tlacitka = array(
                new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
            );

            if($tlacitkoNovy)
                $tlacitka[] = new Stranka_Element_Tlacitko("Nový předpoklad", $this->cestaSem->generujUriDalsi("Stranka_Predpoklad.detail"));

            return $tlacitka;
        }

	
	

}