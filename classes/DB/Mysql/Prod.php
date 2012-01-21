<?php
class DB_Mysql_Prod extends DB_Mysql {
    protected $user   = "prod";
    protected $pass   = "secret";
    protected $dbhost = "dbhost";
    protected $dbname = "production";

    public function __construct() { }
}
?>