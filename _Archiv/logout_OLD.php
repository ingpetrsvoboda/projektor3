<?php
require_once("Projektor/ProjektorAutoload.php");


//setcookie("beh_id");
$cookie = new Framework_Cookie_CryptCookie();
$cookie->signOut();
session_start();
$_SESSION = array();
session_destroy();
header("Location: ./index.php");
?>