<?php
/**
 * Description of Seznam
 *
 * @author pes2704
 */
abstract class Projektor_Controller_Page_Seznam extends Projektor_Controller_Page_AbstractPage {
    
    protected function vychozi() {}

    protected function potomekNeni() {
        $collection = $this->dejCollection();
        $filtr = $this->autofiltr($collection);
        $collection->filter($filtr->generujSQL());
        if (isset($this->controllerParams["razeniPodle"])) {
            $collection->order($this->controllerParams["razeniPodle"], $this->controllerParams["razeni"]);
        }
        $this->generujSeznam($collection);
    }
    
    protected function vzdy() {  
        !! zrušil jsem dispatcherParams a Abstract Dispatcher, je to třeba předělat!
        parent::vzdy();
        // pokud má seznam jen jednoho potomka (obvykle má - je to detail nebo menu) vypiš položku seznamu
        if ($this->vertex->ChildVertexDispatchers[0]->getDispatcherParams) { 
            if(isset($this->vertex->ChildVertexDispatchers[0]->dispatcherParams['id'])) {
            $collection = $this->dejCollection();
            $collection->where("id", "=", $this->vertex->ChildVertexDispatchers[0]->dispatcherParams['id']);
            $this->generujPolozku($collection);
            }
        }
    }

    /**
     * metoda vrací data Collection pro stránku, může být přetížená metodou dejCollection ve stránce, která je potomkem této třídy a takoví metoda vrací
     * collection například s vhodným fitrem where (pracuje jen s některými Item z Collection) nebo collection s jednotlivými item s vlastnostmi,
     *  které neodpovídají sloupcům db tabulky
     */
    public function dejCollection() {
            $tridaCollection = static::TRIDA_Model_COLLECTION;
            return new $tridaCollection($this->vertex->router->appStatus);
    }

    /**
     * Metoda generuje seznam pro template z parametru $collection
     * @param Projektor_Model_Collection $collection
     */
    protected function generujSeznam(Projektor_Model_Collection $collection) {
        // při vytváření seznamu volá generujTlacitkaProSeznam
        $hlavickaTabulky = $this->generujHlavickuTabulky(get_class($collection));
        $this->setViewContextValue("hlavickaTabulky", $hlavickaTabulky);
        $seznamCollection = new Projektor_Model_SeznamCollection();
        $seznamCollectionIterator = $seznamCollection->getIterator();
        foreach($collection as $item) {
            $seznamItem = $this->dejSeznamItemZHlavicky($item, $hlavickaTabulky);
            $seznamItem->tlacitka = $this->generujTlacitkaProSeznam($item);  //přidá vlastnost tlacitka přímo, ne do iterátoru
            $seznamCollectionIterator->append($seznamItem);
        }
        $this->setViewContextValue("seznam", $seznamCollectionIterator);
        $this->setViewContextValue("zprava", "Celkem nalezeno:".  $seznamCollectionIterator->count());
    }

    /**
     * Metoda generuje položku pro template z parametru $collection
     * @param Projektor_Model_Collection $collection
     */
    protected function generujPolozku(Projektor_Model_Collection $collection) {
        // při vytváření seznamu volá generujTlacitkaProPolozku
        $hlavickaTabulky = $this->generujHlavickuTabulky(get_class($collection));
        $this->setViewContextValue("hlavickaTabulky", $hlavickaTabulky);
        $seznamCollection = new Projektor_Model_SeznamCollection();
        foreach($collection as $item) {
            $seznamItem = $this->dejSeznamItemZHlavicky($item, $hlavickaTabulky);
            $seznamItem->tlacitka = $this->generujTlacitkaProPolozku($item);  //přidá vlastnost tlacitka přímo, ne do iterátoru
            $seznamCollection->append($seznamItem);
        }
        $this->setViewContextValue("seznam", $seznamCollection);
    }

//    protected function generujTlacitkaProSeznam(Projektor_Model_Item $item) {}
//
//    protected function generujTlacitkaProPolozku(Projektor_Model_Item $item) {}

    /**
     * Metoda vytvoří automaticky formulář, který umožňuje nastavit parametry filtrování datových objektů v seznamu.
     * Parametrem metody je dotová kolekce Projektor_Model_Collection. Z kolekce metoda vygeneruje formulář s položkami odpovídajícími
     * jednotlivým vlastnostem objektů (item) kolekce. Pro vlastnosti datového objektu obsahující přímo hodnotu vytvoří formulářový prvek input,
     * pro vlastnosti datového objektu obsahující cizí klíč db tabulky vytvoří formulářový prvek select naplněný hodnotami referencované tabulky.
     * Metoda uloží html kód formuláře do proměnné stránky a vrací objekt Projektor_Controller_Page_Element_Filtr, který je možno použít na vygenerování
     * klausule WHERE v SQL dotazu.
     * Metoda použije potomkovskou metodu generujHlavickuTabulky a podle vlastnosti sloupce vygeneruje formulář a filtr.
     * Výsledný html kód formuláře vloží do nové template proměnné stránky "filtrovaciFormular" (volá metodu novaPromenna).
     * Po odeslání formuláře (submit) z přijatých dat vytvoří objekt Projektor_Controller_Page_Element_Filtr ten vrací jako návratovou hodnotu.
     */
    /**
     *
     * @param Projektor_Model_Collection $collection
     * @return Projektor_Controller_Page_Element_Filtr
     */
    protected function autofiltr(Projektor_Model_Collection $collection) {
        $hlavickaTabulky = $this->generujHlavickuTabulky(get_class($collection));
        $form = new HTML_QuickForm("autofiltr", "post", $this->vertex->formAction());
        foreach ($hlavickaTabulky->sloupce as $sloupec) {
            $referencovanaCollection = $collection->dejReferencovanouKolekci($sloupec->nazevVlastnosti);
            $nazevSloupceDb = $collection->dejNazevSloupceZVlastnosti($sloupec->nazevVlastnosti);
            if ($referencovanaCollection) {
                // pokud je ve sloupci hlavičky nastavena vlastnost nazevVlastnostiReferencovanehoObjektu, zobrazuje se ve filtru tato flastnost
                if ($sloupec->nazevVlastnostiReferencovanehoObjektu) {
                    $nazevVlastnosti = $sloupec->nazevVlastnostiReferencovanehoObjektu;
                // pokud není ve sloupci hlavičky nastavena vlastnost nazevVlastnostiReferencovanehoObjektu, zobrazuje je ve filtru
                // default vlastnost - podle konstanty NAZEV_ZOBRAZOVANE_VLASTNOSTI v definici Item
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

        $filtr = new Projektor_Controller_Page_Element_Filtr();
        if($form->validate()) {
            $data = $form->exportValues();
            if ($data["submitFiltrovat"]) {
                unset($data["submitFiltrovat"]);
                unset($data["submitNefiltrovat"]);
                $filtr = Projektor_Controller_Page_Element_Filtr::like($data);
            } else {
                unset($data["submitFiltrovat"]);
                unset($data["submitNefiltrovat"]);
                $filtr = Projektor_Controller_Page_Element_Filtr::like();
            }
        }

// volba rendereru formuláře
// odkomentuj následující dva řádku pro jiný renderer než default
// volba Tableless rendereru
//      $renderer = new HTML_QuickForm_Renderer_Tableless();
//      $form->accept($renderer);
//      $this->novaPromenna("filtrovaciFormular", $renderer->toHtml());

// zakomentuj následující řádek pro jiný renderer než default
    $this->setViewContextValue("filtrovaciFormular", $form->toHtml());
    return $filtr;
    }

    /**
     * Metoda podle objektu $hlavickaTabulky, vygeneruje objekt typu Projektor_Model_SeznamItem s vlastnostmi odpovídajícími
     * sloupcům v objektu $hlavickaTabulky, tedy odpovídající požadovaným sloupcům seznamu. Vlastnostem objektu návratového
     * Projektor_Model_SeznamItem nastaví hodnoty takto:
     *  - V objektu $hlavickaTabulky->sloupec je název vlastnosti parametru $dataItem (např. "dbField°identifikator"):
     * použije hodnotu vlastnosti $dataItem->identifikator ./n
     *  - Objekt $dataItem je hlavní objekt a v objektu $hlavickaTabulky->sloupec je název vlastnosti podřízeného objektu
     * (např. "smlouva->dbField°jmeno"): použije hodnotu vlastnosti $dataItem->smlouva->jmeno ./n
     *  - V objektu $hlavickaTabulky->sloupec je název vlastnosti parametru $dataItem, který obsahuje cizí klíč jiné tabulky
     * (např. "dbField°id_c_kancelar_FK"): použije hodnotu vlastnosti objektu odpovídajícího tabulce referencované cizím klíčem,
     * tedy např objektu Projektor_Model_Auto_CKancelarItem. Kterou vlastnost referencovaného objektu použije zavisí na tom,
     * zda je v hlavičce nastaven parametr sloupce $sloupec->nazevVlastnostiReferencovanehoObjektu. Pokud je nastaven - použije se
     * tato vlastnost, pokud není nastaven - použije se defaultně vlastnost z konstatnty referencovaného datového objektu
     * NAZEV_ZOBRAZOVANE_VLASTNOSTI (např. Projektor_Model_Auto_CKancelarItem::NAZEV_ZOBRAZOVANE_VLASTNOSTI). Tento postup se uplatní
     * i pro podřízenou vlatnost hlavního objektu: např. pro název vlastnoti v hlavičce "dotaznik->dbField°id_c_okres_FK" se použije vlastnost
     * objektu Projektor_Model_Auto_COkresItem::NAZEV_ZOBRAZOVANE_VLASTNOSTI.
     *
     * @param Projektor_Model_Item $item
     * @param Projektor_Controller_Page_Element_Hlavicka $hlavickaTabulky
     * @return \Projektor_Model_SeznamItem
     */
    protected function dejSeznamItemZHlavicky(Projektor_Model_Item $item, Projektor_Controller_Page_Element_Hlavicka $hlavickaTabulky) {
        $seznamItem = new Projektor_Model_SeznamItem();
        $seznamIterator = $seznamItem->getIterator();
        foreach ($hlavickaTabulky->sloupce as $sloupec) {
            $nazevVlastnostiVHlavicce = $sloupec->nazevVlastnosti;
            if (strpos($nazevVlastnostiVHlavicce, "->")) {
                // vlastnost je vlastností hlavního objektu,  název vlastnosti obsahuje znaky "->"
                $castiNazvu = explode("->", str_replace(" ", "", $nazevVlastnostiVHlavicce));
                $dataItem = $item;
                // prochází postupně všechny objekttové vlstnosti až k poslední - vrávená hodnota $dataItem se znovu použije v dalším kroku
                foreach ($castiNazvu as $vlastnost) {
                    $dataItem = $this->dejHodnotu($vlastnost, $dataItem, $sloupec->nazevVlastnostiReferencovanehoObjektu);
                }
                $hodnota = $dataItem;
            } else {
                // vlastnost není vlastností hlavního objektu
                $hodnota = $this->dejHodnotu($nazevVlastnostiVHlavicce, $item, $sloupec->nazevVlastnostiReferencovanehoObjektu);
            }
            $seznamIterator->offsetSet($nazevVlastnostiVHlavicce, $hodnota);
        }
        return $seznamIterator;
    }

    private function dejHodnotu($nazevVlastnostiVHlavicce, Projektor_Model_Item $item, $nazevVlastnostiReferencovanehoObjektu=NULL) {
        // když $nazevVlastnostiVHlavicce odpovídá sloupci s FK, vrací metoda ->dejReferencovanýItem
        // hodnotu vlastnosti referencovaného objektu
        $referencovanyItem = $item->dejReferencovanýItem($nazevVlastnostiVHlavicce);
        if ($referencovanyItem) {
            if ($nazevVlastnostiReferencovanehoObjektu) {
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
