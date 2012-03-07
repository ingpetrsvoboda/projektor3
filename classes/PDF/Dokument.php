<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class PDF_Dokument
{
    public function __construct() {
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
    }           

}
?>
