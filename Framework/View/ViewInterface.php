<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author pes2704
 */
interface Framework_View_ViewInterface {

    /**
     * Metoda vrací text - obsah 
     */
    public function render(array $context=NULL);
}

?>
