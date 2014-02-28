<?php
class Projektor_Dispatcher_TreeDispatcher extends Framework_Dispatcher_AbstractDispatcher {

    /**
     *
     * @var string
     */
    private $prefixCesty;
    /**
     *
     * @var Projektor_Dispatcher_TreeDispatcher_Vertex 
     */
    private $rootVertex;

    public function getPrefixCesty() {
        return $this->prefixCesty;
    }

    private function setPrefixCesty($prefix) {
        $this->prefixCesty = $prefix;
    }

    public function dispatch() {
        $request = Framework_Application_AbstractApplication::getInstance()->getRequest();
        $this->setPrefixCesty($request->getScriptName()."?controller=tree&cesta=");
        Framework_Logger::resetLog();
        $cesta = $request->get('cesta');
        if ($cesta) {
            $this->rootVertex = unserialize($cesta);
        } else {
            $this->rootVertex = new Projektor_Dispatcher_TreeDispatcher_Vertex('Projektor_Controller_Page_Index');  //výchozí stránka
        }
        $output = $this->rootVertex->dispatchVertex($vertex);
        $this->setResponse($output);
    }
}