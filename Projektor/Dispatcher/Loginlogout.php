<?php
/**
 * Description of Login
 *
 * @author pes2704
 */
class Projektor_Dispatcher_Loginlogout extends Framework_Dispatcher_AbstractDispatcher implements Framework_Dispatcher_DispatcherInterface {
    
    public function __construct() {
        $controller = new Projektor_Controller_Loginlogout();
        $this->attachMiddlewareController($controller);
    }

    public function dispatch() {
        $application = Framework_Application_AbstractApplication::getInstance();
        $user = Projektor_Container::getUser();        
        if ($user->isSignedIn()) {
                $this->logout!!!          
            $this->output->setProceedingAllowed(TRUE);
        } else {
            if ($this->login()) {
                $this->logout!!!
                $this->output->setProceedingAllowed(TRUE);
                return $this->output;
            }
        }
        $this->output->setProceedingAllowed(FALSE);
        return $this->output; 
        
        
        $this->response->setDocument($this->controller->getOutput()->getDocument());
        return $this->response;
    }
}

?>
