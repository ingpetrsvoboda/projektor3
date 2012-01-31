<?php
class Stranka_PrezentaceM extends Stranka implements Stranka_Interface
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
            $filtrovaciFormular = $this->filtrovani("prezentacem", $hlavickaTabulky);
                return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());

	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Seznam parametrů pro prezentaci ve formulářích");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nový parametr pro prezentaci ve formuláři", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail")),

		);
                $this->novaPromenna("tlacitka", $tlacitka);


	}

	protected function main°potomekNeni()
	{
                        
            Data_Seznam_SPrezentace::nactiNazvy(array("Ucastnik", "Zajemce"));
            $seznam = Data_Seznam_SPrezentace::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"], TRUE);
            $this->generujSeznamSTlacitky($seznam);
	}

	protected function main°potomek°Stranka_PrezentaceJ°detail()
	{ 
		$this->generujPolozkuSTlacitky("id");
	}
	
//        protected function filtrovani()
//        {
//            $form = new HTML_QuickForm("prezentacem", "post", $this->cestaSem->generujUri());
//
//            $hlavickaTabulky = $this->generujHlavickuTabulky();
//            foreach ($hlavickaTabulky->sloupce as $sloupec) {
//                if ($sloupec->nazevSloupceDb)
//                {
//                    $form->addElement("text", $sloupec->nazevSloupceDb, $sloupec->popisek);
//                }
//            }            
//
//            $form->addElement("submit", "submitFiltrovat", "Filtrovat");
//            $form->addElement("submit", "submitNefiltrovat", "Nefiltrovat");
//
//            $this->filtr = new Stranka_Element_Filtr();
//            if($form->validate())
//            {
//		$data = $form->exportValues();
//                if ($data["submitFiltrovat"]) {
//                        unset($data["submitFiltrovat"]);
//                        unset($data["submitNefiltrovat"]);
//                        $this->filtr = Stranka_Element_Filtr::like($data);
//                    } else {
//                        unset($data["submitFiltrovat"]);
//                        unset($data["submitNefiltrovat"]);
//                        $this->filtr = Stranka_Element_Filtr::like();
//                    }
//            }
//
//            return $form;
//        }
//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------        
	private function generujPolozkuSTlacitky($nazevID)
	{
            if($this->dalsi->parametry[$nazevID])
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky); 
                    
                $polozka = Data_Seznam_SPrezentace::najdiPodleId($this->dalsi->parametry[$nazevID], TRUE);
                if ($polozka)
                {
                    $polozka->tlacitka = array
                    (
				new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail", array("id" => $polozka->id, "zmraz" => 1))),
				new Stranka_Element_Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail", array("id" => $polozka->id))),
                    );
                    $polozka->odeberVsechnyVlastnosti();
                    foreach ($hlavickaTabulky->sloupce as $sloupec) {
                        $polozka->pridejVlastnost($sloupec->nazevVlastnosti);
                    }
                    $this->novaPromenna("polozka", $polozka);
                }
            }
	}
  
        private function generujSeznamSTlacitky($akcem)
        {
            Data_Seznam_SPrezentace::nactiNazvy(array("Ucastnik", "Zajemce"));
            $seznam = Data_Seznam_SPrezentace::vypisVse($this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"], TRUE);
            if ($seznam) {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky); 
                foreach($seznam as $polozka)
		{
                    $polozka->odkaz = $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail", array("id" => $polozka->id, "zmraz" => 1));
                    $polozka->tlacitka = array
                    (
                            new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail", array("id" => $polozka->id, "zmraz" => 1))),
                            new Stranka_Element_Tlacitko("Uprav", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail", array("id" => $polozka->id))),
                            new Stranka_Element_Tlacitko("Duplikuj", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceJ.detail", array("id" => $polozka->id, "duplikuj" => 1))),

                    );
                    $polozka->odeberVsechnyVlastnosti();
                    foreach ($hlavickaTabulky->sloupce as $sloupec) {
                        $polozka->pridejVlastnost($sloupec->nazevVlastnosti);
                    }                    
                    
                }

                $this->novaPromenna("seznam", $seznam);
            } else {
            $this->novaPromenna("zprava", "Nic nenalezeno!");
            }
       }	

        private function generujHlavickuTabulky() 
        {
		/* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
		$hlavickaTabulky->pridejSloupec("id","ID", Data_Seznam_SPrezentace::ID);
		$hlavickaTabulky->pridejSloupec("hlavniObjekt","Hlavní objekt", Data_Seznam_SPrezentace::HLAVNI_OBJEKT);
		$hlavickaTabulky->pridejSloupec("objektVlastnost","Objekt vlastnost", Data_Seznam_SPrezentace::OBJEKT_VLASTNOST);
		$hlavickaTabulky->pridejSloupec("nazevSloupce","Název sloupce", Data_Seznam_SPrezentace::NAZEV_SLOUPCE);
		$hlavickaTabulky->pridejSloupec("titulek","Titulek", Data_Seznam_SPrezentace::TITULEK);
		$hlavickaTabulky->pridejSloupec("zobrazovat","Zobrazovat", Data_Seznam_SPrezentace::ZOBRAZOVAT);
		$hlavickaTabulky->pridejSloupec("valid","Validní", Data_Seznam_SPrezentace::VALID);
                return $hlavickaTabulky;
        }

}