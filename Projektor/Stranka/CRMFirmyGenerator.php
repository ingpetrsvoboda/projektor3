<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    class Projektor_Stranka_CRMFirmyGenerator extends Projektor_Stranka_Generator
    {
	const SABLONA = "seznam.xhtml";

        protected function generujStranku()
        {
            $stranka = new Projektor_Stranka_CRMFirmy($this->uzel, self::SABLONA);
            return $stranka;
        }
    }
?>
