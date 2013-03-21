<?php
class Projektor_DB_Mssql_CRM extends Projektor_DB_Mssql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbHost =  "NB-SVOBODA\SQLEXPRESS2008" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbName =  "test_projektor"; 

    public function __construct() { }
}

?>