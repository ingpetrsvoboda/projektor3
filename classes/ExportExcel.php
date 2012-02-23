<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ExportExcel
{
    
    public $objPHPExcel;
    public $tabulka;
    public $databaze;
    
    const EXPORT_PATH = "C:/_Export Projektor/";

    const SQL_FORMAT = "Y-m-d";
    const BUNKA_NADPIS = "A1";
    const LEVY_HORNI_ROH_TABULKY_RADEK = 3; //řádek s titulky - číslováno os nuly
    const LEVY_HORNI_ROH_TABULKY_SLOUPEC = 0; //číslováno os nuly


    public function __construct($tabulka, $databaze=NULL) {
        $this->tabulka = $tabulka;
        $this->databaze = $databaze;

        $locale = 'cs_CZ';
        $validLocale = PHPExcel_Settings::setLocale($locale);
        if (!$validLocale) {
                echo 'Nepodařilo se nastavit lokalizaci '.$locale." - zůstává nastavena výchozí en_us<br />\n";
        }
        $dbh = App_Kontext::getDbh($databaze);
        switch($dbh->dbType){
        case 'MySQL':
            $dbhi = App_Kontext::getDbMySQLInformationSchema;
            $query = Helper_SqlQuery::getShowColumnsQueryMySQL();            
            break;
        case 'MSSQL':
            $dbhi = App_Kontext::getDbh($dbh->dbName);
            $query = Helper_SqlQuery::getShowColumnsQueryMSSQL();
            break;
        default: throw new Exception('*** Chyba v '.__METHOD__.':<BR>'."Typ databáze ".$dbh->dbType." neexistuje.");
        }
        $res= $dbhi->prepare($query)->execute($dbh->dbName, $this->tabulka);

        $this->objPHPExcel = new PHPExcel();
        $objWorksheet = $this->objPHPExcel->getActiveSheet();
        PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

        $this->objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nadpis');
        $cisloSloupce = self::LEVY_HORNI_ROH_TABULKY_SLOUPEC;
        $cisloRadku = self::LEVY_HORNI_ROH_TABULKY_RADEK;
        //titulky sloupců
        while ($data = $res->fetch_assoc()){
            $var_typelengh[$cisloSloupce] = $data['Delka'];
            $objWorksheet->getCellByColumnAndRow($cisloSloupce, $cisloRadku)->setValue($data['Nazev']);
            $cisloSloupce++;    
        }
        //data
        $cisloSloupce = self::LEVY_HORNI_ROH_TABULKY_SLOUPEC;
        $cisloRadku = self::LEVY_HORNI_ROH_TABULKY_RADEK + 1;
        $query = "SELECT * FROM ~1";                                 
        $data = $dbh->prepare($query)->execute($this->tabulka);
        while ($zaznam = $data->fetch_assoc()) {
            foreach ($zaznam as $value) {
                if ($var_typelengh[$cisloSloupce][0]=="date") {
                    $datum = PHPExcel_Shared_Date::PHPToExcel(DateTime::createFromFormat(self::SQL_FORMAT, $value));
                    $objWorksheet->getCellByColumnAndRow($cisloSloupce, $cisloRadku)->setValue($datum);                
                    $objWorksheet->getStyleByColumnAndRow($cisloSloupce, $cisloRadku)->getNumberFormat()->setFormatCode("D.M.YYYY");
                } else {
                    $objWorksheet->getCellByColumnAndRow($cisloSloupce, $cisloRadku)->setValue($value);
                }  
                $cisloSloupce++;                     
            }
            $cisloSloupce = 0;
            $cisloRadku++;
        }  
        
        $this->objPHPExcel->getProperties()->setCreator("Projektor ExportExcel");
        $this->objPHPExcel->getProperties()->setTitle("Projektor export - tabulka ".$tabulka);
        //$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
    }
    
    public function export($soubor = NULL, $pridejCasUlozeniKNazvuSouboru = FALSE) {
        if (!$soubor)
        {
            $soubor = self::EXPORT_PATH . $this->tabulka . ".xlsx";
        }
            if ($pridejCasUlozeniKNazvuSouboru) {
            $s = split("[.]", $soubor);
            $soubor = $s[0] . "_" . date("Ymd_Hi") . "." . $s[1];
        } else {
            $soubor = $soubor;            
        }
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, "Excel2007");

        try {
        $objWriter->save($soubor); 
        } catch (Exception $e){
            echo ("<hr>Do souboru ".$soubor." pro export seznamu nelze zapsat. <br>") ;
            echo ("Pravděpodobně složka neexistuje nebo je soubor otevřen v nějakém programu - používán. Export seznamu neproběhl.<hr>") ;
            echo $e->getMessage();
            return FALSE;
        }
        echo ("<hr>Data byla uložena do souboru ".$this->soubor." <br>");
        return $soubor;
    }

}
?>
