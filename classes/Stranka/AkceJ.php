<?php
class Stranka_AkceJ extends Stranka implements Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";
        const ID_STAVU_ZRUSENA = 6; //SVOBODA pozor - ve skutečnosti může (a je) v tabulce být více stavů, kdy je akce zrušena - nutno změnit

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	public function detail($parametry = null)
	{ 
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("akcej", "post", $this->cestaSem->generujUri());
		$akcej = Data_Akce::najdiPodleId($parametry["id"]);

		/* Mazani */
		if($parametry["smaz"])
		{
                    //Data_Akce::smaz($akcej);
                    if($akcej) $akcej->zmenStav(Data_Seznam_SStavAkce::najdiPodleId(self::ID_STAVU_ZRUSENA));
                    $akcej = Data_Akce::najdiPodleId($parametry["id"]);
		}
		
		/* Defaultni stavy */
		if($akcej)
		{
                    $datetimeZacatek = Data_Konverze_Datum::zSQL($akcej->datumZacatek);
                    $datetimeKonec = Data_Konverze_Datum::zSQL($akcej->datumKonec);
                    $rokZacatek = $datetimeZacatek->dejRok();
                    $rokKonec = $datetimeKonec->dejRok();
                    $form->setDefaults(array
                    (
                        "akcej_id" => $akcej->id,
                        "akcej_nazev_hlavniho_objektu" => $akcej->nazevHlavnihoObjektu,
                        "akcej_datum_zacatek" => $datetimeZacatek->dejDatumProQuickForm(),
                        "akcej_datum_konec" => $datetimeKonec->dejDatumProQuickForm(),
                        "akcej_nazev" => $akcej->nazev,
                        "akcej_popis" => $akcej->popis,
                        "akcej_idSStavAkceFK" => $akcej->idSStavAkceFK,
                        "akcej_idSTypAkceFK" => $akcej->idSTypAkceFK
                    ));
		} else {
                    $rokZacatek = date("Y");
                    $rokKonec = date("Y");
                }
//		$options = array(
//'language' => 'en',
//'format' => 'Ymd',
//'minYear' => 2007,
//'maxYear' => date('Y') + 2,
//'addEmptyOption' => true
//);
		/* Vytvareni elementu */
		$form->addElement("hidden", "akcej_id");
		$form->addElement("text", "akcej_nazev_hlavniho_objektu", "Název hlavního objektu");
		$form->addElement("date", "akcej_datum_zacatek", "Datum začátku akce", array("format" => "d.m.Y", "minYear" => $rokZacatek-5, "maxYear" => $rokZacatek+10));
		$form->addElement("date", "akcej_datum_konec", "Datum konce akce", array("format" => "d.m.Y", "minYear" => $rokKonec-5, "maxYear" => $rokKonec+10));
		$form->addElement("text", "akcej_nazev", "Název akce");
		$form->addElement("textarea", "akcej_popis", "Popis akce");
		
		/* akcej_idSStavAkceFK */
		$stavAkce = new Stranka_Element_TabulkaElementu($form, "radio", Data_Seznam_SStavAkce::vypisVse(), "akcej_idSStavAkceFK", "Stav akce");
		$stavAkce->nastavSloupce(array("text" => "Stav", "plnyText" => "Popis"));
		
		/* akcej_idSTypAkce */
		$typAkce = new Stranka_Element_TabulkaElementu($form, "radio", Data_Seznam_STypAkce::vypisVse(), "akcej_idSTypAkceFK", "Typ akce");
		$typAkce->nastavSloupce(array(
			"nazev" => "Název", 
			"trvaniDni" => "Trvání dní", 
			"hodinyDen" => "Hodiny/den",
			"minPocetUc" => "Minimálně účastníků", 
			"maxPocetUc" => "Maximálně účasstníků"
		));
		
			
		$form->addElement("submit", "akcej_submit", "Ulozit");
		
		/* Vytvareni pravidel */
		$form->addRule("akcej_nazev_hlavniho_objektu", "Chybí název!", "required");
		$form->addRule("akcej_nazev", "Chybí název!", "required");
		$form->addRule("akcej_idSTypAkceFK", "Vyberte typ akce!", "required");
		$form->addRule("akcej_idSStavAkceFK", "Vyberte stav akce!", "required");

		/* Rozhodovani detail/uprav */
		if($parametry["zmraz"])
		{
                    $form->removeElement("akcej_submit");
                    $form->freeze();
                    $this->novaPromenna("nadpis", "Detail akce");
		}
		else
                    if($akcej)
                        $this->novaPromenna("nadpis", "Úprava akce");
                    else
                        $this->novaPromenna("nadpis", "Nová akce");
		
			
		/* Zpracovani */
		if($form->validate())
		{
                    $form->removeElement("akcej_submit");
                    $form->freeze();
                    $data = $form->exportValues();

                    $akce = new Data_Akce
                    (
                    $data["akcej_nazev_hlavniho_objektu"],
                    Data_Konverze_Datum::zQuickForm($data["akcej_datum_zacatek"])->dejDatumproSQL(),
                    Data_Konverze_Datum::zQuickForm($data["akcej_datum_konec"])->dejDatumproSQL(),
                    $data["akcej_nazev"],
                    $data["akcej_popis"],
                    $data["akcej_idSStavAkceFK"],
                    $data["akcej_idSTypAkceFK"],
                    $data["akcej_id"]
                    );

                    if($akce->uloz())
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
            /*$this->promenne["akcej_detail"] = Data_Akce::najdiPodleId($this->parametry["id"]);
            $this->promenne["akcej_zpet"] = $this->cestaSem->generujUriZpet();*/
	}

}