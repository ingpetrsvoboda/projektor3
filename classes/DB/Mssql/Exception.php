<?php
class DB_Mssql_Exception extends Exception { 
  public $backtrace;
  public function __construct($message=false, $code=false) {

    $this->backtrace = debug_backtrace();
  }
}

?>