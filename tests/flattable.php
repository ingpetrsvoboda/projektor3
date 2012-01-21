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
        $Ucastnik = Data_UcastnikMapper::find_by_id(3);
        echo "Ucastnik:<br>";
        print_r($Ucastnik);
        $flatTable = new Data_Flat_FlatTable("test_flat_table",$Ucastnik);
        echo "=============================================================================================================<br>";
        echo "FlatTable new:<br>";
	print_r($flatTable);

        echo "=============================================================================================================<br>";
        $flatTable->save_values();
        echo "FlatTable cti:<br>";
	print_r($flatTable);

        $pole = array("i" => 888, "c" => "retezec", f => 6.28, u => 98765);
        
        foreach($pole as $klic => $hodnota) {
            $flatTable->$klic = $hodnota;
        }
        $flatTable->uloz();

        $flatTable = new Data_Flat_FlatTable("test_flat_table", '');  //nový
        $flatTable->uloz();

        $flatTable = new Data_Flat_FlatTable("test_flat_table",$Ucastnik);  //pokus o uložení bez nastavení jakýchkoli hodnot
        $flatTable->uloz();


        /**/

	/*
	$ucastnici = Data_Ucastnik::vypisVse(Data_Ucastnik::ID." < 30");
	print_r($ucastnici);
	/**/

?>
