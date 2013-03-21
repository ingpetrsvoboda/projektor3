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
class Projektor_Controller_Login implements Projektor_Controller_ControllerInterface {


    const SABLONA = "login.twig";

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
        if(isset($_COOKIE['lastname'])) {
                $lastname=@$_COOKIE['lastname'];
                if (isset($lastname)) $lastname=trim($lastname);
            }

        //v proměnné $_SESSION['originating_uri'] se předává uri odkud došlo k přesměrování sem (na login),
        //když není správně nastavená, asi se sem uživatel dostal nějakým zlotřilým způsobem
        $originating_uri = $this->appStatus->originating_uri;
        if (!$originating_uri) {
            ob_clean();
            echo "Chybny pokus o pristup do login sekce. Kontaktujte administratora.";
            ob_end_flush();
            session_start();
            session_write_close();
            exit();
        }
        $zpozdeni = $this->appStatus->requestTime - $this->appStatus->responseTime;

        /* Formulář */
        $action = $_SERVER['REQUEST_URI'];
        $form = new HTML_QuickForm2("login", "post", array("action" => $action));
//        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array
//                    (
//                        "name" => $lastname,    // po dobu životnosti cookie lastname vyplňuje do formuláře jméno posledního přihlášení
//                    )));
        $fieldsetCredentials = $form->addElement('fieldset')->setLabel('Přihlášení uživatele do systému Projektor');
        $name = $fieldsetCredentials->addElement('text', 'name')->setLabel('Jméno: ');
        $name->addRule('required', 'Jméno je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $password = $fieldsetCredentials->addElement('password', 'password')->setLabel('Heslo: ');
        $password->addRule('required', 'Heslo je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $form->addElement('submit', 'submit', array('value' => 'Přihlásit'));

        /* Zpracovani */
        $data = array();
        if($form->validate()) {
            $data = $form->getValue();
            //lehká obrana proti robotům - vycházím z předpokladu, že robot bude rychlejší a dávam uživateli minutu času
            if (($zpozdeni>0.5 AND $zpozdeni<60) AND
                ($this->user->login($data["name"],$data["password"]))) {
                //echo "name:".$name." pass:".$password." userid:".$userid."<br>";
                setcookie("lastname",$data["name"],time()+3600);
                $this->response->setRedirectLocation($this->appStatus->originating_uri);   //jde na uri odkud došlo k přesměrování na login
                unset($this->appStatus->originating_uri);
                return array();  //třída controller vrací pole context - v tomto případě prázdné
            } else {
                $loginWarning = "Přihlášení se nezdařilo";
                //vyčištění viditelných proměnných
                $name->setValue("");
                $password->setValue("");
            }
        }

        //verze bez Twig
        //$renderer = HTML_QuickForm2_Renderer::factory('default');
        //$form->render($renderer);
        //// Output javascript libraries, needed for client-side validation
        //$html = $renderer->getJavascriptBuilder()->getLibraries(true, true);
        //$html .= $renderer;

        // pole dat vytvoří Array renderer Quickformu
        $renderer = HTML_QuickForm2_Renderer::factory('array');
        $form->render($renderer);
        $context = $data
                + array('login_warning' => $loginWarning)
                + array(
                        'js_libraries' => $renderer->getJavascriptBuilder()->getLibraries(true, true),  // http://pear.php.net/manual/en/package.html.html-quickform2.javascript.php
                        'form'         => $renderer->toArray()
                       );
        return $context;
    }
}

?>
