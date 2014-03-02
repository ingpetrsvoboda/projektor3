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
class Projektor_Controller_Loginlogout extends Framework_Controller_AbstractController {
    public function getOutput() {
        $user = Projektor_Container::getUser();        
        if ($user->isSignedIn()) {
            $controller = new Projektor_Controller_Logout(); 
            $this->output = $controller->getOutput();
            if ($this->output->getMessage() == "logout") {
                $controller = new Projektor_Controller_Login(); 
                $this->output = $controller->getOutput();                
                $this->output->setProceedingAllowed(FALSE);
            } else {
                $this->output->setProceedingAllowed(TRUE);
            }
        } else {
            $controller = new Projektor_Controller_Login(); 
            $this->output = $controller->getOutput();
            if ($this->output->getMessage() == "login") {
                $controller = new Projektor_Controller_Logout();         
                $this->output = $controller->getOutput();                
                $this->output->setProceedingAllowed(TRUE);
            } else {
                $this->output->setProceedingAllowed(FALSE);                
            }
        }
        return $this->output; 
    }
}
