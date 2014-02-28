<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cookie
 *
 * @author pes2704
 */
class Framework_Response_Cookie {
    public $name;
    public $value;
    public $expire;
    public $path;
    public $domain;
    public $secure;
    public $httponly;

    public function __construct($name, $value=NULL, $expire=0, $path=NULL, $domain=NULL, $secure=FALSE, $httponly=FALSE) {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
    }
        
}
?>
