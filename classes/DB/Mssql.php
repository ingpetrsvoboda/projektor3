<?php 
class DB_Mssql implements DB_Connection {
  protected $user;
  protected $pass;
  protected $dbHost;
  protected $dbName;
  protected $dbh;
  protected $dbType  = "MSSQL" ;



  public function __construct($user, $pass, $dbHost, $dbName) 
  {
    $this->user = $user;
    $this->pass = $pass;
    $this->dbHost = $dbHost;
    $this->dbName = $dbName;
  }
  
  protected function connect() 
  {
      $this->dbh = mssql_connect($this->dbHost, $this->user, $this->pass);
    if(!is_resource($this->dbh)) 
    {
      throw new DB_Mssql_Exception;
    }
  }


  /**
   *
   * @param <string> $query
   * @param <type> $user
   * @return DB_Mssql_Statement
   */
  public function prepare($query,$user=False) 
  {
    if(!$this->dbh) 
    {
        $this->connect();
    }
    return new DB_Mssql_Statement($this->dbh, $this->dbName, $query);
  }

  public function __get($name) {
      return $this->$name;
  }
}
?>