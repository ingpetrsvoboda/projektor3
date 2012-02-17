 <?php
abstract class Stranka_HlavniObjekt extends Stranka implements Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";

	protected function detail($nazevHlavnihoObjektu = null, $parametry = null)
	{
                //TODO: kontrola $nazevHlavnihoObjektu a korektní return
            
                /* Konstrukce objektu formulare a datoveho objektu */
		$form = new HTML_QuickForm("hlavniobjekt", "post", $this->cestaSem->generujUri());
                $tridaHlavnihoObjektu = "Data_".$nazevHlavnihoObjektu;
                if ($parametry["id"]) {
                    $hlavniObjekt = $tridaHlavnihoObjektu::najdiPodleId($parametry["id"]);                    
                } else {
                    $kontext = App_Kontext::getUserKontext();
                    if($kontext->projekt->id AND $kontext->beh->id AND $kontext->kancelar->id)
                    {
                        $hlavniObjekt = new $tridaHlavnihoObjektu(NULL, NULL, $kontext->projekt->id, $kontext->beh->id, $kontext->kancelar->id);                                                                
                    } else {
                        $this->novaPromenna("hlaseni", "Pro uložení údajů ".$nazevHlavnihoObjektu." je nezbytné mít zvolen projekt, běh i kancelář.");  
                        return;
                    }                        
                }

                if (array_key_exists("objektVlastnost", $parametry)){
                    $objektVlastnost = $hlavniObjekt->$parametry["objektVlastnost"];
                }

                if($objektVlastnost)
		{ 
                    $elementy = $this->pripravElementyFormulareZFlatTableObjektu($objektVlastnost, $nazevHlavnihoObjektu);
                    $elementy = $this->prepisTiuilkyZPrezentace($elementy, $nazevHlavnihoObjektu);                   
		}

		/* Defaultni stavy formulare */

// TODO: toto nastavení default stavu hlavního objektu a přidání elementů musíš vyhodit do potomkovské třídy
                    //hodnoty pro ručně přidané elementy (níže)
                    //zde jsou přidány hodnoty vlastností hlavního objektu, hodnoty vlastností objektVlastnost byly přidány automaticky
                    $elementy["default"]["id"] = $hlavniObjekt->id;
                    $elementy["default"]["identifikator"] = $hlavniObjekt->identifikator;
                    $elementy["default"]["projekt"] = $hlavniObjekt->projektKod;
                    $elementy["default"]["turnusText"] = $hlavniObjekt->turnusText;
                    $elementy["default"]["kancelarText"] = $hlavniObjekt->kancelarText;
                    $elementy["default"]["jmeno"] = $hlavniObjekt->smlouva->jmeno;
                    $elementy["default"]["prijmeni"] = $hlavniObjekt->smlouva->prijmeni;

                    $form->setDefaults($elementy["default"]);

		/* Vytvareni elementu formulare */
                // element ucastnik_id musí být hidden nebo static
                    //zde jsou přidány ručně elementy - mohou být prakticky libovolné
                    //přidány elementy pro zobrazení vlastností hlavního objektu
                    $form->addElement("static", "id");
                    $form->addElement("static", "cisloObjektu");
                    $form->addElement("static", "identifikatorObjektu", "Identifikátor");
                    $form->addElement("static", "projekt", "Projekt");
                    $form->addElement("static", "turnusText", "Turnus");
                    $form->addElement("static", "kancelarText", "Kancelář");
                    $form->addElement("static", "jmeno", "Jméno");
                    $form->addElement("static", "prijmeni", "Příjmení");
                    // zde se přidávají automaticky generované elementy - tyto elementy odpovídají sloupců db tabulek s vlastnostmi
                    if ($objektVlastnost) {
                        $form->addElement("header", $parametry["textDoNadpisuStranky"]);
                        foreach ($elementy["default"] as $key => $jmenoVlastnosti) {
                        //    if ($elementy["atributy"][$key]) {
                                $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key], $elementy["atributy"][$key]);
                        //    } else {
                        //        $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key]);
                        //    }
                            
                        }
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
                    if($parametry["id"]) 
                    {
                        $form->addElement("submit", "submit", "Ulozit");
                        $this->novaPromenna("nadpis", "Úprava údajů: ".$parametry["textDoNadpisuStranky"]);                        
                    }
                    else
                        $this->novaPromenna("nadpis", "Nový".$parametry["textDoNadpisuStranky"]);
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
 
                    // hodnoty v elementech typu "static" se metodou exportValues neexportují, ostatní hodnoty (včetně typu "hidden") ano
                    foreach ($data as $key => $value) {
                        $vlastnost = explode(self::SEPARATOR, $key, 2);
                        if (!$vlastnost[1]){
                            $hlavniObjekt->$vlastnost[0] = $value;
                        } else {
                            $hlavniObjekt->$vlastnost[0]->$vlastnost[1] = $value;
                        }
                    }
                    if($hlavniObjekt->uloz($tridaHlavnihoObjektu::TABULKA))
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