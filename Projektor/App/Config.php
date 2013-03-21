<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Projektor_App_Config
{
//    const EXPORT_PDF_DIRECTORY = 'C:/_Export Projektor/PDF/';

// konstanty pro konfigurační soubor xml
    /**
     * název souboru s konfiguračními údaji aplikace
     */
    const XML_INI = "Projektor/ProjektorConfig.xml";

    /**
     * název sekce s konfigurací expotů - název xml elementu druhé úrovně s konfigurací exportů
     */
    const SEKCE_EXPORT = 'export';

    /**
     * název sekce s konfigurací directories - název xml elementu druhé úrovně s konfigurací složek (adresářů)
     */
    const SEKCE_DIRECTORIES = 'directories';

    /**
     * název sekce s konfigurací autorizace - název xml elementu druhé úrovně s konfigurací autorizačních parametrů
     */
    const SEKCE_AUTH = 'auth';

    /**
     * název sekce s konfigurací autocode - název xml elementu druhé úrovně s konfigurací autokódování
     */
    const SEKCE_AUTOCODE = 'autocode';

    /**
     * název sekce s konfigurací databází - název xml elementu druhé úrovně s konfigurací databází
     */
    const SEKCE_DB = 'db';

    /**
     * název atributu elementu sekce
     */
    const ATRIBUT_SEKCE_DATABAZE = 'databaze';

    /**
     * hodnota atributu elementu sekce s konfigurací databáze InformationSchema
     */
    const DATABAZE_INFORMATION_SCHEMA = 'InformationSchema';
    /**
     * hodnota atributu elementu sekce s konfigurací databáze Projektor
     */
    const DATABAZE_PROJEKTOR = 'Projektor';
    /**
     * hodnota atributu elementu sekce s konfigurací databáze PersonalService
     */
    const DATABAZE_PERSONAL_SERVICE = 'PersonalService';
    /**
     * hodnota atributu elementu sekce s konfigurací databáze test_projektor
     */
    const DATABAZE_CRM = 'test_projektor';

// ostatní konstanty třídy
    /**
     * konstanta označují typ databáze MySQL
     */
    const DB_TYPE_MYSQL = "MySQL";
    /**
     * konstanta označují typ databáze MSSQL
     */
    const DB_TYPE_MSSQL = "MSSQL";

    private static $ini;

    /**
     * Metoda vrací objekt s konfiguračními informacemi obsaženými v sekci xml konfiguračního souboru
     * pokud je v xml jen jedna sekce s daným jménem, vrací jeden SimpleXMLObject,
     * pokud je v xml více sekcí s daným názvem, vrací pole objektů SimpleXMLObject
     * @param type $jmenoSekce Název sekce
     * @return boolean
     */
    public static function najdiSekciPodleJmena($jmenoSekce) {
        if(!self::$ini) self::setIni ();
        if (property_exists(self::$ini, $jmenoSekce)){
            return self::prevedSimpleXMLObjectNaObjekt (self::$ini->$jmenoSekce);
        } else {
            return FALSE;
        }
    }

    /**
     * Metoda vrací objekt s konfiguračními informacemi obsaženými v jené sekci xml konfiguračního souboru
     * @param type $jmenoSekce
     * @param type $atributNazev
     * @param type $atributHodnota
     * @return boolean
     */
    public static function najdiPolozkuPodleAtributu($jmenoSekce, $atributNazev="", $atributHodnota="")
    {
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
                if (!isset($objekt)) $objekt = new stdClass ();
                $objekt->$index = $value;
            }
        }
        return $objekt;
    }

    /**
     * Metoda načte konfiguraci z xml souboru do objektu typu SimpleXMLElement a uloží ve statické proměnné třídy
     * @return type
     */
    private static function setIni()
    {
        $xmlFile = self::XML_INI;
        $xmlStr = file_get_contents(CLASS_PATH.$xmlFile);
        //TODO: try..catch  exception -  chybný soubor self::XML_INI
        //$xmlObj = simplexml_load_string($xmlStr); použití této metody způsobuje chybu při použití xdebug - error socket
        self::$ini = new SimpleXMLElement($xmlStr);
        return;
    }

}
?>
