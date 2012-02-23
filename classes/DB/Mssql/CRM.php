<?php
class DB_Mssql_CRM extends DB_Mssql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbHost =  "NB-SVOBODA\SQLEXPRESS2008" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbName =  "test_projektor"; 

    public function __construct() { }
}

?>