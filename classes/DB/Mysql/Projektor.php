<?php
class DB_Mysql_Projektor extends DB_Mysql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbHost =  "radon" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbName =  "projektor_3_00_centrala_vyvoj"; //"projektor_3_00_centrala_vyvoj" ;      //"projektor_2_00_centrala" ;

    public function __construct() { }
}

?>