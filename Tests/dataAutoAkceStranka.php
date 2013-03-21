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

    if (isset($_GET['cesta']))
    {
        echo Projektor_Dispatcher_Cesta::getContent($_GET['cesta']);
    } else {
//        $koren = new Projektor_Router_Uzel("Projektor_Stranka_AkceJ_Menu_Detail", null, array("id" => 5), FALSE);
        $koren = new Projektor_Dispatcher_Uzel("Projektor_Stranka_Akce_Seznam", null, array("id" => 5), FALSE);
        $cesta_serialized = serialize($koren);
        echo Projektor_Dispatcher_Cesta::getContent($cesta_serialized);
    }

        echo "<p >".interval()."</p>";

echo("<h2>Akce</h2>");
    echo ("<h3>Chyba při volání Projektor_Data_Auto_AkceCollection->where a Projektor_Data_Auto_AkceCollection->addWhere a Projektor_Data_Auto_AkceCollection->order:</h3>");
    echo ("<h3>Je načtena celá kolekce bez užití filtru</h3>");
    $akceCollection = new Projektor_Data_Auto_AkceCollection();
    $akceCollection->where("blabla", "=", 'Zajemce');
    $akceCollection->where("blabla", "=", 'Zajemce');
    $akceCollection->order("popis", "pěkně");
    // TODO: PROBLÉM napovídání vlastností objektu Item v kolekci - například pro zadání vlastnosti do where
    //$itemClass::
    vypisTabulku($akceCollection);
    echo "<p >".interval()."</p>";
    echo ("<h3>Korektní volání Projektor_Data_Auto_AkceCollection->where WHERE nazev_hlavniho_objektu = 'Zajemce' ORDER BY Nazev ASC\nse stejnou kolekcí a zavolání metody nacti() přidá nový výběr do kolekce:</h3>");
    $akceCollection->where("nazev_hlavniho_objektu", "=", 'Zajemce');
    $akceCollection->order("nazev", "ASC");
    $akceCollection->nacti();
    vypisTabulku($akceCollection);
    echo "<p >".interval()."</p>";
    echo ("<h3>Korektní volání Projektor_Data_Auto_AkceCollection->where WHERE nazev_hlavniho_objektu = 'Zajemce' ORDER BY Nazev ASC:</h3>");
    $akceCollection = new Projektor_Data_Auto_AkceCollection();
    $akceCollection->where("nazev_hlavniho_objektu", "=", 'Zajemce');
    $akceCollection->order("nazev", "ASC");
    vypisTabulku($akceCollection);
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis nové prázdné položky new Projektor_Data_Auto_AkceItem()</h3>");
    $akceItem = Projektor_Data_Auto_AkceItem::factory();
    vypisPolozku($akceItem);
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis výše vytvořené položky s nově nastavenou vlastností:</h3>");
    $akceItem->dbField°nazev = "nový název";
    vypisPolozku($akceItem);
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis položky new Projektor_Data_Auto_AkceItem(5):</h3>");
    vypisPolozku(new Projektor_Data_Auto_AkceItem(5));
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis jedné vlastnosti položky Projektor_Data_Auto_AkceItem(5):</h3>");
    $akceItem = new Projektor_Data_Auto_AkceItem(5);
    echo "\$akceItem->dbField°popis: ".$akceItem->dbField°popis."\n";
    echo "\$akceItem->popis: ".$akceItem->popis."\n";
    echo "\$akceItem->id: ".$akceItem->id."\n";
    echo "<p >".interval()."</p>";
    echo("<h4>Změna vlastností položky:</h4>");
    $popis = $akceItem->dbField°popis;
    $akceItem->dbField°popis = "Nový krásný popis ".time()."!";
    echo "\$akceItem->dbField°popis: ".$akceItem->dbField°popis."\n";
    echo("<h4>Uložení změněné položky:</h4>");
    echo "Uložena položka s id: ".$akceItem->uloz()."\n";
    echo "<p >".interval()."</p>";
    echo("<h4>Znovunačtení a výpis položky Projektor_Data_Auto_AkceItem(5):</h4>");
    $akceItem = new Projektor_Data_Auto_AkceItem(5);
    vypisPolozku($akceItem);
    echo "<p >".interval()."</p>";
    echo("<h4>Vrácení předchozí hodnoty vlastností položky:</h4>");
    $akceItem->dbField°popis = $popis;
    echo "\$akceItem->dbField°popis: ".$akceItem->dbField°popis."\n";
    echo("<h4>Uložení změněné položky:</h4>");
    echo "Uložena položka s id: ".$akceItem->uloz()."\n";
    echo "<p >".interval()."</p>";
    echo("<h4>Znovunačtení a výpis položky Projektor_Data_Auto_AkceItem(5):</h4>");
    $akceItem = new Projektor_Data_Auto_AkceItem(5);
    vypisPolozku($akceItem);
    echo "<p >".interval()."</p>";

    // Vytvoreni nove
    $novaAkce = Projektor_Data_Auto_AkceItem::factory();
    vypisPolozku($akceItem);
    $novaAkce->dbField°nazev_hlavniho_objektu = "Zajemce";
    $novaAkce->dbField°datum_konec = "2009-11-01";
    $novaAkce->dbField°datum_konec = "2009-11-02";
    $novaAkce->dbField°nazev = "Staropramen 12&deg; ".time();
    $novaAkce->dbField°popis = "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.";
    echo $id = $novaAkce->uloz();
    echo "<p >".interval()."</p>";

    $akceItem = new Projektor_Data_Auto_AkceItem($id);
    vypisPolozku($akceItem);




//Nalezeni vsechn ucastniku dane akce
    /* $akce = Akce::najdiPodleId(34);
      print_r($akce->vsichniUcastnici()); */

//Nalezeni vsech akci ucastnika
    /* $akcev = Akce::vsechnyUcastnika(UcastnikB::najdiPodleId(12));
      print_r($akcev); */


//Zmena stavu
    /* $akce = Akce::najdiPodleId(14);
      $ucastnik = UcastnikB::najdiPodleId(29);
      print_r($akce->stavUcastnika($ucastnik));
      $akce->zmenStavUcastnika($ucastnik, Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId(3), Projektor_Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(3)); */


//Generovani AkceDnu
    /* $novaAkce = new Akce("2009-11-07", "Bernard 12&deg; ", "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.", 2, 22);
      print_r($novaAkce);
      echo $novaAkce->uloz(); */
    /* $novaAkce = Akce::najdiPodleId(14);
      print_r($novaAkce);
      $novaAkce->vytvorDny(Projektor_Data_Seznam_SUcebna::najdiPodleId(1), Projektor_Data_Seznam_SPersonal::najdiPodleId(1)); */

//vypis vsech dnu akce
    /* $akce = Akce::najdiPodleId(14);
      print_r($akce->vsechnyDny()); */

    /* $akce = Akce::najdiPodleId(14);
      $akce->zmenStav(Projektor_Data_Seznam_SStavAkce::najdiPodleId(3), Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId(3), Projektor_Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(3)); */

    echo ('</body></html>');

