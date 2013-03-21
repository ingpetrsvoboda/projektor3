<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    class Projektor_Stranka_UcastnikGenerator extends Projektor_Stranka_Generator
    {
	const SABLONA = "detail.xhtml";

        protected function generujStranku()
        {
            $stranka = new Projektor_Stranka_Ucastnik($this->uzel, self::SABLONA);
            return $stranka;
        }
    }
?>
