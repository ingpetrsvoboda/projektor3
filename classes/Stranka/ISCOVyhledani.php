<?php
class Stranka_ISCOVyhledani extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";

        protected $hledanyText;
        
	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	/*
         *  ~~~~~~~~MAIN~~~~~~~~
         */
	public function main($parametry = null)
	{ 
                /* Vygenerovani vyhledavaciho formulare */
                $dalsi = $this->dalsi;
                $formAjax = print_r('
                    <form>
                    Sem pište ajaxem hledaný text: <input type="text" onkeyup="naseptavani(this.value)" size="40" />
                    </form>
                ', TRUE);           

                $vyhledavaciFormular = $this->hledani($dalsi->parametry['hledanyText']);
                return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, $formAjax, $vyhledavaciFormular->toHtml());
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("id", $this->nazev);                //pro javascript

                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Seznam vyhledaných ISCO");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
                    new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
		);
                $this->novaPromenna("tlacitka", $tlacitka);

            if ($this->hledanyText AND strlen($this->hledanyText)>2)
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);   
                $filtr = Data_Seznam_SISCO::NAZEV . " LIKE '%".$this->hledanyText."%'";
                $vyhledanaIsco = Data_Seznam_SISCO::vypisVse($filtr,  $this->parametry["razeniPodle"], $this->parametry["razeni"]);            
                if ($vyhledanaIsco)
                {
                    foreach($vyhledanaIsco as $jednoisco)
                    {
                        if (strlen($jednoisco->kod) == 5)
                        {
                            $jednoisco->tlacitka = array
                            (
                                // parament hledanyText se prostřednictvím parametrů Stranka_ISCOJ pouze předává a použije se ve stránce Stranka_ISCOVyhledani na předvyplněné textu do vyhledávacího formuláře    
                                new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_ISCOJ.detail", array("id" => $jednoisco->id, "hledanyText" => $this->hledanyText,  "zmraz" => 1))),
                            );
                        } else {
                            $jednoisco->tlacitka = array();
                        }
                        $this->pouzijHlavicku($jednoisco, $hlavickaTabulky);                        
                    }
                    $this->novaPromenna("seznam", $vyhledanaIsco);
                } else {
                    $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
            }
        }

	protected function main°potomekNeni() {}
        
        protected function main°potomek°Stranka_ISCOJ°detail()  {}
        
        private function hledani($vychoziHodnotaTextu="")
        {
            $form = new HTML_QuickForm("iscom", "post", $this->cestaSem->generujUri());
            $form->addElement("text", "hledanyText", "Hledany text");
            if ($vychoziHodnotaTextu) $form->getElement("hledanyText")->setValue($vychoziHodnotaTextu);

            $form->addElement("submit", "submitHledat", "Hledat");

            if($form->validate())
            {
//                $form->freeze();
                $data = $form->exportValues();
                if ($data["submitHledat"]) {
                    $this->hledanyText = $data["hledanyText"];  //protected proměnná
                }
            }

            return $form;
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