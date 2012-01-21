<?php
require_once("ProjektorAutoload.php");


//setcookie("beh_id");
$cookie = new Auth_Cookie();
$cookie->logout();
header("Location: ./login.php");
?>