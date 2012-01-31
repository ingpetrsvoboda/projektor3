<?php
ob_start();

//require_once("autoload_define.php");
require_once("ProjektorAutoload.php");
                
try {
    $cookie = new Auth_Cookie();
    $cookie->validate();
    global $userid;
    $userid =$cookie->get_userid();
    session_id($userid);
    session_start();
}
catch (Auth_Exception $e) {
	header("Location: ./login.php?originating_uri=".$_SERVER['REQUEST_URI']."&index_redir=1");
    exit;
}

$user = Data_Sys_Users::najdiPodleId($userid);    

//Kontrola oprávnění
$povoleneProjekty = Data_Sys_AccUsrProjekt::dejPovoleneProjekty($user->id);
$povoleneKancelare = Data_Sys_AccUsrKancelar::dejPovoleneKancelare($user->id);
//TODO: OPRAVIT COOKIES - kydyž se hlásí jiný uživatel, nastavit cookies projekt, kencelar, beh, debug
//TODO: session
if(isset($_COOKIE)) {
    $cookieProjektId = @$_COOKIE['projektId'];
    $cookieKancelarId = @$_COOKIE['kancelarId'];
    $cookieBehId = @$_COOKIE['behId'];
    $cookieJeDebug = @$_COOKIE['jeDebug'];
    $kancelarZCookie = Data_Ciselnik::najdiPodleId("kancelar", $_COOKIE['kancelarId']);
    $projektZCookie = Data_Ciselnik::najdiPodleId("projekt", $_COOKIE['projektId']);
    $behZCookie = Data_Seznam_SBehProjektu::najdiPodleId($_COOKIE['behId']);
}

if ($povoleneProjekty)
{
    foreach ($povoleneProjekty as $povolenyProjekt) {
        if ($projektZCookie == $povolenyProjekt) $projekt = $projektZCookie;
    }
}

if ($povoleneKancelare)
{
    foreach ($povoleneKancelare as $povolenaKancelar) {
        if ($kancelarZCookie == $povolenaKancelar) $kancelar = $kancelarZCookie;
    }
}

$kontextUser = App_Kontext::setUserKontext(new User_Kontext($user, $projekt, $kancelar, NULL, $povoleneProjekty, $povoleneKancelare));

if ($projekt)
{
    $behyProjektu = Data_Seznam_SBehProjektu::vypisVse();   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
    if ($behyProjektu)
    {
        foreach ($behyProjektu as $behProjektu) {
            if ($behZCookie == $behProjektu) $beh = $behZCookie;    
        }
    } 
$kontextUser = App_Kontext::setUserKontext(new User_Kontext($user, $projekt, $kancelar, $beh, $povoleneProjekty, $povoleneKancelare));
}


$html1 = print_r('
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Grafia.cz | Projektor |</title>
                <link rel="stylesheet" type="text/css" href="css/default.css" />
                <link rel="stylesheet" type="text/css" href="css/highlight.css" />
            <script src="js/kzam_okno.js"></script>
            </head>
            <body>
                <div id="logout-ie">
                    <div id="logout">
                    <form name="Logout" id="Logout" action="./logout.php" method="get">
                        <input type="Submit" value="Odhlásit">
                    </form>
                    </div>
                </div>
            ', TRUE);           
echo $html1;

if (isset($cookieJeDebug) AND $cookieJeDebug) {
        App_Kontext::setJeDebug();
        if ($_GET["debug"] == "0") App_Kontext::unsetJeDebug(); 
} else {
        App_Kontext::unsetJeDebug();
        if ($_GET["debug"] == "1") App_Kontext::setJeDebug();    
}
$d = App_Kontext::getDebug();
echo Generator::getContent(Stranka_Kontext::JMENO, Stranka_Kontext::MAIN, array("debugpovolen" => App_Kontext::getDebug(), "nadpis" => "Výběr projektu, kanceláře a běhu", "zprava" => $zprava));
echo Generator::getContent(Stranka_Index::JMENO, Stranka_Index::MAIN, array("username" => $user->username, "name" => $user->name, "nadpis" => "Projektor", "zprava" => $zprava));
$html2 = print_r('
    </body>
    </html>
            ', TRUE);           
echo $html2;

$kontextUser = App_Kontext::getUserKontext();
        setcookie("projektId",$kontextUser->projekt->id);
	setcookie("kancelarId",$kontextUser->kancelar->id);
	setcookie("behId",$kontextUser->beh->id);
        setcookie('jeDebug', App_Kontext::getDebug());

?>