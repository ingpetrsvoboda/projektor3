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
     * @var SplObjectStorage 
     */
    protected $controllers;

    /**
     * 
     * @param Framework_Response_Response $response
     * @param SplObjectStorage $controllers
     * @param SplObjectStorage $middlewareControllers
     */
    public function __construct(Framework_Response_Response $response=NULL, SplObjectStorage $controllers=NULL, SplObjectStorage $middlewareControllers=NULL) {
        if ($response) {
            $this->response = $response;
            Framework_Logger::setLog(get_class($this).': použit response objekt '.get_class($this->response));
        } else {
            $this->response = new Framework_Response_Response();
            Framework_Logger::setLog(get_class($this).': použit defaultní response objekt '.get_class($this->response));
        }
        if ($middlewareControllers) {
            $this->middlewareControllers = $middlewareControllers;
        } else {
            $this->middlewareControllers = new SplObjectStorage();
        }
        if ($controllers) {
            $this->controllers = $controllers;
        } else {
            $this->controllers = new SplObjectStorage();
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
    public function attachController(Framework_Controller_ControllerInterface $controller) {
        $this->controllers->attach($controller);
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
        $output = $this->chainControllerOutputs($this->middlewareControllers);
        $output = $this->chainControllerOutputs($this->controllers, $output);
        return $output;
    }
    
    private function chainControllerOutputs(SplObjectStorage $controllers, Framework_Response_Output $output=NULL) {
        if (count($controllers)>0) {
            foreach ($controllers as $controller) {
                $nextOutput = $controller->getOutput();
                if (isset($output)) {
                    $output->getDocument()->includeDocument($nextOutput->getDocument(), $nextOutput->getSlot());    
                } else {
                    $output = $nextOutput;
                }
                if (!$nextOutput->isProceedingAllowed()) return $output;
            }
        }
        return $output;
    }  

    protected function setResponse(Framework_Response_Output $output) {
        return $this->response;
    }
}
?>
