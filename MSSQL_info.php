<?php

//The MSSQL extension is enabled by adding extension=php_mssql.dll to php.ini. 


$conn = mssql_connect("NB-SVOBODA\SQLEXPRESS2008", "root", "spravce") or die ("Nepodařilo se připojit k databázi!");

//$query = mssql_query('SELECT @@VERSION');
//
//$row = mssql_fetch_array($query);
//print_r($row);
//// Clean up
//mssql_free_result($query);

$db = mssql_select_db("test_projektor", $conn);
$query = mssql_query('SELECT s_crm_firma.* FROM  s_crm_firma');
$row = mssql_fetch_row($query);
print_r($row);
// Clean up
mssql_free_result($query);


//    protected $user   = "root" ;          //projektor";
//    protected $pass   = "spravce";             //Vekt0r";
//    protected $dbhost =  "localhost" ;     //"radon" ;       // "xenon" ;   ;"localhost"
//    protected $dbname =  "test_projektor"; 
//    $this->dbh = mssql_pconnect($this->dbhost, $this->user, $this->pass);

//    $db = mssql_select_db($this->dbname, $this->dbh);
////$c = new PDO("sqlsrv:Server=localhost;Database=test_projektor", "root", "spravce");
//$c = new PDO("sqlsrv:Server=localhost;Database=test_projektor", "root", "spravce");
//
//$dbo = App_Kontext::getDbMSSQL();
//		$query = "SELECT * FROM ~1";
//		$radek = $dbo->prepare($query)->execute("dbo.s_crm_firma")->fetch_assoc();
?>
