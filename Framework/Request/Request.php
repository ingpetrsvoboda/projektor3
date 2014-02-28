<?php
/**
 * Description of Request
 *
 * @author pes2704
 */
class Framework_Request_Request {
    
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_OVERRIDE = '_METHOD';    
    
    /**
     * @var Framework_Environment 
     */
    protected $environment;
    
    private $queryHash;
    private $formHash;
    private $cookieHash;
    
    /**
     * @var array
     */
    protected static $formDataMediaTypes = array('application/x-www-form-urlencoded');

    public function __construct() {
        $this->environment = Framework_Environment::getInstance();
    }
    
    /**
     * Fetch GET data
     *
     * This method returns a key-value array of data sent in the HTTP request query string, or
     * the value of the array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param   string $key
     * @return  array|mixed|null
     */
    public function get( $key = null ) {
        if ( !isset($this->queryHash) ) {
            $output = array();
            if ( function_exists('mb_parse_str')) {
                mb_parse_str($this->environment->getQUERY_STRING(), $output);
            } else {
                parse_str($this->environment->getQUERY_STRING(), $output);
            }
            $this->queryHash = $this->stripSlashesIfMagicQuotes($output);
        }
        if ( $key ) {
            if ( isset($this->queryHash[$key]) ) {
                return $this->queryHash[$key];
            } else {
                return null;
            }
        } else {
            return $this->queryHash;
        }
    }

    /**
     * Fetch POST data
     *
     * This method returns a key-value array of data sent in the HTTP request body, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param   string $key
     * @return  array|mixed|null
     * @throws  RuntimeException If environment input is not available
     */
    public function post( $key = null ) {
        if (!is_string($this->environment->getInput())) {
            throw new RuntimeException('Missing input in environment variables');
        }
        if ( !isset($this->formHash) ) {
            $this->formHash = array();
            if ( $this->isFormData() ) {
                $output = array();
                if ( function_exists('mb_parse_str')) {
                    mb_parse_str($this->environment->getInput(), $output);
                } else {
                    parse_str($this->environment->getInput(), $output);
                }
                $this->formHash = $this->stripSlashesIfMagicQuotes($output);
            }
        }
        if ( $key ) {
            if ( isset($this->formHash[$key]) ) {
                return $this->formHash[$key];
            } else {
                return null;
            }
        } else {
            return $this->formHash;
        }
    }

    /**
     * Get HTTP method
     * @return string
     */
    public function getMethod()
    {
        return $this->environment->getREQUEST_METHOD();
    }

    /**
     * Is this a GET request?
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() === self::METHOD_GET;
    }

    /**
     * Is this a POST request?
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() === self::METHOD_POST;
    }

    /**
     * Is this a PUT request?
     * @return bool
     */
    public function isPut()
    {
        return $this->getMethod() === self::METHOD_PUT;
    }

    /**
     * Is this a DELETE request?
     * @return bool
     */
    public function isDelete()
    {
        return $this->getMethod() === self::METHOD_DELETE;
    }

    /**
     * Is this a HEAD request?
     * @return bool
     */
    public function isHead()
    {
        return $this->getMethod() === self::METHOD_HEAD;
    }

    /**
     * Is this a OPTIONS request?
     * @return bool
     */
    public function isOptions()
    {
        return $this->getMethod() === self::METHOD_OPTIONS;
    }

    /**
     * Is this an AJAX request?
     * @return bool
     */
    public function isAjax()
    {
        if (isset($this->environment->X_REQUESTED_WITH) && $this->environment->X_REQUESTED_WITH === 'XMLHttpRequest') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is this an XHR request? (alias of Framework_Request_Request::isAjax)
     * @return bool
     */
    public function isXhr()
    {
        return $this->isAjax();
    }

    /**
     * Fetch GET and POST data
     *
     * This method returns a union of GET and POST data as a key-value array, or the value
     * of the array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string           $key
     * @return array|mixed|null
     */
    public function params($key = null)
    {
        $union = array_merge($this->get(), $this->post());
        if ($key) {
            if (isset($union[$key])) {
                return $union[$key];
            } else {
                return null;
            }
        } else {
            return $union;
        }
    }

    /**
     * Fetch PUT data (alias for Framework_Request_Request::post)
     * @param  string           $key
     * @return array|mixed|null
     */
    public function put($key = null)
    {
        return $this->post($key);
    }

    /**
     * Fetch DELETE data (alias for Framework_Request_Request::post)
     * @param  string           $key
     * @return array|mixed|null
     */
    public function delete($key = null)
    {
        return $this->post($key);
    }

    /**
     * Fetch COOKIE data
     *
     * This method returns a key-value array of Cookie data sent in the HTTP request, or
     * the value of a array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string            $key
     * @return array|string|null
     */
    public function cookies($key = null)
    {
        if (!isset($this->cookieHash)) {
            $cookieHeader = isset($this->environment->HTTP_COOKIE) ? $this->environment->HTTP_COOKIE : '';
            $this->cookieHash = $this->parseCookieHeader($cookieHeader);
        }
        if ($key) {
            if (isset($this->cookieHash[$key])) {
                return $this->cookieHash[$key];
            } else {
                return null;
            }
        } else {
            return $this->cookieHash;
        }
    }

    /**
     * Does the Request body contain parseable form data?
     * @return bool
     */
    public function isFormData()
    {
        $method = $this->getMethod();
        return ($method === self::METHOD_POST && is_null($this->getContentType())) || in_array($this->getMediaType(), self::$formDataMediaTypes);
    }

    /**
     * Get Headers
     *
     * This method returns a key-value array of headers sent in the HTTP request, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string $key
     * @param  mixed  $default The default value returned if the requested header is not available
     * @return mixed
     */
    public function headers($key = null, $default = null)
    {
        if ($key) {
            $key = strtoupper($key);
            $key = str_replace('-', '_', $key);
            $key = preg_replace('@^HTTP_@', '', $key);
            if (isset($this->environment->$key)) {
                return $this->environment->$key;
            } else {
                return $default;
            }
        } else {
            $headers = array();
            foreach ($this->environment as $key => $value) {
                if (strpos($key, 'HTTP') === 0) {
                    $headers[$key] = $value;
                }
            }

            return $headers;
        }
    }

    /**
     * Get Body
     * @return string
     */
    public function getBody()
    {
        return $this->environment->getInput();
    }

    /**
     * Get Content Type
     * @return string
     */
    public function getContentType()
    {
        if ($this->environment->getCONTENT_TYPE()) {
            return $this->environment->getCONTENT_TYPE();
        } else {
            return null;
        }
    }

    /**
     * Get Media Type (type/subtype within Content Type header)
     * @return string|null
     */
    public function getMediaType()
    {
        $contentType = $this->getContentType();
        if ($contentType) {
            $contentTypeParts = preg_split('/\s*[;,]\s*/', $contentType);

            return strtolower($contentTypeParts[0]);
        } else {
            return null;
        }
    }

    /**
     * Get Media Type Params
     * @return array
     */
    public function getMediaTypeParams()
    {
        $contentType = $this->getContentType();
        $contentTypeParams = array();
        if ($contentType) {
            $contentTypeParts = preg_split('/\s*[;,]\s*/', $contentType);
            $contentTypePartsLength = count($contentTypeParts);
            for ($i = 1; $i < $contentTypePartsLength; $i++) {
                $paramParts = explode('=', $contentTypeParts[$i]);
                $contentTypeParams[strtolower($paramParts[0])] = $paramParts[1];
            }
        }

        return $contentTypeParams;
    }

    /**
     * Get Content Charset
     * @return string|null
     */
    public function getContentCharset()
    {
        $mediaTypeParams = $this->getMediaTypeParams();
        if (isset($mediaTypeParams['charset'])) {
            return $mediaTypeParams['charset'];
        } else {
            return null;
        }
    }

    /**
     * Get Content-Length
     * @return int
     */
    public function getContentLength()
    {
        if ($this->environment->getCONTENT_LENGTH()) {
            return (int) $this->environment->getCONTENT_LENGTH();
        } else {
            return 0;
        }
    }

    /**
     * Get Host
     * @return string
     */
    public function getHost()
    {
        if ($this->environment->getHTTP_HOST()) {
            if (strpos($this->environment->getHTTP_HOST(), ':') !== false) {
                $hostParts = explode(':', $this->environment->getHTTP_HOST());

                return $hostParts[0];
            }

            return $this->environment->getHTTP_HOST();
        } else {
            return $this->environment->getSERVER_NAME();
        }
    }

    /**
     * Get Host with Port
     * @return string
     */
    public function getHostWithPort()
    {
        return sprintf('%s:%s', $this->getHost(), $this->getPort());
    }

    /**
     * Get Port
     * @return int
     */
    public function getPort()
    {
        return (int) $this->environment->getSERVER_PORT();
    }

    /**
     * Get Scheme (https or http)
     * @return string
     */
    public function getScheme()
    {
        return $this->environment->getUrlScheme();
    }

    /**
     * Get Script Name (physical path)
     * @return string
     */
    public function getScriptName()
    {
        return $this->environment->getSCRIPT_NAME();
    }

    /**
     * LEGACY: Get Root URI (alias for Framework_Request_Request::getScriptName)
     * @return string
     */
    public function getRootUri()
    {
        return $this->getScriptName();
    }

    /**
     * Get Path (physical path + virtual path)
     * @return string
     */
    public function getPath()
    {
        return $this->getScriptName() . $this->getPathInfo();
    }

    /**
     * Get Path Info (virtual path)
     * @return string
     */
    public function getPathInfo()
    {
        return $this->environment->getPATH_INFO();
    }

    /**
     * LEGACY: Get Resource URI (alias for Framework_Request_Request::getPathInfo)
     * @return string
     */
    public function getResourceUri()
    {
        return $this->getPathInfo();
    }

    /**
     * Get URL (scheme + host [ + port if non-standard ])
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getScheme() . '://' . $this->getHost();
        if (($this->getScheme() === 'https' && $this->getPort() !== 443) || ($this->getScheme() === 'http' && $this->getPort() !== 80)) {
            $url .= sprintf(':%s', $this->getPort());
        }

        return $url;
    }

    /**
     * Get IP
     * @return string
     */
    public function getIp()
    {
        if (isset($this->environment->X_FORWARDED_FOR)) {
            return $this->environment->X_FORWARDED_FOR;
        } elseif (isset($this->environment->HTTP_CLIENT_IP)) {
            return $this->environment->HTTP_CLIENT_IP;
        } else {
            return $this->environment->getREMOTE_ADDR();
        }
    }

    /**
     * Get Referrer
     * @return string|null
     */
    public function getReferrer()
    {
        if (isset($this->environment->HTTP_REFERER)) {
            return $this->environment->HTTP_REFERER;
        } else {
            return null;
        }
    }

    /**
     * Get Referer (for those who can't spell)
     * @return string|null
     */
    public function getReferer()
    {
        return $this->getReferrer();
    }

    /**
     * Get User Agent
     * @return string|null
     */
    public function getUserAgent()
    {
        if ($this->environment->getUSER_AGENT()) {
            return $this->environment->getUSER_AGENT();
        } else {
            return null;
        }
    }    
    
    public function getRequestTime() {
        if ($this->environment->getREQUEST_TIME()) {
            return $this->environment->getREQUEST_TIME();
        } else {
            return null;
        }        
    }
    
    public function getRequestTimeFloat() {
        if ($this->environment->getREQUEST_TIME_FLOAT()) {
            return $this->environment->getREQUEST_TIME_FLOAT();
        } else {
            return null;
        }        
    }
    
    public function getRequestUri() {
        if ($this->environment->getREQUEST_URI()) {
            return $this->environment->getREQUEST_URI();
        } else {
            return null;
        }        
    }
################################################################

    /**
     * Parse cookie header
     *
     * This method will parse the HTTP request's `Cookie` header
     * and extract cookies into an associative array.
     *
     * @param  string
     * @return array
     */
    private function parseCookieHeader($header)
    {
        $cookies = array();
        $header = rtrim($header, "\r\n");
        $headerPieces = preg_split('@\s*[;,]\s*@', $header);
        foreach ($headerPieces as $c) {
            $cParts = explode('=', $c);
            if (count($cParts) === 2) {
                $key = urldecode($cParts[0]);
                $value = urldecode($cParts[1]);
                if (!isset($cookies[$key])) {
                    $cookies[$key] = $value;
                }
            }
        }

        return $cookies;
    }
    
    /**
     * Strip slashes from string or array
     *
     * This method strips slashes from its input. By default, this method will only
     * strip slashes from its input if magic quotes are enabled. Otherwise, you may
     * override the magic quotes setting with either TRUE or FALSE as the send argument
     * to force this method to strip or not strip slashes from its input.
     *
     * @var    array|string    $rawData
     * @return array|string
     */
    private function stripSlashesIfMagicQuotes($rawData, $overrideStripSlashes = null)
    {
        $strip = is_null($overrideStripSlashes) ? get_magic_quotes_gpc() : $overrideStripSlashes;
        if ($strip) {
            return self::_stripSlashes($rawData);
        } else {
            return $rawData;
        }
    }
    
    
    /**
     * Strip slashes from string or array
     * @param  array|string $rawData
     * @return array|string
     */
    private function _stripSlashes($rawData)
    {
        return is_array($rawData) ? array_map(array('self', '_stripSlashes'), $rawData) : stripslashes($rawData);
    }
}

?>
