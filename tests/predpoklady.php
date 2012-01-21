<?php

require_once("../autoload.php");

echo("<pre>");

print_r($ucastnik = UcastnikB::najdiPodleId(10));
print_r($akce = Akce::najdiPodleId(5));

//print_r(Data_Seznam_SAkcePredpoklad::vypisVse());

//print_r(Data_Seznam_SAkcePredpoklad::vypisPro(Data_Seznam_STypAkce::najdiPodleId($akce->idSTypAkceFK)));

print_r(Data_Seznam_SAkcePredpoklad::nesplnene($ucastnik, $akce->dejSTypAkce()));
