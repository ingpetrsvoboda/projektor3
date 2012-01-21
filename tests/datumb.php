<?php
require_once("..\autoload.php");

// Specified date/time in your computer's time zone.
$date = Data_Konverze_Datum::DateTime();
print_r( $date);
echo "<br>########################################<br>";
$d = $date->format('Y-m-d H:i:sP') . "<br>";
echo $d;

$dsql = $date->format('Y-m-d');

        $dForm = Data_Konverze_Datum::zSQL($dsql)->dejDatumProQuickForm();
        echo "<br>Datum pro formular QuickForm:<br>";
        print_r($dForm);
        $dSql = Data_Konverze_Datum::zQuickForm($dForm)->dejDatumproSQL();
        echo "<br>Datum pro MySQL databazi:<br>";
        print_r($dSql);
        $dt = Data_Konverze_Datum::zRetezce("1.2.1934");
        echo "<br>Datum z retezce:<br>";
        print_r($dt);
        $dt = Data_Konverze_Datum::zRetezce("jhgjgjg");
        echo "<br>Datum z retezce:<br>";
        print_r($dt);
?>
