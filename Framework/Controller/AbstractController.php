<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Framework_Controller_Abstract
 *
 * @author pes2704
 */
abstract class Framework_Controller_AbstractController implements Framework_Controller_ControllerInterface {

    /**
     * Instanční proměnná - parametry předávané volanému kontroleru
     * @var array
     */
    protected $controllerParams = array();

    /**
     * Promenne pro View.
     */
    protected $context;
    
    /**
     * @var Framework_Response_Output 
     */
    protected  $output;
    
    public function __construct(Framework_Response_Output $output, array $controllerParams = null) {
        $this->output = $output;
        $this->controllerParams = $controllerParams;
    }
    
    /**
     * Vrací pole parametrů kontroléru
     * @return array
     */
    public function getControllerParams() {
        return $this->controllerParams;
    }
    
    /**
     * Nastaví pole parametrů kontroléru
     * @param array $controllerParams
     */
    public function setControllerParams(array $controllerParams) {
        $this->controllerParams = $controllerParams;    //TODO: kontrola parametru (is_array, ... ) a vyhození výjimky
    }
    
    /**
     * @return Framework_Response_Output
     */
    abstract public function getOutput();
}
