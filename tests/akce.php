<?php
require_once("../autoload.php");

echo("<pre>");

// Vytvoreni nove
/*$novaAkce = new Akce("2009-11-01", "Bernard 12&deg; ".time(), "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.", 22);
 print_r($novaAkce);
 echo $novaAkce->uloz();*/

//Uprava
/*$akce = Akce::najdiPodleId(25);
 $akce->nazev = str_replace("Bernard", "Svijany", $akce->nazev);
 $akce->uloz();*/

//Vypis
/*$akcev = Akce::vypisVse();
 echo("<table>
 <tr><th>ID</th><th>Zacatek</th><th>Nazev</th><th>Popis</th><th>IdSTypAkce</th></tr>");
 foreach($akcev as $akcej)
 {
 echo("<tr>");
 foreach($akcej as $polozka)
 echo("<td>{$polozka}</td>");
 echo("</tr>");
 }
 echo("</table>");*/



//Vypis full >;-)
$akcevf = AkceFull::vypisVse();
echo("<table>\n
	<tr><th>Typ Akce</th><th>ID</th><th>Zacatek</th><th>Nazev</th><th>Popis</th><th>TypAkce</th></tr>");
foreach($akcevf as $akcejf)
{
	echo("<tr>");
	foreach($akcejf as $polozkaf)
	if(is_object($polozkaf))
	echo("<td>{$polozkaf->nazev}</td>");
	else
	echo("<td>{$polozkaf}</td>");
	echo("</tr>\n");
}
echo("</table>\n");

//Vlozeni full
/*$novaAkce = new AkceFull("2009-11-01", "Bernard 12&deg; ".time(), "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.", 37);
 print_r($novaAkce);
 echo $novaAkce->uloz();*/

//Uprava
/*$akce = Akce::najdiPodleId(34);
 $akce->nazev = str_replace("Bernard", "Svijany", $akce->nazev);
 $akce->uloz();*/

//Prihlaseni
/*$akce = Akce::najdiPodleId(14);
 $akce->prihlas(UcastnikB::najdiPodleId(29), Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));*/

/*$akce = Akce::najdiPodleId(34);
 $akce->zmenStavUcastnika(UcastnikB::najdiPodleId(12), Data_Seznam_SStavUcastnikAkce::najdiPodleId(4));*/

//Nalezeni vsechn ucastniku dane akce
/*$akce = Akce::najdiPodleId(34);
 print_r($akce->vsichniUcastnici());*/

//Nalezeni vsech akci ucastnika
/*$akcev = Akce::vsechnyUcastnika(UcastnikB::najdiPodleId(12));
 print_r($akcev);*/


//Zmena stavu
/*$akce = Akce::najdiPodleId(14);
 $ucastnik = UcastnikB::najdiPodleId(29);
 print_r($akce->stavUcastnika($ucastnik));
 $akce->zmenStavUcastnika($ucastnik, Data_Seznam_SStavUcastnikAkce::najdiPodleId(3), Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(3));*/


//Generovani AkceDnu
/*$novaAkce = new Akce("2009-11-07", "Bernard 12&deg; ", "Skoleni o vyrobe a konzumaci kvasnicoveho piva, vc. praktickeho cviceni.", 2, 22);
 print_r($novaAkce);
 echo $novaAkce->uloz();*/
/*$novaAkce = Akce::najdiPodleId(14);
 print_r($novaAkce);
 $novaAkce->vytvorDny(Data_Seznam_SUcebna::najdiPodleId(1), Data_Seznam_SPersonal::najdiPodleId(1));*/

//vypis vsech dnu akce
/*$akce = Akce::najdiPodleId(14);
 print_r($akce->vsechnyDny());*/

/*$akce = Akce::najdiPodleId(14);
 $akce->zmenStav(Data_Seznam_SStavAkce::najdiPodleId(3), Data_Seznam_SStavUcastnikAkce::najdiPodleId(3), Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(3));*/



