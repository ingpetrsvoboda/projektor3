<?php
class Projektor_Controller_Page_ISCO_Detail extends Projektor_Controller_Page_Detail
{
    const TYP_STRANKY = Projektor_Controller_Page_Generator::TYP_DETAIL;
    const SABLONA = "detail.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_SISCOItem";

	public function vychozi()
	{
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("iscoj", "post", $this->vertex->formAction());
		$isco = Projektor_Model_Seznam_SISCO::najdiPodleId($this->vertex->params["id"]);

		/* Defaultni stavy */
		if($isco)
		{
			$form->setDefaults(array
			(
				"isco_id" => $isco->id,
				"isco_kod" => $isco->kod,
				"isco_nazev" => $isco->controllerName
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
			$this->setViewContextValue("nadpis", "Detail položky ISCO");
		}
		else
			$this->setViewContextValue("hlaseni", "Položky v seznamu ISCO nelze upravovat");


		/* Zpracovani */
		if($form->validate())
		{
			$form->freeze();
			$data = $form->exportValues();

			$isco = new Projektor_Model_Seznam_SISCO
			(
			$data["isco_kod"],
			$data["isco_nazev"]
			);

			if($isco->uloz())
                            $this->setViewContextValue("ulozeno", true);
                        else
                            $this->setViewContextValue("ulozeno_chyba", true);
		}

		/* Generovani */
                $this->setViewContextValue("formular", $form->toHtml());
	}

	protected function vzdy()
	{
            parent::vzdy();
            $isco = Projektor_Model_Seznam_SISCO::najdiPodleId($this->vertex->params["id"]);
            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                    new Projektor_Controller_Page_Element_Tlacitko("Zájemci vhodní na pozici", $this->vertex->childUri("Projektor_Controller_Page_Zajemci_VhodniNaPozici", array("iscoKod" => $isco->kod)))
            );
            $this->setViewContextValue("tlacitka", $tlacitka);
	}
}