<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    class Projektor_Controller_Page_CRMFirmyGenerator extends Projektor_Controller_Page_Generator
    {
	const SABLONA = "seznam.xhtml";

        protected function generujStranku()
        {
            $stranka = new Projektor_Controller_Page_CRMFirmy($this->uzel, self::SABLONA);
            return $stranka;
        }
    }
?>
