<?php

/**
 * Description of Application
 *
 * @author pes2704
 */
class Projektor_App_Application {

    private $appStatus;
    private $router;

    public function __construct(Projektor_App_StatusInterface $appStatus, Projektor_App_Router_RouterInterface $router) {
        $this->appStatus = $appStatus;
        $this->router = $router;
    }

    public function run() {
        // načtení appStatus
        $this->appStatus->requestTime  = microtime(TRUE);  //TRUE -> float;  //tímto zápisem vlastnosti dojde lazy load k načtení appStatus
        // router provede routování a vrátí správný dispatcher
        $dispatcher =  $this->router->getDispatcher();
        // dispatcher vrátí Response (k tomu volá Controller a View)
        $response = $dispatcher->getResponse($this->appStatus);

        if ($response->isRedirection()) {
            // uložení appStatus a přesměrování
            $this->appStatus->responseTime = microtime(TRUE);  //TRUE -> float
            $this->appStatus->store();
            $response->redirect();
        } else {
            // uložení appStatus a vygenerování výstupu
            $echo = $response->getResponseBody();
            $this->appStatus->responseTime = microtime(TRUE);  //TRUE -> float
            $this->appStatus->store();
            echo $echo;
        }
    }
}

?>
