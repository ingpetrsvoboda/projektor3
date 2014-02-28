<?php
class Projektor_Controller_Page_PrezentaceJ_Menu_Detail extends Projektor_Controller_Page_AbstractPage implements Projektor_Controller_Page_Interface
{
	public function detail()
	{
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("prezentacej", "post", $this->vertex->formAction());
		$prezentacej = Projektor_Model_Seznam_SPrezentace::najdiPodleId($this->vertex->params["id"], TRUE);

		/* Mazani */
		if($this->vertex->params["smaz"])
		{
                    //znevalidní položku
                    if($prezentacej) $prezentacej->smaz();
                    $prezentacej = Projektor_Model_Seznam_SPrezentace::najdiPodleId($this->vertex->params["id"], TRUE);
		}
		/* Duplikace
                 * vytvoří duplikát a otevře ho k úpravě
                 */

		if($this->vertex->params["duplikuj"])
		{
                    //duplikace využává toho, že objekt bez id se uloží jako nový
                    if($prezentacej) {
                        $prezentacej->id = NULL;
                        $this->vertex->params["id"] = $prezentacej->uloz (); //vrací last inserted id
                    }
                    $prezentacej = Projektor_Model_Seznam_SPrezentace::najdiPodleId($this->vertex->params["id"], TRUE);
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
                    $this->setViewContextValue("nadpis", "Detail parametrů pro prezentaci ve formuláři");
		}
		else
                    if($prezentacej)
                        if ($parametry["duplikuj"]) {
                        $this->setViewContextValue("nadpis", "Úprava duplikátu parametrů pro prezentaci ve formuláři");
                        } else {
                        $this->setViewContextValue("nadpis", "Úprava parametrů pro prezentaci ve formuláři");
                        }
                    else
                        $this->setViewContextValue("nadpis", "Nový parametr pro prezentaci ve formuláři");


		/* Zpracovani */
		if($form->validate())
		{
                    $form->removeElement("prezentacej_submit");
                    $form->freeze();
                    $data = $form->exportValues();

                    $prezentace = new Projektor_Model_Seznam_SPrezentace
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
                        $this->setViewContextValue("ulozeno", true);
                    else
                        $this->setViewContextValue("ulozeno_chyba", true);
		}

		/* Generovani */
//		return $this->vytvorStranku("detail", self::SABLONA_DETAIL, $parametry, $form->toHtml());
                $this->setViewContextValue("formular", $form->toHtml());
	}

	protected function detail°vzdy()
	{
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->vertex->backUri()),
            );
            $this->setViewContextValue("tlacitka", $tlacitka);
	}

	protected function detail°potomekNeni()
	{
	}

}