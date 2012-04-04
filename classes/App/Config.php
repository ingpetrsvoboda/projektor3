<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class App_Config
{
//    const EXPORT_PDF_DIRECTORY = 'C:/_Export Projektor/PDF/';
    const XML_INI = "XMLini.xml";
    
    const NAZEV_SEKCE_EXPORT_V_XML = 'export';
    
    const NAZEV_SEKCE_DB_V_XML = 'db';
    const NAZEV_ATRIBUTU_DATABAZE = 'databaze';
    const DATABAZE_INFORMATION_SCHEMA = 'InformationSchema';
    const DATABAZE_PROJEKTOR = 'Projektor';
    const DATABAZE_PERSONAL_SERVICE = 'PersonalService';
    const DATABAZE_CRM = 'test_projektor';

    const DB_TYPE_MYSQL = "MySQL";
    const DB_TYPE_MSSQL = "MSSQL";        
    
    private static $ini;
    /**
     * pokud je v xml jen jedna sekce s daným jménem, vrací jeden SimpleXMLObject, 
     * pokud je v xml více sekcí s daným názvem, vrací pole objektů SimpleXMLObject
     * $name string jméno sekce
     */
    public static function najdiSekciPodleJmena($jmenoSekce) {          
        if(!self::$ini) self::setIni ();
        if (property_exists(self::$ini, $jmenoSekce)){
            return self::prevedSimpleXMLObjectNaObjekt (self::$ini->$jmenoSekce);              
        } else {
            return FALSE;
        }
    }    
    
    public static function najdiPolozkuPodleAtributu($jmenoSekce, $atributNazev="", $atributHodnota="") {
        if(!self::$ini) self::setIni ();
        
        if (property_exists(self::$ini, $jmenoSekce)){
            foreach (self::$ini->$jmenoSekce as $jmeno => $dataSekce) {
                foreach($dataSekce->attributes() as $jmenoAtributu => $hodnotaAtributu) {
                    $h = (string)$hodnotaAtributu;
                    if ($jmenoAtributu==$atributNazev AND $h==$atributHodnota) return self::prevedSimpleXMLObjectNaObjekt ($dataSekce);
                }
            }
        }        
        return FALSE;
    }

    protected static function prevedSimpleXMLObjectNaObjekt($simpleXMLObject)
{
    if (is_object($simpleXMLObject)) {
        $simpleXMLObject = get_object_vars($simpleXMLObject);
    }
   
    if (is_array($simpleXMLObject)) {
        foreach ($simpleXMLObject as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = self::prevedSimpleXMLObjectNaObjekt($value); // rekurze
            }
            $objekt->$index = $value;
        }
    }
    return $objekt;
}
    
    
    private static function setIni() {
        $xmlFile = self::XML_INI;
        $xmlStr = file_get_contents($xmlFile);
        //TODO: try..catch  exception -  chybný soubor self::XML_INI
        //$xmlObj = simplexml_load_string($xmlStr); použití této metody způsobuje chybu při použití xdebug - error socket
        self::$ini = new SimpleXMLElement($xmlStr); 
        return;
    }
       
}        
?>
