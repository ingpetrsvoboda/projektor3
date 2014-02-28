<?php
ob_start();

define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();

require 'test_Projektor_Model_Auto_FUNKCE_PRO_TESTY.php';

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
echo("<h2>Zajemce</h2>");
    echo("<h3>Výpis položky new Projektor_Model_Auto_ZajemceItem(10007):</h3>");
    $zajemce = new Projektor_Model_Auto_ZajemceItem(10007);
    echo "<p >".interval()."</p>";
    vypisItem($zajemce);
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis vlastnosti \$zajemce->Smlouva()->dbField°prijmeni:</h3>");
    // varianta s fungujícím napovídáním IDE (místo vlastnosti (smlouva) se volá factory metoda Smlouva(), která provádí type hinting a napovídání funguje
    // i pro vlastnosti podřízeného objektu
    echo "Příjmení: ". $zajemce->Smlouva()->dbField°prijmeni;
    echo "<p >".interval()."</p>";
    // varianta bez fungujícího napovídání IDE
    echo("<h3>Výpis vlastnosti \$zajemce->smlouva->prijmeni:</h3>");
    echo "Příjmení: ". $zajemce->smlouva->prijmeni;
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis kolekce new Projektor_Model_Auto_ZaFlatTableCollection()->where(\"dbField°prijmeni\", \"LIKE\", \"al\", TRUE, TRUE)</h3>");
    $smlouvy = new Projektor_Model_Auto_ZaFlatTableCollection();
    $smlouvy->where("dbField°prijmeni", "LIKE", "alo", TRUE, TRUE);
    vypisCollection($smlouvy);
    echo "<p >".interval()."</p>";
    $zajemci = new Projektor_Model_Auto_ZajemceCollection();
    echo("<h3>Výpis vlastnosti \$zajemci->Item(10007)->Dotaznik()->dbField°nazev_skoly1:</h3>");
    // varianta s fungujícím napovídáním IDE
    // volá se factory metoda kolekce Item(), která vrací item podel id a následně se místo vlastnosti (dotaznik) se volá factory metoda Dotaznik().
    // Metoda Item i Dotaznik provádí type hinting a napovídání funguje i pro vlastnosti podřízeného objektu
    echo "Vzdělání zájemce: ". $zajemci->Item(10007)->Dotaznik()->dbField°nazev_skoly1;
    echo ("<h3>Výpis kolekce new Projektor_Model_Auto_ZajemceCollection()->where(\"dbField°id_c_kancelar_FK\", \"=\", 15)</h3>");
    $zajemci->where("dbField°id_c_kancelar_FK", "=", 15);
    vypisCollection($zajemci);
    echo "<p >".interval()."</p>";


    echo ('</body></html>');


