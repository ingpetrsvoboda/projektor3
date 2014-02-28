<?php
/**
 * View_PDF třídy musí mít metody save() a getFullFileName(), neboť metoda display() v Projektor2_View_PDF_Base předpokládá užití těchto metod.
 * @author pes2704
 */
interface Projektor2_View_PDF_BaseInterface {
    public function save();
    public function getFullFileName();
}

?>
