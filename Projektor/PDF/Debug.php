<?php

class Projektor2_PDF_Debug
{

    public $debug;
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
    public function Debug($debug=false)
    {
        $this->debug=$debug;
    }
}
