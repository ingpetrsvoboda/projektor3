<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author pes2704
 */
class Projektor_Dispatcher_Logout extends Framework_Dispatcher_AbstractDispatcher implements Framework_Dispatcher_DispatcherInterface {

    public function dispatch() {
        $controller = new Projektor_Controller_Logout($this);
        return $controller->getOutput();
    }
}

?>
