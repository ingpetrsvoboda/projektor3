<?php
class Stranka_ISCOJ extends Stranka implements Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	public function detail($parametry = null)
	{ 
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("iscoj", "post", $this->cestaSem->generujUri());
		$isco = Data_Seznam_SISCO::najdiPodleId($parametry["id"]);
		
		/* Defaultni stavy */
		if($isco)
		{
			$form->setDefaults(array
			(
				"isco_id" => $isco->id,
				"isco_kod" => $isco->kod,
				"isco_nazev" => $isco->nazev
			));
		}
		
		/* Vytvareni elementu */
		$form->addElement("hidden", "isco_id");
		$form->addElement("text", "isco_kod", "Kód ISCO");
		$form->addElement("text", "isco_nazev", "Název ISCO");

		/* Rozhodovani detail/uprav */
		if($parametry["zmraz"])
		{
			$form->freeze();
			$this->novaPromenna("nadpis", "Detail položky ISCO");
		}
		else
			$this->novaPromenna("hlaseni", "Položky v seznamu ISCO nelze upravovat");
	
			
		/* Zpracovani */
		if($form->validate())
		{
			$form->freeze();
			$data = $form->exportValues();

			$isco = new Data_Seznam_SISCO
			(
			$data["isco_kod"],
			$data["isco_nazev"]
			);
			
			if($isco->uloz())
                            $this->novaPromenna("ulozeno", true);
                        else
                            $this->novaPromenna("ulozeno_chyba", true);
		}		

		/* Generovani */
		return $this->vytvorStranku("detail", self::SABLONA_DETAIL, $parametry, $form->toHtml());
	}

	protected function detail°vzdy()
	{
            $isco = Data_Seznam_SISCO::najdiPodleId($this->parametry["id"]);
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
                    new Stranka_Element_Tlacitko("Zájemci vhodní na pozici", $this->cestaSem->generujUriDalsi("Stranka_Zajemci.vhodniNaPozici", array("iscoKod" => $isco->kod)))
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function detail°potomekNeni()
	{

	}

	protected function detail°potomek°Stranka_Zajemci°vhodniNaPozici(){}        
}