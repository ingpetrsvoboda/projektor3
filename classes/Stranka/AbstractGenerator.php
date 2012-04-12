<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class AbstractGenerator
{
    protected $cesta;
    protected $nazev;
    
    public function __construct($cesta, $nazev) {
        $this->cesta = $cesta;
        $this->nazev = $nazev;
    }
    
    abstract protected function generujStranku();

}
?>
