<?php
/**
 * Description of Framework_View_TemplateObject
 *
 * @author pes2704
 */
interface Framework_View_TemplateObjectInterface {

    public function loadTemplate($templateFileName);
    public function render(array $context);
}
