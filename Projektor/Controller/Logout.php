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
class Projektor_Controller_Logout extends Framework_Controller_QuickForm {

    /**
     *
     * @return type
     */
    public function getOutput() {
        $application = Framework_Application_AbstractApplication::getInstance();
        /* Formulář */
        $action = $application->getRequest()->getScriptName()."?controller=loginlogout";
        $form = new HTML_QuickForm2("logout", "post", array("action" => $action));
//        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(
//                      array("name" => $lastname)));    // po dobu životnosti cookie lastname vyplňuje do formuláře jméno posledního přihlášení
        $fieldsetCredentials = $form->addElement('fieldset')->setLabel('Odhlášení uživatele ze systému Projektor');
        $submit = $fieldsetCredentials->addElement('submit', 'submit', array('value' => 'Odhlásit'));

        /* Zpracovani */
        $data = array();
        $loginWarning = "";
        if($form->validate()) {
            $data = $form->getValue();
            $user = Projektor_Container::getUser();        
            $user->signOut();
            $this->output->setMessage("logout");            
            $this->output->setProceedingAllowed(FALSE);  //pro jistotu
            return $this->output;
        } else {
            $this->output->setProceedingAllowed(TRUE);
            $this->renderFormCreateViewAndSetOutput($form, 'logout.twig');
            return $this->output;
        }
    }
}
?>
