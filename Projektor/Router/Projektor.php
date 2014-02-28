<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cesta
 *
 * @author pes2704
 */
class Projektor_Router_Projektor extends Framework_Router_AbstractRouter {    
    public function getDispatcher() {
        $controller = Framework_Application_AbstractApplication::getInstance()->getRequest()->get('controller');
        
        switch ($controller) {
            case 'login':
                return new Projektor_Dispatcher_Loginlogout();
                break;
            case 'logout':
                return new Projektor_Dispatcher_Logout();
                break;
            case 'tree':
                return new Projektor_Dispatcher_TreeDispatcher();
                break;
            case '':
                return new Projektor_Dispatcher_TreeDispatcher();
                break;

            default:
                throw new UnexpectedValueException('Unknown routing. Route in $_GET is: '.$route.' .');
                break;
        }
        return FALSE;
    }
}
