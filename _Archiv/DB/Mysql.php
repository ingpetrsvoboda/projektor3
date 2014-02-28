<?php 
class Projektor_DB_Mysql implements Projektor_DB_Connection {
  protected $user;
  protected $pass;
  protected $dbHost;
  protected $dbName;
  protected $dbh;
  protected $dbType;
  


  public function __construct($user, $pass, $dbHost, $dbName) 
  {
    $this->user = $user;
    $this->pass = $pass;
    $this->dbHost = $dbHost;
    $this->dbName = $dbName;
    $this->dbType = Framework_Config::DB_TYPE_MYSQL;    
  }
  
  protected function connect() 
  {
    $this->dbh = mysql_connect($this->dbHost, $this->user, $this->pass);
    if(!is_resource($this->dbh)) 
    {
      throw new Projektor_DB_Mysql_Exception;
    }
  }

  /**
   *
   * @param <string> $query
   * @param <type> $user
   * @return Projektor_DB_Mysql_Statement
   */
  public function prepare($query,$user=False) 
  {
    if(!$this->dbh) 
    {
        $this->connect();
    }
    return new Projektor_DB_Mysql_Statement($this->dbh, $this->dbName, $query);
  }

  public function __get($name) {
      return $this->$name;
  }
}
?>