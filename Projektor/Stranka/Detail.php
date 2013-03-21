<?php
/**
 * Description of Seznam
 *
 * @author pes2704
 */
abstract class Projektor_Stranka_Detail extends Projektor_Stranka_Base
{
    const MAX_POCET_ZNAKU_TYPU_TEXT = 48; //max. počet znaků, pro který se při automatickém nastavení typu elementů nastaví "text", pro větší "textarea"
    const MAX_SIRKA_TYPU_TEXT = 68;
    const POCET_SLOUPCU_TYPU_TEXTAREA = 51;
    const MAX_POCET_RADKU_TYPU_TEXTAREA = 5;
    const NAZEV_HLAVNIHO_OBJEKTU_PREZENTACE_PRO_FLAT_TABLE = "Table";


    /**
     * metoda vrací data Item pro stránku, může být přetížená metodou dejItem ve stránce, která je potomkem této třídy a taková metoda vrací
     * item například s vhodným fitrem where (pracuje jen s některými Item) nebo item s vlastnostmi, které neodpovídají sloupcům db tabulky
     */
    public function dejItem($id)
    {
            $tridaItem = static::TRIDA_DATA_ITEM;
            return new $tridaItem($id);
    }

    public function vychozi()
    {

        /* Konstrukce objektu formulare a datoveho objektu */
        $form = new HTML_QuickForm("objekt"."_".$this->uzel->trida."_".$this->uzel->parametry['objektVlastnost'], "post", $this->uzel->formAction());
        $tridaItem = static::TRIDA_DATA_ITEM;
        if ($this->uzel->parametry["id"]) {
            $item = new $tridaItem($this->uzel->parametry["id"]);
        } else {
            $item = new $tridaItem();
        }
        /* Defaultni stavy formulare */
        if (is_subclass_of($item, "Projektor_Data_HlavniObjektItem")) //$item je hlavní objekt = $item je potomek Projektor_Data_HlavniObjektItem
        {
            $jmenoObjektuVlastnosti = $this->uzel->parametry["objektVlastnost"];
            $itemObjektVlastnost = $item->$jmenoObjektuVlastnosti;
            $elementy = $this->pripravElementyZItem($itemObjektVlastnost, $tridaItem);
            $elementy = $this->prepisTitulkyZPrezentace($elementy, $tridaItem);
            foreach ($item as $vlastnost=>$hodnota)
            {
                // Přidání elementů typu static zobrazující vlastnosti hlavního objektu (jen ke čtení)
                $elementy["default"][$vlastnost] = $hodnota;
                $form->addElement("static", $vlastnost, $vlastnost);
            }
        } else {
            $elementy = $this->pripravElementyZItem($item);
            $elementy = $this->prepisTitulkyZPrezentace($elementy);
        }


        $form->setDefaults($elementy["default"]);

            /* Vytvareni elementu formulare */
            //zde je možno přidat ručně elementy - mohou být prakticky libovolné (element primární klíč tabulky (id) musí být hidden nebo static  $form->addElement("hidden", "id");)
            // zde se přidávají automaticky generované elementy - tyto elementy odpovídají sloupců db tabulek s vlastnostmi
            $form->addElement("header", $this->uzel->parametry["textDoNadpisuStranky"]);
            foreach ($elementy["default"] as $key => $hodnotaVlastnosti) {
            //    if ($elementy["atributy"][$key]) {
                        $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key], $elementy["atributy"][$key]);
            //    } else {
            //        $form->addElement($elementy["typ"][$key], $key, $elementy["titulek"][$key]);
            //    }

            }

            /* Rozhodovani detail/uprav */
            if($this->uzel->parametry["zmraz"])
            {
                //$form->removeElement("submit");
                $form->freeze();
                $this->novaPromenna("nadpis", "Detail údajů: ".$this->uzel->parametry["textDoNadpisuStranky"]);
            }
            else
            {
                $form->addElement("submit", "submit", "Ulozit");
            if ($this->uzel->parametry["pdf"])
            {
            $form->addElement("submit", $this->uzel->parametry["pdf"], "Vytvořit PDF dokument ".$this->uzel->parametry["pdf"]);
            }
            if($item)
                    $this->novaPromenna("nadpis", "Úprava údajů: ".$this->uzel->parametry["textDoNadpisuStranky"]);
                else
                    $this->novaPromenna("nadpis", "Nový");
            }
            /* Vytvareni pravidel */
//                    if (!array_key_exists("ucastnik_id", $data)) $ucastnik = new Projektor_Data_Ucastnik(0, 0, $idSBehProjektuFK, $idCKancelarFK ); //tyto parametry musí být povinne vyplneny ve formulari
            //$form->addRule("ucastnik_jmeno", "Chybí jméno!", "required");
            //$form->addRule("ucastnik_prijmeni", "Chybí příjmení!", "required");

            /* Zpracovani - validace formuláře a případné uložení dat */
            if($form->validate())
            {
                $form->removeElement("submit");
        if ($this->uzel->parametry["pdf"])
        {
            $form->removeElement($this->uzel->parametry["pdf"]);
        }
                $form->freeze();
                $data = $form->exportValues();
                foreach ($data as $key => $value) {
                    if ($elementy["typ"][$key] == "date") {
                        $data[$key] = Projektor_Data_Konverze_Datum::zQuickForm($data[$key])->dejDatumproSQL();
                    }
                }

                foreach ($data as $key => $value) {
                    $vlastnost = explode(self::SEPARATOR, $key, 3);//!!!!!!!!!!!!!!!!
                    if (!$vlastnost[1]){  //pro ručně přidané elementy
                        $item->$vlastnost[0] = $value;
                    } else {
                        $item->$vlastnost[2] = $value;
                    }
                }
                if($item->uloz())
                    $this->novaPromenna("ulozeno", true);
                else
                    $this->novaPromenna("ulozeno_chyba", true);
            }
//                $this->formular = $form->toHtml();
            $this->novaPromenna("formular", $form->toHtml());
//		/* Generovani */
//		return $this->vytvorStranku("detail", self::SABLONA_DETAIL, $this->uzel->parametry, $form->toHtml());
    }

    /**
        * Metoda vytvoří array elementy, obsahující hodnoty potřebné pro přidání elementů metodami QuickForm do objektu formuláře. Hodnoty načte z datového objektu
        * typu FlatTable
        * @param object $item datový objekt vytvořený třídou FlatTable
        * @param string $nazevHlavnihoObjektu pokud není zadán, metoda předpokládá, že objekt $dataFlatTableObjekt není vlastností hlavního objektu, jde o samostatný
        *               objekt typu FlatTable a jako název hlavního objektu použije hodnotu konstanty (řetězec "Flat_FlatTable")
        * @return type
        */
    protected function pripravElementyZItem(Projektor_Data_Item $item, $nazevHlavnihoObjektu = NULL)
    {
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
        if (!$nazevHlavnihoObjektu) $nazevHlavnihoObjektu = self::NAZEV_HLAVNIHO_OBJEKTU_PREZENTACE_PRO_FLAT_TABLE;
        foreach ($item as $nazevVlastnosti => $value)
        {
            $strukturaSloupce = $item->dejStrukturuSloupce($nazevVlastnosti);
            $index = $nazevHlavnihoObjektu . self::SEPARATOR . $item::TABULKA . self::SEPARATOR . $strukturaSloupce->nazev;      //$jmenoVlastnosti = název sloupce v db
            if ($strukturaSloupce->klic) {             //elementy, které odpovídají sloupcům db tabulky obsahujícím klíče musí být hidden nebo static
                $elementy["typ"][$index] = "static";
            } else {
                if ($strukturaSloupce->typ == "date") {
                    $elementy["typ"][$index] = "date";
                    $elementy["atributy"][$index] = array("format" => "d.m.Y", "minYear" => "1900", "maxYear" => "2050");
                    $elementy["default"][$index] = Projektor_Data_Konverze_Datum::zSQL($value)->dejDatumProQuickForm() ;
                } else {
                    if (intval($strukturaSloupce->delka) <= self::MAX_POCET_ZNAKU_TYPU_TEXT) {
                        $elementy["typ"][$index] = "text";
                        $elementy["atributy"][$index] = array("size" => self::MAX_SIRKA_TYPU_TEXT);

                    } else {
                        $elementy["typ"][$index] = "textarea";
// TODO: nefunguje rows, ve výsledném kódu jsou hodnoty, které se berou kdoví odkud, přitom cols je nastaveno správně
//                                                                        $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA,
//                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval($delky[$key])/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
                        $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA,
                            "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval(strlen(htmlspecialchars($value)))/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
//                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  "1"));
                    }
                }
            }
            if (!$elementy["default"][$index]) {
                $elementy["default"][$index] = htmlspecialchars($value);
                //TODO: html hack pro obsah databázového sloupce, který "asi" obsahuje html
                //rozpoznávám pouze výskyt "<", za kterým je někde ">" v textu
                if (!is_array($value) AND strpos($value, ">", strpos($value, "<")) !== FALSE)
                {
                    $elementy["default"][$index] = $elementy["default"][$index]."<br></br><div style='border: solid blue; width: 400px; list_style_type: circle'>".str_replace("\"", "'", $dataFlatTableObjekt->$hodnotaVlastnosti)."</div>";
                }
            }

            $elementy["titulek"][$index] = $strukturaSloupce->nazev;
        }

        return $elementy;
    }
//        if (!$nazevHlavnihoObjektu) $nazevHlavnihoObjektu = self::NAZEV_HLAVNIHO_OBJEKTU_PREZENTACE_PRO_FLAT_TABLE;
//
//        $klice = $dataFlatTableObjekt->dejKlice();
//        $nazvy = $dataFlatTableObjekt->dejNazvy();
//        $typy = $dataFlatTableObjekt->dejTypy();
//        $delky = $dataFlatTableObjekt->dejDelky();
//
//        foreach ($nazvy as $key => $hodnotaVlastnosti) {
//            $index = $nazevHlavnihoObjektu . self::SEPARATOR . $dataFlatTableObjekt->tabulka . self::SEPARATOR . $hodnotaVlastnosti;      //$jmenoVlastnosti = název sloupce v db
//            if ($klice[$key]) {             //elementy, které odpovídají sloupcům db tabulky obsahujícím klíče musí být hidden nebo static
//                $elementy["typ"][$index] = "static";
//            } else {
//                if ($typy[$key] == "date") {
//                    $elementy["typ"][$index] = "date";
//                    $elementy["atributy"][$index] = array("format" => "d.m.Y", "minYear" => "1900", "maxYear" => "2050");
//                    $elementy["default"][$index] = Projektor_Data_Konverze_Datum::zSQL($dataFlatTableObjekt->$hodnotaVlastnosti)->dejDatumProQuickForm() ;
//                } else {
//                    if (intval($delky[$key]) <= self::MAX_POCET_ZNAKU_TYPU_TEXT) {
//                        $elementy["typ"][$index] = "text";
//                        $elementy["atributy"][$index] = array("size" => self::MAX_SIRKA_TYPU_TEXT);
//
//                    } else {
//                        $elementy["typ"][$index] = "textarea";
//// TODO: nefunguje rows, ve výsledném kódu jsou hodnoty, které se berou kdoví odkud, přitom cols je nastaveno správně
////                                                                        $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA,
////                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval($delky[$key])/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
//                        $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA,
//                            "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval(strlen(htmlspecialchars($dataFlatTableObjekt->$hodnotaVlastnosti)))/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
////                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  "1"));
//                    }
//                }
//            }
//            if (!$elementy["default"][$index]) {
//                $elementy["default"][$index] = htmlspecialchars($dataFlatTableObjekt->$hodnotaVlastnosti);
//                //TODO: html hack pro obsah databázového sloupce, který "asi" obsahuje html -rozpoznávám pouze výskyt "<" v textu
//                if (!is_array($dataFlatTableObjekt->$hodnotaVlastnosti) AND strpos($dataFlatTableObjekt->$hodnotaVlastnosti, "<") !== FALSE)
//                {
//                    $elementy["default"][$index] = $elementy["default"][$index]."<br></br><div style='border: solid blue; width: 400px; list_style_type: circle'>".str_replace("\"", "'", $dataFlatTableObjekt->$hodnotaVlastnosti)."</div>";
//                }
//            }
//
//            $elementy["titulek"][$index] = $hodnotaVlastnosti;
//        }
//
//        return $elementy;
//    }

    protected function prepisTitulkyZPrezentace($elementy, $nazevHlavnihoObjektu = NULL)
    {
            return $elementy;

            if (!$nazevHlavnihoObjektu) $nazevHlavnihoObjektu = self::NAZEV_HLAVNIHO_OBJEKTU_PREZENTACE_PRO_FLAT_TABLE;
            $filtr = Projektor_Data_Auto_SPrezentaceCollection::HLAVNI_OBJEKT." = \"".$nazevHlavnihoObjektu."\"".
                        " AND ".Projektor_Data_Seznam_SPrezentace::OBJEKT_VLASTNOST." = \"".$dataObjekt->tabulka."\"";
            $prezentace = new Projektor_Data_Auto_SPrezentaceCollection();
            $prezentace->filter($filtr);
            if ($prezentace) {
                foreach($prezentace as $polozka)
                {
                    $index = $polozka->hlavniObjekt . self::SEPARATOR . $polozka->objektVlastnost . self::SEPARATOR . $polozka->nazevSloupce;
                    if ($polozka->zobrazovat) {
                        $elementy["titulek"][$index] = $polozka->titulek;
                    } else {
                        unset ($elementy["typ"][$index]);
                        unset ($elementy["atributy"][$index]);
                        unset ($elementy["default"][$index]);
                        unset ($elementy["titulek"][$index]);
                    }

                }
            }
            return $elementy;
    }
}

?>
