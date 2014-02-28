<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author pes2704
 */
abstract class Projektor2_View_PDF_Base implements Projektor2_View_PDF_BaseInterface{
//    abstract function save();
//    abstract function isSaved();
//
//    abstract function getFullFileName();

    public function render() {
        if (!$this->isSaved()) {
            $this->save();
        }
        echo '<script type ="text/javascript">';
//        echo 'PozadovanTisk="' . $filepathprefix . $Zajemce->identifikator . '.pdf"' . ';' ;
        echo 'PozadovanTisk="' . $this->getFullFileName(). '";' ;
        echo 'DruhPdf="smlouvapdf";';  
        echo '</script>';
    }
}

?>
