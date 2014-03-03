<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Slot
 *
 * @author pes2704
 */
class Framework_Helper_Slot {
    /**
     * Metoda vrací řetězec vhodný jako slot do HTML dokumentu. Slot v dokumentu je řetězec, který může být nahrazován
     * například textem vloženého dokumentu. Slot pro HTML dokument je vytvořen zřetězením '<!-- %', parametru převedeného na velká písmena
     * a '% -->'. Vzniklý text slotu má formát html komentáře, proto se text slotu v html stránce v prohlížeči nezobrazuje když nebyl nahrazen.
     * @param string $text Zadaný text pro slot
     * @return string
     */
    public static function getSlotHtmlCode($text) {
        $text = (string) $text;
        return '<!-- %'.strtoupper($text).'% -->';
    }
}
