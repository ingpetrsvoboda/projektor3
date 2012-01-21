<?php
require_once("../autoload.php");
echo ("<pre>");
//uxastnik se zaznamem v databazi
$id_ucastnik = 111;     // Adam První, Testovací kancelář
$Ucastnik = Data_Ucastnik::najdiPodleId($id_ucastnik);
// vrat vlastnot smlouva objektu Ucastnik jako objekt
$sml = $Ucastnik->smlouva;
$prijmeni1 = $sml->prijmeni;
$mesto1 = $sml->mesto;
echo "Vypis vlastnosti objektu Data_Ucastnik ".$Ucastnik->id." (".$Ucastnik->smlouva->jmeno." ".$Ucastnik->smlouva->prijmeni." <br>";
echo "Kod behu ucastnika je: ".$Ucastnik->Beh->kod."<br>";
echo "Kod kancelare ucastnika je: ".$Ucastnik->Kancelar->kod."<br>";
echo "Kod projektu ucastnika je: ".$Ucastnik->Projekt->kod."<br>";
echo "Prijmeni ucastnika je: ".$prijmeni1."<br>";
echo "Mesto ucastnika je: ".$mesto1."<br>";
// vrat vlastnot planFlatTable objektu Ucastnik jako objekt
$planzztptext = $Ucastnik->planFlatTable->zztp_text;
echo "zztp_text ucastnika je: ".$planzztptext."<br>";;
// vrat vlastnot doporucenirkFlatTable objektu Ucastnik jako objekt
$nazevrk1 = $Ucastnik->doporucenirkFlatTable->nazev_RK_1;
echo "nazev_RK_1 ucastnika je: ".$nazevrk1."<br>";;
$duvodukonceni = $Ucastnik->ukoncFlatTable->duvod_ukonceni;
echo "duvod_ukonceni ucastnika je: ".$duvodukonceni."<br>";;
$testpc_01 = $Ucastnik->testpcFlatTable->testpc_01;
echo "testpc_01 ucastnika je: ".$testpc_01."<br>";;
$zamnazev = $Ucastnik->zamFlatTable->zam_nazev;
echo "zam_nazev ucastnika je: ".$zamnazev."<br>";;


// vrat vlastnosti objektu který je vlastností Ucastnika jako pole
$poleFT = $sml->get_values_assoc();
$prijmeni2 = $poleFT['prijmeni'];
$mesto2 = $poleFT['mesto'];
echo "Vypis vlastnosti objektu Data_Ucastnik->smlouva vracenych jako pole metodou get_values_assoc() <br>";
echo "Prijmeni ucastnika id 111 (Adam První) je: ".$prijmeni2."<br>";
echo "Mesto ucastnika id 111 (Adam První) je: ".$mesto2."<br>";

// nový objekt Ucastnik
$uc = new Data_Ucastnik();
// dosud nenaplnena vlastnost
$ucMesto = $uc->smlouva->mesto;
//napln vlastnost
$uc->smlouva->mesto = "Město nového účastníka";
// vrat vlastnost
$ucMesto = $uc->smlouva->mesto;
// nedefinovana vlastnost objektu-vlastnosti
$ucNesmysl = $uc->smlouva->nesmysl;
// nedefinovana vlastnost kořenového objektu - přídá ji metoda __get
$takovaVlastnostNeni = $uc->taneni;

// uložení
$Ucastnik->smlouva->mesto = "Adamovice ".date('H-i-s d.m.Y');  //28 znaků ze 30
echo "Nove nastavene mesto ucastnika ".$Ucastnik->identifikator." pred ulozenim je: ".$Ucastnik->smlouva->mesto."<br>";
$Ucastnik->planFlatTable->zztp_text = 'ZZTP text testovací '.date('H-i-s d.m.Y');
$Ucastnik->uloz()
?>