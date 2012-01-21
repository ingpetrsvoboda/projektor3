<?php
class DB_Mssql_Projektor extends DB_Mssql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbhost =  "localhost" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbname =  "test_projektor"; 

    public function __construct() { }
}

?>