<?php
class DB_MysqlException extends Exception { 
  public $backtrace;
  public function __construct($message=false, $code=false) {
    if(!$message) {
      $this->message = mysql_error();
    }
    if(!$code) {
      $this->code = mysql_errno();
    }
    $this->backtrace = debug_backtrace();
  }
}

?>