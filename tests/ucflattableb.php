<?php
require_once("../autoload.php");

echo("<pre>");

//*
$uc = Data_UcFlatTableB::najdiPodleId(10); //pozor - neexistují všechna id
print_r($uc);
/**/

//*
echo("</pre>");
echo "###########################################################";
echo("<pre>");
$ucastnici = Data_UcFlatTableB::vypisVse(Data_UcFlatTableB::ID." < 5");
print_r($ucastnici);
/**/

