<?php

/**
 * Description of Response
 *
 * @author pes2704
 */
class Framework_Response_Response {
    const HTTP_DEFAULT_VERSION = 'HTTP/1.1';

    const MEDIA_TYPE_TEXT = 'text';
    const MEDIA_TYPE_APPLICATION = 'application';
    
    const MEDIA_SUBTYPE_HTML = 'html';
    const MEDIA_SUBTYPE_PLAIN = 'plain';
    const MEDIA_SUBTYPE_PDF = 'pdf';
    
    /**
     *
     * @var Framework_Response_Cookies 
     */
    protected $cookies;
    /**
     *
     * @var Framework_Response_Headers 
     */
    protected $headers;
    /**
     *
     * @var sttring Target URL for redirection response
     */
    protected $redirectLocation;
    /**
     *
     * @var Framework_Document_DocumentInterface 
     */
    protected $document;
    /**
     *
     * @var string HTTP body
     */
    protected $body;
    
    /**
     * @var int HTTP status code
     */
    protected $httpStatus;

    /**
     * Kód označující typ obsahu v body - odpovídá MIME - Media Type
     * @var string 
     */
    protected $mediaType;
    
    /**
     * Kód označující podtyp obsahu v body - odpovídá MIME - Medie Subtype
     * @var string 
     */
    protected $mediaSubtype;
    
    /**
     * @var array HTTP response codes and messages
     */
    protected static $messages = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );
    
    public function __construct($mediaType=self::MEDIA_TYPE_TEXT, $mediaSubtype=self::MEDIA_SUBTYPE_HTML) {
        $this->setMediaType($mediaType);
        $this->setMediaSubtype($mediaSubtype);
        $this->httpStatus = $this->setHttpStatus(200);
        $this->headers = new Framework_Response_Headers(array('Content-Type' => 'text/html'));
    }
    
    /**
     * Set response HTTP status and Location header with redirection URL.
     * @param string $redirectionUrl
     * @param int $status Default status code is 302.
     */
    public function setRedirection($redirectionUrl, $status = 302) {
        $this->httpStatus = $status;
        $this->headers->merge(array('Location'=>$redirectionUrl));
    }
    
    /**
     * Get response HTTP status
     * @return int
     */
    public function getHttpStatus() {
        return $this->httpStatus;
    }
    /**
     * Set respone HTTP status
     * @param  int|null $HttpStatus
     * @return int
     */
    public function setHttpStatus($HttpStatus) {
        if (array_key_exists($HttpStatus, self::$messages)) {
            $this->httpStatus = (int) $HttpStatus;
        } else {
            throw new LogicException('Neznámý HTTP status '.  print_r($HttpStatus, 1));
        }
        return $this->httpStatus;
    }
    
    /**
     * Get cookie object
     * @return Framework_Response_Cookies
     */
    public function getCookies() {
        return $this->cookies;
    }
    
    /**
     * Set cookies
     * @param Framework_Response_Cookies $cookies
     * @return Framework_Response_Cookies
     */
    public function setCookies(Framework_Response_Cookies $cookies) {
        $this->cookies = $cookies;
        return $this->cookies;
    }
    
    /**
     * Set response document
     * @param Framework_Document_DocumentInterface $responseDocument
     * @return Framework_Document_DocumentInterface
     */
    public function setDocument(Framework_Document_DocumentInterface $responseDocument) {
        $this->document = $responseDocument;
        return $this->document;
    }
    
    /**
     * Get response document
     * @return Framework_Document_DocumentInterface
     */
    public function getDocument() {
        return $this->document;
    }

    /**
     * Get message for HTTP status code
     * @return string|null
     */
    public static function getMessageForHttpStatusCode($status)
    {
        if (isset(self::$messages[$status])) {
            return self::$messages[$status];
        } else {
            return null;
        }
    }    
################# Media Type #######################  
    public function setMediaTypeText() {
        $this->setMediaType(self::MEDIA_TYPE_TEXT);
    }
    
    public function isMediaTypeText() {
        if ($this->mediaType == self::MEDIA_TYPE_TEXT) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function setMediaTypeApplication() {
        $this->setMediaType(self::MEDIA_TYPE_APPLICATION);
    }
    
    public function isMediaTypeApplication() {
        if ($this->mediaType == self::MEDIA_TYPE_APPLICATION) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
################# Media Subtype #######################      
    public function setMediaSubtypeHtml() {
        $this->setMediaType(self::MEDIA_SUBTYPE_HTML);
    }
    
    public function isMediaSubtypeHtml() {
        if ($this->mediaSubtype == self::MEDIA_SUBTYPE_HTML) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function setMediaSubtypePdf() {
        $this->setMediaType(self::MEDIA_SUBTYPE_PDF);
    }
    
    public function isMediaSubtypePdf() {
        if ($this->mediaSubtype == self::MEDIA_SUBTYPE_PDF) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
################# Private functions #######################    

    private function setMediaType($mediaType) {
        if (isset($this->mediaType) AND $this->mediaType != $mediaType) {
            throw new BadMethodCallException('Objekt '.__CLASS__.' má již nastaven Media Type '.$this->mediaType.'. Nelze nastavit požadovaný Media Type '.$mediaType.'.');
        } else {
            $this->mediaType = $mediaType;
        }        
    }
    
    private function setMediaSubtype($mediaSubtype) {
        if (isset($this->mediaSubtype) AND $this->mediaSubtype != $mediaSubtype) {
            throw new BadMethodCallException('Objekt '.__CLASS__.' má již nastaven Media Subtype '.$this->mediaSubtype.'. Nelze nastavit požadovaný Media Type '.$mediaSubtype.'.');
        } else {
            $this->mediaSubtype = $mediaSubtype;
        }        
    }
    
###############################################################    
    /**
     * Finalize
     *
     * This prepares this response 
     */
    private function finalize()
    {
        if (in_array($this->status, array(204, 304))) {
            unset($this->headers['Content-Type'], $this->headers['Content-Length']);
            $this->body = '';
            $this->document = new Framework_Document_Text();
            $this->document->setText('');
        }
    }    
    
    public function send() {
        // set cookies
        if ($this->cookies) {
            foreach ($this->cookies as $cookie) {
                setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httponly);
            }
        }
        $this->finalize();
        //set headers
        if (headers_sent() === false) {
            //Send status
            if (strpos(PHP_SAPI, 'cgi') === 0) {
                header(sprintf('Status: %s', $this->getMessageForHttpStatusCode($this->httpStatus)));
            } else {
                if (isset($_SERVER["SERVER_PROTOCOL"])) {
                    $headerString = sprintf('%s %s', $_SERVER["SERVER_PROTOCOL"], $this->getMessageForHttpStatusCode($this->httpStatus));                    
                } else {
                    $headerString = sprintf('%s %s', self::HTTP_DEFAULT_VERSION, $this->getMessageForHttpStatusCode($this->httpStatus));
                }
                header($headerString);
            }
            //Set headers
            foreach ($this->headers as $name => $value) {
                $hValues = explode("\n", $value);
                foreach ($hValues as $hVal) {
                    $hVal = $hVal;
                    header("$name: $hVal", false);   //false = nepřepisuje již existující hlavičky stejného názvu, přidává je
                }
            }
        }
        //Send body
        echo $this->document->getContent();        
    }
}

?>
