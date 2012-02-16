<?php
class DB_Mysql_Prod extends DB_Mysql {
    protected $user   = "prod";
    protected $pass   = "secret";
    protected $dbHost = "dbhost";
    protected $dbName = "production";

    public function __construct() { }
}
?>