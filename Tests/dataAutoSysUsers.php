<?php
ob_start();

require 'dataAutoFunkceProTest.php';
define("CLASS_PATH", "../");
require_once(CLASS_PATH . "Projektor/ProjektorAutoload.php");
echo ('
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Projektor | test |</title>
        <link rel="icon" type="image/gif" href="../favicon.gif"></link>
        <link rel="stylesheet" type="text/css" href="../css/default.css" />
        <link rel="stylesheet" type="text/css" href="../css/highlight.css" />
    </head>

    <body>
        ');


echo interval();

//Vypis

echo("<h2>SysUsers</h2>");
    echo ("<h3>Výpis new Projektor_Data_Auto_SysUsersCollection</h3>");
    vypisTabulku(new Projektor_Data_Auto_SysUsersCollection);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis \$user = new Projektor_Data_Auto_SysUsersItem() a ->najdiPodleJmena('sys_admin')</h3>");
    $user = new Projektor_Data_Auto_SysUsersItem();
    $user->najdiPodleJmena("sys_admin");
    vypisPolozku($user);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis new Projektor_Data_Auto_CKancelarCollection() a ->dejPovoleneKancelare(\$user->id)</h3>");
    $kancl = new Projektor_Data_Auto_CKancelarCollection();
    $kancl->dejPovoleneKancelare($user->id);
    vypisTabulku($kancl);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis new Projektor_Data_Auto_CProjektCollection a ->dejPovoleneProjekty(\$user->id)</h3>");
    $proj = new Projektor_Data_Auto_CProjektCollection;
    $proj->dejPovoleneProjekty($user->id);
    vypisTabulku($proj);
    echo "<p >".interval()."</p>";

echo ('</body></html>');


