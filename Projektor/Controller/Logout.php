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
class Projektor_Controller_Logout implements Projektor_Controller_ControllerInterface {

    private $appStatus;
    private $response;
    private $user;

    /**
     *
     * @param Projektor_Data_Iterator $user
     */
    public function __construct(Projektor_App_StatusInterface $appStatus, Projektor_App_Response_ResponseInterface $response, Projektor_User_BaseInterface $user) {
        $this->appStatus = $appStatus;
        $this->response = $response;
        $this->user = $user;
    }

    /**
     *
     * @return type
     */
    public function getResult() {

        //v proměnné $_SESSION['originating_uri'] se předává uri odkud došlo k přesměrování sem (na login),
        //když není správně nastavená, asi se sem uživatel dostal nějakým zlotřilým způsobem
        $originating_uri = $this->appStatus->originating_uri;
        if (!$originating_uri) {
            ob_clean();
            echo "Chybny pokus o pristup do logout sekce. Kontaktujte administratora.";
            ob_end_flush();
            session_start();
            session_write_close();
            exit();
        }
        $this->user->logout();
        //echo "name:".$name." pass:".$password." userid:".$userid."<br>";
        setcookie("lastname",$data["name"],time()+3600);
        $this->response->setRedirectLocation($this->appStatus->originating_uri);   //jde na uri odkud došlo k přesměrování na logout
        unset($this->appStatus->originating_uri);
        return array();  //třída controller vrací pole context - v tomto případě prázdné
    }
}
?>
