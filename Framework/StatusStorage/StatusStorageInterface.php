<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author pes2704
 */
interface Framework_StatusStorage_StatusStorageInterface {

    public function read($name);
    public function write($name, $value);
    public function destroy($name);
}

?>
