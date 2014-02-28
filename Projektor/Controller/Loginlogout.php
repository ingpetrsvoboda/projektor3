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
class Projektor_Controller_Loginlogout extends Framework_Controller_AbstractController {

    const SABLONA = "login.twig";
    
    const CHECK_CLIENT_REACTION_TIME = TRUE;
    const LOWEST_REACTION_TIME = 0.5;
    const HIGHEST_REACTION_TIME = 60;

    /**
     * 
     * @return array Asociativní pole pro šablonu
     */
    public function getOutput() {

    }
    
    private function login() {
        $application = Framework_Application_AbstractApplication::getInstance();
//        $originating_uri = $application->getAppStatus()->originating_uri;
//        //v appStatus->originating_uri se předává uri odkud došlo k přesměrování sem (na login),
//        //když není správně nastavená, asi se sem uživatel dostal nějakým zlotřilým způsobem
//        if (!$originating_uri) {
//            $this->forceExit();
//        }        
        /* Formulář */
        $action = $application->getRequest()->getScriptName()."?controller=loginlogout&action=login";
        $form = new HTML_QuickForm2("login", "post", array("action" => $action));
//        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(
//                      array("name" => $lastname)));    // po dobu životnosti cookie lastname vyplňuje do formuláře jméno posledního přihlášení
        $fieldsetCredentials = $form->addElement('fieldset')->setLabel('Přihlášení uživatele do systému Projektor');
        $name = $fieldsetCredentials->addElement('text', 'name')->setLabel('Jméno: ');
        $name->addRule('required', 'Jméno je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $password = $fieldsetCredentials->addElement('password', 'password')->setLabel('Heslo: ');
        $password->addRule('required', 'Heslo je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $form->addElement('submit', 'submit', array('value' => 'Přihlásit'));

        /* Zpracovani */
        $data = array();
        $loginWarning = "";
        if($form->validate()) {
            $data = $form->getValue();
            if ($this->signInUser($data)) {
                return TRUE;
            } else {
                $loginWarning = "Přihlášení se nezdařilo";
                //vyčištění viditelných proměnných
                $name->setValue("");
                $password->setValue("");
            }
        }
        $this->context = $data + array('login_warning' => $loginWarning);
        $this->renderFormCreateViewAndSetOutput($form, 'login.twig');
        return FALSE;
    }
    
    private function signInUser($data) {
        //lehká obrana proti robotům - vycházím z předpokladu, že robot bude rychlejší neř 0.5sec a dávam uživateli minutu času
        $zpozdeni = $application->getRequest()->getRequestTime() - $application->getAppStatus()->responseTime;
        if (self::CHECK_CLIENT_REACTION_TIME AND $zpozdeni>self::LOWEST_REACTION_TIME AND $zpozdeni<self::HIGHEST_REACTION_TIME) {
            return $user->signIn($data["name"],$data["password"]);
        }        
    }
    
    private function Logout() {
                /* Formulář */
        $action = $application->getRequest()->getScriptName()."?controller=loginlogout&action=logout";
        $form = new HTML_QuickForm2("login", "post", array("action" => $action));
//        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(
//                      array("name" => $lastname)));    // po dobu životnosti cookie lastname vyplňuje do formuláře jméno posledního přihlášení
        $fieldsetCredentials = $form->addElement('fieldset')->setLabel('Přihlášení uživatele do systému Projektor');
        $name = $fieldsetCredentials->addElement('text', 'name')->setLabel('Jméno: ');
        $name->addRule('required', 'Jméno je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $password = $fieldsetCredentials->addElement('password', 'password')->setLabel('Heslo: ');
        $password->addRule('required', 'Heslo je nutno zadat', null, HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $form->addElement('submit', 'submit', array('value' => 'Přihlásit'));

        /* Zpracovani */
        $data = array();
        $loginWarning = "";
        if($form->validate()) {
            $data = $form->getValue();
            if ($this->signInUser($data)) {
                return TRUE;
            } else {
                $loginWarning = "Přihlášení se nezdařilo";
                //vyčištění viditelných proměnných
                $name->setValue("");
                $password->setValue("");
            }
        }
        $this->context = $data + array('login_warning' => $loginWarning);
        $this->renderFormCreateViewAndSetOutput($form, 'login.twig');
        return FALSE;
        
        
        
                    $user->signOut();

    }
    
    private function renderFormCreateViewAndSetOutput(HTML_QuickForm2 $form, $template) {
        //verze bez Twig
        //$renderer = HTML_QuickForm2_Renderer::factory('default');
        //$form->render($renderer);
        //// Output javascript libraries, needed for client-side validation
        //$html = $renderer->getJavascriptBuilder()->getLibraries(true, true);
        //$html .= $renderer;

        // pole dat vytvoří Array renderer Quickformu
        $renderer = HTML_QuickForm2_Renderer::factory('array');
        $form->render($renderer);
        $this->context = $this->context + array(
                        'js_libraries' => $renderer->getJavascriptBuilder()->getLibraries(true, true),  // http://pear.php.net/manual/en/package.html.html-quickform2.javascript.php
                        'form'         => $renderer->toArray()
                       );
        
        $twigTemplateObject = Projektor_Container::getTwigTemplateObject();
        $twigTemplateObject->loadTemplate($template);
        $view = new Framework_View_Template($twigTemplateObject);
        
        $htmlDocument = new Framework_Document_Html();
        $bodyElem = $htmlDocument->getHtmlElement()->getBodyElement();
        $bodyElem->appendText($view->render($this->context));
        $this->output->setDocument($htmlDocument);
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
