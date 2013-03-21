<?php
class Projektor_DB_Mysql_InformationSchema extends Projektor_DB_Mysql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbHost =  "localhost" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbName =  "information_schema"; 

    public function __construct() { }
}

?>