<?php
/**
 * Description of Application
 * Třída vytváří svůj objekt jako singleton. Má privátní konstruktor. Její instance je dostupná metodou getInstance().
 * Při intancování třída vytvoří jako své vlastnosti objekty request a appStatus. Přijímá parametry $request a $appStatus, pokud je některý zadán
 * třída použije zadaný objekt. Pokud chybí parametr $request, třída vytvoří objekt Framework_Request_Request. Pokud chybí parametr $appStatus,
 * třída vytvoří objekt Framework_Application_AbstractStatus. Tyto vlastnosti jsou pak dostupné metodami getAppStatus() a getRequest(). Třída dále nabízí metodu
 * 
 * @author pes2704
 */
abstract class Framework_Application_AbstractApplication {
    /**
     * @var Framework_Application_AbstractApplication
     */
    protected static $application;
    
    /**
     * Instanční proměnná
     * @var Framework_Request_Request
     */
    protected $appRequest;

    /**
     * Instanční proměnná
     * @var Framework_Application_StatusInterface
     */
    protected $appStatus;
    /**
     *
     * @var type Framework_Router_RouterInterface
     */
    protected $appRouter;
    /**
     *
     * @var Framework_Dispatcher_AbstractDispatcher 
     */
    protected $appDispatcher;
    /**
     *
     * @var type Framework_Response_Response
     */
    protected $appResponse;
    
    /**
     * 
     * @param Framework_Request_Request $request 
     * @param Framework_Application_StatusInterface $appStatus
     */
    public function __construct(Framework_Request_Request $request, Framework_Application_StatusInterface $appStatus) {
        $this->appRequest = $request;
        $this->appStatus = $appStatus;
        static::$application = $this;          
    }
    
    public function __destruct() {
        $this->appStatus->store();
    }

    /**
     * Get application instance. 
     * @return Framework_Application_AbstractApplication
     */
    public static function getInstance()
    {
        return static::$application;
    }

    /**
     * @return Framework_Application_StatusInterface
     */
    public function getAppStatus() {
        return $this->appStatus;
    }
    
    /**
     * @return Framework_Request_Request
     */
    public function getRequest() {
        return $this->appRequest;
    }
    
    /**
     * @return Framework_Router_RouterInterface
     */
    public function getRouter() {
        return $this->appRouter;
    }
    /**
     * @return Framework_Dispatcher_AbstractDispatcher
     */
    public function getDispatcher() {
        return $this->appDispatcher;
    }
    
    /**
     * @return Framework_Response_Response
     */
    public function getResponse() {
        return $this->appResponse;
    }
    
    /**
     * Potomkovské třídy musí implementovat metodu run().
     * Metoda zabezpečuje celé zpracování hhtp requestu (request lifecycle).
     */
    abstract public function run();
    
}

?>
