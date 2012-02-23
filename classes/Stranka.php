<?php
/**
 * Abstraktni trida Stranka, popisuje obecnou stranku.
 * @author Marek Petko
 * @abstract
 */
abstract class Stranka
{
        /**
	 * Nastaveni slozky kde prebyvaji vsechny sablony.
	 */
	const SLOZKA_SABLON = "templates/";

	/**
	 * Nastaveni klicoveho slova misto ktereho se nahradi html kod potomka.
	 */
	const SLOT_PRO_POTOMKA = "<!-- %NEXT%  -->";
  	const SLOT_PRO_FORMULAR = "<!-- %FORM% -->";
        const SLOT_PRO_NAZEV_STRANKY = "%STRANKA%";
        const SLOT_PRO_FILTROVANI = "<!-- %FILTR%  -->";
 
	/**
	 * Konstanty pro služební metody
	 */        
        const SEPARATOR = "_X_";    //separuje název objektuVlastnosti a vlastnosti v objektu HlavickaTabulky (např. pro vlastnost smlouva->jmeno je v hlaviččce smlouva.self::SEPARATOR.jmeno
        const MAX_POCET_ZNAKU_TYPU_TEXT = 48; //max. počet znaků, pro který se při automatickém nastavení typu elementů nastaví "text", pro větší "textarea"
        const MAX_SIRKA_TYPU_TEXT = 68;
        const POCET_SLOUPCU_TYPU_TEXTAREA = 51;
        const MAX_POCET_RADKU_TYPU_TEXTAREA = 5;  
        const NAZEV_HLAVNIHO_OBJEKTU_PREZENTACE_PRO_FLAT_TABLE = "Flat_FlatTable";
        
        
        /**
         * Počítadlo instancí objektů zděděných z třídy Stranka
         */
        static $instance = 0;

	/**
	 * Ukazatel na globalni cestu.
	 */
	protected $cesta;

        /**
         * Nazev stranky
         */
        public $nazev;
        
        public $nazev_flattable;
        public $nazev_jednotne;
        public $nazev_mnozne;
        public $vsechny_radky;
        public $databaze;
        

	/**
	 * HTML kod stranky.
	 */
	public $html;

	/**
	 * Promenne stranky.
	 */
	public $promenne;

	/**
	 * Ukazatel na cestu sem.
	 */
	protected $cestaSem;

	/**
	 * Ukazatel na nasledujici krok cesty.
	 */
	protected $dalsi;

	/**
	 * Globalizovane parametry metody.
	 */
	protected $parametry;

        /**
	 * Filtr generovany metodou filtrovani.
	 */
        protected $filtr;

	/**
	 * Konstruktor stranky.
	 * @param $cesta Ukazatel na na globalni cestu.
	 */
	protected function __construct($cesta, $nazev = null, $nazev_flattable="", $nazev_jednotne="", $nazev_mnozne="", $vsechny_radky=FALSE, $databaze=NULL)
	{
		$this->cesta = $cesta; // ulozime si cestu
		$this->cestaSem = $this->cesta->sem(); // ulozime si cestu k teto strance/tride
		$this->dalsi = $this->cesta->dalsi(); // posuneme se v ceste na dalsi pozici a ulozime si ji do promenne
                $this->nazev = $nazev.++self::$instance; //název třídy s číslem instance třídy
                $this->nazev_flattable = $nazev_flattable;
                $this->nazev_jednotne = $nazev_jednotne;
                $this->nazev_mnozne = $nazev_mnozne;
                $this->vsechny_radky = $vsechny_radky;
                $this->databaze = $databaze;
                
	}

	/**
	 * Vlozi HTML kod potomka do HTML kodu teto stranky na misto urcene Stranka::SLOT_PRO_POTOMKA.
	 * Dale pripoji pole promennych k promennym teto stranky.
	 * @param Stranka $potomek Potomkovska stranka.
	 */
	private function pripojPotomka()
	{
		$trida = $this->dalsi->trida;
		$metoda = $this->dalsi->metoda;
		$parametry = $this->dalsi->parametry;

                $potomek = $trida::priprav($this->cesta)->$metoda($parametry);

		$this->pripojHtml(self::SLOT_PRO_POTOMKA, $potomek->html);
		if($this->promenne)
                    $this->promenne = array_merge($this->promenne, $potomek->promenne);
		else
                    $this->promenne = $potomek->promenne;
	}

	/**
	 * Pripoji HTML kod formulare do stranky.
	 * @param string $html HTML kod formulare.
	 * @return void
	 */
	private function pripojFormular($html)
	{
		$this->pripojHtml(self::SLOT_PRO_FORMULAR, htmlspecialchars_decode($html));
	}

        /**
        * Pripoji HTML kod formulare filtrovani do stranky.
        * @param string $html HTML kod formulare.
        * @return void
        */
	private function pripojFiltrovani($html)
	{
		$this->pripojHtml(self::SLOT_PRO_FILTROVANI, htmlspecialchars_decode($html));
	}

	/**
	 * Pripoji HTML kod do slotu ve strance.
	 * @param string $slot Kod definujici slot v sablone.
	 * @param string $html HTML kod k pripojeni
	 * @return void
	 */
	private function pripojHtml($slot, $html)
	{
		$this->html = str_replace($slot, $html, $this->html);
	}

	/**
	 * Nacte sablonu stranky ze souboru.
	 * @param $souborSablony Nazev souboru sablony bez udane cesty.
	 */
	private function sablona($souborSablony)
	{
		$this->html = file_get_contents(self::SLOZKA_SABLON . $souborSablony);

                /* Uprava pro dynamicke sablony */
                if($this->nazev)
                    $this->html = str_replace(self::SLOT_PRO_NAZEV_STRANKY, $this->nazev, $this->html);
	}

	/**
	 * Vytvori stranku vcetne potomku, ale bez promennych.
	 * @param $metoda Nazev metody ze ktere tuhle metodu volame
	 * @param $souborSablony Nazev souboru sablony pro stranku
	 * @param $parametry Parametry metody ze ktere tuhle metodu volame
	 */
	public function vytvorStranku($metoda, $souborSablony, $parametry = null, $formular = null, $filtrovaciForm = null)
	{
		$this->sablona($souborSablony); 	// nacteni template
		$this->pripojFormular($formular);		// pripojime html kod formulare
                $this->pripojFiltrovani($filtrovaciForm);		// pripojime html kod formulare
		$this->parametry = $parametry;   	  // globalizujeme parametry
		if($this->dalsi)					// pokud mame nejakeho potomka
		{
			$this->pripojPotomka();			// tak ho pripojime
		}


		if(!$this->dalsi)
		{
			$potomekNeni = $metoda."°potomekNeni";
                        App_Logger::setLog(array("třída" => $this->nazev, "metoda" => $potomekNeni));
			$this->$potomekNeni();

		}
		else
		{
			$privatniMetoda = $metoda."°potomek°".$this->dalsi->trida."°".$this->dalsi->metoda;
			if(method_exists($this, $privatniMetoda))
			{
                            App_Logger::setLog(array("třída" => $this->nazev, "metoda" => $privatniMetoda));
                            $this->$privatniMetoda();
			}
			else
                            echo("<font color=\"red\">Pozor! Metoda <em>".$privatniMetoda."</em> ve tride <strong>".get_class($this)."</strong> neni definovana!</font>");
		}

		/* volame privatni metodu, ktera nam generuje promenne pro obsah, ktery zobrazujeme vzdy, bez ohledu na potomka */
		$vzdy = $metoda."°vzdy";
                App_Logger::setLog(array("třída" => $this->nazev, "metoda" => $vzdy));
		$this->$vzdy();

		return $this;
	}

        /**
         * Prida nebo upravi promennou do pole prommenych pro export do rodicovske stranky.
         */
        public function novaPromenna($klic, $hodnota)
        {
            $this->promenne[$this->nazev][$klic] = $hodnota;
        }
/**
 *
 * následují "služební metody pro potomkovské třídy, metody pokytují různé funkčnosti, které se opakobaně užívají v potomkovských třídách
 * 
 * 
 *  
 */
        /**
         * Metoda vrací html kód formuláře umožňujícího nastavit parametry filtrování datových objektů v seznamu.
         * Metoda použije hlavičku tabulky zadanou v parametru $hlavickaTabulky a podle vlastnosti sloupce vygeneruje formulář.
         * 
         * @param type $nazevFormulare
         * @param type $hlavickaTabulky
         * @return HTML_QuickForm 
         */
        protected function filtrovani($nazevFormulare, $hlavickaTabulky)
        {
            $form = new HTML_QuickForm($nazevFormulare, "post", $this->cestaSem->generujUri());
            
            // volba Tableless rendereru
            $renderer =& new HTML_QuickForm_Renderer_Tableless();            
            
            foreach ($hlavickaTabulky->sloupce as $sloupec) {
                if ($sloupec->prikazGenerujiciPoleReferencovanychObjektu AND $sloupec->nazevVlastnostiReferencovanehoObjektu)
                {
                    $aa = $sloupec->prikazGenerujiciPoleReferencovanychObjektu;
                    $cmd = "\$poleReferencovanychObjektu = ".$sloupec->prikazGenerujiciPoleReferencovanychObjektu.";";
                    eval($cmd);
                    $vlastnost = $sloupec->nazevVlastnostiReferencovanehoObjektu;
                    unset($poleSelect);
                    $poleSelect[""] = "";
                    foreach($poleReferencovanychObjektu as $objektProSelect)
                        $poleSelect[$objektProSelect->id] = $objektProSelect->$vlastnost;
                    $form->addElement("select", $sloupec->nazevSloupceDb, $sloupec->popisek, $poleSelect);
                } else {
                    if ($sloupec->nazevSloupceDb)
                    {
                    $form->addElement("text", $sloupec->nazevSloupceDb, $sloupec->popisek);                        
                    }
                }
            }            
            $form->addElement("submit", "submitFiltrovat", "Filtrovat");
            $form->addElement("submit", "submitNefiltrovat", "Nefiltrovat");
            
            $this->filtr = new Stranka_Element_Filtr();
            if($form->validate())
            {
		$data = $form->exportValues();
                if ($data["submitFiltrovat"]) {
                    unset($data["submitFiltrovat"]);
                    unset($data["submitNefiltrovat"]);
                    $this->filtr = Stranka_Element_Filtr::like($data);
// SVOBODA následná volání by asi mohla být v foreach pro všechny selecty
//                    $this->filtr->striktni(Data_Seznam_SAkcePredpoklad::ID);
//                    $this->filtr->striktni(Data_Seznam_SAkcePredpoklad::ID_S_TYP_AKCE_FK);
//                    $this->filtr->striktni(Data_Seznam_SAkcePredpoklad::ID_S_TYP_AKCE_PRED_FK);
//                    $this->filtr->striktni(Data_Seznam_SAkcePredpoklad::ID_S_STAV_UCASTNIK_AKCE_PRED_FK);
                } else {
                    unset($data["submitFiltrovat"]);
                    unset($data["submitNefiltrovat"]);
                    $this->filtr = Stranka_Element_Filtr::like();
                }                
            }

// odkomentuj následující dva řádku pro jiný renderer než default
// $form->accept($renderer);
// return $renderer;
// zakomentuj následující řádek pro jiný renderer než default
        return $form;
        }

        
    protected function pouzijHlavicku($dataObjekt, $hlavickaTabulky) 
        {        
            $dataObjekt->odeberVsechnyVlastnostiIterator();
            foreach ($hlavickaTabulky->sloupce as $sloupec) {
                $nazevVlastnostiVHlavicce = $sloupec->nazevVlastnosti;                

 // musel bys přidat do hlavičky tabulky ještě prikazGenerujiciReferencovanyObjekt metodou najdiPodleId
                    if ($sloupec->prikazGenerujiciReferencovanyObjekt AND $sloupec->nazevVlastnostiReferencovanehoObjektu)
                    {
                        //vytvoří se nová vlastnost data objektu s názvem složeným z názvu původní vlastnosti (s FK) a textu "_referencovanaHodnota"
                        //vloží se do ní referencovanaHodnota a tato nová vlastnost se přidá do iterátoru
                        $cmd = "\$referencovanyObjekt = ".str_replace("%ID%", $dataObjekt->$nazevVlastnostiVHlavicce, $sloupec->prikazGenerujiciReferencovanyObjekt).";";
                        eval($cmd);
                        $vlastnost = $sloupec->nazevVlastnostiReferencovanehoObjektu;
                        $novaVlastnostDataObjektu = $nazevVlastnostiVHlavicce."_referencovanaHodnota";
                        $dataObjekt->$novaVlastnostDataObjektu = $referencovanyObjekt->$vlastnost;
                        $dataObjekt->pridejVlastnostIterator($novaVlastnostDataObjektu);                        
                    } else {                
                        if ($dataObjekt->$nazevVlastnostiVHlavicce == FALSE)
                        {
                            $ss = explode(self::SEPARATOR, $nazevVlastnostiVHlavicce);
                            $dd = $dataObjekt;
                            foreach ($ss as $value) 
                            {
                                $dd = $dd->$value;
                            } 
                            $dataObjekt->$nazevVlastnostiVHlavicce = $dd;
                        } 
                        $dataObjekt->pridejVlastnostIterator($nazevVlastnostiVHlavicce);                        
                    }    
                        
            }        
        }

        /**
         * Metoda vytvoří array elementy, obsahující hodnoty potřebné pro přidání elementů metodami QuickForm do objektu formuláře. Hodnoty načte z datového objektu
         * typu FlatTable
         * @param object $dataFlatTableObjekt datový objekt vytvořený třídou FlatTable
         * @param string $nazevHlavnihoObjektu pokud není zadán, metoda předpokládá, že objekt $dataFlatTableObjekt není vlastností hlavního objektu, jde o samostatný
         *               objekt typu FlatTable a jako název hlavního objektu použije hodnotu konstanty (řetězec "Flat_FlatTable")
         * @return type 
         */
        protected function pripravElementyFormulareZFlatTableObjektu($dataFlatTableObjekt, $nazevHlavnihoObjektu = NULL)
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

            $klice = $dataFlatTableObjekt->dejKlice();
            $nazvy = $dataFlatTableObjekt->dejNazvy();
            $typy = $dataFlatTableObjekt->dejTypy();
            $delky = $dataFlatTableObjekt->dejDelky();

            foreach ($nazvy as $key => $hodnotaVlastnosti) {
                $index = $nazevHlavnihoObjektu . self::SEPARATOR . $dataFlatTableObjekt->jmenoTabulky . self::SEPARATOR . $hodnotaVlastnosti;      //$jmenoVlastnosti = název sloupce v db
                if ($klice[$key]) {             //elementy, které odpovídají sloupcům db tabulky obsahujícím klíče musí být hidden nebo static
                    $elementy["typ"][$index] = "static";                            
                } else {
                    if ($typy[$key] == "date") {
                        $elementy["typ"][$index] = "date";
                        $elementy["atributy"][$index] = array("format" => "d.m.Y", "minYear" => "1900", "maxYear" => "2050");
                        $elementy["default"][$index] = Data_Konverze_Datum::zSQL($dataFlatTableObjekt->$hodnotaVlastnosti)->dejDatumProQuickForm() ;
                    } else {
                        if (intval($delky[$key]) <= self::MAX_POCET_ZNAKU_TYPU_TEXT) {
                            $elementy["typ"][$index] = "text";
                            $elementy["atributy"][$index] = array("size" => self::MAX_SIRKA_TYPU_TEXT);

                        } else {
                            $elementy["typ"][$index] = "textarea";
// TODO: nefunguje rows, ve výsledném kódu jsou hodnoty, které se berou kdoví odkud, přitom cols je nastaveno správně
//                                                                        $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA, 
//                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval($delky[$key])/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
                            $elementy["atributy"][$index] = array("cols" => self::POCET_SLOUPCU_TYPU_TEXTAREA, 
                                "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  intval(intval(strlen(htmlspecialchars($dataFlatTableObjekt->$hodnotaVlastnosti)))/self::POCET_SLOUPCU_TYPU_TEXTAREA)+0.5));
//                                        "rows" => min(self::MAX_POCET_RADKU_TYPU_TEXTAREA,  "1"));
                        }
                    }
                }
                if (!$elementy["default"][$index]) {
                    $elementy["default"][$index] = htmlspecialchars($dataFlatTableObjekt->$hodnotaVlastnosti);                                                        
                    //TODO: html hack pro obsah databázového sloupce, který "asi" obsahuje html
                    if (!is_array($dataFlatTableObjekt->$hodnotaVlastnosti) AND strpos($dataFlatTableObjekt->$hodnotaVlastnosti, "<") !== FALSE)
                    {
                        $elementy["default"][$index] = $elementy["default"][$index]."<br></br><div style='border: solid blue; width: 400px; list_style_type: circle'>".str_replace("\"", "'", $dataFlatTableObjekt->$hodnotaVlastnosti)."</div>";
                    }
                }

                $elementy["titulek"][$index] = $hodnotaVlastnosti;
            }

            return $elementy;
        }
 
        protected function prepisTituilkyZPrezentace($elementy, $nazevHlavnihoObjektu = NULL)
        {
                if (!$nazevHlavnihoObjektu) $nazevHlavnihoObjektu = self::NAZEV_HLAVNIHO_OBJEKTU_PREZENTACE_PRO_FLAT_TABLE;
                                
                $filtr = Data_Seznam_SPrezentace::HLAVNI_OBJEKT." = \"".$nazevHlavnihoObjektu."\"".
                            " AND ".Data_Seznam_SPrezentace::OBJEKT_VLASTNOST." = \"".$dataObjekt->jmenoTabulky."\"";
                $prezentace = Data_Seznam_SPrezentace::vypisVse($filtr, $this->parametry["razeniPodle"], $this->parametry["razeni"]);
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