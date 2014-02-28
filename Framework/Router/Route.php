<?php
/**
 * Route
 * @author  Josh Lockhart, Thomas Bley, 
 * @author      Josh Lockhart <info@slimframework.com>
 * @copyright   2011 Josh Lockhart
 * @link        http://www.slimframework.com
 * @license     http://www.slimframework.com/license
 */
class Framework_Router_Route
{
    /**
     * @var string The route pattern (e.g. "/books/:id")
     */
    protected $pattern;

    /**
     * @var Framework_Dispatcher_DispatcherInterface The route dispatcher
     */
    protected $dispatcher;

    /**
     * @var string The name of this route (optional)
     */
    protected $name;

    /**
     * @var array Key-value array of URL parameters
     */
    protected $params = array();

    /**
     * @var array value array of URL parameter names
     */
    protected $paramNames = array();

    /**
     * @var array key array of URL parameter names with + at the end
     */
    protected $paramNamesPath = array();

    /**
     * @var Framework_Router_RouteMethods HTTP methods supported by this Route
     */
    protected $methods;

    /**
     * Constructor
     * @param string $pattern  The URL pattern (e.g. "/books/:id")
     * @param mixed  $callable Anything that returns TRUE for is_callable()
     */
    public function __construct($pattern, $callable)
    {
        $this->setPattern($pattern);
        $this->setDispatcher($callable);
        $this->methods = new Framework_Router_RouteMethods();
    }

    /**
     * Get route pattern
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set route pattern
     * @param  string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Get route callable
     * @return Framework_Dispatcher_DispatcherInterface
     */
    public function getDispatcher() {
        return $this->dispatcher;
    }

    /**
     * Set route dispatcher
     * @param Framework_Dispatcher_DispatcherInterface $dispatcher
     */
    public function setDispatcher(Framework_Dispatcher_DispatcherInterface $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get route name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set route name
     * @param  string $name
     */
    public function setName($name) {
        $this->name = (string) $name;
    }

    /**
     * Get route parameters
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Set route parameters
     * @param  array $params
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * Get route parameter value
     * @param  string                    $index     Name of URL parameter
     * @return string
     * @throws \InvalidArgumentException If route parameter does not exist at index
     */
    public function getParam($index) {
        if (!isset($this->params[$index])) {
            throw new \InvalidArgumentException('Route parameter does not exist at specified index');
        }
        return $this->params[$index];
    }

    /**
     * Set route parameter value
     * @param  string                    $index     Name of URL parameter
     * @param  mixed                     $value     The new parameter value
     * @throws \InvalidArgumentException If route parameter does not exist at index
     */
    public function setParam($index, $value) {
        if (!isset($this->params[$index])) {
            throw new \InvalidArgumentException('Route parameter does not exist at specified index');
        }
        $this->params[$index] = $value;
    }

    /**
     * Add supported HTTP method(s)
     */
    public function setHttpMethods() {
        $methods = func_get_args();
        foreach ($methods as $method) {
            $this->methods->setMethod($method);
        }
        return $this;        
    }

    /**
     * Get supported HTTP methods
     * @return Framework_Router_RouteMethods
     */
    public function getHttpMethods() {
        return $this->methods;
    }

    /**
     * Append supported HTTP methods
     */
    public function appendHttpMethods() {
        $this->setHttpMethods();
        return $this;        
    }

    /**
     * Append supported HTTP methods (alias for Route::appendHttpMethods)
     * @return \Framework_Router_Route
     */
    public function forMethods() {
        $this->setHttpMethods();
        return $this;
    }

    /**
     * Detect support for an HTTP method
     * @return bool
     */
    public function supportsHttpMethod($method) {
        return $this->methods->hasMethod($method);
    }

    /**
     * Matches URI?
     *
     * Parse this route's pattern, and then compare it to an HTTP resource URI
     * This method was modeled after the techniques demonstrated by Dan Sosedoff at:
     *
     * http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
     *
     * @param  string $resourceUri A Request URI
     * @return bool
     */
    public function matches($resourceUri)
    {
        //Convert URL params into regex patterns, construct a regex for this route, init params
        $patternAsRegex = preg_replace_callback('#:([\w]+)\+?#', array($this, 'matchesCallback'),
            str_replace(')', ')?', (string) $this->pattern));
        if (substr($this->pattern, -1) === '/') {
            $patternAsRegex .= '?';
        }

        //Cache URL params' names and values if this route matches the current HTTP request
        if (!preg_match('#^' . $patternAsRegex . '$#', $resourceUri, $paramValues)) {
            return false;
        }
        foreach ($this->paramNames as $name) {
            if (isset($paramValues[$name])) {
                if (isset($this->paramNamesPath[ $name ])) {
                    $this->params[$name] = explode('/', urldecode($paramValues[$name]));
                } else {
                    $this->params[$name] = urldecode($paramValues[$name]);
                }
            }
        }

        return true;
    }

    /**
     * Convert a URL parameter (e.g. ":id", ":id+") into a regular expression
     * @param  array    URL parameters
     * @return string   Regular expression for URL parameter
     */
    protected function matchesCallback($m)
    {
        $this->paramNames[] = $m[1];
        if (isset($this->conditions[ $m[1] ])) {
            return '(?P<' . $m[1] . '>' . $this->conditions[ $m[1] ] . ')';
        }
        if (substr($m[0], -1) === '+') {
            $this->paramNamesPath[ $m[1] ] = 1;

            return '(?P<' . $m[1] . '>.+)';
        }

        return '(?P<' . $m[1] . '>[^/]+)';
    }

    /**
     * Set route name
     * @param  string $name The name of the route
     * @return \Framework_Router_Route
     */
    public function name($name) {
        $this->setName($name);
        return $this;
    }
}
