<?php
/**
 * Description of Framework_Document_Text
 *
 * @author pes2704
 */
class Framework_Document_Text implements Framework_Document_DocumentInterface {

    private $text;

    /**
     * Nasraví text obsahu.
     * @param string $text
     * @return string
     */
    public function setText($text) {
        $this->text = (string) $text;
        return $this->text;
    }    
    
    /**
     * Přidá další text na konec obasahu.
     * @param string $text
     * @return string
     */
    public function mergeText($text, $slot="") {
        if ($slot) {
            $this->text = str_replace($slot, $text, $this->text);
        } else {
            $this->text .= (string) $text;
        }
        return $this->text;
    }
     
    /**
     * vloží dokument do tohoto dokumentu. Pokud v textu tohoto dokumentu je řetězec $slot,
     * vloží text vkládaného dokumentu místo žetězce $slot. Jinak vloží 
     * text vkládaného dokumentu na konec. Vkládaný dokument je vždy vložen jako text (řetězec). Pro převod je použita 
     * metoda getContent() vkládaného dokumentu.
     * @param Framework_Document_DocumentInterface $document
     * @param type $slot
     * @return type
     */
    public function includeDocument(Framework_Document_DocumentInterface $document, $slot="") {
        return $this->mergeText($document->getContent(), $slot);
    }
    /**
     * Vrací obsah dokunmetu.
     * @return mixed
     */
    public function getContent() {
        return $this->text;
    }  
}
