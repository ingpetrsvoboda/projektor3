<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Framework_View_TemplateObject
 *
 * @author pes2704
 */
class Framework_View_PHPTALTemplateObject implements Framework_View_TemplateObjectInterface{
    private $templateSystemObject;
    private $templateObject;
    
    public function __construct(PHPTAL $templateSystemObject) {
        $this->templateSystemObject = $templateSystemObject;
    }
    
    public function loadTemplate($templateFileName) {
        $this->templateObject = $this->templateSystemObject;
        $this->templateObject->setTemplate($templateFileName);
        return $this;
    }

    public function render(array $context) {
        if ($context) {
            foreach($context as $klic => $hodnota) $this->templateObject->$klic = $hodnota;
            $content = $this->templateObject->execute();
            return $content;
        } else {
            return NULL;
        }
    }
}
