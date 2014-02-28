<?php
abstract class Framework_Router_AbstractRouter implements Framework_Router_RouterInterface
{   
    /**
     * Objekt, ve kterém je ukládán stav aplikace mezi jednotlivými requesty
     * @var Framework_Application_StatusInterface
     */
    public $appStatus;
    
    /**
     * @var Framework_Request_Request 
     */
    public $request;

    // proměnné přístupné přes gettery
    public $dispatcher;

    public function __construct(Framework_Request_Request $request, Framework_Application_StatusInterface $appStatus) {
        $this->appStatus = $appStatus;
        $this->request = $request;
    }

    abstract public function getDispatcher();
}