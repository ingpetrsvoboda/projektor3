<?php
class DB_MysqlStatement implements DB_Statement {
  public $result;
  public $binds;
  public $query;
  public $dbh;
  public function __construct($dbh, $query) {
    $this->query = $query;
    $this->dbh = $dbh;
    if(!is_resource($dbh)) {
      throw new DB_MysqlException("Not a valid database connection");
    }
  }
  public function bind_param($ph, $pv) {
    $this->binds[$ph] = $pv;
    return $this;
  }

  /**
   * Doplní do SQL příkazu připraveného metodou mysql->prepare() parametry a vykoná jej
   *
   * Doplní do SQL příkazu připraveného metodou mysql->prepare() parametry a vykoná jej. Metoda přijímá libovolný počet parametrů
   * načtených php funkcí func_get_args. Parametry předané metodě jsou přijaty v pořadí v jakém jsou uvedeny při volání a tedy první
   * parametr v pořadí je doplněn na pozici parametru uvedeného v předpřipraveném sql dorazu (prepare) s číslem jedna, druhý s číslem dva atd.
   *
   * @var - metoda přijímá libovolný počet parametrů
   * @return DB_MysqlStatement
   */
  public function execute() {
    $binds = func_get_args();
    foreach($binds as $index => $name) {
      $this->binds[$index + 1] = $name;
    }
    $cnt = count($binds);
    $query = $this->query;

//echo $query;    
    for($i=$cnt;$i>0;$i--){
        $ph=strval($i);
        $pv=$this->binds[$i];
        if($pv =="NULL"){
            $query = str_replace(":$ph",mysql_escape_string($pv), $query);
        }
        else {
            $query = str_replace(":$ph", "\"".mysql_escape_string($pv)."\"", $query); // parametr je hodnota - uzavre se do uvozovek (cislo uzavrene v uvozovkach je korektni sql
            $query = str_replace("~$ph", "`".mysql_escape_string($pv)."`", $query);  // parametr je identifikator - napriklad nazev tabulky nebo sloupce - uzavre se do apostrofů
        }

    }
    // echo("<p style=\"color:red\">MysqlStatement execute: {$query}</p>");
    $this->result = mysql_query($query, $this->dbh);
    if(!$this->result) {
      echo("<p style=\"color:red\">MysqlStatement query: {$query}</p>");
      throw new DB_MysqlException;      // SVOBODA vypadá to, že se tato exceútion nezachytává
    }
    return $this;
  }
  public function fetch_row() {
    if(!$this->result) {
      throw new DB_MysqlException("Query not executed");
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
  
  public function last_insert_id() {
  	return mysql_insert_id($this->dbh);
  } 
}

?>