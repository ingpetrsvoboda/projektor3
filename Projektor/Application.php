<?php

/**
 * Description of Application
 *
 * @author pes2704
 */
class Projektor_Application extends Framework_Application_AbstractApplication {
    
    /**
     * Metoda zabezpečuje celé zpracování hhtp requestu.
     * Metoda načte stav aplikace uložený v instanční proměnné $appStatus, požádá instanční proměnnou (objekt) $router o vyhodnocení requestu a vrácení
     *  vhodného dispatcheru, dispatcher pak požádá o vygenerování response (dispatcher pro vytvoření response volá Controller a View).
     *  Pokud hlavička http response je "redirection", metoda pouze uloží nový stav $appStatus a provede přesměrování. Pokud se nejedná o přesměrování,
     *  metoda uloží nový $appStatus a tělo vygenerovaného response odešle na výstup.
     */
    public function run() {
        // načtení appStatus
//        $this->router->appStatus->requestTime  = microtime(TRUE);  //TRUE -> float;  //tímto zápisem vlastnosti dojde lazy load k načtení appStatus
        // router provede routování a vrátí správný dispatcher
//        $dispatcher =  $this->router->getDispatcher();
        // dispatcher vrátí Response (k tomu volá Controller a View)
//        $response = $dispatcher->dispatch($this->appStatus);

        Framework_Logger::resetLog();
//        $appStatusStorage = Projektor_Container::getStorageSession();
//        $appStatus = new Framework_Application_AbstractStatus($appStatusStorage);
        $router = new Projektor_Router_Projektor($this->appRequest, $this->appStatus);
        $dispatcher =  $router->getDispatcher();
        $response = $dispatcher->dispatch();        
        
        $this->appStatus->responseTime = microtime(TRUE);
        $response->send();
    }
}

?>
