<?php
/**
 * Objekt pro uložení obsahu elementu head. Element head je určen k použití jako obsah vlastnosti head
 * objektu Framework_Document_Html_HtmlElement.
 *
 * @author pes2704
 */
abstract class Framework_Document_Html_Element {
    /**
     * @var string Text, který bude vložen do otevíracího tagu <body ...> jako atributy tagu
     */
    protected $attributes;
    
    /**
     * @var string textový obsah (inner text) elemenntu
     * Text, typicky obsahující HTML elementy, které jsou uvnitř tagu body.
     */    
    protected $text;
    
    /**
     * Metoda přidá parametr na konec obsahu (inner text) HTML elementu ve formě prostého textu.
     * @param string $text Další část obsahu (inner text) HTML elementu ve formě prostého textu.
     * @return string Obsah (inner text) HTML elementu ve formě prostého textu.
     */
    public function appendText($text) {
        $this->text .= (string) $text;
        return $this->text;
    }
                
    /**
     * Sloučí obsahy elementů. Pokud v obsahu (inner text) elementu je řetězec $slot,
     * bude text slučovaného elementu vložen místo žetězce $slot. Jinak bude 
     * text slučovaného elementu vložen na konec.
     * @param string $text
     * @return string
     */
    public function mergeBodyElementText($text, $slot="") {
        if ($slot) {
            $this->text = str_replace($slot, $text, $this->text);
        } else {
            $this->text .= (string) $text;
        }
        return $this->text;
    }
    
    /**
     * Metoda nastaví attributy HTML elementu.
     * @param string $text Atributy HTML elementu ve formě prostého textu
     * @return string Atributy HTML elementu ve formě prostého textu
     */
    public function setAttributes($text) {
        $this->attributes = ' '.trim($text);
        return $this->attributes;
    }
}
