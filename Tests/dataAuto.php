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

echo("<h2>CKancelar</h2>");
    echo("<h3>Výpis položky new Projektor_Data_Auto_CKancelarItem(2):</h3>");
    vypisPolozku(new Projektor_Data_Auto_CKancelarItem(2));
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis položky new Projektor_Data_Auto_CKancelarItem() - bez zadaného id (default hodnoty sloupců):</h3>");
    vypisPolozku(new Projektor_Data_Auto_CKancelarItem());
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis položky new Projektor_Data_Auto_CKancelarItem(123456789) - s neexistujícím id:</h3>");
    vypisPolozku(new Projektor_Data_Auto_CKancelarItem(12345678));
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis kolekce new Projektor_Data_Auto_CKancelarCollection()</h3>");
    vypisTabulku(new Projektor_Data_Auto_CKancelarCollection());
    echo "<p >".interval()."</p>";

echo("<h2>SysAccUsrKancelar</h2>");
    echo ("<h3>Výpis kolekce new Projektor_Data_Auto_SysAccUsrKancelarCollection()</h3>");
    $k = new Projektor_Data_Auto_SysAccUsrKancelarCollection();
    vypisTabulku($k);
    echo "<p >".interval()."</p>";

echo("<h2>CProjekt</h2>");
    echo("<h3>Výpis položky new Projektor_Data_Auto_CProjektItem(2) - prázdná položka, není validní</h3>");
    vypisPolozku(new Projektor_Data_Auto_CProjektItem(2));
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis kolekce new Projektor_Data_Auto_CProjektCollection() - jen validní</h3>");
    vypisTabulku(new Projektor_Data_Auto_CProjektCollection());
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis položky new Projektor_Data_Auto_CProjektItem(2), ->vsechnyRadky() - vypíše i nevalidní položky</h3>");
    $p = new Projektor_Data_Auto_CProjektItem(2);
    $p->vsechnyRadky();
    vypisPolozku($p);
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis kolekce new Projektor_Data_Auto_CProjektCollection(), ->vsechnyRadky()</h3>");
    $p = new Projektor_Data_Auto_CProjektCollection();
    $p->vsechnyRadky();
    vypisTabulku($p);
    echo "<p >".interval()."</p>";

echo("<h2>SStavAkce</h2>");
    echo("<h3>Výpis položky new Projektor_Data_Auto_SStavAkceItem(2):</h3>");
    vypisPolozku(new Projektor_Data_Auto_SStavAkceItem(2));
    echo "<p >".interval()."</p>";
    echo("<h3>Výpis položky new Projektor_Data_Auto_SStavAkceItem(123456):</h3>");
    vypisPolozku(new Projektor_Data_Auto_SStavAkceItem(123456));
    echo "<p >".interval()."</p>";
    echo ("<h3>Výpis kolekce new Projektor_Data_Auto_StavAkceCollection()</h3>");
    vypisTabulku(new Projektor_Data_Auto_SStavAkceCollection());
    echo "<p >".interval()."</p>";

echo("<h2>STypAkce</h2>");
    echo ("<h3>Výpis kolekce new Projektor_Data_Auto_STypAkceCollection()</h3>");
    vypisTabulku(new Projektor_Data_Auto_STypAkceCollection());
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

?>