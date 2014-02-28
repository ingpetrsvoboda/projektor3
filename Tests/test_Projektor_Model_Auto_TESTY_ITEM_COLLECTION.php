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
echo "<h2>Pomocné funkce užité v příkladech</h2>";
    echo "<h3>Tyto funkce jsou současně ukázkou práce s objekty Item a Collection, zejména ukazují použití Item a Collection ve foreach cyklu (objekty jsou iterovatelné, implementují rozhranní Iterator)</h3>";
    echo "Kód: <br><pre class='kod'>".getFunctionCode('vypisItem', TRUE, TRUE)."</pre>";
    echo "Kód: <br><pre class='kod'>".getFunctionCode('vypisCollection', TRUE, TRUE)."</pre>";
    echo "Kód: <br><pre class='kod'>".getFunctionCode('vypisCollections', TRUE, TRUE)."</pre>";
    echo "Kód: <br><pre class='kod'>".getFunctionCode('vypisHlavickuProItem', TRUE, TRUE)."</pre>";
    echo "Kód: <br><pre class='kod'>".getFunctionCode('vypisHlavickuProPrazdnyItem', TRUE, TRUE)."</pre>";
    echo "Kód: <br><pre class='kod'>".getFunctionCode('dejReferencovaneKolekce', TRUE, TRUE)."</pre>";

echo interval();

//Vypis

echo "<h2>Item, který nemá reference na jiné Collection - Item z db tabulky bez cizích klíčů.</h2>";
    echo "<h3>Výpis položky:</h3>";
    $test = function (){
        vypisItem(new Projektor_Model_Auto_CKancelarItem(10));        
    };
    runTestFunction($test);
    echo "<h3>Výpis položky s předepsáním seznamu vlastností (sloupců):</h3>";
    echo "<p>Pokud voláním metody select() nenastavíte seznam vlastností, jsou do objektu Item načteny všechny sloupce db tabulky ( je použit SQL příkaz SELECT * FROM ...).</>";
    echo "<p>Pokud chcete, aby objekt Item měl pouze požadované vlastnosti, použijte metodu select(). Parametrem metody je pole obsahující řetězce s názvy 
        požadovaných vlastností objektu nebo názvy požadovaných sloupců db tabulky. Vlastnost odpovídající primárnímu klíči db tabulky je doplněna vždy. 
        Příklad předpokládá prefix 'dbField°'</p>";
    $test = function (){
        $kancelarItem = new Projektor_Model_Auto_CKancelarItem(10);
        $kancelarItem->select(array('kod', 'dbField°plny_text'));
        echo "<p>Výpis položky se dvěma požadovanými vlastnostmi - 'kod' zadána názvem sloupce, 'dbField°plny_text' zadána názvem vlastnosti. Vlastnost 'id_c_kancelar' je doplněna automaticky.</p>";
        vypisItem($kancelarItem);        
        $kancelarItem->select();
        echo "<p>Výpis položky bez vlastností (požadavek, aby neměla žádné). Vlastnost 'id_c_kancelar' je doplněna automaticky.</p>";
        vypisItem($kancelarItem);
        $kancelarItem->select(array('*'));
        echo "<p>Výpis položky se všemi vlastnostmi. Takto je možno vrátit výchozí (default) nastavení objektu Item, které by měl, pokud byste metodu select() vůbec nevolali.</p>";
        vypisItem($kancelarItem);
        echo "<p>Výpis položky s předepsáním seznamu vlastností (sloupců) s chybou.</p>";        
        $kancelarItem->select(array('kod', 'dbField°qqplny_text'));
        vypisItem($kancelarItem);         };
    runTestFunction($test);
    echo "<h3>Výpis položky bez zadaného parametru id (vrací default hodnoty sloupců db tabulky):</h3>";
    $test = function (){
        vypisItem(new Projektor_Model_Auto_CKancelarItem());
    };
    runTestFunction($test);
    echo "<h3>Výpis položky s neexistujícím id:</h3>";
    echo "<p>Při vytvoření položky s neexistujícím id vznikne prázdná položka (Item bez vlastností), obdobně jako při volání factory().
        Takto vzniklý Item je možno upravovat - nastavovat vlastnosti a volat metody uloz(). Pak je uložen nový objekt.";
    $test = function (){
        vypisItem(new Projektor_Model_Auto_CKancelarItem(12345678));
    };
    runTestFunction($test);
    
echo "<h2>Collection, která nemá reference na jiné Collection - Collection pro db tabulku bez cizích klíčů.</h2>";    
    echo "<h3>Výpis kolekce:</h3>";
    $test = function (){
        vypisCollection(new Projektor_Model_Auto_CKancelarCollection());
    };
    runTestFunction($test);
    echo "<h3>Výpis kolekce s předepsáním seznamu vlastností (sloupců):</h3>";
    echo "<p>Metoda selectAttributes() objektu Collection funguje obdobně jako metoda select() objektu Item - viz výše.</p>";
    $test = function (){
        $kancelarCollection = new Projektor_Model_Auto_CKancelarCollection();
        $kancelarCollection->selectAttributes(array('kod', 'dbField°plny_text'));
        vypisCollection($kancelarCollection);
    };
    runTestFunction($test);
echo "<h2>Item a Collection bez referencí na jiné Collection - práce s validními a nevalidními položkami.</h2>";
    echo "<h3>Pokus o výpis položky, která není validní:</h3>";
    $test = function (){
        vypisItem(new Projektor_Model_Auto_CProjektItem(2));
    };
    runTestFunction($test);
    echo "<h3>Výpis kolekce - jen validní položky:</h3>";
    $test = function (){
        vypisCollection(new Projektor_Model_Auto_CProjektCollection());
    };
    runTestFunction($test);
    echo "<h3>Výpis položky ať je validní nebo nevalidní:</h3>";
    $test = function (){
        $p = new Projektor_Model_Auto_CProjektItem(2);
        $p->vsechnyRadky();
        vypisItem($p);
    };
    runTestFunction($test);
    echo "<h3>Výpis kolekce s validními i nevalidními položkami:</h3>";
    $test = function (){
        $p = new Projektor_Model_Auto_CProjektCollection();
        $p->vsechnyRadky();
        vypisCollection($p);
    };
    runTestFunction($test);

echo "<h2>Načtení Collection s referencemi na jiné Collection - Collection pro db tabulku s cizími klíči.</h2>";
    echo "<h3>Výpis kolekce a referencovaných kolekcí:</h3>";
    $test = function (){
        $k = new Projektor_Model_Auto_SysAccUsrKancelarCollection();
        vypisCollection($k);
    };
    runTestFunction($test);
    
echo "<h2>Načtení Collection s referencemi na jiné Collection a s použítím filtrů where a order.</h2>";
    echo "<h3>Korektní volání where() a order():</h3>";
    $test = function (){
        $akceCollection = new Projektor_Model_Auto_AkceCollection();
        $akceCollection->where('nazev_hlavniho_objektu', '=', 'Zajemce');
        $akceCollection->order('nazev', 'DESC');
        vypisCollection($akceCollection);
    };
    runTestFunction($test);
    echo "<h3>Korektní volání where() a order() s podmínkou, která vrací prázdnou kolekci:</h3>";
    $test = function (){
        $akceCollection = new Projektor_Model_Auto_AkceCollection();
        $akceCollection->where('nazev_hlavniho_objektu', '=', 'blabla');
        vypisCollection($akceCollection);
    };
    runTestFunction($test);    
    echo "<h3>Chybné volání metod where() a order() a použití metody nacti().</h3>";
    echo "<h4>Chyby při volání metod where() a order():</h4>";
    echo "<p>Chyby při volání metod where() a order() vyvolají výjimku, která je ošetřena tak, že vypíše chybové hlášní a pokračuje v běhu skruptu. 
        Filtr není vytvořen, ale provádění skriptu pokračuje, objekt existuje a je funkční. Je načtena tedy celá kolekce (celá db tabulka) bez řazení!</p>";
    echo "<h4>Použití metody nacti(): </h4>";
    echo "<p>Metodou načti je nmožno přidávat položky do již načtené kolekce. Korektní volání  where() a order() a následné zavolání metody nacti() přidá nový výběr do kolekce:</p>";
        // TODO: PROBLÉM napovídání vlastností objektu Item v kolekci - například pro zadání vlastnosti do where
        //$itemClass::
    $test = function (){
        $akceCollection = new Projektor_Model_Auto_AkceCollection();
        $akceCollection->where('blabla', '=', 'Zajemce');
        $akceCollection->where('nazev_hlavniho_objektu', 'KLIKE', 'Zajemce');
        $akceCollection->order('popis', 'pěkně');
        echo "<p> Výpis kolekce po chybné volání where() a order():</p>";
        vypisCollection($akceCollection);
        $akceCollection->where('nazev_hlavniho_objektu', '=', 'Zajemce');
        $akceCollection->order('nazev', 'ASC');
        $akceCollection->nacti();
        echo "<p>Výpis stejné kolekce (výpis stejnéh instance objektu) po použití metody nacti():</p>";
        vypisCollection($akceCollection);
    };
    runTestFunction($test);

echo("<h2>Načtení objektu Item z databáze, práce s vlastnostmi objektu Item a uložení do databáze.</h2>");
    echo "<p>Item je načítán z databáze, jde tedy o již dříve uložený Item.</p>";
    echo("<h3>Výpis jednotlivých vlastností položky:</h3>");
    echo ("<p>1. Výpis vlastnosti objektu - název vlastnosti je tvořen: prefix°názevSloupceTabulky.</p>");
    echo ("<p>2. Výpis vlastnosti objektu - jako název vlastnosti je použit přímo název sloupce db tabulky.</p>");
    echo ("<p>3. Výpis vlastnosti objektu - identifikátoru. Identifikátor je vlastnost pojmenovaná vždy 'id'.</p>");
    echo ("<p>4. Výpis skutečného názvu sloupce s identifikátorem</p>");
    $test = function (){
        $akceItem = new Projektor_Model_Auto_AkceItem(6);
        vypisItem($akceItem);
        echo "<p>1. ".$akceItem->dbField°popis."</p>";
        echo "<p>2. ".$akceItem->popis."</p>";
        echo "<p>3. ".$akceItem->id,"</p>";
        echo "<p>4.".$akceItem->dejPrimaryKeyFieldName(),"</p>";
    };
    runTestFunction($test);
    echo "<h3>Změna vlastností položky a uložení změněné položky.</h3>";
    echo "<p>Návratová hodnota metody uloz() je počet uložených položek (affected rows)</p>
        <p>1. Změna vlastnosti položky a výpis nově nastavené vlastnosti \$akceItem->dbField°popis.</p>
        <p>2. Uložení objektu a výpis počtu uložených položek. V případě úspěšného uložení je návratová hodnota 1.</p>
        <p>3. Další změna vlastnosti položky (vrácení staré hodnoty) a výpis nově nastavené vlastnosti \$akceItem->dbField°popis.</p>
        <p>4. Uložení objektu a výpis počtu uložených položek.</p>";
    $test = function (){
        $akceItem = new Projektor_Model_Auto_AkceItem(6);
        $uschovanyPopis = $akceItem->dbField°popis;
        $akceItem->dbField°popis = 'Nový krásný popis '.time().'!';
        echo "<p>1. ".$akceItem->dbField°popis."</p>";
        echo "<p>2. Uloženo položek: ".$akceItem->uloz()."</p>";
        $akceItem->dbField°popis = $uschovanyPopis;
        echo "<p>3. ".$akceItem->dbField°popis."</p>";
        echo "<p>4. Uloženo položek: ".$akceItem->uloz()."</p>";
    };
    runTestFunction($test);
    echo "<h3>Stejný příklad - změna vlastností položky a uložení změněné položky, ale s demonstrací metod isNacten() a isZmenen().</h3>";
    echo "<p>Metoda isNacten() vrací TRUE po načtení dat z db do vlastností objektu. Vlastnosti objektu Item jsou načítány lazy load
        až v okamžiku prvního použití některé vlastnosti objektu Item pro čtení nebo zapsání nové hodnoty.</p>
        <p>Metoda isZmenen() informuje, zda vlastnosti objektu mají jiné hodnoty než hodnoty uložené v databázi. 
        Vrací FALSE po načtení nebo uložení objektu. Vrací TRUE po změně hodnoty některé vlastnosti.</p>
        <p>Návratová hodnota metody uloz() je počet uložených položek (affected rows).</p>
        <p>1. Změna vlastnosti položky a výpis nově nastavené vlastnosti \$akceItem->dbField°popis.</p>
        <p>2. Uložení objektu a výpis počtu uložených položek. V případě úspěšného uložení je návratová hodnota 1.</p>
        <p>3. Nová změna vlastnosti položky a výpis nově nastavené vlastnosti \$akceItem->dbField°popis.</p>
        <p>4. Nové uložení objektu a výpis počtu uložených položek. V případě úspěšného uložení je návratová hodnota 1.</p>";
    $test = function (){
        $akceItem = new Projektor_Model_Auto_AkceItem(6);
        echo "<p>Stav po vytvoření objektu:";
        echo "<p>Data načtena z db: ".$akceItem->isNacten()." Data změněna od posledního načtení nebo uložení: ".$akceItem->isZmenen()."</p>";
        $uschovanyPopis = $akceItem->dbField°popis;
        echo "<p>Stav po přečtení hodnoty vlastnosti:";
        echo "<p>Data načtena z db: ".$akceItem->isNacten()." Data změněna od posledního načtení nebo uložení: ".$akceItem->isZmenen()."</p>";
        $akceItem->dbField°popis = 'Další nový krásný popis '.time().'!';
        echo "<p>Stav po nastavení hodnoty vlastnosti:";
        echo "<p>Data načtena z db: ".$akceItem->isNacten()." Data změněna od posledního načtení nebo uložení: ".$akceItem->isZmenen()."</p>";        
        echo "<p>1. ".$akceItem->dbField°popis."</p>";
        echo "<p>2. Uloženo položek: ".$akceItem->uloz()."</p>";
        echo "<p>Stav po uložení objektu:";
        echo "<p>Data načtena z db: ".$akceItem->isNacten()." Data změněna od posledního načtení nebo uložení: ".$akceItem->isZmenen()."</p>";
        $akceItem->dbField°popis = $uschovanyPopis;
        echo "<p>Stav po nastavení vlastnosti objektu:";
        echo "<p>Data načtena z db: ".$akceItem->isNacten()." Data změněna od posledního načtení nebo uložení: ".$akceItem->isZmenen()."</p>";
        echo "<p>3. ".$akceItem->dbField°popis."</p>";
        echo "<p>4. Uloženo položek: ".$akceItem->uloz()."</p>";
        echo "<p>Stav po uložení objektu:";
        echo "<p>Data načtena z db: ".$akceItem->isNacten()." Data změněna od posledního načtení nebo uložení: ".$akceItem->isZmenen()."</p>";
    };
    runTestFunction($test);
    
echo("<h2>Vytvoření nového objektu Item, nastavení vlastností, uložení nového objektu, načtení id</h2>");
    echo("<p>Příklad vytvoření nového objektu Item, nastavení vlastností (cizí klíče zadány číslem) 
        a uložení do databáze. Následné zjištění identifikátoru právě uložené položky (po uložení již Item má id). 
        Pro kontrolu následuje znovunačtení právě vytvořené položky:</p>");
    $test = function (){
        // Vytvoreni nove
        echo "<p >Před factory ".interval()."</p>";
        $novaAkce = Projektor_Model_Auto_AkceItem::factory();
        echo "<p >Za factory ".interval()."</p>";
        echo "<p>Výpis nově vytvořeného objektu - hodnoty vlastností jsou default hodnoty sloupců db tabulky:</p>";
        vypisItem($novaAkce);
        echo "<p >Za vypisItem ".interval()."</p>";
        $novaAkce->dbField°nazev_hlavniho_objektu = "Zajemce";
        $novaAkce->dbField°datum_zacatek = "2009-11-01";
        $novaAkce->dbField°datum_konec = "2009-11-02";
        $novaAkce->dbField°nazev = "Staropramen 12&deg; ".time();
        $novaAkce->dbField°popis = "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.";
        $novaAkce->dbField°id_s_stav_akce_FK = 2;
        $novaAkce->dbField°id_s_typ_akce_FK = 6;
        echo "<p >Za nastavením vlastností ".interval()."</p>";
        echo "<p>Výpis objektu po nastavení hodnot vlastností:</p>";
        vypisItem($novaAkce);
        echo "<p >Za vypisIten ".interval()."</p>";
        $c = $novaAkce->uloz();
        echo "<p >Za uloz() ".interval()."</p>";
        echo "<p>Uloženo položek: ".$c."</p>";
        echo "<p>Výpis objektu po uložení - objekt již má vlastnost id:</p>";
        echo "<p>Id uloženého objektu:$novaAkce->id</p>";
        vypisItem($novaAkce);
    };
    runTestFunction($test);

?>