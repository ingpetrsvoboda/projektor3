<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Projektor_Data_Auto_Autocode_Logger
 * Třída lloguje tak, že zapisuje do souboru. Pro každý soubor vytváří jednu istanci objektu Projektor_Data_Auto_Autocode_Logger, je to singleton
 * pro jeden logovací soubor.
 *
 * @author pes2704
 */
class Projektor_Data_Auto_Autocode_Logger {

    private static $instances = array();

    private $fullLogFileName;
    private $logFileHandle;


    /**
     * Default soubor pro zápis logu
     */
    const LOG_SOUBOR = "Autocode.log";
    const ODSAZENI = "    ";

    private function __construct($fullLogFileName){
        $this->fullLogFileName = $fullLogFileName;
    }

    final public function __clone(){}

    final public function __wakeup(){}

    /**
     * Factory metoda, metoda vrací instanci objektu třídy Projektor_Data_Auto_Autocode_Logger. Objekt Projektor_Data_Auto_Autocode_Logger je vytvářen
     * jako singleton pro logovací soubor. Metoda vrací jeden unikátní objekt pro jednu kombinaci parametrů $pathPrefix a $logFileName
     * (jeden singleton pro jeden logovací soubor).
     * @param string $directoryPath Pokud parametr není zadán, třída loguje do adresáře, ve kterém je soubor s definicí třídy Projektor_Data_Auto_Autocode_Logger
     * @param string $logFileName Název logovacího souboru (řetězec ve formátu jméno.přípona např. Mujlogsoubor.log). Pokud parametr není zadán,
     *  třída loguje do souboru se jménem v konstantě třídy LOG_SOUBOR.
     * @return Projektor_Data_Auto_Autocode_Logger
     */
    public static function getInstance($directoryPath=NULL, $logFileName=NULL) {
        if (!$directoryPath) $directoryPath = __DIR__."\\"; //
        if (!$logFileName) $logFileName = self::LOG_SOUBOR;
        $fullLogFileName = str_replace(" ", "", $directoryPath.$logFileName);
        if(!self::$instances[$fullLogFileName]){
            self::$instances[$fullLogFileName] = new self($fullLogFileName);
        }
        return self::$instances[$fullLogFileName];
    }

    /**
     * Metoda zapíše do logovacího souboru obsah zadaného parametru (string), pokud parametr $bezOdradkovani není zadán nebo je FALSE, metoda za něj přidá znak konce řádku (\n).
     * Pokud logovací soubor dosud nebyl otevřen (první volání metody v běhu skriptu), otevře soubor v režim 'w', tedy pokud soubor již existival smaže jeho starý
     * obsah a otevře soubor pro zápis. První volání metody v jednom běhu skriptu tedy zahájí nové logování do prázdného souboru.
     * @param type $string
     * @param type $bezOdradkovani Pokud parametr není zadán nebo je FALSE, metoda za zapsaný obsah přidá znak konce řádku (\n).
     */
    public function loguj($string, $bezOdradkovani=FALSE)
    {
        if (!isset($this->logFileHandle)) $this->logFileHandle = fopen($this->fullLogFileName, 'w');
        if (!$bezOdradkovani) $string .="\n";
        fwrite($this->logFileHandle, $string);
    }

    /**
     * Metoda zavře logovací soubor, následně načte jeho obsah a ten vrací. Volání metody getLog tedy zároveň ukončí zapisování do jednoho logovacího souboru.
     * Případné následné volání metody loguj() zahají nové logování.
     * @return string
     */
    public function getLog() {
        fclose($this->logFileHandle);  //metoda loguj nezavírá a nemám destruktor - tak aspoň takhle
        unset($this->logFileHandle);
        return file_get_contents($this->fullLogFileName);
    }
}
?>
