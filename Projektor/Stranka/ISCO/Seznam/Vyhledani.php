<?php
class Projektor_Stranka_ISCO_Seznam_Vyhledani extends Projektor_Stranka_ISCO_Seznam
{
//        protected $hledanyText;

	protected function potomekNeni()
        {
            /* Vygenerovani vyhledavaciho formulare */
            $vyhledavaciFormular = $this->hledani($this->uzel->parametry['hledanyText']);
            $this->novaPromenna("formular", $vyhledavaciFormular->toHtml());
            /* Nadpis stranky */
            $this->novaPromenna("nadpis", "Seznam vyhledaných ISCO");

            if ($this->uzel->parametry['hledanyText'] AND strlen($this->uzel->parametry['hledanyText'])>2)
            {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
                $filtr = Projektor_Data_Seznam_SISCO::NAZEV . " LIKE '%".$this->uzel->parametry['hledanyText']."%'";
                $vyhledanaIsco = Projektor_Data_Seznam_SISCO::vypisVse($filtr,  $this->parametry["razeniPodle"], $this->parametry["razeni"]);
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
                    $this->novaPromenna("seznam", $vyhledanaIsco);
                } else {
                    $this->novaPromenna("zprava", "Nic nenalezeno!");
                }
            }
        }

        private function hledani($vychoziHodnotaTextu="")
        {
            $form = new HTML_QuickForm("hledaniisco", "post", $this->uzel->formAction());
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
                    $this->uzel->parametry['hledanyText'] = $data["hledanyText"];
                } else {
//                    $this->hledanyText = $data["hledanyText"];  //protected proměnná

                }
            }

            return $form;
        }
}