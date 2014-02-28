<?php
/**
 * Description of Twig
 *
 * @author pes2704
 */
class Framework_View_Template extends Framework_View_View implements Framework_View_ViewInterface {

    private $templateObject;

    /**
     *
     * @param array $context Asociativní pole dat pro šablonu
     * @param Twig_Environment $templateSystemObject Objekt šablonovacího systému
     * @param string $templateName Jméno šablony
     */
    public function __construct(Framework_View_TemplateObjectInterface $templateObject) {
        $this->templateObject = $templateObject;
    }

    /**
     * Metoda vrací vykreslenou šasblonu ve tvaru html - základní metoda View
     * @return string
     */
    public function render(array $context=NULL) {
        $this->appendToContext($context);
        $content = $this->templateObject->render($this->context);
        return $content;
    }
}

?>
