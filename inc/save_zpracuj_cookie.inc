<?php

if ( ($User->username == "sys_admin" ))  {
   // echo "a COOKIE * " ; print_r($_COOKIE) ;
}

try {
    $cookie = new Projektor_App_Auth_Cookie();
    $cookie->validate();
    //session_id($cookie->userid);
    //session_start();
    global $userid;
    $userid =$cookie->get_userid();
    session_id($userid);
    session_start();
}
catch (Projektor_App_Auth_Exception $e) {
	header("Location: ./login.php?originating_uri=".$_SERVER['REQUEST_URI']);
    exit;
}
//---------------------------------------------------------------------------
//require_once "./PDF/PDF/pdf.php";

//----------------------------------------------------------------------------
//echo "<BR>AKCE*" . $_GET['akce'] ."**"  .$_POST['akce'] ;
//echo "<BR>POST*"; print_r($_POST) ; exit;

// $dbh = new Projektor_DB_Mysql_Projektor;    //DDBBHH //musi byt	//	klon
    $dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);

//ind_save_form.php se vola zvlast (ne pres zobrazeni indexem jako akce formulare v ind_reg_dot.inc)

//zjisteni jmena uzivatele
$query = "SELECT * FROM sys_users
            WHERE id_sys_users = :1";
$data_users = $dbh->prepare($query)->execute($userid)->fetch_assoc();
//print_r("*data_users* " . $data_users['username'] . " * " .  $data_users['povolen_zapis']   );


$Kancelar = Projektor_Data_KancelarMProjektor_Apper::find_by_id($_COOKIE['kancelarId']);     //DDBBHH  aby se nevytvarelo pokazde nove pripojeni
$Projekt = Projektor_Data_ProjektMProjektor_Apper::find_by_id($_COOKIE['projektId']);
$Beh = Projektor_Data_BehMProjektor_Apper::find_by_id($_COOKIE['beh_id'], $dbh);
//Overeni prav
    //$dbh = new Projektor_DB_Mysql_Projektor;         //DDBBHH  aby se nevytvarelo pokazde nove pripojeni
    //Kancelar
$query = "SELECT id_sys_acc_usr_kancelar
            FROM sys_acc_usr_kancelar
            WHERE id_sys_users =:1
            AND id_c_kancelar =:2";
$data=$dbh->prepare($query)->execute($userid,$Kancelar->id)->fetch_row();
if(!$data) {
    throw new Exception("No valid credentials for this Kancelar");
}
    //Projekt
$query = "SELECT id_sys_acc_usr_projekt
            FROM sys_acc_usr_projekt
            WHERE id_sys_users =:1
            AND id_c_projekt =:2";
$data=$dbh->prepare($query)->execute($userid,$Projekt->id)->fetch_row();
if(!$data) {
    throw new Exception("No valid credentials for this Projekt");
}

    //Beh projektu
$beh_ok = false;
if(!$Beh->closed) {
    if($Beh->konec->f_mysql != "NULL") {
        $konec_tstmp = strtotime($Beh->konec->f_web);
        $ted_tstmp = strtotime("now");
        echo"\n".$konec_tstmp."-".$ted_tstmp;
        if($konec_tstmp>$ted_tstmp) {
            $beh_ok = true;
        }
    }
    else {
        $beh_ok= true;
    }
}
if(!$beh_ok) {
    throw new Exception("Not valid data for Beh projektu");
}



//Nový účastník nebo UPDATE
if(isset($_COOKIE['id_ucastnik']) && $_COOKIE['id_ucastnik'] != "") {
    //Kontrola a nalezeni ucastnika
    if(!$Ucastnik = Projektor_Data_UcastnikMProjektor_Apper::find_by_id($_COOKIE['id_ucastnik'])) {
        throw new Exception("You are trying to manipulate with non existing object ucastnik");
    }
    //Kontrola zda je zvoleny ucastnik ve zvolenem projektu, kancelari a behu
    if(!($Ucastnik->projekt->id == $Projekt->id
         && $Ucastnik->kancelar->id == $Kancelar->id
         && $Ucastnik->beh->id == $Beh->id)){
        throw new Exception("Your object - Ucastnik is not in current projekt or kancelar or beh, sorry");
    }

}
else {
    $Ucastnik = new Ucastnik($Projekt,$Kancelar,$Beh);
}

//echo "<BR>*\Ucastnik*";  print_r ($Ucastnik); exit;
