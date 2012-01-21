<?php
require_once("../autoload.php");
echo("<pre>");

echo Data_Seznam_SPrechodUcastnikAkce::jeMozny(Data_Seznam_SStavUcastnikAkce::najdiPodleId(3), Data_Seznam_SStavUcastnikAkce::najdiPodleId(4));