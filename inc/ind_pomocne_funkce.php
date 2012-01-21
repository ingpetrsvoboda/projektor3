<?php
function text_retezec_kurz($vkurz) {
    if ($vkurz) {
    $ret= trim( trim($vkurz['projekt_kod']). "_" .trim($vkurz['kurz_druh']). "_" . trim($vkurz['kurz_cislo']) . "_" .
                   trim($vkurz['beh_cislo']) . "T_" . trim($vkurz['kurz_zkratka']). " | " .
                   trim($vkurz['kurz_nazev'])." | ".trim($vkurz['kurz_termin'])." | " . trim($vkurz['kurz_zkratka']) );
    }
    else $ret="";
    return $ret;
}    

/*
function text_retezec_diag($vkurz) {
    if ($vkurz) {
    $ret=   trim( trim($vkurz['projekt_kod']). "_" .trim($vkurz['kurz_druh']). "_" . trim($vkurz['kurz_cislo']) . "_" .
                   trim($vkurz['beh_cislo']) . "T" . " | " .
                   trim($vkurz['kurz_nazev'])." | ".trim($vkurz['kurz_termin'] ) );
    }
    else $ret="";
    return $ret;
}
*/
?>