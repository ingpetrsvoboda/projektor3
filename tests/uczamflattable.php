<?php
	require_once("../autoload.php");

	echo("<pre>");

	/*
	$dbh = AppContext::getDB();
/*
zjisteni jmena uzivatele
$query = "SELECT * FROM sys_users
            WHERE id_sys_users = :1";
$data_users = $dbh->prepare($query)->execute($userid)->fetch_assoc();
//print_r($da['username']);
/**/
	/*
        $Kancelar = Data_KancelarMapper::find_by_id(10);
        $Projekt = Data_ProjektMapper::find_by_id(4);
        $Beh = Data_BehMapper::find_by_id(12);
        $User = Data_UserMapper::find_by_id(1); // sys_admin
	/**/
        $Ucastnik = Data_UcastnikMapper::find_by_id(111);
        echo "Ucastnik:<br>";
        print_r($Ucastnik);
        $zamFlatTable = new Data_Flat_UcZamFlatTable($Ucastnik);
        echo "=============================================================================================================<br>";
        echo "UcZamFlatTable:<br>";
	print_r($zamFlatTable);
	/**/

	/*
	$ucastnici = Data_Ucastnik::vypisVse(Data_Ucastnik::ID." < 30");
	print_r($ucastnici);
	/**/

?>
