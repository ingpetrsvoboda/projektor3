<?php
ob_start();

require 'test_Projektor_Model_Auto_FUNKCE_PRO_TESTY.php';
define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();
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
    echo ("<h3>Výpis new Projektor_Model_Auto_SysUsersCollection</h3>");
    vypisCollection(new Projektor_Model_Auto_SysUsersCollection);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis \$user = new Projektor_Model_Auto_SysUsersItem() a ->najdiPodleJmena('sys_admin')</h3>");
    $user = new Projektor_Model_Auto_SysUsersItem();
    $user->najdiPodleJmena("sys_admin");
    vypisItem($user);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis new Projektor_Model_Auto_CKancelarCollection() a ->dejPovoleneKancelare(\$user->id)</h3>");
    $kancl = new Projektor_Model_Auto_CKancelarCollection();
    $kancl->dejPovoleneKancelare($user->id);
    vypisCollection($kancl);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis new Projektor_Model_Auto_CProjektCollection a ->dejPovoleneProjekty(\$user->id)</h3>");
    $proj = new Projektor_Model_Auto_CProjektCollection;
    $proj->dejPovoleneProjekty($user->id);
    vypisCollection($proj);
    echo "<p >".interval()."</p>";

echo ('</body></html>');


