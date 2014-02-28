<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Projektor_Model_Auto_Autocode_Logger
 * Třída loguje tak, že zapisuje do souboru. Pro každý soubor vytváří jednu istanci objektu Projektor_Model_Auto_Autocode_Logger, je to singleton
 * pro jeden logovací soubor.
 *
 * @author pes2704
 */
class Projektor_Model_Auto_Autocode_Logger {

    private static $instances = array();

//    private $fullLogFileName;
    private $logFileHandle;


    /**
     * Default soubor pro zápis logu
     */
    const LOG_SOUBOR = "Autocode.log";
    const ODSAZENI = "    ";

    /**
     * Privátní konstruktor. Objekt je vytvářen voláním factory metody getInstance().
     * @param Resource $logFileHandle
     */
    private function __construct($logFileHandle){
        if (!is_resource($logFileHandle)) {
            throw new \InvalidArgumentException('Cannot create '.__CLASS__.'. Invalid resource handle: '.print_r($logFileHandle, TRUE));
        }
        $this->logFileHandle = $logFileHandle;
    }

    final public function __clone(){}

    final public function __wakeup(){}

    /**
     * Factory metoda, metoda vrací instanci objektu třídy Projektor_Model_Auto_Autocode_Logger. 
     * Objekt Projektor_Model_Auto_Autocode_Logger je vytvářen jako singleton vždy pro jeden logovací soubor. Metoda vrací jeden unikátní 
     * objekt pro jednu kombinaci parametrů $pathPrefix a $logFileName.
     * @param string $logDirectoryPath Pokud parametr není zadán, třída loguje do složky, ve které je soubor s definicí třídy.
     * @param string $logFileName Název logovacího souboru (řetězec ve formátu jméno.přípona např. Mujlogsoubor.log). Pokud parametr není zadán,
     *  třída loguje do souboru se jménem v konstantě třídy LOG_SOUBOR.
     * @return Projektor_Model_Auto_Autocode_Logger
     */
    public static function getInstance($logDirectoryPath=NULL, $logFileName=NULL) {
        if (!$logDirectoryPath) {
            $logDirectoryPath = __DIR__."\\Log\\"; //složka Log jako podsložka aktuálního adresáře
        }
        $logDirectoryPath = str_replace('/', '\\', $logDirectoryPath);  //obrácená lomítka
        if (substr($logDirectoryPath, -1)!=='\\') {  //pokud path nekončí znakem obrácené lomítko, přidá ho
            $logDirectoryPath .='\\';
        }
        if (!is_dir($logDirectoryPath)) {  //pokud není složka, vytvoří ji
            mkdir($logDirectoryPath);
        }
        if (!$logFileName) {
            $logFileName = self::LOG_SOUBOR;
        }
        $fullLogFileName = $logDirectoryPath.$logFileName;
        $handle = fopen($fullLogFileName, 'w+'); //vymaže obsah starého logu
        if(!self::$instances[$fullLogFileName]){
            self::$instances[$fullLogFileName] = new self($handle);
        }
        return self::$instances[$fullLogFileName];
    }

    /**
     * Metoda zapíše do logovacího souboru obsah zadaného parametru (string), pokud parametr $bezOdradkovani není zadán nebo je FALSE, 
     * metoda za zapsaný string přidá znak konce řádku (PHP_EOL).
     * Pokud logovací soubor dosud nebyl otevřen (první volání metody v běhu skriptu), otevře soubor v režimu 'w', 
     * pokud soubor již existoval smaže jeho starý obsah a otevře soubor pro zápis. 
     * První volání metody v jednom běhu skriptu tedy zahájí nové logování do prázdného souboru.
     * @param type $string
     * @param type $bezOdradkovani Hodnota TRUE zakazuje odřádkování mezi za zapsaným záznamem do logu. 
     * Pokud parametr není zadán nebo je FALSE, metoda za zapsaný obsah přidává znak konce řádku (PHP_EOL).
     */
    public function loguj($string, $bezOdradkovani=FALSE)
    {
        if (!$bezOdradkovani) $string .=PHP_EOL;
        fwrite($this->logFileHandle, $string);
    }

    /**
     * Metoda zavře logovací soubor, následně načte jeho obsah a ten vrací. Volání metody getLog tedy zároveň ukončí zapisování do jednoho logovacího souboru.
     * Případné následné volání metody loguj() zahají nové logování.
     * @return string
     */
    public function getLog() {
        $position = ftell($this->logFileHandle);
        $r = rewind($this->logFileHandle);
        $content = fread($this->logFileHandle, $position);
        return $content;
    }

    public function __destruct() {
        if ($this->logFileHandle) fclose($this->logFileHandle);
    }    
}
?>
