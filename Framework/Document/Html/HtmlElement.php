<?php
/**
 * HtmlElement obsahuje elementy head a body. Element head je typu Framework_Document_Html_HeadElement
 * a element body je typu Framework_Document_Html_BodyElement.
 *
 * @author pes2704
 */
class Framework_Document_Html_HtmlElement {
    
    /**
     * @var Framework_Document_Html_HeadElement 
     */    
    private $head;
    
    /**
     * @var Framework_Document_Html_BodyElement 
     */
    private $body;

    /**
     * KOnstruktor naplní element head prázdným objektem typu Framework_Document_Html_HeadElement
     * a element body prázdným objektem Framework_Document_Html_BodyElement.
     */
    public function __construct() {
        $this->setHeadElement(new Framework_Document_Html_HeadElement());
        $this->setBodyElement(new Framework_Document_Html_BodyElement());
    }
    
    /**
     * 
     * @param Framework_Document_Html_HeadElement $head
     * @return Framework_Document_Html_HeadElement
     */
    public function setHeadElement(Framework_Document_Html_HeadElement $head) {
        $this->head = $head;
        return $this->head;
    }
    
    /**
     * 
     * @return Framework_Document_Html_HeadElement
     */
    public function getHeadElement() {
        return $this->head;
    }

    /**
     * 
     * @param Framework_Document_Html_BodyElement $body
     * @return Framework_Document_Html_BodyElement
     */
    public function setBodyElement(Framework_Document_Html_BodyElement $body) {
        $this->body = $body;
        return $this->body;
    }
    
    /**
     * 
     * @return Framework_Document_Html_BodyElement
     */
    public function getBodyElement() {
        return $this->body;
    }    
    
    /**
     * Metoda vrací tag html ve formě prostého textu. Pro obsah (inner text) elementu html 
     * se použije text elementu head a text elementu body.
     * 
     * @return string Tag html ve formě prostého textu.
     */
    public function getHtmlElementText() {
        return '<html>'.PHP_EOL.$this->head->getCode().PHP_EOL.$this->body->getCode().PHP_EOL.'</html>'.PHP_EOL;
        
    }
}
