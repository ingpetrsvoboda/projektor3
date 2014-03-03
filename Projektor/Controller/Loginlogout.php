<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loginlogout
 *
 * @author pes2704
 */
class Projektor_Controller_Loginlogout extends Framework_Controller_AbstractMiddlewareController {
    public function getOutput() {
        $user = Projektor_Container::getUser();        
        if ($user->isSignedIn()) {
            $controller = new Projektor_Controller_Logout($this->output); 
            $this->output = $controller->getOutput();
            if ($this->output->getMessage() == "logout") {
                $controller = new Projektor_Controller_Login($this->output); 
                $this->output = $controller->getOutput();                
                $this->setProceedingAllowed(FALSE);
            } else {
                $this->setProceedingAllowed(TRUE);
            }
        } else {
            $controller = new Projektor_Controller_Login($this->output); 
            $this->output = $controller->getOutput();
            if ($this->output->getMessage() == "login") {
                $controller = new Projektor_Controller_Logout($this->output);         
                $this->output = $controller->getOutput();                
                $this->setProceedingAllowed(TRUE);
            } else {
                $this->setProceedingAllowed(FALSE);                
            }
        }
        return $this->output; 
    }
}
