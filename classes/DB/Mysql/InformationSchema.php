<?php
class DB_Mysql_InformationSchema extends DB_Mysql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbHost =  "radon" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbName =  "information_schema"; 

    public function __construct() { }
}

?>