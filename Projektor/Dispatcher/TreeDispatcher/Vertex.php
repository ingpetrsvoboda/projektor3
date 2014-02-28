<?php

class Projektor_Dispatcher_TreeDispatcher_Vertex{
    
    const INDEX_OF_CONTEXT_FOR_CHILD_PAGES = 'potomek';
    
    /**
     * @var Projektor_Dispatcher_TreeDispatcher_Vertex 
     */
    public $parentVertex;
    /**
     * @var Projektor_Dispatcher_TreeDispatcher_Vertex 
     */
    public $childVertices;
    /**
     * @var string
     */
    public $controllerClassName;
    
    public $controllerParams;
    
    public function __construct($controllerClassName, $controllerParams = NULL) {
        $this->controllerClassName = $controllerClassName;
        $this->controllerParams = $controllerParams;
    }
    
    public function __sleep() {
        return array('parentVertex', 'childVertices', 'controllerClassName', 'controllerParams');
    }

    public function __wakeup() { }
    
    public function addChildVertex($controllerClassName, $controllerParams = NULL) {
        if (class_exists($controllerClassName)) {
            $this->childVertices[] = new Projektor_Dispatcher_TreeDispatcher_Vertex($controllerClassName, $controllerParams);
        } else {
            throw new InvalidArgumentException('Neexistuje třída '.$controllerClassName);
        }
        return end($this->childVertices);          //metoda vrací právě přidaného potomka
    }

    /**
     * Metoda rekurzivně prochází strom do hloubky
     * @return Framework_Response_Output
     * @throws LogicException Pokud některý z potomků vrací jiný obsah (jiný Media Type obsahu) než text/html
     */
    public function dispatchVertex() {
        $controllerClassName = $vertex->controllerClassName;
        $controller = new $controllerClassName($this, NULL, $this->controllerParams);
        
        $controller->vychozi();
        if ($this->childVertices) {
            $childOutputs = new Framework_Response_Output();  //html dokument
            foreach($this->childVertices as $childVertex) {
                $childPageOutput = $childVertex->dispatchVertex();
                if (!$childPageOutput->isMediaTypeText() OR !$childPageOutput->isMediaSubtypeHtml()) {
                    throw new LogicException('Návratový output objekt potomka stránky '.$this->controllerClassName
                                              .', kterým je '.$childVertex->controllerClassName.' má jiný Media Type obsahu než text/html');
                }
                $childOutputs->mergeBodyHtmlDocument($childPageOutput->getDocument());
                //volám metodu rodičovské stránky pro potomka
                $potomkovskaMetoda = "potomek°".$childVertex->controllerClassName;
                $controller->$potomkovskaMetoda();
            }   
        } else {
            // není žádný potomek - volám metodu potomek není
            $controller->potomekNeni();
        }
        $controller->vzdy();
        
        $controller->setViewContextValue(self::INDEX_OF_CONTEXT_FOR_CHILD_PAGES, $childOgetBodyHtmlDocumentBodyHtml()->getHtmlDocumentText());
        return $controller->getOutput();
    }

    /**
     * Metoda vrací uri do stranky, ve ktere je volana (odkaz na "tuto" stranku)
     * mohla by se jmenovet semUri, ale je téměř vždy užita v akci formuláře, proto název formAction.
     * Příklad: "<form action=".$uzel->formAction(),">
     * @return string
     */
    public function formAction() {
        $prefix = Projektor_Dispatcher_TreeDispatcher::getPrefixCesty();
        $koren = Projektor_Dispatcher_TreeDispatcher::getRootVertexDispatcher();
        $uri = $prefix.serialize($koren);
        return $uri;
    }

    /**
     * Metoda vraci uri do stranky potomka
     * @param string $controllerClassName
     * @param string $metoda
     * @param array $controllerParams
     * @return string
     */
    public function childUri($controllerClassName, $controllerParams=null) {
        $p = $this->addChildVertex($controllerClassName, $controllerParams);
        $uri = $this->formAction();
        $p = array_pop($this->childVertices);
        return $uri;
    }

    /**
     * Metoda vraci uri do stranky rodice
     * @return type
     */
    public function backUri() {
        // uschová potomky svého rodiče = sourozence
        $uzlyPotomci = $this->parentVertex->childVertices;
        //smaže potomky z potomků rodiče (smaže sourozence) a vygeneruje uri
        $this->parentVertex->childVertices = $this->najdiASmazUzel($uzlyPotomci);
        $koren = Projektor_Dispatcher_TreeDispatcher::getRootVertexDispatcher();
        $prefix = Projektor_Dispatcher_TreeDispatcher::getPrefixCesty();
        $uri = $prefix.serialize($koren);
        // vrátí uschované potomky (sourozence)
        $this->parentVertex->childVertices = $uzlyPotomci;
        return $uri;
    }

    public function breadcrumbNavigation(Projektor_Dispatcher_TreeDispatcher_Vertex $vertexDispatcher = null, $navigation = "") {
        if (!$vertexDispatcher) $vertexDispatcher = $this;

        if ($vertexDispatcher->parentVertex) {
            if ($navigation) $navigation = " - ".$navigation;
            $navigation = $vertexDispatcher->parentVertex->controllerClassName . $navigation;
            $navigation = $this->breadcrumbNavigation($vertexDispatcher->parentVertex, $navigation);
        }

        return $navigation;

    }

    public static function debugUri($uri) {
        $cesta = unserialize(str_replace(Projektor_Dispatcher_TreeDispatcher::getPrefixCesty(), "", $uri));
        return print_r($cesta, True);
    }


// ################################# PRIVÁTNÍ FUNKCE ########################################################

    private function najdiASmazUzel($uzlyPotomci) {
        if (count($uzlyPotomci) == 0) {
            $uzlyPotomci = array();
            return $uzlyPotomci;
        }
        foreach($uzlyPotomci as $key=>$uzelPotomek) {
            if ($uzelPotomek === $this) {
                    $potomciPred = array();
                    $potomciPost = array();
                    if ($key > 0) {
                        $potomciPred = array_slice($uzlyPotomci, 0, $key);
                    }
                    if ($key+1 <  count($uzlyPotomci)) {
                        $potomciPost = array_slice($uzlyPotomci, $key+1);
                    }
                    $uzlyPotomci = array_merge($potomciPred, $potomciPost);
                    return $uzlyPotomci;
            }
        }
    }

    private function shodaUzlu(Projektor_Dispatcher_TreeDispatcher_Vertex $uzel1, Projektor_Dispatcher_TreeDispatcher_Vertex $uzel2)
    {
        // When using the comparison operator (==), object variables are compared in a simple manner,
        // namely: Two object instances are equal if they have the same attributes and values, and are instances of the same class.
        // On the other hand, when using the identity operator (===), object variables are identical if and only if they refer to the same
        // instance of the same class.
        if (($uzel1->controllerClassName == $uzel2->controllerClassName)) return TRUE;
        return FALSE;
    }
}