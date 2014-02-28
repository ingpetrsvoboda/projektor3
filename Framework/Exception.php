<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Exception
 *
 * @author pes2704
 */
class Framework_Exception extends Exception {
    public function __construct($message, $code=0, $previous=NULL)
    {
        parent::__construct($message, $code, $previous);
    }
}