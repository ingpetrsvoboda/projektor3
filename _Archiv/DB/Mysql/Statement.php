<?php
class Projektor_DB_Mysql_Statement implements Projektor_DB_Statement {
  public $result;
  public $binds;
  public $bindedParams;
  public $dbh;
  public $dbName;
  public $preparedQuery;
  public $executedQuery;

  public function __construct($dbh, $dbName, $preparedQuery) {
//    if(!is_resource($dbh)) {
//      throw new Projektor_DB_Mysql_Exception("Not a valid database connection");
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
                $this->bindedParams[$param] = "'".mysql_real_escape_string($value)."'"; // parametr je hodnota - uzavre se do apostrofu
                }
                break;
            case "~":    // parametr je identifikator - napriklad nazev tabulky nebo sloupce - uzavre se do "databázových" apostrofů
                $this->bindedParams[$param] = "`".mysql_real_escape_string($value)."`";
                break;
            default:
                break;
        }
    }

  /**
   * Doplní do SQL příkazu připraveného metodou mysql->prepare() parametry a vykoná jej
   *
   * Doplní do SQL příkazu připraveného metodou mysql->prepare() parametry a vykoná jej. Metoda přijímá libovolný počet parametrů
   * načtených php funkcí func_get_args. Parametry předané metodě jsou přijaty v pořadí v jakém jsou uvedeny při volání a tedy první
   * parametr v pořadí je doplněn na pozici parametru uvedeného v předpřipraveném sql dorazu (prepare) s číslem jedna, druhý s číslem dva atd.
   *
   * @var - metoda přijímá libovolný počet parametrů
   * @return Projektor_DB_Mysql_Statement
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
//            $query = str_replace(":$ph",mysql_escape_string($pv), $query);
//        }
//        else {
            $query = str_replace(":$ph", "\"".mysql_real_escape_string($pv)."\"", $query); // parametr je hodnota - uzavre se do uvozovek (cislo uzavrene v uvozovkach je korektni sql
            $query = str_replace("~$ph", "`".mysql_real_escape_string($pv)."`", $query);  // parametr je identifikator - napriklad nazev tabulky nebo sloupce - uzavre se do apostrofů
//        }

    }
    // binding pojmenovaných parametrů v query zadaných metodou bindParams
    if (isset($this->bindedParams))
    {
        foreach ($this->bindedParams as $key => $value) {
            $query = str_replace($key, $value, $query);
        }
    }

    if(!mysql_select_db($this->dbName, $this->dbh)) {
      throw new Projektor_DB_Mysql_Exception;
    }

      //Nastaveni znakove sady pro přenos dat
      mysql_query("SET CHARACTER SET utf8");

    $this->result = mysql_query($query, $this->dbh);
    if(!$this->result) {
      echo("<p style=\"color:red\">MysqlStatement query: {$query}</p>");
      print_r($this->dbh);
      throw new Projektor_DB_Mysql_Exception;
    }
    $this->executedQuery = $query;
    return $this;
  }
  public function fetch_row() {
    if(!$this->result) {
      throw new Projektor_DB_Mysql_Exception("Query not executed");
    }
    return mysql_fetch_row($this->result);
  }
  public function fetch_assoc() {
    return mysql_fetch_assoc($this->result);
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
      return mysql_affected_rows($this->dbh);
  }

  public function last_insert_id() {
  	return mysql_insert_id($this->dbh);
  }
}

?>