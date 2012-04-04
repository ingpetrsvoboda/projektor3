<?php
class Stranka_Predpoklad extends Stranka implements Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	public function detail($parametry = null)
	{ 
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("predpoklad", "post", $this->cestaSem->generujUri());
		$predpoklad = Data_Seznam_SAkcePredpoklad::najdiPodleId($parametry["id"]);

		/* Mazani */
		if($parametry["smaz"])
		{
			Data_Seznam_SAkcePredpoklad::smaz($predpoklad);
                        header("Location: " . $this->cestaSem->generujUriZpet());
		}
		
		/* Defaultni stavy */
		if($predpoklad)
		{
			$form->setDefaults(array
			(
				"predpoklad_id" => $predpoklad->id,
				"predpoklad_text" => $predpoklad->text,
				"predpoklad_fullText" => $predpoklad->fullText,
				"predpoklad_idSTypAkceFK" => $predpoklad->idSTypAkceFK,
				"predpoklad_idSTypAkcePredFK" => $predpoklad->idSTypAkcePredFK,
				"predpoklad_idSStavUcastnikAkcePredFK" => $predpoklad->idSStavUcastnikAkcePredFK,
                                "predpoklad_valid" => $predpoklad->valid
			));
		}
		
		/* Vytvareni elementu */
		$form->addElement("hidden", "predpoklad_id");
		$form->addElement("text", "predpoklad_text", "Název");
		$form->addElement("textarea", "predpoklad_fullText", "Popis");
		
		/* predpoklad_idSTypAkce */
		$typAkce = new Stranka_Element_TabulkaElementu($form, "radio", Data_Seznam_STypAkce::vypisVse(), "predpoklad_idSTypAkceFK", "Typ akce");
		$typAkce->nastavSloupce(array(
			"nazev" => "Název", 
			"trvaniDni" => "Trvání dní", 
			"hodinyDen" => "Hodiny/den",
			"minPocetUc" => "Minimálně účastníků", 
			"maxPocetUc" => "Maximálně účastníků"
		));
		
		/* predpoklad_idSTypAkce */
		$typAkcePred = new Stranka_Element_TabulkaElementu($form, "radio", Data_Seznam_STypAkce::vypisVse(), "predpoklad_idSTypAkcePredFK", "Typ akce před");
		$typAkcePred->nastavSloupce(array(
			"nazev" => "Název", 
			"trvaniDni" => "Trvání dní", 
			"hodinyDen" => "Hodiny/den",
			"minPocetUc" => "Minimálně účastníků", 
			"maxPocetUc" => "Maximálně účastníků"
		));
		
		/* predpoklad_idSStavUcastnikAkcePredFK */                       
		$stavUcastnikAkcePred = new Stranka_Element_TabulkaElementu($form, "radio", Data_Seznam_SStavUcastnikAkce::vypisVse(), "predpoklad_idSStavUcastnikAkcePredFK", "Stav účastník-akce před");
		$stavUcastnikAkcePred->nastavSloupce(array(
			"text" => "Název", 
			"plnyText" => "Popis" 
		));
		$form->addElement("text", "predpoklad_valid", "valid");
			
		$form->addElement("submit", "predpoklad_submit", "Ulozit");
		
		/* Vytvareni pravidel */
		$form->addRule("predpoklad_text", "Chybí název!", "required");
		$form->addRule("predpoklad_idSTypAkceFK", "Vyberte typ akce!", "required");
		$form->addRule("predpoklad_idSTypAkcePredFK", "Vyberte typ akce před!", "required");
		$form->addRule("predpoklad_idSStavUcastnikAkcePredFK", "Vyberte stav účastník-akce před!", "required");

		/* Rozhodovani detail/uprav */
		if($parametry["zmraz"])
		{
			$form->removeElement("predpoklad_submit");
			$form->freeze();
			$this->novaPromenna("nadpis", "Detail předpokladu");
		}
		else
			if($predpoklad)
                            $this->novaPromenna("nadpis", "Úprava předpokladu");
                        else
                            $this->novaPromenna("nadpis", "Nový předpoklad");
		
			
		/* Zpracovani */
		if($form->validate())
		{
			$form->removeElement("predpoklad_submit");
			$form->freeze();
			$data = $form->exportValues();

			$predpoklad = new Data_Seznam_SAkcePredpoklad
			(
			$data["predpoklad_text"],
			$data["predpoklad_fullText"],
			$data["predpoklad_idSTypAkceFK"],
			$data["predpoklad_idSTypAkcePredFK"],
			$data["predpoklad_idSStavUcastnikAkcePredFK"],
			$data["predpoklad_valid"],
			$data["predpoklad_id"]  
			);
			            
			if($predpoklad->uloz())
                            $this->novaPromenna("ulozeno", true);
                        else
                            $this->novaPromenna("ulozeno_chyba", true);
		}		
		
		/* Generovani */
		return $this->vytvorStranku("detail", self::SABLONA_DETAIL, $parametry, $form->toHtml());
	}

	protected function detail°vzdy()
	{
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function detail°potomekNeni()
	{
		/*$this->promenne["predpoklad_detail"] = Data_Seznam_SAkcePredpoklad::najdiPodleId($this->parametry["id"]);
		$this->promenne["predpoklad_zpet"] = $this->cestaSem->generujUriZpet();*/
	}

}