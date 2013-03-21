<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Projektor_Pdf_Dokument
{
    public $pdf;
    
    public function __construct() 
    {
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
        $this->pdf =  new Projektor_Pdf_VytvorPDF;
    }           

}
?>
