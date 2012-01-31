<?php
class DB_Mysql_Statement implements DB_Statement {
  public $result;
  public $binds;
  public $dbh;
  public $dbName;
  public $preparedQuery;
  
  public function __construct($dbh, $dbName, $preparedQuery) {
//    if(!is_resource($dbh)) {
//      throw new DB_Mysql_Exception("Not a valid database connection");
//    }
    $this->dbh = $dbh;
    $this->dbName = $dbName;
    $this->preparedQuery = $preparedQuery;

  }

//  
//  public function bind_param($ph, $pv) {
//    $this->binds[$ph] = $pv;
//    return $this;
//  }

  /**
   * Doplní do SQL příkazu připraveného metodou mysql->prepare() parametry a vykoná jej
   *
   * Doplní do SQL příkazu připraveného metodou mysql->prepare() parametry a vykoná jej. Metoda přijímá libovolný počet parametrů
   * načtených php funkcí func_get_args. Parametry předané metodě jsou přijaty v pořadí v jakém jsou uvedeny při volání a tedy první
   * parametr v pořadí je doplněn na pozici parametru uvedeného v předpřipraveném sql dorazu (prepare) s číslem jedna, druhý s číslem dva atd.
   *
   * @var - metoda přijímá libovolný počet parametrů
   * @return DB_Mysql_Statement
   */
  public function execute() {
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
            $query = str_replace(":$ph", "\"".mysql_escape_string($pv)."\"", $query); // parametr je hodnota - uzavre se do uvozovek (cislo uzavrene v uvozovkach je korektni sql
            $query = str_replace("~$ph", "`".mysql_escape_string($pv)."`", $query);  // parametr je identifikator - napriklad nazev tabulky nebo sloupce - uzavre se do apostrofů
//        }
//.$this->dbName."."
    }
//    if(!$this->dbh) {
//        $this->connect($this->dbObject);
//        }    
    mysql_select_db($this->dbName, $this->dbh);      
    
//    if(!$this->dbh) {
//      $this->connect();
      //Nastaveni znakove sady pro přenos dat
      mysql_query("SET CHARACTER SET utf8"); 
//    }
    //Nastaveni uzivatele pro zaznam do tabulky aktualizaci
//    if(!$user) {
//        mysql_query("SET @uz_jmeno = 1",$this->dbh);
//    }
//    else {
//        $this->user = $user;
//        mysql_query("SET @uz_jmeno = ".$this->user->id.";",$this->dbh);
//    }
    
    
    // echo("<p style=\"color:red\">MysqlStatement execute: {$query}</p>");
    $this->result = mysql_query($query, $this->dbh);
    if(!$this->result) {
      echo("<p style=\"color:red\">MysqlStatement query: {$query}</p>");
      throw new DB_Mysql_Exception;
    }
    return $this;
  }
  public function fetch_row() {
    if(!$this->result) {
      throw new DB_Mysql_Exception("Query not executed");
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