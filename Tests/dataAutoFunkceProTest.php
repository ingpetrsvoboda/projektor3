<?php

//==== privátní funkce ====================================================
    function interval()
    {
        static $lasttime;
        if ($lasttime)
        {
            $t = microtime_float()-$lasttime;
        } else {
            $t = 0;
        }
        $lasttime = microtime_float();
        return $t;
    }
    function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

    function vypisTabulku($collection) {
    echo("<table border=1>\n
            <tr>");
    if ($collection)
    {
        $i = $collection->getIterator();
        $i->rewind();
        if ($i->valid()) $refCollections = vypisHlavicku($i->current());

        echo("</tr>");
        foreach ($collection as $item) {
            echo("<tr>");
            foreach ($item as $polozka)
                if (is_object($polozka))
                    echo("<td>{$polozka->nazev}</td>");
                else
                    echo("<td>{$polozka}</td>");
            echo("</tr>\n");
        }
    } else {
        $refCollections = vypisHlavickuProPrazdnyItem($collection);
    }
    echo("</table>\n");
    if ($refCollections)
    {
        foreach ($refCollections as $refCollection)
        {
            echo "<div style='color:blue'>";
            echo "<p>Kolekce pro cizí klíč:</p>";
            vypisTabulku($refCollection);
            echo "</div>";
        }
    }
}

function vypisHlavickuProPrazdnyItem($collection)
{
    $itemClassName = $collection::NAZEV_TRIDY_ITEM;
    $nazvy = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->nazvy;
    $refCollections = array();
    foreach ($nazvy as $nazev)
    {
        echo ("<th>{$nazev}</th>");
        $refCollection = $item->dejReferencovanouKolekci($nazev);
        if ($refCollection) $refCollections[] = $refCollection;
    }
    if (count($refCollections)) return $refCollections;
    return FALSE;
}

function vypisHlavicku(Projektor_Data_Item $item)
{
    $refCollections = array();
    foreach ($item as $name=>$v) {
        $nazevSloupceDb = $item->dejStrukturuSloupce($name)->nazev;
        $refCollection = $item->dejReferencovanouKolekci($name);
        if ($nazevSloupceDb)
        {
            echo ("<th>{$nazevSloupceDb}</th>");
            if ($refCollection) $refCollections[] = $refCollection;
        }
    }
    if (count($refCollections)) return $refCollections;
    return FALSE;
}
function vypisPolozku(Projektor_Data_Item $item) {
    echo("<table border=1>\n
            <tr>");
    $refCollections = vypisHlavicku($item);
    echo("</tr>");
        echo("<tr>");
        foreach ($item as $polozka)
            if (is_object($polozka))
                echo("<td>{$polozka->nazev}</td>");
            else
                echo("<td>{$polozka}</td>");
        echo("</tr>\n");

        echo("</table>\n");
        if ($refCollections)
        {
            foreach ($refCollections as $refCollection)
            {
                echo "<div style='color:blue'>";
                echo "<p>Kolekce pro cizí klíč:</p>";
                vypisTabulku($refCollection);
                echo "</div>";
            }
        }
    }
//Vlozeni full
    /* $novaAkce = new AkceFull("2009-11-01", "Bernard 12&deg; ".time(), "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.", 37);
      print_r($novaAkce);
      echo $novaAkce->uloz(); */

//Uprava
    /* $akce = Akce::najdiPodleId(34);
      $akce->nazev = str_replace("Bernard", "Svijany", $akce->nazev);
      $akce->uloz(); */

//Prihlaseni
    /* $akce = Akce::najdiPodleId(14);
      $akce->prihlas(UcastnikB::najdiPodleId(29), Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Projektor_Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2)); */

    /* $akce = Akce::najdiPodleId(34);
      $akce->zmenStavUcastnika(UcastnikB::najdiPodleId(12), Projektor_Data_Seznam_SStavUcastnikAkce::najdiPodleId(4)); */

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


