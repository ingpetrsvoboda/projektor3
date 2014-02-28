<?php
class Projektor_Controller_Page_ISCO_Seznam_Vyhledani extends Projektor_Controller_Page_ISCO_Seznam
{
//        protected $hledanyText;

	protected function potomekNeni()
        {
            /* Vygenerovani vyhledavaciho formulare */
            $vyhledavaciFormular = $this->hledani($this->vertex->params['hledanyText']);
            $this->setViewContextValue("formular", $vyhledavaciFormular->toHtml());
            /* Nadpis stranky */
            $this->setViewContextValue("nadpis", "Seznam vyhledaných ISCO");

            if ($this->vertex->params['hledanyText'] AND strlen($this->vertex->params['hledanyText'])>2)
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->setViewContextValue("hlavickaTabulky", $hlavickaTabulky);
                $filtr = Projektor_Model_Seznam_SISCO::NAZEV . " LIKE '%".$this->vertex->params['hledanyText']."%'";
                $vyhledanaIsco = Projektor_Model_Seznam_SISCO::vypisVse($filtr,  $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                if ($vyhledanaIsco)
                {
                    foreach($vyhledanaIsco as $jednoisco)
                    {
                        $this->dejSeznamItemZHlavicky($jednoisco, $hlavickaTabulky);
                        if (strlen($jednoisco->kod) == 5)
                        {
                            $this->generujTlacitkaProPolozku($jednoisco);

                        } else {
                            $this->generujTlacitkaProSeznam($jednoisco);
                        }
                    }
                    $this->setViewContextValue("seznam", $vyhledanaIsco);
                } else {
                    $this->setViewContextValue("zprava", "Nic nenalezeno!");
                }
            }
        }

        private function hledani($vychoziHodnotaTextu="")
        {
            $form = new HTML_QuickForm("hledaniisco", "post", $this->vertex->formAction());
            // javascript nastavuje focus na příslučný input a následně (onMouse je nejlepší co jsem našel) kurzor na konec textu
            //nastavování kurzoru na konec se dělá tak, že se text smaže a následně znovu napíše
            $form->addElement("text", "hledanyText", "Hledany text",
                    array('id' => "hledanyText", 'onkeyup' => "self.document.forms.iscom.submit()",
                          "onMouseover" => "self.document.forms.iscom.hledanyText.focus(); var val=self.document.forms.iscom.hledanyText.value; self.document.forms.iscom.hledanyText.value = '';self.document.forms.iscom.hledanyText.value=val"));
            if ($vychoziHodnotaTextu) $form->getElement("hledanyText")->setValue($vychoziHodnotaTextu);
//self.document.forms.form1.submit()
            $form->addElement("submit", "submitHledat", "Hledat");

            if($form->validate())
            {
//                $form->freeze();
                $data = $form->exportValues();
                if ($data["submitHledat"]) {
//                    $this->hledanyText = $data["hledanyText"];  //protected proměnná
                    $this->vertex->params['hledanyText'] = $data["hledanyText"];
                } else {
//                    $this->hledanyText = $data["hledanyText"];  //protected proměnná

                }
            }

            return $form;
        }
}