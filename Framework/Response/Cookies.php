<?php
 /**
  * Cookies
  *
  * This class is an abstraction of the HTTP response cookies and
  * provides array access to the cookies list.
  *
  * This class also implements the `Iterator` and `Countable`
  * interfaces for even more convenient usage.
  */
class Framework_Response_Cookies extends SplObjectStorage
{
    /**
     * @var array HTTP cookies
     */
    protected $cookies;

    /**
     * Attach cookie (adds cookie) in the storage
     * @param  Framework_Response_Cookie $cookie objekt Framework_Response_Cookie
     */
    public function attach(Framework_Response_Cookie $cookie)
    {
        parent::attach($cookie);
    }
    
    /**
     * Adds all cookies from another Framework_Response_Cookies storage object.
     * @param Framework_Response_Cookies $cookies
     */
    public function addAll(Framework_Response_Cookies $cookies) {
        parent::addAll($cookies);
    }

    /**
     * Array Access: Offset Set
     * Vždy ukládá do indexu shodného se jménem cookie.
     * @param type $offset
     * @param Framework_Response_Cookie $cookie
     * @throws LogicException Výjimka nastane při pokusu o uložení cookie s indexem odlišnýmod jména cookie. Doporučené užití je $cookies[] = $cookie - volá metodu 
     * s hodnotou $offset=NULL.
     */
    public function offsetSet(Framework_Response_Cookie $cookie)
    {
        parent::offsetSet($cookie);
    }

    /**
     * Array Access: Offset Unset
     * Cookie smaže a nastaví novou cookie se shodným názvem a časem expirace v minulosti. Tak je vynuceno smazání cookie v prohlížeči.
     */
    public function offsetUnset(Framework_Response_Cookie $cookie)
    {   
        parent::detach($cookie);
        parent::attach(new Framework_Response_Cookie($cookie->name, '', time()-100));
    }
}
