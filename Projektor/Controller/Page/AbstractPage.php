<?php
/**
 * Abstraktni trida Projektor_Stranka, popisuje obecnou stranku.
 * @author Marek Petko
 * @abstract
 */
abstract class Projektor_Controller_Page_AbstractPage extends Framework_Controller_AbstractController {
    
    /**
     * Konstanty pro služební metody
     */
    const SEPARATOR = "->";    //separuje název objektuVlastnosti a vlastnosti v objektu HlavickaTabulky (např. pro vlastnost smlouva->jmeno je v hlaviččce smlouva.self::SEPARATOR.jmeno

    /**
     * Počítadlo instancí objektů zděděných z této třídy
     */
    static $instance = 0;

    /**
     * Unikátní název kontroleru
     */
    public $controllerName;

    /**
     * Sablona stranky
     */
    protected $templateFile;

    /**
     * Třída datových objektů se kterými stranka pracuje
     */
    protected $tridaData;  //TODO: v kontrolerech (stranka) všude ODSTRANIT  použití tridaData - jsou to staré stránky neopravené
    
    /**
     * @var Projektor_Dispatcher_TreeDispatcher_Vertex 
     */
    protected $vertex;

    /**
     * 
     * @param array $controllerParams Parametry pro kotroler
     * @param Projektor_Dispatcher_TreeDispatcher_Vertex $vertex Dispatcher, který volá tento kontroler. Kontroler volá metody dispatcheru typu 
     * Projektor_Dispatcher_TreeDispatcher_Vertex pro generování uri.
     * @param Framework_Response_Output $output Návratový objekt metody getOutput()
     */
    public function __construct(Projektor_Dispatcher_TreeDispatcher_Vertex $vertex, Framework_Response_Output $output=NULL, array $controllerParams = null)
    {
        parent::__construct($output, $controllerParams);
        $this->vertex = $vertex;
        $this->controllerName = get_class().++self::$instance; //název třídy s číslem instance třídy
        $this->setViewContextValue("id", $this->controllerName);

    }
    

    /**
     * 
     * @return Framework_Response_Output
     */
    public function getOutput() {
        $phptalTemplateObject = Projektor_Container::getPhptalTemplateObject();        
        $phptalTemplateObject->loadTemplate(static::SABLONA);
        $view = new Framework_View_Template($phptalTemplateObject);
        $text = $view->render($this->context);
//        $bodyElement = new Framework_Document_Html_BodyElement();
//        $bodyElement->appendBodyText($text);
//        $htmlElement = new Framework_Document_Html_HtmlElement();
//        $htmlElement->setBodyElement($bodyElement);
//        $htmlDocument = new Framework_Document_HtmlDocument();
//        $htmlDocument->setHtmlElement($htmlElement);
        $htmlDocument->getHtmlElement()->getBodyElement()->appendText($text);
        $this->output->setDocument($htmlDocument);
        return $this->output;        
    }

    public function __call($metoda, $parametryMetody = null)
    {
            if(method_exists($this, $metoda))
            {
                Framework_Logger::setLog(array("stránka" => $this->controllerName, "třída" => get_class($this), "metoda" => $metoda));
                if ($parametryMetody)
                {
                    $ret = $this->$metoda($parametryMetody[0]);
                } else {
                    $ret = $this->$metoda();
                }
                return $ret;
            } else {
                Framework_Logger::setLog(array("Varování" => "Metoda ".$metoda." ve tride ".get_class($this)." neni definovana"));
                if (Projektor_Container::getDebug()) echo("<font color=\"red\">Metoda <em>".$metoda."</em> ve tride <strong>".get_class($this)."</strong> neni definovana!</font>");
                return NULL;
            }
    }
    
    abstract protected function vychozi();
    abstract protected function potomekNeni();
    
    protected function vzdy()
    {
        $this->setViewContextValue("id", $this->controllerName);
        $this->setViewContextValue("navigace", $this->vertex->breadcrumbNavigation());
        if ( !$this->vertex->returnsValues)
        {
            $tlacitkoZpet = new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->vertex->backUri());
            $this->setViewContextValue("tlacitkozpet", $tlacitkoZpet);
        }
    }
    

    public function setParameter($index, $value) {
        if (isset($value)) {
            $this->controllerParams[$index] = $value;
        } else {
            unset($this->controllerParams[$index]);
        }
        return $this;
    }

    public function getParameter($index) {
        return $this->controllerParams[$index];
    }    

protected function debuguj($sablona = NULL)
{
        $content = "<h1>Debugovaci vypis</h1>\n";
        $content .= "<h2>Logger:</h2>";
        $content .= "<pre>";
        $content .= Framework_Logger::getLogText();
        $content .= "</pre>";
        $content .= "<h2>Vygenerovany template z ".$this->templateFile."</h2>";
        if($sablona)
        {
                $hlHTML = Text_Highlighter::factory("HTML");
                $content .= $hlHTML->highlight($sablona);
        }

        $content .= "<h2>Nastavene promenne ".$this->controllerName."</h2>\n";
        if($this->context)
        {
                $content .= "<pre>";
                $content .= print_r($this->context, TRUE);
                $content .= "</pre>";
        }

        return $content;
}

    /**
     * Set hodnotu do pole proměnných pro View.
     */
    public function setViewContextValue($key, $value)
    {
        if(isset($value)) $this->context[$key] = $value;
    }
    
    /**
     * Set hodnotu z pole proměnných pro View.
     */
    public function getViewContextValue($key)
    {
        return $this->context[$key];
    }    
}