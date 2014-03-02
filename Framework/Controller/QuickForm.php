<?php
/**
 * Description of Framework_Controller_QuickForm
 *
 * @author pes2704
 */
abstract class Framework_Controller_QuickForm extends Framework_Controller_AbstractController {
    
    protected function renderFormCreateViewAndSetOutput(HTML_QuickForm2 $form, $template) {
        //verze bez Twig
        //$renderer = HTML_QuickForm2_Renderer::factory('default');
        //$form->render($renderer);
        //// Output javascript libraries, needed for client-side validation
        //$html = $renderer->getJavascriptBuilder()->getLibraries(true, true);
        //$html .= $renderer;

        // pole dat vytvoří Array renderer Quickformu
        $renderer = HTML_QuickForm2_Renderer::factory('array');
        $form->render($renderer);
        if (!$this->context) {
            $this->context = array();
        }
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
    
}
