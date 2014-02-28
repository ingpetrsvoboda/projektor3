<?php
class Projektor_Controller_Page_Akce_Detail extends Projektor_Controller_Page_Detail
{
    const SABLONA = "detail.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_AkceItem";


        const ID_STAVU_ZRUSENA = 6; //SVOBODA pozor - ve skutečnosti může (a je) v tabulce být více stavů, kdy je akce zrušena - nutno změnit

	public function vychozi()
	{
		/* Konstrukce objektu */
		$form = new HTML_QuickForm("akcej", "post", $this->vertex->formAction());
		$akcej = new Projektor_Model_Auto_AkceItem($this->vertex->params["id"]);

		/* Mazani */
		if($this->vertex->params["smaz"])
		{
                    //Projektor_Model_Akce::smaz($akcej);
                    $a = new Projektor_Model_Auto_SStavAkceItem($id);
                    if($akcej) $akcej->zmenStav(new Projektor_Model_Auto_SStavAkceItem (self::ID_STAVU_ZRUSENA));
                    $akcej = new Projektor_Model_Auto_AkceItem($this->vertex->params["id"]);
		}

		/* Defaultni stavy */
		if($akcej)
		{
                    $datetimeZacatek = Projektor_Helper_DatumCas::zSQL($akcej->dbField°datum_zacatek);
                    $datetimeKonec = Projektor_Helper_DatumCas::zSQL($akcej->dbField°datum_konec);
                    $rokZacatek = $datetimeZacatek->dejRok();
                    $rokKonec = $datetimeKonec->dejRok();
                    $form->setDefaults(array
                    (
                        "id" => $akcej->id,
                        "dbField°nazev_hlavniho_objektu" => $akcej->dbField°nazev_hlavniho_objektu,
                        "dbField°datum_zacatek" => $datetimeZacatek->dejDatumProQuickForm(),
                        "dbField°datum_konec" => $datetimeKonec->dejDatumProQuickForm(),
                        "dbField°nazev" => $akcej->dbField°nazev,
                        "akcej_popis" => $akcej->dbField°popis,
                        "dbField°id_s_stav_akce_FK" => $akcej->dbField°id_s_stav_akce_FK,
                        "dbField°id_s_typ_akce_FK" => $akcej->dbField°id_s_typ_akce_FK
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
		$form->addElement("hidden", "id");
		$form->addElement("text", "dbField°nazev_hlavniho_objektu", "Název hlavního objektu");
		$form->addElement("date", "dbField°datum_zacatek", "Datum začátku akce", array("format" => "d.m.Y", "minYear" => $rokZacatek-5, "maxYear" => $rokZacatek+10));
		$form->addElement("date", "dbField°datum_konec", "Datum konce akce", array("format" => "d.m.Y", "minYear" => $rokKonec-5, "maxYear" => $rokKonec+10));
		$form->addElement("text", "dbField°nazev", "Název akce");
		$form->addElement("textarea", "akcej_popis", "Popis akce");

		/* akcej_idSStavAkceFK */
		$stavAkce = new Projektor_Controller_Page_Element_BlokRadioButton($form, new Projektor_Model_Auto_SStavAkceCollection(), "dbField°id_s_stav_akce_FK", "Stav akce");
		$stavAkce->nastavSloupce(array("text" => "Stav", "plnyText" => "Popis"));

		/* akcej_idSTypAkce */
		$typAkce = new Projektor_Controller_Page_Element_BlokRadioButton($form, new Projektor_Model_Auto_STypAkceCollection(), "dbField°id_s_typ_akce_FK", "Typ akce");
		$typAkce->nastavSloupce(array(
			"nazev" => "Název",
			"trvaniDni" => "Trvání dní",
			"hodinyDen" => "Hodiny/den",
			"minPocetUc" => "Minimálně účastníků",
			"maxPocetUc" => "Maximálně účasstníků"
		));


		$form->addElement("submit", "akcej_submit", "Ulozit");

		/* Vytvareni pravidel */
		$form->addRule("dbField°nazev_hlavniho_objektu", "Chybí název!", "required");
		$form->addRule("dbField°nazev", "Chybí název!", "required");
		$form->addRule("dbField°id_s_stav_akce_FK", "Vyberte typ akce!", "required");
		$form->addRule("dbField°id_s_typ_akce_FK", "Vyberte stav akce!", "required");

		/* Rozhodovani detail/uprav */
		if($this->vertex->params["zmraz"])
		{
                    $form->removeElement("akcej_submit");
                    $form->freeze();
                    $this->setViewContextValue("nadpis", "Detail akce");
		}
		else
                    if($akcej)
                        $this->setViewContextValue("nadpis", "Úprava akce");
                    else
                        $this->setViewContextValue("nadpis", "Nová akce");


		/* Zpracovani */
		if($form->validate())
		{
                    $form->removeElement("akcej_submit");
                    $form->freeze();
                    $data = $form->exportValues();


                    $akcej->dbField°nazev_hlavniho_objektu = $data["dbField°nazev_hlavniho_objektu"];
                    $akcej->dbField°datum_zacatek = Projektor_Helper_DatumCas::zQuickForm($data["dbField°datum_zacatek"])->dejDatumproSQL();
                    $akcej->dbField°datum_konec = Projektor_Helper_DatumCas::zQuickForm($data["dbField°datum_konec"])->dejDatumproSQL();
                    $akcej->dbField°nazev = $data["dbField°nazev"];
                    $akcej->dbField°popis = $data["akcej_popis"];
                    $akcej->dbField°id_s_stav_akce_FK = $data["dbField°id_s_stav_akce_FK"];
                    $akcej->dbField°id_s_typ_akce_FK = $data["dbField°id_s_typ_akce_FK"];
//                    $data["id"]    //id neukládám

                    if($akcej->uloz())
                        $this->setViewContextValue("ulozeno", true);
                    else
                        $this->setViewContextValue("ulozeno_chyba", true);
		}

		/* Generovani */
                $this->setViewContextValue("formular", $form->toHtml());
	}
}