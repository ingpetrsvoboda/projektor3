<?php
 /**
  * HTTP Headers
  *
  * This class is an abstraction of the HTTP response headers and
  * provides array access to the header list while automatically
  * stores and retrieves headers with lowercase canonical keys regardless
  * of the input format.
  *
  * This class also implements the `Iterator` and `Countable`
  * interfaces for even more convenient usage.
  *
  * @package Slim
  * @author  Josh Lockhart
  * @since   1.6.0
  */
class Framework_Response_Headers implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array HTTP headers
     */
    protected $cookies;

    /**
     * @var array Map canonical header name to original header name
     */
    protected $map;

    /**
     * Constructor
     * @param  array $headers Pole ve formátu array('jméno hlavičky'=>'obsah hlavičky'), např. array('Content-Type' => 'text/html'). Po
     */
    public function __construct($headers = array())
    {
        $this->merge($headers);
    }

    /**
     * Merge Headers
     * @param  array $headers Pole ve formátu array('jméno hlavičky'=>'obsah hlavičky'), např. array('Content-Type' => 'text/html')
     */
    public function merge($headers)
    {
        foreach ($headers as $name => $value) {
            $this[$name] = $value;
        }
    }

    /**
     * Transform header name into canonical form. 
     * @param  string $name
     * @return string
     */
    protected function canonical($name)
    {
        return strtolower($this->repair($name));
    }

    /**
     * Strip from the beginning and the end of the name whitespaces and eventually doublecolon ":".
     * @param string $name
     * @return string
     */
    protected function repair($name) {
        return trim($name, ": \t\n\r\0\x0B");
    }
    
    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset)
    {
        return isset($this->headers[$this->canonical($offset)]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset)
    {
        $canonical = $this->canonical($offset);
        if (isset($this->headers[$canonical])) {
            return $this->headers[$canonical];
        } else {
            return null;
        }
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value)
    {
        $canonical = $this->canonical($offset);
        $strValue = (string) $value;
        $this->headers[$canonical] = trim($strValue);
        $this->map[$canonical] = $this->repair($offset);
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset)
    {
        $canonical = $this->canonical($offset);
        unset($this->headers[$canonical], $this->map[$canonical]);
    }

    /**
     * Countable: Count
     */
    public function count()
    {
        return count($this->headers);
    }

    /**
     * Iterator: Rewind
     */
    public function rewind()
    {
        reset($this->headers);
    }

    /**
     * Iterator: Current
     */
    public function current()
    {
        return current($this->headers);
    }

    /**
     * Iterator: Key
     */
    public function key()
    {
        $key = key($this->headers);

        return $this->map[$key];
    }

    /**
     * Iterator: Next
     */
    public function next()
    {
        return next($this->headers);
    }

    /**
     * Iterator: Valid
     */
    public function valid()
    {
        return current($this->headers) !== false;
    }
}
