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
class Projektor_Controller_Logout extends Framework_Controller_AbstractController {

    /**
     *
     * @return type
     */
    public function getOutput() {
        $user = Projektor_Container::getUser();
        $appStatus = Framework_Application_AbstractApplication::getInstance()->getAppStatus();
        //v appStatus->originating_uri se předává uri odkud došlo k přesměrování sem (na login),
        //když není správně nastavená, asi se sem uživatel dostal nějakým zlotřilým způsobem
        $originating_uri = $appStatus->originating_uri;
        if (!$originating_uri) {
            ob_clean();
            echo "Chybny pokus o pristup do logout sekce. Kontaktujte administratora.";
            ob_end_flush();
            session_start();
            session_write_close();
            exit();
        }
        $user->signOut();

        $this->output->setRedirection($originating_uri);
        $this->output->setProceedingAllowed(FALSE);  //pro jistotu
        $appStatus->originating_uri=NULL;
        return $this->output;  //třída controller vrací pole context - v tomto případě prázdné
    }
}
?>
