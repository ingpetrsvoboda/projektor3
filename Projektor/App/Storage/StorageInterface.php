<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author pes2704
 */
interface Projektor_App_Storage_StorageInterface {

    public function read($name);
    public function write($name, $value);
}

?>
