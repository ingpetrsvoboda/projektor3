<?php
class Projektor_Controller_Page_TypAkce_Detail extends Projektor_Controller_Page_Detail
{
    const SABLONA = "detail.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_STypAkceItem";


//	public function detail($parametry = null)
//	{
//		/* Konstrukce objektu */
//		$form = new HTML_QuickForm("typakce", "post", $this->cesta->formAction());
//		$typakce = Projektor_Model_Seznam_STypAkce::najdiPodleId($parametry["id"]);
//
//		/* Mazani */
//		if($parametry["smaz"])
//		{
//			Projektor_Model_Seznam_STypAkce::smaz($typakce);
//                        header("Location: " . $this->cesta->zpetUri());
//		}
//
//		/* Defaultni stavy */
//		if($typakce)
//		{
//			$form->setDefaults(array
//			(
//				"typakce_id" => $typakce->id,
//				"typakce_nazev" => $typakce->nazev,
//				"typakce_trvaniDni" => $typakce->trvaniDni,
//				"typakce_hodinyDen" => $typakce->hodinyDen,
//				"typakce_minPocetUc" => $typakce->minPocetUc,
//				"typakce_maxPocetUc" => $typakce->maxPocetUc,
//				"typakce_valid" => $typakce->valid
//
//			));
//		}
//
//		/* Vytvareni elementu */
//		$form->addElement("hidden", "typakce_id");
//		$form->addElement("text", "typakce_nazev", "Název typu akce");
//		$form->addElement("text", "typakce_trvaniDni", "Trvání akce (dny)");
//		$form->addElement("text", "typakce_hodinyDen", "Hodiny/den");
//		$form->addElement("text", "typakce_minPocetUc", "Minimální počet účastníků");
//		$form->addElement("text", "typakce_maxPocetUc", "Maximální počet účastníků");
//		$form->addElement("text", "typakce_valid", "valid");
//
//		$form->addElement("submit", "typakce_submit", "Ulozit");
//
//		/* Vytvareni pravidel */
//		$form->addRule("typakce_nazev", "Chybí název!", "required");
//                $form->addRule("typakce_trvaniDni", "Chybí trvání dní!", "required");
//                $form->addRule("typakce_hodinyDen", "Chybí hodiny za den!", "required");
//                $form->addRule("typakce_minPocetUc", "Chybí minimální počet účastníků!", "required");
//                $form->addRule("typakce_maxPocetUc", "Chybí maximální počet účastníků!", "required");
//
//		/* Rozhodovani detail/uprav */
//		if($parametry["zmraz"])
//		{
//			$form->removeElement("typakce_submit");
//			$form->freeze();
//			$this->novaPromenna("nadpis", "Detail typu akce");
//		}
//		else
//			if($typakce)
//				$this->novaPromenna("nadpis", "Úprava typu akce");
//			else
//				$this->novaPromenna("nadpis", "Nový typ akce");
//
//
//		/* Zpracovani */
//		if($form->validate())
//		{
//			$form->removeElement("typakce_submit");
//			$form->freeze();
//			$data = $form->exportValues();
//
//			$typakce = new Projektor_Model_Seznam_STypAkce
//			(
//                            $data["typakce_nazev"],
//                            $data["typakce_trvaniDni"],
//                            $data["typakce_hodinyDen"],
//                            $data["typakce_minPocetUc"],
//                            $data["typakce_maxPocetUc"],
//                            $data["typakce_valid"],
//                            $data["typakce_id"]
//			);
//
//			if($typakce->uloz())
//                            $this->novaPromenna("ulozeno", true);
//                        else
//                            $this->novaPromenna("ulozeno_chyba", true);
//		}
//
//		/* Generovani */
//		return $this->vytvorStranku("detail", self::SABLONA_DETAIL, $parametry, $form->toHtml());
//	}
//
//	protected function detail°vzdy()
//	{
//            /* Ovladaci tlacitka stranky */
//            $tlacitka = array
//            (
//                    new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->cesta->zpetUri()),
//            );
//            $this->novaPromenna("tlacitka", $tlacitka);
//	}
//
//	protected function detail°potomekNeni()
//	{
//		/*$this->promenne["typakce_detail"] = Projektor_Model_Seznam_STypAkce::najdiPodleId($this->parametry["id"]);
//		$this->promenne["typakce_zpet"] = $this->cestaSem->generujUriZpet();*/
//	}

}