<?php
require_once("Projektor/ProjektorAutoload.php");


//setcookie("beh_id");
$cookie = new Projektor_App_Auth_Cookie();
$cookie->logout();
session_start();
$_SESSION = array();
session_destroy();
header("Location: ./index.php");
?>