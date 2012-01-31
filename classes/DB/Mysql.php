<?php 
class DB_Mysql implements DB_Connection {
  protected $user;
  protected $pass;
  protected $dbhost;
  protected $dbname;
  protected $dbh;


  public function __construct($user, $pass, $dbhost, $dbname) {
    $this->user = $user;
    $this->pass = $pass;
    $this->dbhost = $dbhost;
    $this->dbname = $dbname;
  }
  protected function connect() {
    $this->dbh = mysql_connect($this->dbhost, $this->user, $this->pass);
    if(!is_resource($this->dbh)) {
      throw new DB_Mysql_Exception;
    }
//    if(!mysql_select_db($this->dbname, $this->dbh)) {
//      throw new DB_Mysql_Exception;
//    }
  }

//  public function execute($query) {
//    if(!$this->dbh) {
//      $this->connect();
//    }
//    $ret = mysql_query($query, $this->dbh); 
//    if(!$ret) {
//      throw new DB_Mysql_Exception;
//    }
//    else if(!is_resource($ret)) {
//      return TRUE;
//    } else {
//      $stmt = new DB_Mysql_Statement($this->dbh, $query);
//      $stmt->result = $ret;
//      return $stmt;
//    }
//  }
  /**
   *
   * @param <string> $query
   * @param <type> $user
   * @return DB_Mysql_Statement
   */
  public function prepare($query,$user=False) {
    // SVOBODA - zde se pripojuje k databazi a neco se nastavuje, ale ve skutecnosti se vlastni sql prikaz provadi az pri volani DB_MysqlStatement->execute
    // bylo by asi spravne propojovat se a nastavovat az v DB_MysqlStatement->execute
    // zde se pripojuje moc brzy (ne lazy) a takto se muze stat, ze si pripravim vice dotazu opakovanym volanim prepare s ruznym Data_user
    // zatim nevim k cemu je Data_user
    if(!$this->dbh) {
      $this->connect();
      //Nastaaveni znakove sady pro přenos dat
//      mysql_query("SET CHARACTER SET utf8");
    }
//    //Nastaveni uzivatele pro zaznam do tabulky aktualizaci
//    if(!$user) {
//        mysql_query("SET @uz_jmeno = 1",$this->dbh);
//    }
//    else {
//        mysql_query("SET @uz_jmeno = ".$this->user->id.";",$this->dbh);
//    }
    
        
    
    return new DB_Mysql_Statement($this->dbh, $this->dbname, $query);
  }


//    public function __get($name) {
//        if (isset($name)) 
//        {
//        	return $this->$name;
//        }
//        else
//        {
//            return false;
//        }
//    }
}
?>