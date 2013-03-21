<?php

define ("CLASS_PATH", "../");
require_once(CLASS_PATH."ProjektorAutoload.php");
echo ('
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Projektor | test akce |</title>
        <link rel="icon" type="image/gif" href="favicon.gif"></link>
        <link rel="stylesheet" type="text/css" href="css/default.css" />
        <link rel="stylesheet" type="text/css" href="css/highlight.css" />
    </head>

    <body>
        ');
echo("<pre>");

    $databaze = Projektor_App_Config::DATABAZE_PROJEKTOR;

    $tabulka = "akce";
    $objektStruktura = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka);
    vypisTabulku($objektStruktura);

    $tabulka = "c_projekt";
    $objektStruktura = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka);
    vypisTabulku($objektStruktura);
    
    $tabulka = "za_flat_table";
    $objektStruktura = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka);
    vypisTabulku($objektStruktura);


echo ('</body></html>');


function vypisTabulku(Projektor_Data_Auto_Cache_StrukturaTabulky $objektStruktura)
    {
        echo ("<h2>Tabulka: ".$objektStruktura->tabulka."</h2>");
        echo("<table border=1px>\n");
        foreach($objektStruktura as $key =>$var)
        {
            echo("<tr>");
            echo ("<td>{$key}</td>");
            if (is_array($var) OR is_object($var))
            {
                echo("<td>");
                echo("<table border=2px>\n");
                foreach($var as $key2=>$var2)
                {
                    echo("<tr>");
                    echo ("<td>{$key2}</td>");
                    if (is_array($var2) OR is_object($var2))
                    {
                        foreach($var2 as $polozka)  echo("<td>{$polozka}</td>");
                    } else {
                        echo("<td>{$var2}</td>");
                    }
                    echo("</tr>\n");
                }
                echo("</table>\n");
            } else {
                echo("<td>{$var}</td>");
            }
            echo("</tr>\n");


        }

        echo("</table>\n");
    }

    /**
     * Metoda vrací pole názvů sloupců db tabulky
     * @return array() Pole názvů sloupců db tabulky
     */
    function dejNazvy() {
    return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka)->nazvy;
    }

    /**
     * Metoda vrací název primárního klíče tabulky
     * @return string Název primárního klíče tabulky
     */
    function dejPrimarniKlic() {
    return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka)->primaryKeyFieldName;
    }

    /**
     * Metoda vrací pole hodnot obsahující TRUE pokud sloupec db tabulky je primární klíč, jinak FALSE
     * @return array() Pole hodnot příznaku KEY sloupců db tabulky
     */
    function dejKlice() {
    return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka)->pk;
    }

    /**
     * Metoda vrací pole hodnot obsahující datové typy sloupců db tabulky. Vrací pouze řetězec odpovídající názvu typu.
     * @return array() Pole hodnot typů sloupců db tabulky
     */
    function dejTypy() {
    return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka)->typy;
    }

    /**
     * Metoda vrací pole hodnot obsahující délky datových typů sloupců db tabulky.
     * Vrací pouze celé číslo odpovídající celkové délce.
     * @return array() Pole celočíselných délek sloupců db tabulky
     */
    function dejDelky() {
    return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka)->delky;
    }
?>
