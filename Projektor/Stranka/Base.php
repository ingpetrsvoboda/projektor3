<?php
/**
 * Abstraktni trida Projektor_Stranka, popisuje obecnou stranku.
 * @author Marek Petko
 * @abstract
 */
abstract class Projektor_Stranka_Base
{
    /**
     * Konstanty pro služební metody
     */
    const SEPARATOR = "->";    //separuje název objektuVlastnosti a vlastnosti v objektu HlavickaTabulky (např. pro vlastnost smlouva->jmeno je v hlaviččce smlouva.self::SEPARATOR.jmeno

    /**
        * Počítadlo instancí objektů zděděných z třídy Stranka
        */
    static $instance = 0;

    /**
     * Uzel, podle kterého je stránka generována předávaný v konstruktoru
     * @var Projektor_Dispatcher_Uzel Description
     */
   protected $uzel;

    /**
     * Status objekt aplikace předávaný v konstruktoru
     * @var Projektor_App_StatusInterface
     */
    protected $appStatus;

    /**
     * Reference na rodičovskou stránku
     */
   protected $strankaPotomek;

    /**
     * Nazev stranky
     */
    public $nazev;

    /**
     * Sablona stranky
     */
    protected $souborSablony;

    /**
     * Třída datových objektů se kterými stranka pracuje
     */
    protected $tridaData;  //TODO: v kontrolerech (stranka) všude tridaData  a v metodách přidat k parametru typ Interface (collection nebo item)
    /**
     * Objekt typu Data_Collection s daty pro stránky typu SEZNAM
     */
//    protected $dataCollection;
    /**
     * Objekt typu Data_Item s daty pro stránky typu MENU a DETAIL
     */
//    protected $dataItem;
    /**
     * Formular stranky
     */
//    protected $formular;
    /**
     * Filtrovaci formular stranky
     */
//    protected $filtrovaciFormular;

    /**
     * Promenne stranky.
     */
    public $promenne;

    public function __construct(Projektor_App_StatusInterface $appStatus, Projektor_Dispatcher_Uzel $uzel)
    {
        $this->uzel = $uzel;
        $this->appStatus = $appStatus;
        $this->nazev = $uzel->trida.++self::$instance; //název třídy s číslem instance třídy
        $this->novaPromenna("id", $this->nazev);
    }


    public function __call($metoda, $parametryMetody = null)
    {
            if(method_exists($this, $metoda))
            {
                Projektor_App_Logger::setLog(array("stránka" => $this->nazev, "třída" => get_class($this), "metoda" => $metoda));
                if ($parametryMetody)
                {
                    $ret =$this->$metoda($parametryMetody[0]);
                } else {
                    $ret = $this->$metoda();
                }
            }
            else {
                Projektor_App_Logger::setLog(array("Varování" => "Metoda ".$metoda." ve tride ".get_class($this)." neni definovana"));
                if (Projektor_App_Container::getDebug()) echo("<font color=\"red\">Metoda <em>".$metoda."</em> ve tride <strong>".get_class($this)."</strong> neni definovana!</font>");
            }
            if ($ret) return $ret;
    }

    protected function vzdy()
    {
        $this->novaPromenna("id", $this->nazev);
        $this->novaPromenna("navigace", $this->uzel->drobeckovaNavigace());
        if ( !$this->uzel->vraciHodnoty)
        {
            $tlacitkoZpet = new Projektor_Stranka_Element_Tlacitko("Zpět", $this->uzel->zpetUri());
            $this->novaPromenna("tlacitkozpet", $tlacitkoZpet);
        }
    }

protected function debuguj($sablona = NULL)
{
        $content = "<h1>Debugovaci vypis</h1>\n";
        $content .= "<h2>Logger:</h2>";
        $content .= "<pre>";
        $content .= Projektor_App_Logger::getLogText();
        $content .= "</pre>";
        $content .= "<h2>Vygenerovany template z ".$this->souborSablony."</h2>";
        if($sablona)
        {
                $hlHTML = Text_Highlighter::factory("HTML");
                $content .= $hlHTML->highlight($sablona);
        }

        $content .= "<h2>Nastavene promenne ".$this->nazev."</h2>\n";
        if($this->promenne)
        {
                $content .= "<pre>";
                $content .= print_r($this->promenne, TRUE);
                $content .= "</pre>";
        }

        return $content;
}

    /**
     * Prida nebo upravi promennou do pole prommenych pro šablonu.
     */
    public function novaPromenna($klic, $hodnota)
    {
//        $this->promenne[$this->nazev][$klic] = $hodnota;
        if(isset($hodnota)) $this->promenne[$klic] = $hodnota;
    }
}