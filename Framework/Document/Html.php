<?php
/**
 * Objekt html dokumentu. 
 * <p>Oobsahuje element !doctype a html. Elementu !doctype je možno nastavovat pouze atributy ve formě řetězce, 
 * element html je typu Framework_Document_Html_HtmlElement.</p>
 *
 * @author pes2704
 */
class Framework_Document_Html implements Framework_Document_DocumentInterface {
    
    const DEFAULT_DOCTYPE_ATTRIBUTE = 'html';
    
    /**
     *
     * @var string (HTML element) 
     */
    private $doctype;
    
    /**
     * @var Framework_Document_Html_HtmlElement 
     */
    private $html;
    
    /**
     * Konstruktor nastaví element !doctype na default hodnotu atribitu a element html
     * naplní prázným objektem Framework_Document_Html_HtmlElement
     */
    public function __construct() {
        $this->setDoctypeAttributes(self::DEFAULT_DOCTYPE_ATTRIBUTE);
        $htmlElement = new Framework_Document_Html_HtmlElement();
        $this->setHtmlElement($htmlElement);
    }
    
    /**
     * Metoda nastaví atributy tagu !doctype.
     * @param string $text Text, který bude použit jako atributy tagu !doctype
     * @return string Atributy tagu !doctype ve formě prostého textu
     */
    public function setDoctypeAttributes($text) {
        $this->doctype = ' '.trim((string) $text);
        return $this->doctype;
    }
    
    /**
     * Metoda vrací atributy tagu !doctype ve formě prostého textu.
     * @return string
     */
    public function getDoctypeAttributesText() {
        return $this->doctype;
    }
    /**
     * 
     * @param Framework_Document_Html_HtmlElement $html
     * @return Framework_Document_Html_HtmlElement
     */
    public function setHtmlElement(Framework_Document_Html_HtmlElement $html) {
        $this->html = $html;
        return $this->html;
    }
    
    /**
     * Metoda vrací html element html dokumentu.
     * @return Framework_Document_Html_HtmlElement
     */
    public function getHtmlElement() {
        return $this->html;
    }
    /**
     * Metoda vrací html dokument ve formě prostého textu. Pro obsah dokumentu html 
     * se použije text elementu !doctype a text elementu html.
     * 
     * @return string Obsah dokumentu html ve formě prostého textu.
     */
    public function getContent() {
        $content = '<!doctype'.$this->doctype.'>';
        $content .= $this->html->getHtmlElementText();
        return $content;
    }
    
    /**
     * Vloží html dokument do tohoto dokumentu. Pokud v elementu '<body>' tohoto html dokumentu je řetězec $slot,
     * vloží text elementu '<body>' vkládaného html dokumentu místo žetězce $slot. Jinak vloží 
     * text elementu '<body>' vkládaného html dokumentu na konec. Obsah elementu '<head>' vkládaného 
     * dokumentu vloží vždy na konec elementu '<head>' tohoto dokumentu.
     * @param Framework_Document_DocumentInterface $mergedHtmlDocument Metoda akceptuje pouze dokument typu Framework_Document_Html
     * @throws LogicException 'Není možno zahrnout dokument '.get_class($mergedHtmlDocument).' do dokumentu '.get_class($mergedHtmlDocument).'.'
     * @throws LogicException Není možno sloučit html dokumenty s různými atributy !doctype.
     */
    public function includeDocument(Framework_Document_DocumentInterface $mergedHtmlDocument, $slot="") {
        if (get_class($mergedHtmlDocument)==get_class($this)) {
            $mergedDoctype = $mergedHtmlDocument->getDoctypeAttributesText();
            if ($this->doctype != $mergedDoctype) {
                throw new LogicException('Není možno sloučit html dokumenty s různými atributy !doctype: '.$mergedDoctype.' a '.$this->doctype);
            }
            $mergedHeadText = $mergedHtmlDocument->getHtmlElement()->getHeadElement()->getCode();
            $this->html->getHeadElement()->appendText($mergedHeadText);
            $mergedBodyText = $mergedHtmlDocument->getHtmlElement()->getBodyElement()->getCode();
            $this->html->getBodyElement()->mergeBodyElementText($mergedBodyText, $slot);
        } else {
            throw new LogicException('Není možno zahrnout dokument '.get_class($mergedHtmlDocument).' do dokumentu '.get_class($mergedHtmlDocument).'.');
        }
    }
}
