<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author pes2704
 */
class Projektor_Dispatcher_Base {

    /**
     * Instanční proměnná
     * @var Projektor_App_Response_ResponseInterface
     */
    protected $response;

    /**
     * Instanční proměnná - parametry předávané volanému kontroleru
     * @var array
     */
    protected $controllerParams = array();


    public function __construct(Projektor_App_Response_ResponseInterface $response, array $controllerParams = null) {
        $this->response = $response;
        $this->controllerParams = $controllerParams;
    }

}

?>
