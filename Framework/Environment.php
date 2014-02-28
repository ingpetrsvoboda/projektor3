<?php
/**
 * Třída Framework_Environment - vznikla přepracováním třídy Environment, která je součástí frameworku Slim.
 * Licence a původní kód třídy Environment je uveden v komentáři pod třídou.
 * Třída Framework_Environment je změněna tak, že envitonment není pole, ale objekt a jednotlivé proměnné nejsou prvky pole, 
 * ale protected vlastnosti objektu. Ke všem vlastnostem jsou vytvořeny gettery.
 * Třída neimplemetujr rozhranní ArrayIterator.
 * Vlastnosti třídy, které odpovídají globálním proměnným php (prvkům globálních polí) jsou pojmenovány shodně s indexy 
 * těchto globálních polí velkými písmeny (např. proměnné $_SERVER['SCRIPT_NAME'] odpovídá vlastnost App_Environment->SCRIPT_NAME). 
 * Obdobně jsou pojmenovány gettery - např getSCRIPT_NAME().
 */
class Framework_Environment {
    /**
     * @var array
     */
//    protected $properties;

    /**
     * @var Framework_Environment
     */
    protected static $environment;

    /**
     * The HTTP request method. Which request method was used to access the page; i.e. 'GET', 'HEAD', 'POST', 'PUT'.
     * Note:
     * PHP script is terminated after sending headers (it means after producing any output without output buffering) if the request method was HEAD.
     * @var string
     */
    protected $REQUEST_METHOD;
    /**
     * The timestamp of the start of the request. Available since PHP 5.1.0.
     * @var string
     */
    protected $REQUEST_TIME;
    /**
     * The timestamp of the start of the request, with microsecond precision. Available since PHP 5.4.0.
     * @var string
     */
    protected $REQUEST_TIME_FLOAT;
    /**
     * URI of the request
     * @var string 
     */
    protected $REQUEST_URI;
    /**
     * The IP address from which the user is viewing the current page.
     * @var string
     */
    protected $REMOTE_ADDR;
    /**
     * The SCRIPT_NAME is the real, physical path to the application, be it in the root directory or a subdirectory of the public document root. \n
     * With htaccess, the SCRIPT_NAME will be an absolute path (without file name). \n
     * If not using htaccess, it will also include the file name. If it is "/", it is set to an empty string (since it cannot have a trailing slash).
     *
     * @var string
     */
    protected $SCRIPT_NAME;
    /**
     * The PATH_INFO is the virtual path to the requested resource within the application context.
     * The PATH_INFO will be an absolute path with a leading slash; this will be used for application routing.
     * @var string
     */
    protected $PATH_INFO;
    /**
     * The portion of the request URI following the '?'
     * @var string
     */
    protected $QUERY_STRING;
    /**
     * Name of server host that is running the script
     * @var string
     */
    protected $SERVER_NAME;
    /**
     * Number of server port that is running the script
     * @var string
     */
    protected $SERVER_PORT;

    // Special headers accepted by class
    /**
     * Array of headers accepted by Framework_Environment class
     */
    protected $SPECIAL_HEADERS = array('CONTENT_TYPE', 'CONTENT_LENGTH', 'PHP_AUTH_DIGEST', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'AUTH_TYPE');
    protected $CONTENT_TYPE;
    protected $CONTENT_LENGTH;
    /**
     * When doing Digest HTTP authentication this variable is set to the 'Authorization' header sent by the client (which you should then use to make the appropriate validation).
     * @var type
     */
    protected $PHP_AUTH_DIGEST;
    /**
     * When doing HTTP authentication this variable is set to the username provided by the user.
     * @var type
     */
    protected $PHP_AUTH_USER;
    /**
     * When doing HTTP authentication this variable is set to the password provided by the user.
     * @var type
     */
    protected $PHP_AUTH_PW;
    /**
     * When doing HTTP authenticated this variable is set to the authentication type.
     * @var type
     */
    protected $AUTH_TYPE;

    // HTTP headers
    /**
     * Contents of the Accept: header from the current request, if there is one.
     * @var type
     */
    protected $HTTP_ACCEPT;
    /**
     * Contents of the Accept-Charset: header from the current request, if there is one. Example: 'iso-8859-1,*,utf-8'.
     * @var type
     */
    protected $HTTP_ACCEPT_CHARSET;
    /**
     * Contents of the Accept-Encoding: header from the current request, if there is one. Example: 'gzip'.
     * @var type
     */
    protected $HTTP_ACCEPT_ENCODING;
    /**
     * Contents of the Accept-Language: header from the current request, if there is one. Example: 'en'.
     * @var type
     */
    protected $HTTP_ACCEPT_LANGUAGE;
    /**
     * Contents of the Connection: header from the current request, if there is one. Example: 'Keep-Alive'.
     * @var type
     */
    protected $HTTP_CONNECTION;
    /**
     * Contents of the Host: header from the current request, if there is one.
     * @var type
     */
    protected $HTTP_HOST;
    /**
     * The address of the page (if any) which referred the user agent to the current page. This is set by the user agent. Not all user agents will set this, and some provide the ability to modify HTTP_REFERER as a feature. In short, it cannot really be trusted.
     * @var type
     */
    protected $HTTP_REFERER;
    /**
     * Contents of the User-Agent: header from the current request, if there is one. This is a string denoting the user agent being which is accessing the page. A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). Among other things, you can use this value with get_browser() to tailor your page's output to the capabilities of the user agent.
     * @var type
     */
    protected $HTTP_USER_AGENT;
    /**
     * Alias HTTP_ACCEPT
     * @var type
     */
    protected $ACCEPT;
    /**
     * Alias HTTP_ACCEPT_LANGUAGE
     * @var type
     */
    protected $ACCEPT_LANGUAGE;
    /**
     * Alias HTTP_ACCEPT_CHARSET
     * @var type
     */
    protected $ACCEPT_CHARSET;
    /**
     * Alias HTTP_USER_AGENT
     * @var type
     */
    protected $USER_AGENT;

    // Projektor properties
    /**
     * Is the application running under HTTPS or HTTP protocol? Variable will contain 'http' or 'https' string.
     * @var string
     */
    protected $urlScheme;
    /**
     * Input stream (readable one time only; not available for mutipart/form-data requests)
     * @var string
     */
    protected $input;
    /**
     * Error stream
     * @var file handler
     */
    protected $errors;
    /**
     * Associative array of query variables
     * @var type 
     */
    protected $queryHash;
    /**
     * Associative array of form variables
     * @var type  As
     */
    protected $formHash;
    /**
     * Get environment instance (singleton)
     *
     * This creates and/or returns an environment instance (singleton)
     * derived from $_SERVER variables. You may override the global server
     * variables by using `\Slim\Environment::mock()` instead.
     *
     * @param  bool             $refresh Refresh properties using global server variables?
     * @return Framework_Environment
     */
    public static function getInstance($refresh = false) {
        if (is_null(self::$environment) || $refresh) {
            self::$environment = new self();  
        }
        return self::$environment;
    }

    /**
     * Get mock environment instance
     *
     * @param  array       $userSettings
     * @return Framework_Environment
     */
    public static function mock($userSettings = array()) {
        self::$environment = new self(array_merge(array(
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '',
            'PATH_INFO' => '',
            'QUERY_STRING' => '',
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => 80,
            'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
            'ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
            'USER_AGENT' => 'Projektor',
            'REMOTE_ADDR' => '127.0.0.1',
            'projektorUrlScheme' => 'http',
            'projektorInput' => '',
            'projektorErrors' => @fopen('php://stderr', 'w')
        ), $userSettings));

        return self::$environment;
    }

    /**
     * Constructor (private access)
     *
     * @param  array|null $settings If present, these are used instead of global server variables
     */
    private function __construct($settings = null) {
        if ($settings) {
            foreach ($settings as $key => $value) {
                $this->$key = $value;
            }
        } else {
            //The HTTP request method
            $this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

            //The IP
            $this->REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
            
            //The request times
            $this->REQUEST_TIME = $_SERVER['REQUEST_TIME'];
            $this->REQUEST_TIME_FLOAT = $_SERVER['REQUEST_TIME_FLOAT'];            
            
            //Original request uri
            $this->REQUEST_URI = $_SERVER['REQUEST_URI'];
                    
            /**
             * Application paths
             *
             * This derives two paths: SCRIPT_NAME and PATH_INFO. The SCRIPT_NAME
             * is the real, physical path to the application, be it in the root
             * directory or a subdirectory of the public document root. The PATH_INFO is the
             * virtual path to the requested resource within the application context.
             *
             * With htaccess, the SCRIPT_NAME will be an absolute path (without file name);
             * if not using htaccess, it will also include the file name. If it is "/",
             * it is set to an empty string (since it cannot have a trailing slash).
             *
             * The PATH_INFO will be an absolute path with a leading slash; this will be
             * used for application routing.
             */
            if (strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) === 0) {
                $this->SCRIPT_NAME = $_SERVER['SCRIPT_NAME']; //Without URL rewrite
            } else {
                $this->SCRIPT_NAME = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']) ); //With URL rewrite
            }
            $this->PATH_INFO = substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($this->SCRIPT_NAME));
            if (strpos($this->PATH_INFO, '?') !== false) {
                $this->PATH_INFO = substr_replace($this->PATH_INFO, '', strpos($this->PATH_INFO, '?')); //query string is not removed automatically
            }
            $this->SCRIPT_NAME = rtrim($this->SCRIPT_NAME, '/');
            $this->PATH_INFO = '/' . ltrim($this->PATH_INFO, '/');

            //The portion of the request URI following the '?'
            $this->QUERY_STRING = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

            //Name of server host that is running the script
            $this->SERVER_NAME = $_SERVER['SERVER_NAME'];

            //Number of server port that is running the script
            $this->SERVER_PORT = $_SERVER['SERVER_PORT'];

            //HTTP request headers
//            $specialHeaders = array('CONTENT_TYPE', 'CONTENT_LENGTH', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'PHP_AUTH_DIGEST', 'AUTH_TYPE');
            foreach ($_SERVER as $key => $value) {
                $value = is_string($value) ? trim($value) : $value;
                if (strpos($key, 'HTTP_') === 0) {
                    $name = substr($key, 5);  // za HTTP_
                    $this->$key = $value;
                    $this->$name = $value;
                } elseif (strpos($key, 'X_') === 0 || in_array($key, $this->SPECIAL_HEADERS)) {
                    $this->$key = $value;
                }
            }

            //Is the application running under HTTPS or HTTP protocol?
            $this->urlScheme = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';

            //Input stream (readable one time only; not available for mutipart/form-data requests)
            $rawInput = @file_get_contents('php://input');
            if (!$rawInput) {
                $rawInput = '';
            }
            $this->input = $rawInput;

            //Error stream
            $this->errors = fopen('php://stderr', 'w');

//            $this->properties = $env;
        }
    }
##################################################   
// GETTERS    
    
    /**
     * The HTTP request method. Which request method was used to access the page; i.e. 'GET', 'HEAD', 'POST', 'PUT'.
     * Note:
     * PHP script is terminated after sending headers (it means after producing any output without output buffering) if the request method was HEAD.
     * @var string
     */
    public function getREQUEST_METHOD() {
        return $this->REQUEST_METHOD;
    }
    /**
     * The timestamp of the start of the request. Available since PHP 5.1.0.
     * @var string
     */
    public function getREQUEST_TIME() {
        return $this->REQUEST_TIME;
    }
    /**
     * The timestamp of the start of the request, with microsecond precision. Available since PHP 5.4.0.
     * @var string
     */
    public function getREQUEST_TIME_FLOAT() {
        return $this->REQUEST_TIME_FLOAT;
    }
    /**
     * URI of the original request received
     * @var string
     */
    public function getREQUEST_URI() {
        return $this->REQUEST_URI;
    }
    /**
     * The IP address from which the user is viewing the current page.
     * @var string
     */
    public function getREMOTE_ADDR() {
        return $this->REMOTE_ADDR;
    }
    /**
     * The SCRIPT_NAME is the real, physical path to the application, be it in the root directory or a subdirectory of the public document root. \n
     * With htaccess, the SCRIPT_NAME will be an absolute path (without file name). \n
     * If not using htaccess, it will also include the file name. If it is "/", it is set to an empty string (since it cannot have a trailing slash).
     *
     * @var string
     */
    public function getSCRIPT_NAME() {
        return $this->SCRIPT_NAME;
    }
    /**
     * The PATH_INFO is the virtual path to the requested resource within the application context.
     * The PATH_INFO will be an absolute path with a leading slash; this will be used for application routing.
     * @var string
     */
    public function getPATH_INFO() {
        return $this->PATH_INFO;
    }
    /**
     * The portion of the request URI following the '?'
     * @var string
     */
    public function getQUERY_STRING() {
        return $this->QUERY_STRING;
    }
    /**
     * Name of server host that is running the script
     * @var string
     */
    public function getSERVER_NAME() {
        return $this->SERVER_NAME;
    }
    /**
     * Number of server port that is running the script
     * @var string
     */
    public function getSERVER_PORT() {
        return $this->SERVER_PORT;
    }

    // Special headers accepted by class
    /**
     * Array of headers accepted by Framework_Environment class
     */
    public function getSPECIAL_HEADERS() {
        return $this->SPECIAL_HEADERS = array('CONTENT_TYPE', 'CONTENT_LENGTH', 'PHP_AUTH_DIGEST', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'AUTH_TYPE');
    }
    public function getCONTENT_TYPE() {
        return $this->CONTENT_TYPE;
    }
    public function getCONTENT_LENGTH() {
        return $this->CONTENT_LENGTH;
    }
    /**
     * When doing Digest HTTP authentication this variable is set to the 'Authorization' header sent by the client (which you should then use to make the appropriate validation).
     * @var type
     */
    public function getPHP_AUTH_DIGEST() {
        return $this->PHP_AUTH_DIGEST;
    }
    /**
     * When doing HTTP authentication this variable is set to the username provided by the user.
     * @var type
     */
    public function getPHP_AUTH_USER() {
        return $this->PHP_AUTH_USER;
    }
    /**
     * When doing HTTP authentication this variable is set to the password provided by the user.
     * @var type
     */
    public function getPHP_AUTH_PW() {
        return $this->PHP_AUTH_PW;
    }
    /**
     * When doing HTTP authenticated this variable is set to the authentication type.
     * @var type
     */
    public function getAUTH_TYPE() {
        return $this->AUTH_TYPE;
    }
    // HTTP headers
    /**
     * Contents of the Accept: header from the current request, if there is one.
     * @var type
     */
    public function getHTTP_ACCEPT() {
        return $this->HTTP_ACCEPT;
    }
    /**
     * Contents of the Accept-Charset: header from the current request, if there is one. Example: 'iso-8859-1,*,utf-8'.
     * @var type
     */
    public function getHTTP_ACCEPT_CHARSET() {
        return $this->HTTP_ACCEPT_CHARSET;
    }
    /**
     * Contents of the Accept-Encoding: header from the current request, if there is one. Example: 'gzip'.
     * @var type
     */
    public function getHTTP_ACCEPT_ENCODING() {
        return $this->HTTP_ACCEPT_ENCODING;
    }
    /**
     * Contents of the Accept-Language: header from the current request, if there is one. Example: 'en'.
     * @var type
     */
    public function getHTTP_ACCEPT_LANGUAGE() {
        return $this->HTTP_ACCEPT_LANGUAGE;
    }
    /**
     * Contents of the Connection: header from the current request, if there is one. Example: 'Keep-Alive'.
     * @var type
     */
    public function getHTTP_CONNECTION() {
        return $this->HTTP_CONNECTION;
    }
    /**
     * Contents of the Host: header from the current request, if there is one.
     * @var type
     */
    public function getHTTP_HOST() {
        return $this->HTTP_HOST;
    }
    /**
     * The address of the page (if any) which referred the user agent to the current page. This is set by the user agent. Not all user agents will set this, and some provide the ability to modify HTTP_REFERER as a feature. In short, it cannot really be trusted.
     * @var type
     */
    public function getHTTP_REFERER() {
        return $this->HTTP_REFERER;
    }
    /**
     * Contents of the User-Agent: header from the current request, if there is one. This is a string denoting the user agent being which is accessing the page. A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). Among other things, you can use this value with get_browser() to tailor your page's output to the capabilities of the user agent.
     * @var type
     */
    public function getHTTP_USER_AGENT() {
        return $this->HTTP_USER_AGENT;
    }
    /**
     * Alias HTTP_ACCEPT
     * @var type
     */
    public function getACCEPT() {
        return $this->ACCEPT;
    }
    /**
     * Alias HTTP_ACCEPT_LANGUAGE
     * @var type
     */
    public function getACCEPT_LANGUAGE() {
        return $this->ACCEPT_LANGUAGE;
    }
    /**
     * Alias HTTP_ACCEPT_CHARSET
     * @var type
     */
    public function getACCEPT_CHARSET() {
        return $this->ACCEPT_CHARSET;
    }
    /**
     * Alias HTTP_USER_AGENT
     * @var type
     */
    public function getUSER_AGENT() {
        return $this->USER_AGENT;
    }

    // Projektor properties
    /**
     * Is the application running under HTTPS or HTTP protocol? Variable will contain 'http' or 'https' string.
     * @var string
     */
    public function getUrlScheme() {
        return $this->urlScheme;
    }
    /**
     * Input stream (readable one time only; not available for mutipart/form-data requests)
     * @var string
     */
    public function getInput() {
        return $this->input;
    }
    /**
     * Error stream
     * @var file handler
     */
    public function getErrors() {
        return $this->errors;
    }
    /**
     * Associative array of query variables
     * @var type 
     */
    public function getQueryHash() {
        return $this->queryHash;
    }
    /**
     * Associative array of form variables
     * @var type  As
     */
    public function getFormHash() {
        return $this->formHash;    
    }
}
/**
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart <info@slimframework.com>
 * @copyright   2011 Josh Lockhart
 * @link        http://www.slimframework.com
 * @license     http://www.slimframework.com/license
 * @version     2.2.0
 * @package     Slim
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
//namespace Slim;

/**
 * Environment
 *
 * This class creates and returns a key/value array of common
 * environment variables for the current HTTP request.
 *
 * This is a singleton class; derived environment variables will
 * be common across multiple Slim applications.
 *
 * This class matches the Rack (Ruby) specification as closely
 * as possible. More information available below.
 *
 * @package Slim
 * @author  Josh Lockhart
 * @since   1.6.0
 */
//class Environment implements \ArrayAccess, \IteratorAggregate
//{
//    /**
//     * @var array
//     */
//    protected $properties;
//
//    /**
//     * @var \Slim\Environment
//     */
//    protected static $environment;
//
//    /**
//     * Get environment instance (singleton)
//     *
//     * This creates and/or returns an environment instance (singleton)
//     * derived from $_SERVER variables. You may override the global server
//     * variables by using `\Slim\Environment::mock()` instead.
//     *
//     * @param  bool             $refresh Refresh properties using global server variables?
//     * @return \Slim\Environment
//     */
//    public static function getInstance($refresh = false)
//    {
//        if (is_null(self::$environment) || $refresh) {
//            self::$environment = new self();
//        }
//
//        return self::$environment;
//    }
//
//    /**
//     * Get mock environment instance
//     *
//     * @param  array       $userSettings
//     * @return \Slim\Environment
//     */
//    public static function mock($userSettings = array())
//    {
//        self::$environment = new self(array_merge(array(
//            'REQUEST_METHOD' => 'GET',
//            'SCRIPT_NAME' => '',
//            'PATH_INFO' => '',
//            'QUERY_STRING' => '',
//            'SERVER_NAME' => 'localhost',
//            'SERVER_PORT' => 80,
//            'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//            'ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
//            'ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
//            'USER_AGENT' => 'Slim Framework',
//            'REMOTE_ADDR' => '127.0.0.1',
//            'slim.url_scheme' => 'http',
//            'slim.input' => '',
//            'slim.errors' => @fopen('php://stderr', 'w')
//        ), $userSettings));
//
//        return self::$environment;
//    }
//
//    /**
//     * Constructor (private access)
//     *
//     * @param  array|null $settings If present, these are used instead of global server variables
//     */
//    private function __construct($settings = null)
//    {
//        if ($settings) {
//            $this->properties = $settings;
//        } else {
//            $env = array();
//
//            //The HTTP request method
//            $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
//
//            //The IP
//            $env['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
//
//            /**
//             * Application paths
//             *
//             * This derives two paths: SCRIPT_NAME and PATH_INFO. The SCRIPT_NAME
//             * is the real, physical path to the application, be it in the root
//             * directory or a subdirectory of the public document root. The PATH_INFO is the
//             * virtual path to the requested resource within the application context.
//             *
//             * With htaccess, the SCRIPT_NAME will be an absolute path (without file name);
//             * if not using htaccess, it will also include the file name. If it is "/",
//             * it is set to an empty string (since it cannot have a trailing slash).
//             *
//             * The PATH_INFO will be an absolute path with a leading slash; this will be
//             * used for application routing.
//             */
//            if (strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) === 0) {
//                $env['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME']; //Without URL rewrite
//            } else {
//                $env['SCRIPT_NAME'] = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']) ); //With URL rewrite
//            }
//            $env['PATH_INFO'] = substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($env['SCRIPT_NAME']));
//            if (strpos($env['PATH_INFO'], '?') !== false) {
//                $env['PATH_INFO'] = substr_replace($env['PATH_INFO'], '', strpos($env['PATH_INFO'], '?')); //query string is not removed automatically
//            }
//            $env['SCRIPT_NAME'] = rtrim($env['SCRIPT_NAME'], '/');
//            $env['PATH_INFO'] = '/' . ltrim($env['PATH_INFO'], '/');
//
//            //The portion of the request URI following the '?'
//            $env['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
//
//            //Name of server host that is running the script
//            $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
//
//            //Number of server port that is running the script
//            $env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
//
//            //HTTP request headers
//            $specialHeaders = array('CONTENT_TYPE', 'CONTENT_LENGTH', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'PHP_AUTH_DIGEST', 'AUTH_TYPE');
//            foreach ($_SERVER as $key => $value) {
//                $value = is_string($value) ? trim($value) : $value;
//                if (strpos($key, 'HTTP_') === 0) {
//                    $env[substr($key, 5)] = $value;
//                } elseif (strpos($key, 'X_') === 0 || in_array($key, $specialHeaders)) {
//                    $env[$key] = $value;
//                }
//            }
//
//            //Is the application running under HTTPS or HTTP protocol?
//            $env['slim.url_scheme'] = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';
//
//            //Input stream (readable one time only; not available for mutipart/form-data requests)
//            $rawInput = @file_get_contents('php://input');
//            if (!$rawInput) {
//                $rawInput = '';
//            }
//            $env['slim.input'] = $rawInput;
//
//            //Error stream
//            $env['slim.errors'] = fopen('php://stderr', 'w');
//
//            $this->properties = $env;
//        }
//    }
//
//    /**
//     * Array Access: Offset Exists
//     */
//    public function offsetExists($offset)
//    {
//        return isset($this->properties[$offset]);
//    }
//
//    /**
//     * Array Access: Offset Get
//     */
//    public function offsetGet($offset)
//    {
//        if (isset($this->properties[$offset])) {
//            return $this->properties[$offset];
//        } else {
//            return null;
//        }
//    }
//
//    /**
//     * Array Access: Offset Set
//     */
//    public function offsetSet($offset, $value)
//    {
//        $this->properties[$offset] = $value;
//    }
//
//    /**
//     * Array Access: Offset Unset
//     */
//    public function offsetUnset($offset)
//    {
//        unset($this->properties[$offset]);
//    }
//
//    /**
//     * IteratorAggregate
//     *
//     * @return \ArrayIterator
//     */
//    public function getIterator()
//    {
//        return new \ArrayIterator($this->properties);
//    }
//}