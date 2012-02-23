 <?php
abstract class Stranka_FlatTableJ extends Stranka implements Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";

	public function detail($parametry = null)
	{

                /* Konstrukce objektu formulare a datoveho objektu */
		$form = new HTML_QuickForm("objekt", "post", $this->cestaSem->generujUri());
                if ($parametry["id"]) {
                    $dataObjekt = Data_Flat_FlatTable::najdiPodleId($this->nazev_flattable, $parametry["id"], FALSE, "", NULL, $this->vsechny_radky, $this->databaze);                    
                } else {
                    $dataObjekt = Data_Flat_FlatTable::najdiPodleId($this->nazev_flattable, NULL, FALSE, "", NULL, $this->vsechny_radky, $this->databaze);                                        
                }

                $elementy = $this->pripravElementyFormulareZFlatTableObjektu($dataObjekt);
                $elementy = $this->prepisTituilkyZPrezentace($elementy);   

		/* Defaultni stavy formulare */
                    $form->setDefaults($elementy["default"]);

		/* Vytvareni elementu formulare */
                //zde je možno přidat ručně elementy - mohou být prakticky libovolné (element primární klíč tabulky (id) musí být hidden nebo static  $form->addElement("hidden", "id");)
                // zde se přidávají automaticky generované elementy - tyto elementy odpovídají sloupců db tabulek s vlastnostmi
                $form->addElement("header", $parametry["textDoNadpisuStranky"]);
                foreach ($elementy["default"] as $key => $hodnotaVlastnosti) {
                //    if ($elementy["atributy"][$key]) {
                            $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key], $elementy["atributy"][$key]);                                
                //    } else {
                //        $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key]);
                //    }

                }
		/* Rozhodovani detail/uprav */
		if($parametry["zmraz"])
		{
                    //$form->removeElement("submit");
                    $form->freeze();
                    $this->novaPromenna("nadpis", "Detail údajů: ".$parametry["textDoNadpisuStranky"]);
		}
		else
                {
                    $form->addElement("submit", "submit", "Ulozit");
                    if($dataObjekt)
                        $this->novaPromenna("nadpis", "Úprava údajů: ".$parametry["textDoNadpisuStranky"]);
                    else
                        $this->novaPromenna("nadpis", "Nový");
                }
		/* Vytvareni pravidel */
//                    if (!array_key_exists("ucastnik_id", $data)) $ucastnik = new Data_Ucastnik(0, 0, $idSBehProjektuFK, $idCKancelarFK ); //tyto parametry musí být povinne vyplneny ve formulari
		//$form->addRule("ucastnik_jmeno", "Chybí jméno!", "required");
		//$form->addRule("ucastnik_prijmeni", "Chybí příjmení!", "required");

		/* Zpracovani - validace formuláře a případné uložení dat */
		if($form->validate())
		{
                    $form->removeElement("submit");
                    $form->freeze();
                    $data = $form->exportValues();
                    foreach ($data as $key => $value) {
                        if ($elementy["typ"][$key] == "date") {
                            $data[$key] = Data_Konverze_Datum::zQuickForm($data[$key])->dejDatumproSQL();
                        }
                    }
 
                    foreach ($data as $key => $value) {
                        $vlastnost = explode(self::SEPARATOR, $key, 3);
                        if (!$vlastnost[1]){  //pro ručně přidané elementy
                            $dataObjekt->$vlastnost[0] = $value;
                        } else {
                            $dataObjekt->$vlastnost[2] = $value;
                        }
                    }
                    if($dataObjekt->uloz())
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
            /*$this->promenne["ucastnik_detail"] = Data_Akce::najdiPodleId($this->parametry["id"]);
            $this->promenne["ucastnik_zpet"] = $this->cestaSem->generujUriZpet();*/
	}

}