<?php
require_once("../autoload.php");
echo("<pre>");

// Vytvoreni nove
/*$novyAkceDen = new AkceDen("2009-11-12", 5, 1, 1);
 print_r($novyAkceDen);
 echo $novyAkceDen->uloz();*/

//Uprava
/*$akceden = AkceDen::najdiPodleId(6);
 $akceden->datum = "2009-11-10";
 $akceden->uloz();*/

//Prihlaseni
/*$akceDen = AkceDen::najdiPodleId(4);
 $ucastnik = UcastnikB::najdiPodleId(43);
 $stav = Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(7);
 $akceDen->prihlas($ucastnik, $stav);*/

/*$akceDen = AkceDen::najdiPodleId(4);
 $ucastnik = UcastnikB::najdiPodleId(22);
 print_r($akceDen->stavUcastnika($ucastnik));
 $akceDen->zmenStavUcastnika($ucastnik, Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(3));*/

//Vypis vsechn ucastniku AkceDne
/*$den = AkceDen::najdiPodleId(4);
 print_r($den->vsichniUcastnici());*/

//Vypis vsech AkceDnu ucastnika
print_r(AkceDen::vsechnyUcastnika(UcastnikB::najdiPodleId(22)));
