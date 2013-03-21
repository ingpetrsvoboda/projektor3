<?php 
class Projektor_DB_Mssql implements Projektor_DB_Connection {
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
    $this->dbType = Projektor_App_Config::DB_TYPE_MSSQL;
  }
  
  protected function connect() 
  {
//      $this->dbh = mssql_connect($this->dbHost, $this->user, $this->pass);
      $this->dbh = sqlsrv_connect( $this->dbHost, array( "Database"=>  $this->dbName, "UID"=>  $this->user, "PWD"=>  $this->pass));
    if(!is_resource($this->dbh)) 
    {
        print_r( sqlsrv_errors());
      throw new Projektor_DB_Mssql_Exception(print_r( sqlsrv_errors(), true));
    }
  }


  /**
   *
   * @param <string> $query
   * @param <type> $user
   * @return Projektor_DB_Mssql_Statement
   */
  public function prepare($query,$user=False) 
  {
    if(!$this->dbh) 
    {
        $this->connect();
    }
    return new Projektor_DB_Mssql_Statement($this->dbh, $this->dbName, $query);
  }

  public function __get($name) {
      return $this->$name;
  }
}
?>