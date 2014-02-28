0<?php
/**
 * @author      Josh Lockhart <info@slimframework.com>
 * @copyright   2011 Josh Lockhart
 * @link        http://www.slimframework.com
 * @license     http://www.slimframework.com/license
 */

/**
 * Router
 *
 * This class organizes, iterates, and dispatches Framework_Router_Route objects.
 *
 * @package Slim
 * @author  Josh Lockhart
 * @since   1.0.0
 */
class Framework_Router_RESTRouter extends Framework_Router_AbstractRouter{
    /**
     * @var Route The current route (most recently dispatched)
     */
    protected $currentRoute;

    /**
     * @var array Lookup hash of all route objects
     */
    protected $routes;

    /**
     * @var array Lookup hash of named route objects, keyed by route name (lazy-loaded)
     */
    protected $namedRoutes;

    /**
     * @var array Array of route objects that match the request URI (lazy-loaded)
     */
    protected $matchedRoutes;

    /**
     * Constructor
     */
    public function __construct(Framework_Request_Request $request, Framework_Application_StatusInterface $appStatus)
    {
        parent::__construct($request, $appStatus);
        $this->routes = array();
    }

    /**
     * Get Current Route object or the first matched one if matching has been performed
     * @return Framework_Router_Route|null
     */
    public function getCurrentRoute()
    {
        if ($this->currentRoute !== null) {
            return $this->currentRoute;
        }
        if (is_array($this->matchedRoutes) && count($this->matchedRoutes) > 0) {
            return $this->matchedRoutes[0];
        }
        return null;
    }

    /**
     * Return route objects that match the given HTTP method and URI
     * @param  string               $httpMethod   The HTTP method to match against
     * @param  string               $resourceUri  The resource URI to match against
     * @param  bool                 $reload       Should matching routes be re-parsed?
     * @return array[Framework_Router_Route]
     */
    public function getMatchedRoutes($httpMethod, $resourceUri, $reload = false)
    {
        if ($reload || is_null($this->matchedRoutes)) {
            $this->matchedRoutes = array();
            foreach ($this->routes as $route) {
                if (!$route->supportsHttpMethod($httpMethod)) {
                    continue;
                }

                if ($route->matches($resourceUri)) {
                    $this->matchedRoutes[] = $route;
                }
            }
        }

        return $this->matchedRoutes;
    }

    /**
     * Map a route object to a callback function
     * @param  string     $pattern      The URL pattern (ie. "/books/:id")
     * @param  mixed      $callable     Anything that returns TRUE for is_callable()
     * @return Framework_Router_Route
     */
    public function map($pattern, $callable)
    {
        $route = new Framework_Router_Route($pattern, $callable);
        $this->routes[] = $route;

        return $route;
    }

    /**
     * Get URL for named route
     * @param  string               $name   The name of the route
     * @param  array                Associative array of URL parameter names and replacement values
     * @throws RuntimeException     If named route not found
     * @return string               The URL for the given route populated with provided replacement values
     */
    public function urlFor($name, $params = array())
    {
        if (!$this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route not found for name: ' . $name);
        }
        $search = array();
        foreach (array_keys($params) as $key) {
            $search[] = '#:' . $key . '\+?(?!\w)#';
        }
        $pattern = preg_replace($search, $params, $this->getNamedRoute($name)->getPattern());

        //Remove remnants of unpopulated, trailing optional pattern segments
        return preg_replace('#\(/?:.+\)|\(|\)#', '', $pattern);
    }


    /**
     * Add named route
     * @param  string            $name   The route name
     * @param  Framework_Router_Route       $route  The route object
     * @throws \RuntimeException If a named route already exists with the same name
     */
    public function addNamedRoute($name, Framework_Router_Route $route)
    {
        if ($this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route already exists with name: ' . $name);
        }
        $this->namedRoutes[(string) $name] = $route;
    }

    /**
     * Has named route
     * @param  string   $name   The route name
     * @return bool
     */
    public function hasNamedRoute($name)
    {
        $this->getNamedRoutes();

        return isset($this->namedRoutes[(string) $name]);
    }

    /**
     * Get named route
     * @param  string           $name
     * @return Framework_Router_Route|null
     */
    public function getNamedRoute($name)
    {
        $this->getNamedRoutes();
        if ($this->hasNamedRoute($name)) {
            return $this->namedRoutes[(string) $name];
        } else {
            return null;
        }
    }

    /**
     * Get named routes
     * @return \ArrayIterator
     */
    public function getNamedRoutes()
    {
        if (is_null($this->namedRoutes)) {
            $this->namedRoutes = array();
            foreach ($this->routes as $route) {
                if ($route->getName() !== null) {
                    $this->addNamedRoute($route->getName(), $route);
                }
            }
        }

        return new \ArrayIterator($this->namedRoutes);
    }
}
