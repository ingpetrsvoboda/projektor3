<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PDF_Dokument extends PDF_VytvorPDF
{
    public $pdf;
    public $identifikator;
    
    public function __construct($objekt) 
    {
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
        if (!$objekt->identifikator)  throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Není zadán parametr objekt nebo objekt nemá vlastnost identifikator  ".$objekt->identifikator);
        parent::__construct();

        $this->identifikator = $objekt->identifikator;
    }           

}
?>
