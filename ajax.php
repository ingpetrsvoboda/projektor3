<?php
ob_start();

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
    exit;
}

echo Generator::getContent(Stranka_Kontext::JMENO, Stranka_Kontext::MAIN, array("debugpovolen" => App_Kontext::getDebug(), "nadpis" => "Výběr projektu, kanceláře a běhu", "zprava" => $zprava));
echo Generator::getContent(Stranka_Index::JMENO, Stranka_Index::MAIN, array("debugpovolen" => App_Kontext::getDebug(), "username" => $user->username, "name" => $user->name, "nadpis" => "Projektor", "zprava" => $zprava));

?>