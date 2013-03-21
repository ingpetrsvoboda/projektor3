<?php
/**
 * Description of Seznam
 *
 * @author pes2704
 */
abstract class Projektor_Stranka_Seznam extends Projektor_Stranka_Base
{

    protected function vzdy()
    {
        parent::vzdy();
        if (isset($this->uzel->uzlyPotomci[0]->parametry['id']))   //TODO: předpokládán, že potomkem je stránk a meni a ta má jen jednoho potomka (uzlyPotomci[0])
        {
            $collection = $this->dejCollection();
            $collection->where("id", "=", $this->uzel->uzlyPotomci[0]->parametry['id']);
            $this->generujPolozku($collection);
        }
    }

    protected function potomekNeni()
    {
        $collection = $this->dejCollection();
        $filtr = $this->autofiltr($collection);
        $collection->filter($filtr->generujSQL());
        if (isset($this->uzel->parametry["razeniPodle"]))
        {
            $collection->order($this->uzel->parametry["razeniPodle"], $this->uzel->parametry["razeni"]);
        }
        $this->generujSeznam($collection);
    }

    /**
     * metoda vrací data Collection pro stránku, může být přetížená metodou dejCollection ve stránce, která je potomkem této třídy a takoví metoda vrací
     * collection například s vhodným fitrem where (pracuje jen s některými Item z Collection) nebo collection s jednotlivými item s vlastnostmi,
     *  které neodpovídají sloupcům db tabulky
     */
    public function dejCollection()
    {
            $tridaCollection = static::TRIDA_DATA_COLLECTION;
            return new $tridaCollection();
    }

    /**
     * Metoda generuje seznam pro template z parametru $collection
     * @param Projektor_Data_Collection $collection
     */
    protected function generujSeznam(Projektor_Data_Collection $collection)
    {
        // při vytváření seznamu volá generujTlacitkaProSeznam
        $hlavickaTabulky = $this->generujHlavickuTabulky(get_class($collection));
        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
        $seznamCollection = new Projektor_Data_SeznamCollection();
        foreach($collection as $item)
        {
            $seznamItem = $this->dejSeznamItemZHlavicky($item, $hlavickaTabulky);
            $seznamItem->tlacitka = $this->generujTlacitkaProSeznam($item);  //přidá vlastnost tlacitka přímo, ne do iterátoru
            $seznamCollection->add($seznamItem);
        }
        $this->novaPromenna("seznam", $seznamCollection);
        $this->novaPromenna("zprava", "Celkem nalezeno:".  $seznamCollection->count());
    }

    /**
     * Metoda generuje položku pro template z parametru $collection
     * @param Projektor_Data_Collection $collection
     */
    protected function generujPolozku(Projektor_Data_Collection $collection)
    {
        // při vytváření seznamu volá generujTlacitkaProPolozku
        $hlavickaTabulky = $this->generujHlavickuTabulky(get_class($collection));
        $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
        $seznamCollection = new Projektor_Data_SeznamCollection();
        foreach($collection as $item)
        {
            $seznamItem = $this->dejSeznamItemZHlavicky($item, $hlavickaTabulky);
            $seznamItem->tlacitka = $this->generujTlacitkaProPolozku($item);  //přidá vlastnost tlacitka přímo, ne do iterátoru
            $seznamCollection->add($seznamItem);
        }
        $this->novaPromenna("seznam", $seznamCollection);
    }

//    protected function generujTlacitkaProSeznam(Projektor_Data_Item $item) {}
//
//    protected function generujTlacitkaProPolozku(Projektor_Data_Item $item) {}

    /**
     * Metoda vytvoří automaticky formulář, který umožňuje nastavit parametry filtrování datových objektů v seznamu.
     * Parametrem metody je dotová kolekce Projektor_Data_Collection. Z kolekce metoda vygeneruje formulář s položkami odpovídajícími
     * jednotlivým vlastnostem objektů (item) kolekce. Pro vlastnosti datového objektu obsahující přímo hodnotu vytvoří formulářový prvek input,
     * pro vlastnosti datového objektu obsahující cizí klíč db tabulky vytvoří formulářový prvek select naplněný hodnotami referencované tabulky.
     * Metoda uloží html kód formuláře do proměnné stránky a vrací objekt Projektor_Stranka_Element_Filtr, který je možno použít na vygenerování
     * klausule WHERE v SQL dotazu.
     * Metoda použije potomkovskou metodu generujHlavickuTabulky a podle vlastnosti sloupce vygeneruje formulář a filtr.
     * Výsledný html kód formuláře vloží do nové template proměnné stránky "filtrovaciFormular" (volá metodu novaPromenna).
     * Po odeslání formuláře (submit) z přijatých dat vytvoří objekt Projektor_Stranka_Element_Filtr ten vrací jako návratovou hodnotu.
     */
    /**
     *
     * @param Projektor_Data_Collection $collection
     * @return Projektor_Stranka_Element_Filtr
     */
    protected function autofiltr(Projektor_Data_Collection $collection)
    {
        $hlavickaTabulky = $this->generujHlavickuTabulky(get_class($collection));
        $form = new HTML_QuickForm("autofiltr", "post", $this->uzel->formAction());
        foreach ($hlavickaTabulky->sloupce as $sloupec) {
            $referencovanaCollection = $collection->dejReferencovanouKolekci($sloupec->nazevVlastnosti);
            $nazevSloupceDb = $collection->dejNazevSloupceZVlastnosti($sloupec->nazevVlastnosti);
            if ($referencovanaCollection) {
                // pokud je ve sloupci hlavičky nastavena vlastnost nazevVlastnostiReferencovanehoObjektu, zobrazuje se ve filtru tato flastnost
                if ($sloupec->nazevVlastnostiReferencovanehoObjektu) {
                    $nazevVlastnosti = $sloupec->nazevVlastnostiReferencovanehoObjektu;
                // pokud není ve sloupci hlavičky nastavena vlastnost nazevVlastnostiReferencovanehoObjektu, zobrazuje je ve filtru
                // default vlastnost -
                } else {
                    $itemClass = $referencovanaCollection::NAZEV_TRIDY_ITEM;
                    $nazevVlastnosti = $itemClass::NAZEV_ZOBRAZOVANE_VLASTNOSTI;
                }
                $poleSelect = array();
                $poleSelect[""] = "";
                foreach($referencovanaCollection as $objektProSelect)
                    $poleSelect[$objektProSelect->id] = $objektProSelect->$nazevVlastnosti;
                $form->addElement("select", $nazevSloupceDb, $sloupec->titulek, $poleSelect);
            } else {
                if ($sloupec->nazevVlastnosti)
                {
                $form->addElement("text", $nazevSloupceDb, $sloupec->titulek);
                }
            }
        }
        $form->addElement("submit", "submitFiltrovat", "Filtrovat");
        $form->addElement("submit", "submitNefiltrovat", "Nefiltrovat");

        $filtr = new Projektor_Stranka_Element_Filtr();
        if($form->validate())
        {
            $data = $form->exportValues();
            if ($data["submitFiltrovat"]) {
                unset($data["submitFiltrovat"]);
                unset($data["submitNefiltrovat"]);
                $filtr = Projektor_Stranka_Element_Filtr::like($data);
            } else {
                unset($data["submitFiltrovat"]);
                unset($data["submitNefiltrovat"]);
                $filtr = Projektor_Stranka_Element_Filtr::like();
            }
        }

// volba rendereru formuláře
// odkomentuj následující dva řádku pro jiný renderer než default
// volba Tableless rendereru
//      $renderer = new HTML_QuickForm_Renderer_Tableless();
//      $form->accept($renderer);
//      $this->novaPromenna("filtrovaciFormular", $renderer->toHtml());

// zakomentuj následující řádek pro jiný renderer než default
    $this->novaPromenna("filtrovaciFormular", $form->toHtml());
    return $filtr;
    }

    /**
     * Metoda podle objektu $hlavickaTabulky, vygeneruje objekt typu Projektor_Data_SeznamItem s vlastnostmi odpovídajícími
     * sloupcům v objektu $hlavickaTabulky, tedy odpovídající požadovaným sloupcům seznamu. Vlastnostem objektu návratového
     * Projektor_Data_SeznamItem nastaví hodnoty takto:
     *  - V objektu $hlavickaTabulky->sloupec je název vlastnosti parametru $dataItem (např. "dbField°identifikator"):
     * použije hodnotu vlastnosti $dataItem->identifikator ./n
     *  - Objekt $dataItem je hlavní objekt a v objektu $hlavickaTabulky->sloupec je název vlastnosti podřízeného objektu
     * (např. "smlouva->dbField°jmeno"): použije hodnotu vlastnosti $dataItem->smlouva->jmeno ./n
     *  - V objektu $hlavickaTabulky->sloupec je název vlastnosti parametru $dataItem, který obsahuje cizí klíč jiné tabulky
     * (např. "dbField°id_c_kancelar_FK"): použije hodnotu vlastnosti objektu odpovídajícího tabulce referencované cizím klíčem,
     * tedy např objektu Projektor_Data_Auto_CKancelarItem. Kterou vlastnost referencovaného objektu použije zavisí na tom,
     * zda je v hlavičce nastaven parametr sloupce $sloupec->nazevVlastnostiReferencovanehoObjektu. Pokud je nastaven - použije se
     * tato vlastnost, pokud není nastaven - použije se defaultně vlastnost z konstatnty referencovaného datového objektu
     * NAZEV_ZOBRAZOVANE_VLASTNOSTI (např. Projektor_Data_Auto_CKancelarItem::NAZEV_ZOBRAZOVANE_VLASTNOSTI). Tento postup se uplatní
     * i pro podřízenou vlatnost hlavního objektu: např. pro název vlastnoti v hlavičce "dotaznik->dbField°id_c_okres_FK" se použije vlastnost
     * objektu Projektor_Data_Auto_COkresItem::NAZEV_ZOBRAZOVANE_VLASTNOSTI.
     *
     * @param Projektor_Data_Item $item
     * @param Projektor_Stranka_Element_Hlavicka $hlavickaTabulky
     * @return \Projektor_Data_SeznamItem
     */
    protected function dejSeznamItemZHlavicky(Projektor_Data_Item $item, Projektor_Stranka_Element_Hlavicka $hlavickaTabulky)
    {
        $seznamItem = new Projektor_Data_SeznamItem();
        foreach ($hlavickaTabulky->sloupce as $sloupec)
        {
            $nazevVlastnostiVHlavicce = $sloupec->nazevVlastnosti;
            if (strpos($nazevVlastnostiVHlavicce, "->"))
            {
                // vlastnost je vlastností hlavního objektu,  název vlastnosti obsahuje znaky "->"
                $castiNazvu = explode("->", str_replace(" ", "", $nazevVlastnostiVHlavicce));
                $dataItem = $item;
                // prochází postupně všechny objekttové vlstnosti až k poslední - vrávená hodnota $dataItem se znovu použije v dalším kroku
                foreach ($castiNazvu as $vlastnost)
                {
                    $dataItem = $this->dejHodnotu($vlastnost, $dataItem, $sloupec->nazevVlastnostiReferencovanehoObjektu);
                }
                $hodnota = $dataItem;
            } else {
                // vlastnost není vlastností hlavního objektu
                $hodnota = $this->dejHodnotu($nazevVlastnostiVHlavicce, $item, $sloupec->nazevVlastnostiReferencovanehoObjektu);
            }
            $seznamItem->addOrReplace($nazevVlastnostiVHlavicce, $hodnota);
        }
        return $seznamItem;
    }

    private function dejHodnotu($nazevVlastnostiVHlavicce, Projektor_Data_Item $item, $nazevVlastnostiReferencovanehoObjektu=NULL)
    {
        // když $nazevVlastnostiVHlavicce odpovídá sloupci s FK, vrací metoda ->dejReferencovanýItem
        // hodnotu vlastnosti referencovaného objektu
        $referencovanyItem = $item->dejReferencovanýItem($nazevVlastnostiVHlavicce);
        if ($referencovanyItem)
        {
            if ($nazevVlastnostiReferencovanehoObjektu)
            {
                $nazev = $nazevVlastnostiReferencovanehoObjektu;
            } else {
                $nazev = $referencovanyItem::NAZEV_ZOBRAZOVANE_VLASTNOSTI;
            }
            return $referencovanyItem->$nazev;
        } else {
            return $item->$nazevVlastnostiVHlavicce;
        }
    }
}
?>
