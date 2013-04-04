 <?php
class Projektor_Stranka_Ucastnik extends Projektor_Stranka_Base implements Projektor_Stranka_Interface
{
	const SABLONA_DETAIL = "detail.xhtml";

        const SEPARATOR = "_X_";
        const MAX_POCET_ZNAKU_TYPU_TEXT = 48; //max. počet znaků, pro který se při automatickém nastavení typu elementů nastaví "text", pro větší "textarea"
        const MAX_SIRKA_TYPU_TEXT = 68;
        const POCET_SLOUPCU_TYPU_TEXTAREA = 48;
        const MAX_POCET_RADKU_TYPU_TEXTAREA = 5;

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	public function detail($parametry = null)
	{

                /* Konstrukce objektu formulare a datoveho objektu */
		$form = new HTML_QuickForm("ucastnik", "post", $this->cesta->formAction());
		$ucastnik = Projektor_Data_Ucastnik::najdiPodleId($parametry["id"]);

                if ($ucastnik AND array_key_exists("objektVlastnost", $parametry)){
                    $objektVlastnost = $ucastnik->$parametry["objektVlastnost"];
                }

		/* Příprava defaultnich stavů, typů a titulků prvků formuláře pro objektVlastnost */
                //v poli $elementy se připraví defaultní hodnoty formuláře (převážně přečtené z databáze), typy elemntů ve formuláři
                //a titulky (nadpisy) elementů formuláře,
                //Pole elementy je automaticky naplněno takto:
                //  - index pole elementy je řetězec složený z názvu objektuVlastnosti, konstatnty SEPARATOR, a názvu vlastnosti objektuVlastnosti
                //  - elementy odpovídající sloupcům databázi obsahujícím primární nebo cizí klíče (id) se nesmí změnit a tedy
                //    musí být hidden nebo static, je nastaven typ static
                //  - ostatní elementy (odpovídající sloupcům v db neobsahujícím klíče) jsou nastaveny takto:
                //      typ date: na typ date
                //      ostaní typy: podle délky
                //                          s délkou menší ne rovnou const MAX_SIRKA_TYPU_TEXT na typ text
                //                          s délkou větší než const MAX_SIRKA_TYPU_TEXT na typ textarea
                //  - elementy mají jako titulek použit název sloupce v databázi
                //Jakýkoli jiný typ elementu než "date" nebo "text" a titulky elementů je nutno zapsat do pole elementy v úseku programu uvedeném za cyklem foreach
                //DOPORUČENÍ: při psaná programového kódu stránky se tak nejprve napíše kód s použitím automatického vyplnění pole elementy
                //v níže zapsaném cyklu foreach, zobrazí se stránka
                //a podle zobrazení stránky se postupně doplní do pole elementy typy a titulky elementů
                if($ucastnik AND $objektVlastnost)
		{
                    $klice = $objektVlastnost->dejKlice();
                    $nazvy = $objektVlastnost->dejNazvy();
                    $typy = $objektVlastnost->dejTypy();
                    $delky = $objektVlastnost->dejDelky();
                    foreach ($nazvy as $key => $jmenoVlatnosti) {
                        $index = $parametry["objektVlastnost"] . self::SEPARATOR . $jmenoVlatnosti;      //$jmenoVlatnosti = název sloupce v db
                        if ($klice[$key]) {             //elementy, které odpovídají sloupcům db tabulky obsahujícím klíče musí být hidden nebo static
                            $elementy["typ"][$index] = "static";
                        } else {
                            if ($typy[$key] == "date") {
                                $elementy["typ"][$index] = "date";
                                $elementy["atributy"][$index] = array("format" => "d.m.Y", "minYear" => "1900", "maxYear" => "2050");
                                $elementy["default"][$index] = Projektor_Helper_DatumCas::zSQL($objektVlastnost->$jmenoVlatnosti)->dejDatumProQuickForm() ;
                            } else {
                                if (intval($delky[$key]) <= self::MAX_POCET_ZNAKU_TYPU_TEXT) {
                                    $elementy["typ"][$index] = "text";
                                    $elementy["atributy"][$index] = array("size" => self::MAX_SIRKA_TYPU_TEXT);

                                } else {
                                    $elementy["typ"][$index] = "textarea";
                                    $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA,
                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval($delky[$key])/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
                                }
                            }
                        }
                        if (!$elementy["default"][$index]) {
                            $elementy["default"][$index] = $objektVlastnost->$jmenoVlatnosti;
                        }
                        $elementy["titulek"][$index] = $jmenoVlatnosti;

                    }
                    // Konec cyklu pro nastavení pole elementů - za tento řádek je možno psát jiné nastavení typů a titulků elementů
                    //
		}

		/* Defaultni stavy formulare */
		if($ucastnik)
		{
                    $elementy["default"]["id"] = $ucastnik->id;
                    $elementy["default"]["identifikator"] = $ucastnik->identifikator;
                    $elementy["default"]["idSBehProjektuFK"] = $ucastnik->idSBehProjektuFK;
                    $elementy["default"]["idCKancelarFK"] = $ucastnik->idCKancelarFK;
                    $elementy["default"]["projekt"] = $ucastnik->projektKod;
                    $elementy["default"]["turnusText"] = $ucastnik->turnusText;
                    $elementy["default"]["kancelarText"] = $ucastnik->kancelarText;
                    $elementy["default"]["jmeno"] = $ucastnik->smlouva->jmeno;
                    $elementy["default"]["prijmeni"] = $ucastnik->smlouva->prijmeni;

                    $form->setDefaults($elementy["default"]);
		}

		/* Vytvareni elementu formulare */
                // element ucastnik_id musí být hidden nebo static
                if($ucastnik) {
                    //zde jsou přidány ručně elementy - mohou být prakticky libovolné
                    $form->addElement("hidden", "id");
                    $form->addElement("hidden", "cisloUcastnika");
                    $form->addElement("static", "identifikator", "Identifikátor účastníka");
                    $form->addElement("static", "projekt", "Projekt");
                    $form->addElement("static", "turnusText", "Turnus");
                    $form->addElement("static", "kancelarText", "Kancelář");
                    $form->addElement("static", "jmeno", "Jméno");
                    $form->addElement("static", "prijmeni", "Příjmení");
                    // zde se přidávají automaticky generované elementy - tyto elementy odpovídají sloupců db tabulek s vlastnostmi
                    if ($objektVlastnost) {
                        $form->addElement("header", $parametry["textDoNadpisuStranky"]);
                        foreach ($elementy["default"] as $key => $jmenoVlatnosti) {
                        //    if ($elementy["atributy"][$key]) {
                                $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key], $elementy["atributy"][$key]);
                        //    } else {
                        //        $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key]);
                        //    }

                        }
                    }
                } else {
                    $form->addElement("hidden", "id");
                    $form->addElement("hidden", "cisloUcastnika");
                    $form->addElement("hidden", "identifikator");
                    $form->addElement("hidden", "idSBehProjektuFK", "idSBehProjektuFK");
                    $form->addElement("hidden", "idCKancelarFK", "idCKancelarFK");
                }

		/* Rozhodovani detail/uprav */
		if($parametry["zmraz"])
		{
                    //$form->removeElement("submit");
                    $form->freeze();
                    $this->novaPromenna("nadpis", "Detail údajů účastníka: ".$parametry["textDoNadpisuStranky"]);
		}
		else
                {
                    $form->addElement("submit", "submit", "Ulozit");
                    if($ucastnik)
                        $this->novaPromenna("nadpis", "Úprava údajů účastníka: ".$parametry["textDoNadpisuStranky"]);
                    else
                        $this->novaPromenna("nadpis", "Nový účastník");
                }
		/* Vytvareni pravidel */
//                    if (!array_key_exists("ucastnik_id", $data)) $ucastnik = new Projektor_Data_Ucastnik(0, 0, $idSBehProjektuFK, $idCKancelarFK ); //tyto parametry musí být povinne vyplneny ve formulari
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
                            $data[$key] = Projektor_Helper_DatumCas::zQuickForm($data[$key])->dejDatumproSQL();
                        }
                    }


                    if ($ucastnik) {
                        // starý - již existující účastník
                        $ucastnikZFormulare = Projektor_Data_Ucastnik::najdiPodleId($parametry["id"]);
                    } else {
                        // nový účastník
                        $ucastnikZFormulare = new Projektor_Data_Ucastnik($data["cisloUcastnika"], $data["identifikator"], $data["idSBehProjektuFK"], $data["idCKancelarFK"], 1, $data["id"]);

                    }

                    // hodnoty v elementech typu "static" se metodou exportValues neexportují, ostatní hodnoty (včetně typu "hidden") ano
                    foreach ($data as $key => $value) {
                        $vlastnost = explode(self::SEPARATOR, $key, 2); //SVOBODA nastavuje vlastnost ucastnikZFormulare->ucastnik_submit - nesmysl
                        if (!$vlastnost[1]){
                            $ucastnikZFormulare->$vlastnost[0] = $value;
                        } else {
                            $ucastnikZFormulare->$vlastnost[0]->$vlastnost[1] = $value;
                        }
                    }
                    if($ucastnikZFormulare->uloz())
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
                    new Projektor_Stranka_Element_Tlacitko("Zpět", $this->cesta->zpetUri()),
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function detail°potomekNeni()
	{
            /*$this->promenne["ucastnik_detail"] = Projektor_Data_Akce::najdiPodleId($this->parametry["id"]);
            $this->promenne["ucastnik_zpet"] = $this->cestaSem->generujUriZpet();*/
	}

    /* prihlaseni */
	public function prihlaseni($parametry = null)
	{
		return $this->vytvorStranku("prihlaseni", self::SABLONA_DETAIL, $parametry);
	}

	protected function prihlaseni°vzdy()
	{
		$akce = Projektor_Data_Auto_AkceItem::najdiPodleId($this->parametry["id_akce"]);
                $this->novaPromenna("nadpis", "Přihlášení účastníka na akci");

                try
		{
			$akce->prihlas(Projektor_Data_Ucastnik::najdiPodleId($this->parametry["id"]), Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Projektor_Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));
                        $this->novaPromenna("ucastnik_prihlaseni", "Prihlaseni bylo uspesne!");
		}
		catch(Exception $e)
		{
                        $this->novaPromenna("ucastnik_prihlaseni", $e->getMessage());
		}

		$this->detail°vzdy();
	}

	protected function prihlaseni°potomekNeni()
	{
		$this->detail°potomekNeni();
	}

}