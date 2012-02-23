<?php 
class DB_Mysql implements DB_Connection {
  protected $user;
  protected $pass;
  protected $dbHost;
  protected $dbName;
  protected $dbh;
  protected $dbType   = "MySQL" ;
  


  public function __construct($user, $pass, $dbHost, $dbName) 
  {
    $this->user = $user;
    $this->pass = $pass;
    $this->dbHost = $dbHost;
    $this->dbName = $dbName;
  }
  
  protected function connect() 
  {
    $this->dbh = mysql_connect($this->dbHost, $this->user, $this->pass);
    if(!is_resource($this->dbh)) 
    {
      throw new DB_Mysql_Exception;
    }
  }


  /**
   *
   * @param <string> $query
   * @param <type> $user
   * @return DB_Mysql_Statement
   */
  public function prepare($query,$user=False) 
  {
    if(!$this->dbh) 
    {
        $this->connect();
    }
    return new DB_Mysql_Statement($this->dbh, $this->dbName, $query);
  }

  public function __get($name) {
      return $this->$name;
  }
}
?>