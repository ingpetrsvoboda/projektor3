<?php
class Projektor_DB_Mysql_Prod extends Projektor_DB_Mysql {
    protected $user   = "prod";
    protected $pass   = "secret";
    protected $dbHost = "dbhost";
    protected $dbName = "production";

    public function __construct() { }
}
?>