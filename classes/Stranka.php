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
        public $dbh;
        

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
	protected function __construct($cesta, $nazev = null, $nazev_flattable="", $nazev_jednotne="", $nazev_mnozne="", $vsechny_radky=FALSE, $dbh=NULL)
	{
		$this->cesta = $cesta; // ulozime si cestu
		$this->cestaSem = $this->cesta->sem(); // ulozime si cestu k teto strance/tride
		$this->dalsi = $this->cesta->dalsi(); // posuneme se v ceste na dalsi pozici a ulozime si ji do promenne
                $this->nazev = $nazev.++self::$instance; //název třídy s číslem instance třídy
                $this->nazev_flattable = $nazev_flattable;
                $this->nazev_jednotne = $nazev_jednotne;
                $this->nazev_mnozne = $nazev_mnozne;
                $this->vsechny_radky = $vsechny_radky;
                $this->dbh = $dbh;
                
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
        
}