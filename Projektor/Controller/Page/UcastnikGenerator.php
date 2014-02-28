<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    class Projektor_Controller_Page_UcastnikGenerator extends Projektor_Controller_Page_Generator
    {
	const SABLONA = "detail.xhtml";

        protected function generujStranku()
        {
            $stranka = new Projektor_Controller_Page_Ucastnik($this->uzel, self::SABLONA);
            return $stranka;
        }
    }
?>
