<?php
/**
 * Objekt pro uložení obsahu elementu body. Element body je určen k použití jako obsah vlastnosti body
 * objektu Framework_Document_Html_HtmlElement.
 *
 * @author pes2704
 */
class Framework_Document_Html_BodyElement extends Framework_Document_Html_Element{
    
    /**
     * Metoda vrací HTML element body ve formě prostého textu.
     * 
     * @return string Element body ve formě prostého textu.
     */
    public function getCode() {
        return '<body '.$this->attributes.'>'.PHP_EOL.$this->text.PHP_EOL.'</body>'.PHP_EOL;
    }
}