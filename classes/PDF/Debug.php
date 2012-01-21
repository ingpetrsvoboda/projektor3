<?php

class PDF_Debug
{
    
    var $debug;
    const DEBUG = false;    
    
    public function __construct($debug=self::DEBUG)
    {
      $this->debug = $debug;
    }
    
    /**
     * Nastaví objektu PDF_Debug vlastnost debug
     * @param boolean $debug
     * @return 
     */
    function Debug($debug=false)
    {
        $this->debug=$debug;
    }
}
