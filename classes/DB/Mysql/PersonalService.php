<?php
class DB_Mysql_PersonalService extends DB_Mysql {
    protected $user   = "root" ;          //projektor";
    protected $pass   = "spravce";             //Vekt0r";
    protected $dbHost =  "localhost" ;     //"radon" ;       // "xenon" ;   ;"localhost"
    protected $dbName =  "personalservice_web"; //"projektor_3_00_centrala_vyvoj" ;      //"projektor_2_00_centrala" ;

    public function __construct() { }
}

?>