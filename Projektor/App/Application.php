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
        $this->appStatus->requestTime  = microtime(TRUE);  //TRUE -> float;  //tímto zápisem vlastnosti dojde lazy load k načtení appStatus
        $dispatcher =  $this->router->getDispatcher();
        $response = $dispatcher->getResponse($this->appStatus);

        if ($response->isRedirection()) {
            $this->appStatus->responseTime = microtime(TRUE);  //TRUE -> float
            $this->appStatus->store();
            $response->redirect();
        } else {
            $echo = $response->getResponseBody();
            $this->appStatus->responseTime = microtime(TRUE);  //TRUE -> float
            $this->appStatus->store();
            echo $echo;
        }
    }
}

?>
