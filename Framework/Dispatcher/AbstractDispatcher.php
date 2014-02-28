<?php
/**
 * Description of Base
 *
 * @author pes2704
 */
abstract class Framework_Dispatcher_AbstractDispatcher  implements Framework_Dispatcher_DispatcherInterface {

    /**
     *
     * @var Framework_Response_Response 
     */
    protected $response;
    /**
     * @var SplObjectStorage
     */
    protected $middlewareControllers;
    
    /**
     *
     * @var Framework_Controller_ControllerInterface 
     */
    protected $controller;

    /**
     * 
     * @param Framework_Response_Response $response
     * @param Framework_Controller_ControllerInterface $controller
     * @param SplObjectStorage $middlewareControllers
     */
    public function __construct(Framework_Response_Response $response=NULL, Framework_Controller_ControllerInterface $controller = NULL, SplObjectStorage $middlewareControllers=NULL) {
        if ($response) {
            $this->response = $response;
        } else {
            $this->response = new Framework_Response_Response();
            Framework_Logger::setLog(__CLASS__.' - použit defaultní response objekt '.get_class($this->response));
        }
        if ($middlewareControllers) {
            $this->middlewareControllers = $middlewareControllers;
        } else {
            $this->middlewareControllers = new SplObjectStorage();
        }
        if ($controller) {
            $this->setController($controller);
        }
    }

    /**
     * Metoda vrací storage kontrolerů typu SplObjectStorage
     * @return SplObjectStorage
     */
    public function getMiddkewareControllers() {
        return $this->middlewareControllers;
    }

    /**
     * Metoda přidá kontroler do storage kontrolerů. Kontrolery jsou při dispatch volány
     * v opačném pořadí, než v jaké byly přidávány touito metodou. 
     * @param Framework_Controller_ControllerInterface $controller
     */
    public function attachMiddlewareController(Framework_Controller_ControllerInterface $controller) {
        $this->middlewareControllers->attach($controller);
    } 
    
    /**
     * Metoda nastaví kontroler. Tento kontroler je použit jako jediný kontroler nebo zavolán jako poslední.
     * @param Framework_Controller_ControllerInterface $controller
     */
    public function setController(Framework_Controller_ControllerInterface $controller) {
        $this->controller = $controller;
    }
    
    /**
     * Metoda nejprve volá postupně metodu getOutput všech kontrolerů nastavených v dispatcheru metodou attachController() 
     * a následně metodu getOutput kontroleru nastavený metodou setLastController nebo zadaného jako parametr při volání konstruktoru. 
     * Kontrolery nastavené v dispatcheru metodou attachMiddlewareController() jsou volány v pořadí, ve kterém byly metodou 
     * attachController() přidány do dispatcheru.
     */
    public function dispatch() {
        $output = $this->getChainedOutput();
        $this->response->setDocument($output->getDocument());
        return $this->response;
    }
    
    /**
     * provizorní verze - neřeší cookies, status
     * @return type
     */
    private function getChainedOutput() {
        if (count($this->middlewareControllers)>0) {
            foreach ($this->middlewareControllers as $middlewareController) {
                if (!isset($output)) {
                    $output = $middlewareController->getOutput();
                    if (!$output->isProceedingAllowed()) return $output;
                } else {
                    $middlewareOutput = $middlewareController->getOutput();
                    $output->getDocument()->includeDocument($middlewareOutput->getDocument());                    
                    if (!$middlewareOutput->isProceedingAllowed()) return $output;
                }
            }
            $output->getDocument()->includeDocument($this->controller->getOutput()->getDocument());
        } else {
            $output = $this->controller->getOutput();
        }        
        return $output;
    }
    
    protected function setResponse(Framework_Response_Output $output) {
        return $this->response;
    }
}
?>
