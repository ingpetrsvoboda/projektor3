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
class Projektor_Controller_Login extends Framework_Controller_QuickForm {

    const SABLONA = "login.twig";
    
    const CHECK_CLIENT_REACTION_TIME = TRUE;
    const LOWEST_REACTION_TIME = 0.5;
    const HIGHEST_REACTION_TIME = 60;

    public function getOutput() {
        $application = Framework_Application_AbstractApplication::getInstance();
//        $originating_uri = $application->getAppStatus()->originating_uri;
//        //v appStatus->originating_uri se předává uri odkud došlo k přesměrování sem (na login),
//        //když není správně nastavená, asi se sem uživatel dostal nějakým zlotřilým způsobem
//        if (!$originating_uri) {
//            $this->forceExit();
//        }        
        /* Formulář */
        $action = $application->getRequest()->getScriptName()."?controller=loginlogout";
        $form = new HTML_QuickForm2("login", "post", array("action" => $action));
//        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(
//                      array("name" => $lastname)));    // po dobu životnosti cookie lastname vyplňuje do formuláře jméno posledního přihlášení
        $fieldsetCredentials = $form->addElement('fieldset')->setLabel('Přihlášení uživatele do systému Projektor');
        $name = $fieldsetCredentials->addElement('text', 'name')->setLabel('Jméno: ');
        $name->addRule('required', 'Jméno je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $password = $fieldsetCredentials->addElement('password', 'password')->setLabel('Heslo: ');
        $password->addRule('required', 'Heslo je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
//        $form->addElement('submit', 'submit', array('value' => 'Přihlásit'));
        $submit = $fieldsetCredentials->addElement('submit', 'submit', array('value' => 'Přihlásit'));


        /* Zpracovani */
        $data = array();
        $loginWarning = "";
        if($form->validate()) {
            $data = $form->getValue();
            if ($this->signInUser($data)) {
                $this->output->setMessage("login");
                return $this->output;
            } else {
                $loginWarning = "Přihlášení se nezdařilo";
                //vyčištění viditelných proměnných
                $name->setValue("");
                $password->setValue("");
            }
        }
        $this->context = $data + array('login_warning' => $loginWarning);
        $this->renderFormCreateViewAndSetOutput($form, 'login.twig');  //zapíše dokument do outputu
        return $this->output;
    }
    
    private function signInUser($data) {
        $application = Framework_Application_AbstractApplication::getInstance();
        //lehká obrana proti robotům - vycházím z předpokladu, že robot bude rychlejší neř 0.5sec a dávam uživateli minutu času
        $zpozdeni = $application->getRequest()->getRequestTime() - $application->getAppStatus()->responseTime;
        if (self::CHECK_CLIENT_REACTION_TIME AND $zpozdeni>self::LOWEST_REACTION_TIME AND $zpozdeni<self::HIGHEST_REACTION_TIME) {
            $user = Projektor_Container::getUser();        
            return $user->signIn($data["name"],$data["password"]);
        }        
    }
    
    private function forceExit() {
        ob_clean();
        echo "Chybny pokus o pristup do login sekce. Kontaktujte administratora.";
        ob_end_flush();
        session_start();
        session_write_close();
        exit();
    }
}

?>
