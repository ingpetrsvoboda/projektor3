<?php
class Stranka_PrezentaceJ extends Stranka implements Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	public function detail($parametry = null)
	{ 
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("prezentacej", "post", $this->cestaSem->generujUri());
		$prezentacej = Data_Seznam_SPrezentace::najdiPodleId($parametry["id"], TRUE);

		/* Mazani */
		if($parametry["smaz"])
		{
                    //znevalidní položku
                    if($prezentacej) $prezentacej->smaz();
                    $prezentacej = Data_Seznam_SPrezentace::najdiPodleId($parametry["id"], TRUE);
		}
		/* Duplikace
                 * vytvoří duplikát a otevře ho k úpravě
                 */
                
		if($parametry["duplikuj"])
		{
                    //duplikace využává toho, že objekt bez id se uloží jako nový
                    if($prezentacej) {
                        $prezentacej->id = NULL;
                        $parametry["id"] = $prezentacej->uloz (); //vrací last inserted id
                    }
                    $prezentacej = Data_Seznam_SPrezentace::najdiPodleId($parametry["id"], TRUE);
		}
                
		/* Defaultni stavy */
		if($prezentacej)
		{
			$form->setDefaults(array
			(
				"prezentacej_id" => $prezentacej->id,
				"prezentacej_hlavniObjekt" => $prezentacej->hlavniObjekt,
				"prezentacej_objektVlastnost" => $prezentacej->objektVlastnost,
				"prezentacej_nazevSloupce" => $prezentacej->nazevSloupce,
				"prezentacej_titulek" => $prezentacej->titulek,
				"prezentacej_zobrazovat" => $prezentacej->zobrazovat,
				"prezentacej_valid" => $prezentacej->valid
			));
		}
		
		/* Vytvareni elementu */
		$form->addElement("hidden", "prezentacej_id");
		$form->addElement("text", "prezentacej_hlavniObjekt", "Název hlavního objektu");
		$form->addElement("text", "prezentacej_objektVlastnost", "Název objektu-vlastnosti");
		$form->addElement("text", "prezentacej_nazevSloupce", "Název sloupce");
		$form->addElement("text", "prezentacej_titulek", "Titulek");
		$form->addElement("text", "prezentacej_zobrazovat", "Zobrazovat");
		$form->addElement("text", "prezentacej_valid", "valid");		
			
		$form->addElement("submit", "prezentacej_submit", "Uložit");
		
		/* Vytvareni pravidel */
		$form->addRule("prezentacej_hlavniObjekt", "Chybí název!", "required");
		$form->addRule("prezentacej_objektVlastnost", "Chybí název!", "required");
		$form->addRule("prezentacej_nazevSloupce", "Chybí název!", "required");

		/* Rozhodovani detail/uprav */
		if($parametry["zmraz"])
		{
                    $form->removeElement("prezentacej_submit");
                    $form->freeze();
                    $this->novaPromenna("nadpis", "Detail parametrů pro prezentaci ve formuláři");
		}
		else
                    if($prezentacej)
                        if ($parametry["duplikuj"]) {
                        $this->novaPromenna("nadpis", "Úprava duplikátu parametrů pro prezentaci ve formuláři");                            
                        } else {
                        $this->novaPromenna("nadpis", "Úprava parametrů pro prezentaci ve formuláři");                            
                        }
                    else
                        $this->novaPromenna("nadpis", "Nový parametr pro prezentaci ve formuláři");
		
			
		/* Zpracovani */
		if($form->validate())
		{
                    $form->removeElement("prezentacej_submit");
                    $form->freeze();
                    $data = $form->exportValues();

                    $prezentace = new Data_Seznam_SPrezentace
                    (
                    $data["prezentacej_hlavniObjekt"],
                    $data["prezentacej_objektVlastnost"],
                    $data["prezentacej_nazevSloupce"],
                    $data["prezentacej_titulek"],
                    $data["prezentacej_zobrazovat"],
                    $data["prezentacej_valid"],
                    $data["prezentacej_id"]
                    );

                    if($prezentace->uloz())
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
	}

}