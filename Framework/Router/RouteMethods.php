<?php

/*
 * RESTful Web Service HTTP methods 
 * Resource 	GET 	PUT 	POST 	DELETE
 * Collection URI, such as http://example.com/resources/
 * GET      List the URIs and perhaps other details of the collection's members.
 * PUT      Replace the entire collection with another collection.
 * POST     Create a new entry in the collection. The new entry's ID is assigned automatically and is usually returned by the operation.
 * DELETE   Delete the entire collection.
 * Element URI, such as http://example.com/resources/142
 * GET      Retrieve a representation of the addressed member of the collection, expressed in an appropriate Internet media type.
 * PUT      Update the addressed member of the collection, or if it doesn't exist, create it.
 * POST     Treat the addressed member as a collection in its own right and create a new entry in it.
 * DELETE   Delete the addressed member of the collection.
 */

/**
 * Description of RESTMethods
 *
 * @author pes2704
 */
class Framework_Router_RouteMethods {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
    private $methods = array();
    
    /**
     * Set method.
     * @param string $method
     * @throws InvalidArgumentException
     */
    public function setMethod($method) {
        $index = $this->method($method);
        if ($index) {
            $this->methods[$index] = TRUE;
        } else {
            throw new InvalidArgumentException('Unsupported route HTTP method '. $method);
        }
    }
    
    /**
     * Tells if methods object has method setted.
     * @param type $method
     * @return boolean
     */
    public function hasMethod($method) {
        if ($this->methods[$this->canonical($method)]) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    private function method($method) {
        $method = $this->canonical($method);
        if (self::METHOD_GET === $method) {
            return self::METHOD_GET;
        }
        if (self::METHOD_POST === $method) {
            return self::METHOD_POST;
        }
        if (self::METHOD_PUT === $method) {
            return self::METHOD_PUT;
        }
        if (self::METHOD_DELETE === $method) {
            return self::METHOD_DELETE;
        }
        return NULL;
    }
    
    private function canonical($method) {
        return strtoupper(trim($method));
    }
}
