<?php
/**
 * Description of Twig
 *
 * @author pes2704
 */
class Projektor_View_Twig implements Projektor_View_ViewInterface {

    private $context;
    private $templateSystemObject;
    private $templateFileName;

    /**
     *
     * @param array $context Asociativní pole dat pro šablonu
     * @param Twig_Environment $templateSystemObject Objekt šablonovacího systému
     * @param string $templateName Jméno šablony
     */
    public function __construct(array $context, Twig_Environment $templateSystemObject, $templateFileName) {
        $this->context = $context;
        $this->templateSystemObject = $templateSystemObject;
        $this->templateFileName = $templateFileName;
    }

    /**
     * Metoda vrací vykreslenou šasblonu ve tvaru html - základní metoda View
     * @return string
     */
    public function render() {
        $templateObject = $this->templateSystemObject->loadTemplate('login.twig');
        $content = $templateObject->render($this->context);
        return $content;
    }
}

?>
