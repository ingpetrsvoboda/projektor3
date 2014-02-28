<?php
/**
 * Description of Output
 *
 * @author pes2704
 */
class Framework_Response_Output {
    
    const DEFAULT_MEDIA_TYPE_TEXT_HTML = TRUE;
    const DEFAULT_PROCEEDING_ALLOWED = TRUE;
    
    /**
     * Semafor signalizující, že dispatcher může pokračovat voláním dalšího kontroleru
     * @var boolean
     */
    protected $proceedingAllowed;
    
    /**
     * HTML výstup View - html objekt pro zapsání do response body
     * @var Framework_Document_Html 
     */
    protected $document;  
    
    /**
     * Řetězec, který může nahrazen vkládaným dokumentem
     * @var string 
     */
    protected $slot;
    
    /**
     * Konstruktor 
     * nastaví proccedingAllowwed na hodnotu konstatntx třídy self::DEFAULT_PROCEEDING_ALLOWED.
     * 
     * @param type $mediaType
     * @param type $mediaSubtype
     */
    public function __construct() {
        $this->proceedingAllowed = self::DEFAULT_PROCEEDING_ALLOWED;
        $this->slot = "";
    }
    
    public function setProceedingAllowed($allowed=self::DEFAULT_PROCEEDING_ALLOWED) {
        $this->proceedingAllowed = $allowed;
    }
    
    public function isProceedingAllowed() {
        return $this->proceedingAllowed;
    }
    
    /**
     * Nastaví output dokument.
     *
     * @param Framework_Document_DocumentInterface $document
     * @return Framework_Document_DocumentInterface Dokument, který byl nastaven jako obsah 
     */
    public function setDocument(Framework_Document_DocumentInterface $document) {
        $this->document = $document;
        return $this->document;
    }
    
    /**
     * Vrací output dokument
     * @return Framework_Document_DocumentInterface
     */
    public function getDocument() {
        return $this->document;
    }
    
    public function includeDocument(Framework_Document_DocumentInterface $includingDocument) {
        $this->document->includeDocument($includingDocument, $this->getSlot());
        return $this;
    }
    
    /**
     * Nastaví řetězec slot.
     * @param string $slot
     */
    public function setSlot($slot) {
        $this->slot = (string) $slot;
        return $this->slot;
    }
    
    /**
     * Vrací řetězec slot
     * @return string
     */
    public function getSlot() {
        return $this->slot;
    }
}
