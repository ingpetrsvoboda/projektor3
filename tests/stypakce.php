<?php

require_once("../autoload.php");

//echo("<pre>");

// Nalezeni podle id
$typAkce = Data_Seznam_STypAkce::najdiPodleId(26);
print_r($typAkce);

// Vytvoreni nove
/*$novyTypAkce = new Data_Seznam_STypAkce("Kurz svareni ".time(), 9, 6, 1, 5);
 print_r($novyTypAkce);
 echo $novyTypAkce->uloz();*/

// task: vypustit id z konstruktoru

// Uprava stavajici
/*$novyTypAkce = new Data_Seznam_STypAkce("Kurz svareni ".time(), 9, 6, 1, 5, 26);
 print_r($novyTypAkce);
 echo $novyTypAkce->uloz();*/

//Vypsani bez filtru
/*$typy = Data_Seznam_STypAkce::vypisVse();
 print_r($typy);*/

//Vypsani vcetne filtru
/*$typy = Data_Seznam_STypAkce::vypisVse(Data_Seznam_STypAkce::TRVANI_DNI."=14");
 print_r($typy);*/

//Vypsani normalne bez pouziti print_r tj. jak se to bude delat v kodu
$typyEcho = Data_Seznam_STypAkce::vypisVse();
echo("<table>
	<tr><th>ID</th><th>Nazev</th><th>Trvani dni</th><th>Hodiny/den</th><th>Min. ucastniku</th><th>Max. ucastniku</th></tr>");
foreach($typyEcho as $typ)
{
	echo("<tr>");
	foreach($typ as $polozka)
	echo("<td>{$polozka}</td>");
	echo("</tr>");
}
echo("</table>");

//Mazani
//Data_Seznam_STypAkce::smaz(Data_Seznam_STypAkce::najdiPodleId(36));
?>