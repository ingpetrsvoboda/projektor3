<?php

ob_start();
require_once("ProjektorAutoload.php");



if(isset($_COOKIE)) {
	$lastname=trim(@$_COOKIE['lastname']);      // SVOBODA - pokud neexistuje lastname v COOKIE, vrací prázdný řetězec - proměnná $lastname vznikne
	$lastprojektid=@$_COOKIE['lastprojektid'];
	$lastkancelarid=@$_COOKIE['lastkancelarid'];
	setcookie("projektId");
	setcookie("kancelarId");
	
    }
if(isset($_GET['uri'])) {
    $uri = $_GET['uri'];
}
else {
    $uri = @$_REQUEST['originating_uri'];  
    if(!$uri) {                             //$_REQUEST['originating_uri' neexistuje a $uri pak také ne, pokud se sem přišlo ze zobrazené přuhlašovací stránky po stisku přihlásit
	$uri = "index.php";
    }
}
$warning = @$_GET['warning'];
//print_r($_GET);
if( isset($_POST['sent']) && $_POST['sent']){
    $name = @$_POST['name'];
    $password = @$_POST['password'];
//    $projektid = @$_POST['Projekt'];
//    $kancelarid = @$_POST['Kancelar'];
    setcookie("lastname",$name,time()+3600);
//    setcookie("lastprojektid",$projektid,time()+3600);
//    setcookie("lastkancelarid",$kancelarid,time()+3600);
//    if(!isset($_POST['Projekt'])) {
//	$warning = "projekt";
//	header("Location: login.php?uri=$uri&warning=$warning");
//	exit;
//    }

    $userid = Auth_Authentication::check_credentials($name,$password);    

    //echo "name:".$name." pass:".$password." userid:".$userid."<br>";
    if($userid){
	
	$cookie = new Auth_Cookie($userid);
	$cookie->set();
	
//        $kancelar = Ciselnik_CiselnikB::najdiPodleId("kancelar", $kancelarid);
//        $projekt = Ciselnik_CiselnikB::najdiPodleId("projekt", $projektid);
//	setcookie("projektId",$projekt->id);
//	setcookie("kancelarId",$kancelar->id);
	header("Location: $uri");
	exit;
    }
    else {
	$warning = "name";
	header("Location: login.php?uri=$uri&warning=$warning");
	exit;
    }
}
 //vydumpovani  databaze 
 //exec("C:\\XAMPP\\mysql\\bin\\mysqldump --user=root --password=spravce projektor2kancelar>D:\\%COMPUTERNAME%_sql.sql");
 
 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <title>Grafia.cz | Projektor | Přihlášení k systému</title>
    </head>
    <body>


<?php
    if($warning=="name") {
?>
	<span  style="color: red"><strong>Přihlášení se nezdařilo</strong></span>
	<br>
<?php
    }
//    if($warning=="projekt") {
?>
<!--	<span  style="color: red"><strong>Prosím vyberte projekt ke kterému se chcete přihlásit a přihlašte se znovu !</strong></span>
	<br>-->
<?php
//    }
?>

	<strong>Přihlášení do systému projektor</strong>
	<form name="Login" ID="Login" action="login.php" method="post">
	    <input type="hidden" name="sent" value="1">
	    <table>
		<tr>
		    <td><label for="text2" >Uživatelské jméno:</label></td>
		    <td><input  type ="text" name="name" ID="Text2" value="<?php echo @$lastname ?>"></td>
		</tr>
		<tr>
		    <td><label for="Password2" >Heslo:</label></td>
		    <td><input type="password" name="password" ID="Password2" class="txtinput"></td>
		</tr>
<!--		<tr>
		    <td><label for="Projekt" >Projekt</label></td>
		    <td><select ID="Projekt" size="1" name="Projekt">
//<?php
//    $dbh = AppContext::getDB();
//    $query = "SELECT id_c_projekt,text FROM c_projekt WHERE valid=True";
//    //echo $query;
//    $data = $dbh->prepare($query)->execute("");
//    while($zaznam = $data->fetch_assoc()) {
//	echo "\t\t\t<option ";
//	if($zaznam['id_c_projekt'] == $lastprojektid) {
//	    echo " selected ";
//	}
//	echo "value=\"".$zaznam['id_c_projekt']."\">".$zaznam['text']."</option>\n";
//    }
//?>
			</select>
		    </td>
		</tr>-->
<!--		<tr>
		    <td><label for="Kancelar" >Kancelář</label></td>
		    <td><select ID="Kancelar" size="1" name="Kancelar">
//<?php
//    $query = "SELECT id_c_kancelar,text FROM c_kancelar WHERE valid=True";
//    $data = $dbh->prepare($query)->execute("");
//    while($zaznam = $data->fetch_assoc()) {
//	echo "\t\t\t<option ";
//	if($zaznam['id_c_kancelar'] == $lastkancelarkod) {
//	    echo " selected ";
//	}
//	echo "value=\"".$zaznam['id_c_kancelar']."\">".$zaznam['text']."</option>\n";
//	
//    }
//?>
			</select>
		    </td>
		</tr>-->
		<tr>
		    <td></td>
		    <td colspan="2">
			<input type="submit" value="Přihlásit" ID="Submit2" NAME="Submit1" class="btn">
		    </td>
		</tr>
	    </table>
	</form>
    </body>
</html>

