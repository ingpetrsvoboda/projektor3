<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author pes2704
 */
abstract class Framework_View_View implements Framework_View_ViewInterface {
    
    protected $context = array();
    
    public function setContext(array $appendContext=NULL) {
        $this->context = array_merge($this->context, $appendContext);
    }
    
    public function appendToContext(array $appendContext=NULL) {
        $this->context = array_merge($this->context, $appendContext);
    }
}

?>
