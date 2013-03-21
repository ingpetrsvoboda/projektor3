<?php
class Projektor_DB_Mssql_Statement implements Projektor_DB_Statement {
  public $result;
  public $binds;
  public $dbh;
  public $dbName;
  public $preparedQuery;
  public $executedQuery;


  public function __construct($dbh, $dbName, $preparedQuery) {
//    if(!is_resource($dbh)) {
//      throw new Projektor_DB_Mssql_Exception("Not a valid database connection");
//    }
    $this->dbh = $dbh;
    $this->dbName = $dbName;
    $this->preparedQuery = $preparedQuery;

  }


    public function bindParam($param, $value)
    {
        $paramPrefix = substr($param, 0, 1);
        switch ($paramPrefix)
        {
            case ":":    // parametr je hodnota
                if (is_numeric($value))
                {
                $this->bindedParams[$param] = $value; // parametr je číslo
                } else {
                $this->bindedParams[$param] = "'".$this->escape($value)."'"; // parametr je hodnota - uzavre se do apostrofu
                }
                break;
            case "~":    // parametr je identifikator - napriklad nazev tabulky nebo sloupce - uzavre se do hranatých závorek
                $this->bindedParams[$param] = "[".$this->escape($value)."]";
                break;
            default:
                break;
        }
    }

  /**
   * Doplní do SQL příkazu připraveného metodou Mssql->prepare() parametry a vykoná jej
   *
   * Doplní do SQL příkazu připraveného metodou Mssql->prepare() parametry a vykoná jej. Metoda přijímá libovolný počet parametrů
   * načtených php funkcí func_get_args. Parametry předané metodě jsou přijaty v pořadí v jakém jsou uvedeny při volání a tedy první
   * parametr v pořadí je doplněn na pozici parametru uvedeného v předpřipraveném sql dorazu (prepare) s číslem jedna, druhý s číslem dva atd.
   *
   * @var - metoda přijímá libovolný počet parametrů
   * @return Projektor_DB_Mssql_Statement
   */
  public function execute() {
      // binding očíslovaných parametrů v query s parametry zadanými v execute
    $binds = func_get_args();
    foreach($binds as $index => $name) {
      $this->binds[$index + 1] = $name;
    }
    $cnt = count($binds);
    $query = $this->preparedQuery;

//echo $query;
    for($i=$cnt;$i>0;$i--){
        $ph=strval($i);
        $pv=$this->binds[$i];
//        if($pv =="NULL"){
//            $query = str_replace(":$ph",Mssql_escape_string($pv), $query);
//        }
//        else {
            if (is_numeric($pv))
            {
                $query = str_replace(":$ph", $pv, $query); // parametr je číslo
            } else {
                $query = str_replace(":$ph", "'".$this->escape($pv)."'", $query); // parametr je hodnota - uzavre se do apostrofu
            }
//            $query = str_replace("?$ph", "[".$this->escape($pv)."]", $query);  // parametr je databázový objekt - uzavre se do hranatych zavorek
            $query = str_replace("~$ph", "[".$this->escape($pv)."]", $query);  // parametr je identifikator - napriklad nazev tabulky nebo sloupce - uzavre se do apostrofů
//        }

    }
    if (isset($this->bindedParams))
    {
        foreach ($this->bindedParams as $key => $value) {
            $query = str_replace($key, $value, $query);
        }
    }

//    if(!Mssql_select_db($this->dbName, $this->dbh)) {
//      throw new Projektor_DB_Mssql_Exception;
//    }

      //Nastaveni znakove sady pro přenos dat
//      Mssql_query("SET CHARACTER SET utf8");
    sqlsrv_query($this->dbh, "SET CHARACTER SET utf8");

//    $this->result = Mssql_query($query, $this->dbh);
    $this->result = sqlsrv_query($this->dbh, $query);
    if(!$this->result) {
      echo("<p style=\"color:red\">MssqlStatement query: {$query}</p>");
//      print_r($this->dbh);
      print_r( sqlsrv_errors());
      throw new Projektor_DB_Mssql_Exception;
    }
    $this->executedQuery = $query;
    return $this;
  }
  public function fetch_row() {
    if(!$this->result) {
      throw new Projektor_DB_Mssql_Exception("Query not executed");
    }
//    return Mssql_fetch_row($this->result);
    return sqlsrv_fetch_array( $this->result, SQLSRV_FETCH_NUMERIC);
  }
  public function fetch_assoc() {
//    return Mssql_fetch_assoc($this->result);
    return sqlsrv_fetch_array( $this->result, SQLSRV_FETCH_ASSOC);
  }
  public function fetchall_assoc() {
    $retval = array();
    while($row = $this->fetch_assoc()) {
      $retval[] = $row;
    }
    return $retval;
  }

  public function affectedRows()
  {
      return sqlsrv_rows_affected($this->result);
  }

  public function last_insert_id() {
  	return Mssql_insert_id($this->dbh);
  }

  private function escape($str)
    {
      //    http://www.php.net/manual/en/function.mysql-real-escape-string.php
            $search=array("\\","\0","\n","\r","\x1a","'",'"');
            $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
            return str_replace($search,$replace,$str);
    }
}

?>