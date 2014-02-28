<?php
/**
 * Objekt pro uložení obsahu elementu head. Element head je určen k použití jako obsah vlastnosti head
 * objektu Framework_Document_Html_HtmlElement.
 *
 * @author pes2704
 */
class Framework_Document_Html_HeadElement extends Framework_Document_Html_Element {
    /**
     * Metoda vrací HTML element head ve formě prostého textu. 
     * 
     * @return string Element head ve formě prostého textu.
     */
    
    public function getCode() {
        return '<head>'.PHP_EOL.$this->text.PHP_EOL.'</head>'.PHP_EOL;
    }
}
