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
class Projektor_Dispatcher_Logout extends Projektor_Dispatcher_Base implements Projektor_Dispatcher_DispatcherInterface {

    public function getResponse(Projektor_App_StatusInterface $appStatus) {
        $user = Projektor_App_Container::getUser();
        $controller = new Projektor_Controller_Logout($appStatus, $this->response, $user);
        $context = $controller->getResult();
        $twig = Projektor_App_Container::getTwig();
        $view = new Projektor_View_Twig($context, $twig, 'logout.twig');
        $this->response->setResponseBody($view->render());
        return $this->response;
    }
}

?>
