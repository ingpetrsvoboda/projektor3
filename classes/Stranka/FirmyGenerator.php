<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    class FirmyGenerator
    {
        const NAZEV_FLAT_TABLE = "s_firma";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "Firma";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "Firmy";
        
        protected $databaze;
        protected $nazev_flattable;
        protected $nazev_jednotne;
        protected $nazev_mnozne;
        protected $vsechny_radky;

        protected function generujStranku() 
        {
            $stranka = new Stranka_Firmy($cesta, $nazev);
            $stranka->databaze = App_Config::DATABAZE_PROJEKTOR;
            $stranka->nazev_flattable = self::NAZEV_FLAT_TABLE;
            $stranka->nazev_jednotne = self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE;
            $stranka->nazev_mnozne = self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE;
            $stranka->vsechny_radky = FALSE;
        }
    }
?>
