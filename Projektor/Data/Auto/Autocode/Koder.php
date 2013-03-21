<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Projektor_Data_Auto_Autocode_Koder
 *
 * @author pes2704
 */
class Projektor_Data_Auto_Autocode_Koder {

    /**
     *
     * @var array Statické pole pro uložení jednotlivých sigleton instancí této třídy.
     */
    private static $instances = array();

    protected $logger;
    protected $phpFullFileName;
    protected $finalize;

    protected $constNames = array();
    protected $className;
    protected $parentClassNames = array();

    protected $phpFileHandle;
    protected $phpContent;
    protected $autocodeStartPosition;
    protected $autocodeLength;
    protected $class;
    protected $autocode;

    protected $status = FALSE;

    const ODSAZENI = "    ";

// hodnoty nařítané z konfigurace aplikace ve třídě Projektor_App_Config
    /**
     * Hodnota načítána z konfigurace. Objekt s vlastnostmi:
     * dbfieldprefix - prefix názvu vlastností automaticky generovaných projednotlivé sloupce db tabulky - obvykle "dbField°"
     * objectidname - název identifikátoru datového objektu Item užívaný v aplikaci - obvykle "id"
     * autocodeStart - řetězec označující začátek místa v kódu třídy pro vložení automaticky generovaného kódu - obvykle "###START_AUTOCODE"
     * autocodeEnd - řetězec označující konec místa v kódu třídy pro vložení automaticky generovaného kódu - obvykle "###END_AUTOCODE"
     * @var object
     */
    protected $autocodeConfig = stdClass;

    private function __construct($phpFullFileName, $finalize, $autocodeConfig, $logDirectoryPath) {
        $this->phpFullFileName = $phpFullFileName;
        $this->finalize = $finalize;
        $phpFileName = substr($phpFullFileName, strrpos($phpFullFileName, "\\")+1, strrpos($phpFullFileName, ".")-strrpos($phpFullFileName, "\\")-1);
        $this->logger = Projektor_Data_Auto_Autocode_Logger::getInstance($logDirectoryPath, $phpFileName.".log");
        $this->autocodeConfig = $autocodeConfig;
        if ($this->getContent()){
            if ($this->findAutocodeSlot()) {
                $this->parsePhpContent();
                if ($this->className){
                    $maKonstanty = TRUE;
                    if (is_subclass_of($this->class, "Projektor_Data_Item")) {
                        if (!isset($this->constNames['DATABAZE'])) {
                            $this->logger->loguj(self::ODSAZENI."V souboru ".$this->phpFullFileName." s definicí třídy ".$this->className." není definována povinná konstanta DATABAZE.");
                            $maKonstanty = FALSE;
                        }
                        if (!isset($this->constNames['TABULKA'])) {
                            $this->logger->loguj(self::ODSAZENI."V souboru ".$this->phpFullFileName." s definicí třídy ".$this->className." není definována povinná konstanta TABULKA.");
                            $maKonstanty = FALSE;
                        }
                        if (!isset($this->constNames['NAZEV_ZOBRAZOVANE_VLASTNOSTI'])) {
                            $this->logger->loguj(self::ODSAZENI."V souboru ".$this->phpFullFileName." s definicí třídy ".$this->className." není definována konstanta NAZEV_ZOBRAZOVANE_VLASTNOSTI.");
                            $maKonstanty = FALSE;
                        }
                    }
                    if (is_subclass_of($this->class, "Projektor_Data_Collection")) {
                        if (!isset($this->constNames['NAZEV_TRIDY_ITEM'])) {
                            $this->logger->loguj(self::ODSAZENI."V souboru ".$this->phpFullFileName." s definicí třídy ".$this->className." není definována povinná konstanta NAZEV_TRIDY_ITEM.");
                            $maKonstanty = FALSE;
                        }
                    }

                    if ($maKonstanty) {
                        $this->status = TRUE;
                    }
                } else {
                    $this->logger->loguj(self::ODSAZENI."Nenalezeno klíčové slovo 'class' v kódu.");
                }
            }
        }
    }

    final public function __clone(){}

    final public function __wakeup(){}

    /**
     * Metoda vrací instanci objektu třídy Projektor_Data_Auto_Autocode_Koder. Objekt Projektor_Data_Auto_Autocode_Koder je vytvářen jako singleton pro jeden php soubor.
     * Metoda vrací jeden unikátní objekt (jeden singleton) pro jednu hodnotu parametru $phpFileName, tedy pro jeden unikátní soubor s php kódem.
     * Jednotlivé instatnce se ukládají do statické proměnné třídy $instances.
     * @param string $pathPrefix
     * @param string $logFileName
     * @return Projektor_Data_Auto_Autocode_Koder
     */
    public static function getInstance($phpFullFileName, $finalize, $autocodeConfig, $logDirectoryPath=NULL) {
        $phpFullFileName = str_replace(" ", "", $phpFullFileName);
        if (!$logDirectoryPath){
            $logDirectoryPath = __DIR__."\\";  // adresář, ve kterém je soubor s touto třídou
        }
        if(!self::$instances[$phpFullFileName]){
            self::$instances[$phpFullFileName] = new self($phpFullFileName, $finalize, $autocodeConfig, $logDirectoryPath);
        }
        return self::$instances[$phpFullFileName];
    }

    public function generujAutocode() {
        $this->logger->loguj("Autocode pro {$this->phpFullFileName}");

            if ($this->status) {
                $this->logger->loguj(self::ODSAZENI."kontrola existence a syntaxe souboru {$this->phpFullFileName} pro class: ".  $this->className);
                //pokud je v souboru s definicí classname syntaktická chyba - autoload nenačte soubor a skončí s exception
                //hlásící tuto syntax chybu - pak je nutno číst logy
        //                    is_callable????
                $className = $this->className;
                $this->class = new $className;

                if (is_subclass_of($this->class, "Projektor_Data_Item")) {
                    $this->autocode = $this->dejAutocodeItem();
                }
                if (is_subclass_of($this->class, "Projektor_Data_Collection")) {
                    $this->autocode = $this->dejAutocodeCollection();
                }
                $this->zapisAutocode();
                return "Autocode úspěšně vygenerován a zapsán.\n";
        } else {
                $this->logger->loguj(self::ODSAZENI."Nepodařilo se vygenerovat autocode.");
        }
        return $this->logger->getLog();
    }

    private function getContent() {
        $this->phpContent = file_get_contents($this->phpFullFileName);
        if (!$this->phpContent) {
            $this->logger->loguj(self::ODSAZENI."Soubor .".$this->phpFullFileName." je prázdný.");
            return FALSE;
        }
        return $this->phpContent;
    }

    private function findAutocodeSlot() {
            $this->autocodeStartPosition = strpos($this->phpContent, $this->autocodeConfig->autocodeStart);
            if (!$this->autocodeStartPosition) {
                $this->logger->loguj(self::ODSAZENI."Nenalezen ".$this->autocodeConfig->autocodeStart.".");
            } else {
                $this->autocodeLength = strpos($this->phpContent, $this->autocodeConfig->autocodeEnd, $this->autocodeStartPosition)-$this->autocodeStartPosition;
    //            $size = strlen($phpContent);
                if (!$this->autocodeLength) {
                    $this->logger->loguj(self::ODSAZENI."Nenalezen ".$this->autocodeConfig->autocodeEnd.".");
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
    }

    private function parsePhpContent() {
        $tokens = token_get_all($this->phpContent);
        $bylNalezenKeywordClass = FALSE;
        $bylNalezenKeywordConst = FALSE;
        $bylNalezenNazevKonstanty = FALSE;
        $bylNalezenKeywordExtends = FALSE;

                    foreach ($tokens as $token) {
            if ($token[0] != T_WHITESPACE) {
                // class
                if ($token[0] == T_STRING AND $bylNalezenKeywordClass) {
                    $this->className = $token[1];
                    $bylNalezenKeywordClass = FALSE;
                    $bylNalezenClassName = TRUE;
                }
                if ($token[0] == T_CLASS) $bylNalezenKeywordClass = TRUE;
                // const
                if ($token[0] == T_CONSTANT_ENCAPSED_STRING AND $bylNalezenNazevKonstanty) {
                    $this->constNames[$constName] = $token[1];
                    $bylNalezenNazevKonstanty = FALSE;
                }
                if ($bylNalezenKeywordConst AND $token[0] == T_STRING) {
                    $constName = $token[1];
                    $bylNalezenNazevKonstanty = TRUE;
                    $bylNalezenKeywordConst = FALSE;
                }
                if ($token[0] == T_CONST AND $token[1] == 'const') $bylNalezenKeywordConst = TRUE;
                // extedns
                if ($token[0] == T_STRING AND $bylNalezenKeywordExtends) {
                    $this->parentClassNames[] = $token[1];
                    $bylNalezenKeywordExtends = FALSE;
                }
                if ($token[0] == T_EXTENDS) $bylNalezenKeywordExtends = TRUE;
            }
            if (isset($this->parentClassNames)) {
                
            }
        }
        return;
    }

    private function zapisAutocode()
    {
        $this->logger->loguj("Vygenerován nový autocode.");
        $this->logger->loguj("*** Původní php kód:");
        $this->logger->loguj($this->phpContent, TRUE);
        $phpFileNamePart = explode(".", $this->phpFullFileName);
        $phpBackupFileName = $phpFileNamePart[0].".bak";
        rename($this->phpFullFileName, $phpBackupFileName);
        $this->logger->loguj("Vytvořena záloha php souboru: ".$phpBackupFileName);
        $this->phpFileHandle = fopen($this->phpFullFileName, 'w');
        $newPhpContent = substr($this->phpContent, 0, $this->autocodeStartPosition-1);
        $newPhpContent .= "\n".$this->autocodeConfig->autocodeStart."\n".$this->autocode."\n";
        $newPhpContent .= substr($this->phpContent, $this->autocodeStartPosition+$this->autocodeLength); //řetězec $this->autocodeConfig->autocodeEnd zůstal zachován
        $this->logger->loguj("*** Nový php kód: ", TRUE);
        $this->logger->loguj($this->phpContent, TRUE);
        $this->logger->loguj("Ukládám vygenerovaný autocode do souboru {$this->phpFullFileName} pro class: ".$this->className);
        fwrite($this->phpFileHandle, $newPhpContent);
//                                        $this->logger->loguj(self::ODSAZENI."Vytvořen nový php soubor s autokódem:");
//                                        $this->logger->loguj(self::ODSAZENI."***********");
//                                        $this->logger->loguj($autoCode);
//                                        $this->logger->loguj(self::ODSAZENI."***********");
        fclose($this->phpFileHandle);
        unlink($phpBackupFileName);
        $this->logger->loguj("Smazána záloha php souboru: ".$phpBackupFileName."\n"); //odřádkování na konci
    }



    private function dejAutocodeItem()
    {
        $className = $this->className;
        $databaze = $className::DATABAZE;  //současně i test existence konstatnty ve třídě
        $tabulka = $className::TABULKA;  //současně i test existence konstatnty ve třídě
        $zobraz = $className::NAZEV_ZOBRAZOVANE_VLASTNOSTI;  //pouze test existence konstatnty ve třídě
        if (is_subclass_of($this->class, "Projektor_Data_HlavniObjektItem")) { //class definuje hlavní objekt - je potomek Projektor_Data_HlavniObjektItem
            $poleMapovani = $this->class->getMapovani();
            unset($this->class);
        } else {
            unset($poleMapovani);
        }

        if (!$this->finalize) {
            $strukturaTabulky = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka);
            self::kontrolaStrukturyTabulky($strukturaTabulky);
            $autocodeKomentar =  "    // Nový kód pro databázi ".$databaze." a tabulku ".$tabulka."\n";
            $autocodeKomentar .= "    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem ".$this->autocodeConfig->dbfieldprefix."\n";
            $autocodeKomentar .= "    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.\n";
            $autocodeKomentar .= "    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.\n";
            $autocodeKomentar .= "    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.\n";
            $autocodeKomentar .= "    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.\n";
            $autocodeKomentar .= "    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public\n";
            $autocodeKomentar .= "    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).\n\n";

            $autocodeReset = "";
            $autocodeMetody = "";
            foreach ($strukturaTabulky->sloupce as $strukturaSloupce) {
                $autocodeDB .= "    /**\n";
                $autocodeDB .= "     * Generovaná vlastnost pro tabulku ".$tabulka." a sloupec ".$strukturaSloupce->nazev.". Vlatnosti sloupce: typ=".$strukturaSloupce->typ;
                    if ($strukturaSloupce->delka) $autocodeDB .= ", delka=".$strukturaSloupce->delka;
                    if ($strukturaSloupce->default) $autocodeDB .= ", default=".$strukturaSloupce->default;
                switch ($strukturaSloupce->klic) {
                case "PK":
                    $autocodeDB .= ", sloupec je primární klíč";
                    if ($strukturaSloupce->extra=="auto_increment") {
                        $autocodeDB .= " a je ".$strukturaSloupce->extra."\n";
                        $autocodeDB .= "     * je vygenerována public vlastnost se jménem \$".$this->autocodeConfig->objectidname."\n     */\n";
                        $vlastnostDBField = $this->autocodeConfig->objectidname;
                    } else {
                        $autocodeDB .= " a není autoicrement\n";
                        $autocodeDB .= "     * je vygenerována standardní vlastnost\n     */\n";
                        $vlastnostDBField = $this->autocodeConfig->dbfieldprefix.$strukturaSloupce->nazev;
                    }
                    break;
                case "FK":
                    $autocodeDB .= "\n";
                    $autocodeDB .= "     * , sloupec je cizí klíč z tabulky ".$strukturaSloupce->referencovanaTabulka." a sloupce ".$strukturaSloupce->referencovanySloupec."\n     */\n";
                    $vlastnostDBField = $this->autocodeConfig->dbfieldprefix.$strukturaSloupce->nazev;
                    break;
                default:
                    $autocodeDB .= "\n";
                    $autocodeDB .= "     */\n";
                    $vlastnostDBField = $this->autocodeConfig->dbfieldprefix.$strukturaSloupce->nazev;
                    break;
                }
//                $autocodeDB .= "    public static $".$vlastnostDBField.";\n";
                $autocodeDB .= "    public $".$vlastnostDBField.";\n";
                $autocodeReset .= "        unset(\$this->".$vlastnostDBField.");\n";
            }
            $autocodeHlavniObjekt = "";
            if (isset($poleMapovani)) {
                foreach ($poleMapovani as $vlastnost => $itemClass) {
                    $autocodeHlavniObjekt .= "\n";
                    $autocodeHlavniObjekt .= "    /**\n";
                    $autocodeHlavniObjekt .= "     * vlastnost, která je podřízeným objektem hlavního objektu a je typu ".$itemClass."\n";
                    $autocodeHlavniObjekt .= "     */\n";
                    $autocodeHlavniObjekt .= "    public $".$vlastnost.";\n";
                    $autocodeReset .= "        unset(\$this->".$vlastnost.");\n";
                    $autocodeMetody .="    /**\n";
                    $autocodeMetody .="     * Metoda vrací vlastnost hlavního objektu smlouva typu ".$itemClass."\n";
                    $autocodeMetody .="     * @param ".$itemClass." \$object\n";
                    $autocodeMetody .="     * @return \\".$itemClass."\n";
                    $autocodeMetody .="     */\n";
                    $autocodeMetody .="    public function ".  ucfirst($vlastnost)."(".$itemClass." &\$object=NULL){\n";
                    $autocodeMetody .="        if (isset(\$this->".$vlastnost."))\n";
                    $autocodeMetody .="        {\n";
                    $autocodeMetody .="            \$object = \$this->".$vlastnost.";\n";
                    $autocodeMetody .="        } else {\n";
                    $autocodeMetody .="            \$object = new ".$itemClass."(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_".$tabulka."\n";
                    $autocodeMetody .="            \$object->where(\"id_".$tabulka."\", \"=\", \$this->id);\n";
                    $autocodeMetody .="            \$this->".$vlastnost." = \$object;   //uloží object do vlastnosti\n";
                    $autocodeMetody .="        }\n";
                    $autocodeMetody .="        return \$object;\n";
                    $autocodeMetody .="    }\n\n";

                }
            }
        }
        if (isset($poleMapovani)) {
            $autocodeGetSet ="    public function __get(\$nazevVlastnosti) {\n";
            $autocodeGetSet .="        if (array_key_exists(strtolower(\$nazevVlastnosti), \$this->_mapovaniVlastnostItem))\n";
            $autocodeGetSet .="        {\n";
            $autocodeGetSet .="            if (isset(\$this->\$nazevVlastnosti))\n";
            $autocodeGetSet .="            {\n";
            $autocodeGetSet .="                return \$this->\$nazevVlastnosti;\n";
            $autocodeGetSet .="            } else {\n";
            $autocodeGetSet .="                \$factoryFunction = ucfirst(strtolower(\$nazevVlastnosti));\n";
            $autocodeGetSet .="                return \$this->\$factoryFunction();\n";
            $autocodeGetSet .="            }\n";
            $autocodeGetSet .="        }\n";
            $autocodeGetSet .="        return parent::__get(\$nazevVlastnosti);\n";
            $autocodeGetSet .="    }\n";
            $autocodeGetSet .="\n";
            $autocodeGetSet .="    public function __set(\$nazevVlastnosti, \$value) {\n";
            $autocodeGetSet .="        if (array_key_exists(strtolower(\$nazevVlastnosti), \$this->_mapovaniVlastnostItem))\n";
            $autocodeGetSet .="        {\n";
            $autocodeGetSet .="            \$this->\$nazevVlastnosti = \$value;\n";
            $autocodeGetSet .="            return \$value;\n";
            $autocodeGetSet .="        }\n";
            $autocodeGetSet .="        return parent::__set(\$nazevVlastnosti);\n";
            $autocodeGetSet .="    }\n";
            $autocodeGetSet .="\n";
        }
        if (!$this->finalize) {
            $autocode = $autocodeKomentar.$autocodeDB.$autocodeHlavniObjekt;
            $autocode .= "\n";
            $autocode .= "    public function reset()\n";  //TODO: bylo by lepší protected (metoda je jen v potomcích Item a volaná z Item konstruktor, ale neumím si poradit s deklarací protected function v interface
            $autocode .= "    {\n";
            $autocode .=            $autocodeReset;
            $autocode .= "    }\n";
            $autocode .= $autocodeMetody;
            $autocode .= $autocodeGetSet;
        } else {
            $autocode = $autocodeGetSet;
            $autocode .= "    public function reset()\n";  //TODO: bylo by lepší protected (metoda je jen v potomcích Item a volaná z Item konstruktor, ale neumím si poradit s deklarací protected function v interface
            $autocode .= "    {\n";
            $autocode .= "    }\n";
        }

        return $autocode;
    }
    private function dejAutocodeCollection() {
        $this->logger->loguj(self::ODSAZENI."kontrola existence a syntaxe nově vytvořeného souboru {$this->phpFullFileName} pro class: ".  $this->className);
        //pokud je v souboru s definicí classname syntaktická chyba - autoload nenačte soubor a skončí s exception hlásící tuto syntax chybu
//                    is_callable????
        $className = $this->className;
        $itemClassName = $className::NAZEV_TRIDY_ITEM;  //současně i test existence konstatnty ve třídě
        $databaze = $itemClassName::DATABAZE;  //současně i test existence konstatnty ve třídě
        $tabulka = $itemClassName::TABULKA;  //současně i test existence konstatnty ve třídě
        if (!$this->finalize) {
            $strukturaTabulky = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($databaze, $tabulka);
            self::kontrolaStrukturyTabulky($strukturaTabulky);
            $autocode = "";
            $autocode .= "\n";
            $autocode .= "    /**\n";
            $autocode .= "     * Metoda vrací Item, prvek kolekce ".$className." typu ".$itemClassName."\n";
            $autocode .= "     * @param ".$itemClassName." \$object\n";
            $autocode .= "     * @return \\".$itemClassName."\n";
            $autocode .= "     */\n";
            $autocode .= "    public function Item(\$id, ".$itemClassName." &\$object=NULL){\n";
            $autocode .= "        \$object = new ".$itemClassName."(\$id); //factory na Item\n";
            $autocode .= "        return \$object;\n";
            $autocode .= "    }\n";
        }
        return $autocode;
    }

    private function kontrolaStrukturyTabulky(Projektor_Data_Auto_Cache_StrukturaTabulky $strukturaTabulky) {

    }

    //    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $t->name)) {
    //
    //                    echo "*****************************************************************\n".
    //
    //                        "**               WARNING COLUMN NAME UNUSABLE                  **\n".
    //
    //                        "** Found column '{$t->name}', of type  '{$t->type}'            **\n".
    //
    //                        "** Since this column name can't be converted to a php variable **\n".
    //
    //                        "** name, and the whole idea of mapping would result in a mess  **\n".
    //
    //                        "** This column has been ignored...                             **\n".
    //
    //                        "*****************************************************************\n";
    //
    //                    continue;
    //
    //                }

}
?>
