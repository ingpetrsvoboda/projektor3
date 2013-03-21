<?php
class Projektor_Stranka_PrezentaceJ_Menu_Detail extends Projektor_Stranka_Base implements Projektor_Stranka_Interface
{
	public function detail()
	{
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("prezentacej", "post", $this->uzel->formAction());
		$prezentacej = Projektor_Data_Seznam_SPrezentace::najdiPodleId($this->uzel->parametry["id"], TRUE);

		/* Mazani */
		if($this->uzel->parametry["smaz"])
		{
                    //znevalidní položku
                    if($prezentacej) $prezentacej->smaz();
                    $prezentacej = Projektor_Data_Seznam_SPrezentace::najdiPodleId($this->uzel->parametry["id"], TRUE);
		}
		/* Duplikace
                 * vytvoří duplikát a otevře ho k úpravě
                 */

		if($this->uzel->parametry["duplikuj"])
		{
                    //duplikace využává toho, že objekt bez id se uloží jako nový
                    if($prezentacej) {
                        $prezentacej->id = NULL;
                        $this->uzel->parametry["id"] = $prezentacej->uloz (); //vrací last inserted id
                    }
                    $prezentacej = Projektor_Data_Seznam_SPrezentace::najdiPodleId($this->uzel->parametry["id"], TRUE);
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

                    $prezentace = new Projektor_Data_Seznam_SPrezentace
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
//		return $this->vytvorStranku("detail", self::SABLONA_DETAIL, $parametry, $form->toHtml());
                $this->novaPromenna("formular", $form->toHtml());
	}

	protected function detail°vzdy()
	{
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Stranka_Element_Tlacitko("Zpět", $this->uzel->zpetUri()),
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function detail°potomekNeni()
	{
	}

}