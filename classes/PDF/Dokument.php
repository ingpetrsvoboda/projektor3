<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PDF_Dokument
{
    public $pdf;
    
    public function __construct() 
    {
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
        $this->pdf =  new PDF_VytvorPDF;
    }           

}
?>
