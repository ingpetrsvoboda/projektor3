<?php
/**
 * Projektor_Model_Item
 *
 * @author pes2704
 */
abstract class Projektor_Model_Item implements IteratorAggregate {    
    /**
     * Vlastnost pro uložení databázového handleru.
     * Handler je potomek třídy PDO. Objekty třídy PDO nejsou serializovatelné, proto je vlastnost dbh
     * odstaněna v metodě __sleep() a vytvořena v metodě __wakeup() podle hodnoty vlastnosti $this->database
     * @var PDO 
     */
    protected $dbh;
    
    /**
     * Vlastnost pro uložení jména databáze. Jméno databáze je použito jako parametr pro
     * volání metody Framework_Container::getDbh(), která vrací aktuální databázový handler pro zadanou databázi.
     * Tato vlastnost je použita vždy, když se vytváří objekt - tedy v metodě __construct() a unserialize().
     * @var string 
     */
    protected $database;
    
    /**
     * Statická vlastnost pro uložení prefixu názvů vlastností objektu odpovídajících sloupcům db tabulky
     * Je nastavována v metodě dejDbFieldPrefix() na hodnotu načtenou z konfigurace.
     * @var string
     */
    protected static $dbFieldPrefix;
    
    /**
     * Vlastnost pro uložení hodnoty id, se kterým byl objekt vytvářen. Instanční proměnná.
     * @var integer 
     */
    protected $id;
    
    /**
     * Název vlastnosti obsahující identifikátor objektu. Tento název je načítán z konfigurace.
     * @var string
     */
    protected static $objectIdName;

    /**
     * Hodnoty načtené z databáze. Asociativní pole, klíče odpovídají názvům vlastností.
     * @var array 
     */
    protected $attributes = array();
    
    /**
     * Vlastnost pro uložení objektu Projektor_Model_Sql s připraveným sql dotazem pro načítání dat
     * Je protected, je nastavována metodami Item a uplatní se při načítání dat v ItemIterator
     * @var Projektor_Model_Sql_Select
     */
    protected $sqlSelect;

    /**
     * Vlastnost rozlišuje nově vytvořený prázdný objekt, který nebude načítán z databáze (hodnota je FALSE)
     *  a již dříve uložený, "starý" objekt, který bude z databáze načten.
     * Jde o semafor pro lazy load z databáze, na FALSE se nastavuje v metodě getIterator po načtení dat z db, 
     * na TRUE se nastavuje v metodě where(), filter() a načti(). To znamená, že změna filtru, filtru where nebo volba nacti() způsobí,
     * že příští pokus o přístup k vlastnosti objektu Item vyvolá nové načtení dat z databáze.
     * @var boolean
     */
    protected $novyObjekt;
    
    /**
     * Iterátor vracený metodou getIterator (implementace IteratorAggregate).
     * Současně tato vlastnost slouží jako semafor pro lazy load dat z databáze v metodě ItemIterator->getIterator().
     * @var ArrayIterator
     */
    protected $arrayIterator;

    /**
     * Semafor pro metodu $this->uloz()
     * @var bolean
     */
    protected $dataZmenena;

    /**
     * Konstruktor vytvoří a vrátí objekt pro jeden řádek tabulky v DB, volání konstruktoru je určeno pro vytvoření objektu, který vždy bude načítán z databáze.
     * Pokud je zadán parametr $id, konstruktor vytvoří objekt s prislusnym id.
     * Pokud $id není zadáno, je nutné před prvním pokusem o čtení dat z objektu zadat vhodný filtr where, který zajistí načtení jen 
     * jednoho řádku db tabulky, jinak nedojde k načtení dat. Pokud nastavíte filtr where, který načte více řádků databázové tabulky, 
     * dojde k vyhození výjimky.
     * @param int $id Identifikator řádku tabulky
     * @return Instance třídy potomka Projektor_Model_Item připravená na lazy load načtení dat z databáze
     */
    public function __construct($id=NULL) {

        $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA); //Projektor_Model_Auto_Cache_StrukturaTabulky
        $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;
        //pro ručně psané třídy (negenerované autocode) - nemají metodu reset()
        if (\method_exists($this, 'reset')) {
            $this->reset();
        }
        $this->database = static::DATABAZE;
        $this->dbh = Projektor_Container::getDbh($this->database);  
        if ($id) {
            $this->id = $id;
            $this->setStaryObjekt();
            $this->where($nazevSloupcePK, "=", $id);
            if (isset($strukturaTabulky->sloupce["valid"])) {
                $templateSqlClassName = get_class($this->sqlSelect);
                $this->sqlSelect->validFilter = $templateSqlClassName::DEFAULT_VALID_FILTER;
            }
        } else {
            $this->novyObjekt = TRUE;  
        }
    }
    
    public function __sleep() {
//        get_class_vars($class_name)
    return array('database', 'dbFieldPrefix', 'id','objectIdName', 'attributes', 'sqlSelect', 'novyObjekt', 'arrayIterator', 'dataZmenena');    
    }
    
    public function __wakeup() {
        if (\method_exists($this, 'reset')) {    // zajuišťuje volání __get() po deserializaci
            $this->reset();
        }
        $this->dbh = Projektor_Container::getDbh($this->database);
    }

    /**
     * Statická "factory" metoda. Metoda vytvoří a vrátí nový prázdný item, který se nebude načítat z databáze. 
     * Pokud chcete, aby se takto vytvořený item načítal z databáze, je třeba objekt vytvořit standardně voláním konstruktoru.
     * @return \static
     */
    public static function factory() {
        $class = new static;  //vytvoří nový POTOMKOVSKÝ objekt
        return $class;
    }
    
    private function setStaryObjekt() {
        if (!isset($this->novyObjekt) OR $this->novyObjekt) {
            $this->novyObjekt = FALSE;
            $this->sqlSelect = new Projektor_Model_Sql_Select($this->database);
            $this->sqlSelect->select = "*";
            $this->sqlSelect->from = static::TABULKA;  //TODO: vkládat jako identifikátor -> sem s vlnovkou a přidat do pole identificators[]
        }
    }
    
    // !!! metody selectAttribute() v Collection a metoda select() v Item obsahují obdobný kód
    // !!! udržuj konzistenci kódu selectAttributes() v Collection a select() v Item    
    /**
     * Metoda nastaví sloupce db tabulky načítané do objektu Item. Parametr $nazvy je pole obsahující jako hodnoty názvy vlastností objektu Item 
     * nebo názvy sloupců db tabulky. \n
     * Pokud pole $nazvy neobsahuje název odpovídající sloupci s primárním klíčem db tabulky,
     * metoda název tohoto pole doplní. Objekt Item tedy vždy obsahuje alespoň vlastnost odpovídající sloupci s primárním klíčem db tabulky. 
     * Pokud není zadán parametr $nazvy, metoda nastaví jedinou vlastnost, vlastnost odpovídající sloupci s primárním klíčem db tabulky.
     * Pokud pole $nazvy bude obsahovat pouze jeden prvek s hodnotou '*', metoda nastaví všechny sloupce db tabulky.\n
     * Použití:\n
     * Pro tabulku 'tabulka' s primárním klíčem 'id_tabulka'
     * ->select(): Objekt Item bude obsahovat pouze sloupec id_tabulka
     * ->select(array('aaa', 'bbb')): Objekt Item bude obsahovat sloupce id_tabulka, aaa, bbb  \n
     * ->select(array('*')): Objekt Item bude obsahovat všechny sloupce db tabulky. Je třeba použít jen pokud jste předtím nastavili jiný select a nyní chcete, aby Item měl všechny vlastnosti. 
     * Pokud metodu select() nevoláte vůbec, objekt Item má všechny vlastnosti defaulně.\n
     * Metoda automaticky převádí názvy vlastností na názvy sloupců. Pokud parametr $nazvy není zadán, je použita 
     * default hodnota *, tedy select vybírá všechny sloupce db tabulky (SELECT * FROM ...). Zadané názvy sloupců nebo vlastností jsou převedeny na korektnmí název 
     * identifikátory v SQL automaticky podle typu databáze, ze které je objekt Item načítán (např. pro MySQL je obalen databázovými apostrofy, 
     * pro MSSQL uzavřen do hranatých závorek), jako náze může být teedy použit žetězec s mezerami.
     * @param array $nazvy
     * @return string
     */
    public function select(array $nazvy=NULL) {
        $this->setStaryObjekt();
        if ($nazvy) {
            if ($nazvy == array('*')) {
                $nazvySloupcuDb = $nazvy;
            } else {
                $nazvySloupcuDb = array();
                // přidá sloupoec s primárním klíčem db tabulky
                if (!array_key_exists($this->dejPrimaryKeyFieldName(), $nazvy)) $nazvy = array_merge (array(0=>$this->dejPrimaryKeyFieldName()), $nazvy);
                foreach ($nazvy as $nazev) {
                    try {
                        $nazevSloupceDb = self::dejNazevSloupceZVlastnosti($nazev);
                        if ($nazevSloupceDb) {
                            $nazvySloupcuDb[] = $this->dbh->getFormattedIdentificator($nazevSloupceDb);
                        } else {
                            throw new Projektor_Model_Exception("Zadaná vlastnost objektu nebo název sloupce ".$name." neodpovídá sloupci v db tabulce. Tento název nebyl zahrnut do výběru.");
                        }
                    } catch (Projektor_Model_Exception $e) {
                        echo $e;
                    }
                }
            }
        } else {
            $nazvySloupcuDb = array(0=>$this->dbh->getFormattedIdentificator($this->dejPrimaryKeyFieldName()));
        }
        $this->arrayIterator = FALSE;
        $this->sqlSelect->select = implode(", ", $nazvySloupcuDb);
        return $this->sqlSelect->select;
    }
    
    /**
     * Metoda nastaví filtr WHERE a vrací řetězec právě vytvořeného filtru.
     * Pokud byl již filtr nastaven, přidá další podmínku s operátorem AND, například: podmínka1 AND podmínka2.
     * Nové nastavení filtru způsobí, že každé další čtení/zápis vlastnosti objektu vyvolá nové načtení dat z databáze s novým filtrem
     * Filtr je vytvořen prostým zřetězením názvu sloupce a podmínky. Použití: ->where("prijmeni", "= 'Novák'") vytvoří filtr "WHERE prijmeni = 'Novák',
     * ->where ("vek", "> 20") vytvoří filtr "WHERE vek > 20". Metoda kontroluje existenci názvu sloupce v db tabulce, pro neexistující sloupec vyhodí výjimku.
     * @param type $nazevVlastnosti Název sloupce db tabulky nebo vlastnosti autocode generované vlastnosti
     * @param type $podminka
     * @return boolean
     * @throws Projektor_Model_Exception
     */
    public function where($nazevVlastnosti = NULL, $podminka = NULL, $hodnota = NULL, $otevreneZleva=NULL, $otevreneZprava=NULL) {
        try {
            $nazevSloupce = self::dejNazevSloupceZVlastnosti($nazevVlastnosti);
            if ($nazevSloupce) {
                $this->setStaryObjekt();
                $this->sqlSelect->where($nazevSloupce, $podminka, $hodnota, $otevreneZleva, $otevreneZprava);
                $this->arrayIterator = FALSE;
                return TRUE;
            } else {
                throw new Projektor_Model_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    /**
     * Metoda nastaví uživatelský filtr.
     * @param string $filter Řetězec ve formátu SQL podmínky, bude použit při skládání připraveného SQL dotazu 
     * a bude vložen za klíčové slovo WHERE v SQL dotazu
     */
    public function filter($filter = NULL) {
        if ($filter) {
            $this->setStaryObjekt();
            $this->sqlSelect->filter = $filter;
            $this->arrayIterator = FALSE;
        }
    }

    /**
     * Pokud parametr je TRUE nebo není zadán metoda nastaví filtr tak, že při načítání dat do Collection nebo Item budou načítány i řádky s hodnotou valid=0 (všechny řádky tabulky,
     * bez nastavení filtru jsou načítány jen řádky s hodnotou valid=1 (nesmazané).
     * Pokud parametr je FALSE metoda vrátí nastavení filtru na výchozí a budou načítaány jen řádky s hodnotou valid=1 (nesmazané).
     * @param type $vsechnyRadky
     */
    public function vsechnyRadky($vsechnyRadky = TRUE) {
        $this->setStaryObjekt();
        if ($vsechnyRadky) {
            $this->sqlSelect->validFilter = "";
        } else {
            if (isset(Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce["valid"])) {
                $psqlClassName = get_class($this->sqlSelect);
                $this->sqlSelect->validFilter = $psqlClassName::DEFAULT_VALID_FILTER;
            }
        }
    }
    
    /**
     * Metoda uloží (zapíše) hodnoty datových vlastností itemu do databáze. Metoda rozpozná, zda se jedná o nový item (použije SQL INSERT) 
     * nebo zda se jedná o item, kterýjiž předtím v databáti existoval (použije SQL UPDATE)
     * @return mixed integer/boolean Vrací počet uložených nebo změněných řádek (affected rows), 
     * pokud se vlastnosti Item od posledního čtení nebo uložení nezměnily a tedy není co ukládat vrací nulu, 
     * pokud dijde k chybě a uložení se nezdaří vrací FALSE. 
     */
    
//TODO: přesun příkazů insert a update (a delete) do sql objektu
    public function uloz() {
        if (!$this->dataZmenena) return 0;

        $nazevSloupcePK = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->primaryKeyFieldName;
        if($this->novyObjekt) {
            try {
                // INSERT
                $identificators = array();
                $params = array();
                // při pokusu o přístup k objektu v příkazu foreach dojde k zavolání metody getIterator a následně se ve smyčce krokuje iterátorem
                // Projektor_Model_ItemIterator je pro případ, ža data nemají být načítána z databáze (new datový objekt) vytvořen
                // podle struktury db tabulky, má tolik položek, kolik je sloupců tabulky a položky jsou naplněny default hodnotami z databáze
                foreach ($this as $nazevSloupceDb => $hodnota) {
                    if ($hodnota AND $nazevSloupceDb!=$nazevSloupcePK) {
                        $identificators["~".$nazevSloupceDb] = $nazevSloupceDb;
                        $params[":".$nazevSloupceDb] = $hodnota;  
                    }
                }
                $query_column_names = implode(', ', array_keys($identificators));
                $query_values = implode(', ', array_keys($params));
            } catch (Projektor_Model_Exception $e) {
                echo $e;
            }
            $query="INSERT INTO ".static::TABULKA." (".$query_column_names.") VALUES (".$query_values.");";
        } else {
            // UPDATE
            $identificators = array();
            $params = array();
            foreach ($this as $nazevSloupceDb=>$hodnota) { // foreach volá getIterator
                if ($hodnota AND $nazevSloupceDb!=$nazevSloupcePK) {
                    if ($query) {
                        $query .= ",";
                    }
                    $query .= " ~".$nazevSloupceDb."=:".$nazevSloupceDb;
                    $identificators["~".$nazevSloupceDb] = $nazevSloupceDb;
                    $params[":".$nazevSloupceDb] = $hodnota;
                }
            }

            $query ="UPDATE ".static::TABULKA." SET ".$query;
            $query .=" WHERE ".$nazevSloupcePK." = ".$this->$nazevSloupcePK;
        }
        if ($identificators) {
            foreach ($identificators as $slot => $identificator) {
                $formattedIdentificator = $this->dbh->getFormattedIdentificator($identificator);
                $query = str_replace($slot, $formattedIdentificator, $query);
            }
        } 
        $prep = $this->dbh->prepare($query);

        try {
            $success = $prep->execute($params);
            if ($success) {
                if (!$this->$nazevSloupcePK) {
//                    $this->$nazevSloupcePK = $this->dbh->lastInsertId();  //nastaví id objektu po TO NEFUNGUJE setter odmítne nasravit PK
                    $this->arrayIterator->offsetSet($nazevSloupcePK, $this->dbh->lastInsertId());
                }
                return $prep->rowCount();;
            } else {
                $errorInfo = $prep->errorInfo();
                throw new Projektor_Model_Exception("Item nebyl uložen, nastala chyba při provádění SQL příkazu ".$query.". \nInformace o chybě: ".
                                    "<pre>".print_r($errorInfo, TRUE)."\n".var_dump($params)."</pre>");
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }            
            
            
    }

################  SETTER A GETTER ######################################################################
     /**
     * Setter -
     * hodnota vlastnosti, která má odpovídající sloupec v db tabulce je zapsána do pole položek IteratorAggregate a metoda vrací právě zapsanou hodnotu.
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) načte vlastnosti sloupce db tabulky.
     * Pokud zadané jméno vlastnosti odpovídá sloupci, který je primární klíč a je autoincrement, metoda nezapisuje nic a vrací FALSE.
     * Pro vlastnost objektu, která nemá odpovidajici sloupec v db tabulce položka není vytvořena a metoda vyhodí výjimku.
     * @param type $name
     * @param type $value
     * @return type
     */
    public function __set($name,$value) {
        try {
            $strukturaSloupce = self::dejStrukturuSloupce($name);

            if ($strukturaSloupce) {
                if ($strukturaSloupce->klic=="PK" AND $strukturaSloupce->extra=="auto_increment") return FALSE;
                $nazevSloupceDb = $strukturaSloupce->controllerName;
//                $this->addOrReplace($nazevSloupceDb, $value);
                $iterator = $this->getIterator();  //pokud není načten, načte item
                if ($this->isNacten()) {
                    $iterator->offsetSet($nazevSloupceDb, $value);
                    $this->dataZmenena = TRUE;
                    return $iterator->offsetGet($nazevSloupceDb);
                } else {
                    throw new Projektor_Model_Exception("Není možné nastavovat vlastnost objektu, data objektu se nepodařilo načíst z databáze. "
                            ."Objekt se zadaným id ".$this->id." pravděpodobně nemá záznam v databázi");
                }
            } else {
                throw new Projektor_Model_Exception("Není možné nastavovat hodnotu vlastnosti objektu, která neodpovídá sloupci v db tabulce: ".$name);
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    /**
     * Getter - hodnota vlastnosti, která má odpovídající sloupec v db tabulce je načtena z Iterátoru a vrací se.
     * Pokud dosud nebyla načtena data z databáze volání této metody je načte (lazy load).
     * Pro vlastnost generovanou jako id (viz konfigurace aplikace Framework_Config) vrací hodnotu primárního klíče.
     * @param type $nazevVlastnosti
     * @return mixed/boolean 
    */
    public function __get($nazevVlastnosti) {
        if (isset($this->$nazevVlastnosti)) {
            return $this->$nazevVlastnosti;
        } else {     
            try {
                $nazevSloupceDb = self::dejNazevSloupceZVlastnosti($nazevVlastnosti);
                if ($nazevSloupceDb) {
                    if ($this->isNacten()) {
                        return $this->arrayIterator->offsetGet($nazevSloupceDb);
                    } else {
                        if ($this->createHydratedIterator()) {  //pokud není načten, načte item
                            if ($this->isNacten()) {
                                return $this->arrayIterator->offsetGet($nazevSloupceDb);
                            } else {
                                throw new Projektor_Model_Exception("Není možné číst vlastnost objektu, data objektu se nepodařilo načíst z databáze. "
                                        ."Objekt se zadaným id ".$this->id." pravděpodobně nemá záznam v databázi");
                            }
                        } else {
                            throw new Projektor_Model_Exception("Není možné číst vlastnost objektu, nepodařilo čtení z databáze. ");
                        }
                    }
                } else {
                    return NULL;
                }
            } catch (Projektor_Model_Exception $e) {
                echo $e;
                return FALSE;
            }
        }
    }

############### PUBLIC METODY (INFORMAČNÍ) #############################################################

    public function isNacten() {
        return $this->arrayIterator ? TRUE : FALSE;
    }
    public function isZmenen() {
        return $this->dataZmenena;
    }    
    public function isNovy() {
        return $this->novyObjekt;
    }     
    /**
     * Metoda vrací název sloupce db tabulky odpovídající zadané vlastnosti, pokud neexistuje odpovídající sloupec, vrací FALSE.
     * Pro vlastnost generovanou jako id (viz konfigurace aplikace Framework_Config) vrací název primárního klíče.
     * Pro vlastnost obsahující za prefixem ještě další znaky '°' vrací název sloupce vytvořený záměnou znaků '°' za mezery, tedy opačný postup,
     * než kterým byla vytvořeny názvy vlastností objektu Item z názvů sloupců obsahujících mezery při autokódování.
     * Názvy sloupců jsou převedeny na korektnmí identifikátory v SQL automaticky podle typu databáze, ze které je objekt Item načítán 
     * (např. pro MySQL je obalen databázovými apostrofy, pro MSSQL uzavřen do hranatých závorek). 
     * Jako název může být tedy použit žetězec s mezerami.
     * Příklady:\n
     * - databáze MySQL, prefix="dbField°" a tabulka s primárním klíčem "id_akce": pro název "dbField°text" vrací `text`, pro název "text" vrací `text`, 
     * pro název "id" vrací "id_akce"\n
     * - databáze MSSQL, prefix="dbField°" a tabulka s primárním klíčem "id_akce": pro název "dbField°text" vrací [text], pro název "text" vrací [text], 
     * pro název "id" vrací "id_akce"\n
     * - databáze MySQL, prefix="dbField°": pro název "dbField°jmeno°osoby" vrací `jmeno osoby`.\n
     * @param type $nazevVlastnosti Název vlastnosti objektu
     * @return string Název sloupce db tabulky
     */
    public static function dejNazevSloupceZVlastnosti($nazevVlastnosti) {
        if ($nazevVlastnosti == self::dejObjectIdName()) { //TODO: metody self::dejObjectIdName() a self::dejDbFieldPrefix() do této třídy NEPATŘÍ! Patří někm do autocode.
            return Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->primaryKeyFieldName;
        } else {
            $nazevSloupceDb = str_replace(self::dejDbFieldPrefix(), "", $nazevVlastnosti);
            $nazevSloupceDb = str_replace('°', ' ', $nazevSloupceDb);
            if (isset(Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce[$nazevSloupceDb])) return $nazevSloupceDb;
            return FALSE;
        }
    }

    /**
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) vytvoří název sloupce,
     * ostatní vlastnosti považuje přímo za název sloupce.
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) vytvoří název sloupce,
     * pro vlastnost generovanou jako id (viz konfigurace aplikace Framework_Config) použije sloupec primárního klíče.
     * pro ostatní vlastnosti použije zadaný název beze změny jako název sloupce.
     * Vrací strukturu sloupce db tabulky (objekt Projektor_Model_Auto_Cache_StrukturaSloupce), pro neexistující sloupec vrací FALSE
     * @param type $nazevVlatnosti
     * @return Projektor_Model_Auto_Cache_StrukturaSloupce/boolean
     */
    public static function dejStrukturuSloupce($nazevVlatnosti) {
        $nazevSloupceDb = self::dejNazevSloupceZVlastnosti($nazevVlatnosti);
        // kontrola existence sloupoe se zadaným názvem v db tabulce
        if ($nazevSloupceDb) {
            $strukturaSloupce = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce[$nazevSloupceDb];
            if (isset($strukturaSloupce) AND $strukturaSloupce) return $strukturaSloupce;
        }
        return FALSE;
    }

    /**
     * Metoda vrací název sloupce db tabulky obsahující primární klíč tabulky.
     */
    public static function dejPrimaryKeyFieldName() {
        $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA); //Projektor_Model_Auto_Cache_StrukturaTabulky
        return $strukturaTabulky->primaryKeyFieldName;
    }

    /**
     * Metoda vrací název proměnné obsahující id objektu Item. Tento názec je nastaven v konfiguraci v sekci autocode pod položkou objectidname.
     * Metoda vrací hodnotu uloženou ve statické vlastnosti objektu Item $objectIdName, 
     * pokud není uložena, hodnotu nejprve načte z konfigurace a do vlastnosti uloží. 
     * Pokud vlastnost z konfigurace nelze načíst, metoda vyhodí výjimku.
     * @throws Exception
     */
    public static function dejObjectIdName() {
        if (!self::$objectIdName) {
            $autocodeConfig = Framework_Config::najdiSekciPodleJmena(Framework_Config::SEKCE_AUTOCODE);
            if (!$autocodeConfig->objectidname) throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Framework_Config::XML_INI." v sekci ".Framework_Config::SEKCE_AUTOCODE." není definován objectidname: ".$autocodeConfig->objectidname);
            self::$objectIdName = $autocodeConfig->objectidname;
        }
        return self::$objectIdName;
    }
    
    /**
     * Metoda vrací prefix vlastností, které odpovídají sloupcům db tabulky. Prefix je načítán z konfigurace. 
     * Název vlastnosti objektu je složen z prefixu a názvu sloupce v databázi. 
     * Metoda vrací hodnotu uloženou ve statické vlastnosti objektu Item $dbfieldprefix, pokud není uložena hodnotu nejprve načte z konfigurace 
     * a do vlastnosti uloží. 
     * Pokud vlastnost z konfigurace nelze načíst, metoda vyhodí výjimku.
     * @return string
     * @throws Projektor_Model_Exception
     */
    public static function dejDbFieldPrefix() {
        if (!self::$dbFieldPrefix) {
            $autocodeConfig = Framework_Config::najdiSekciPodleJmena(Framework_Config::SEKCE_AUTOCODE);
            if (!$autocodeConfig->dbfieldprefix) throw new Projektor_Model_Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Framework_Config::XML_INI." v sekci ".Framework_Config::SEKCE_AUTOCODE." není definován dbfieldprefix: ".$autocodeConfig->dbfieldprefix);
            self::$dbFieldPrefix = $autocodeConfig->dbfieldprefix;
        }
        return self::$dbFieldPrefix;
    }

    /**
     * Vrací kolekci Collection řádkových objektů Item pro db tabulku referencovanou sloupcem, odpovídajícím zadanému názvu vlastnosti.
     * Pro sloupec který není referencí (není cizí klíč) metoda vrací FALSE.
     * Pro neexistující název sloupce nebo pokud se nepodaří nalézt třídu pro vytvoření kolekce odpovídající referencované tabulce metoda vyhodí výjimku.
     * Pokud je zadaný parametr $nazevVlastnosti vlastnost generovaná autocode (vlastnost s prefixem dbFieldPrefix) metoda vytvoří název sloupce,
     * pro ostatní vlastnosti použije zadaný název beze změny jako název sloupce.
     * @param type $nazevVlastnosti
     * @return boolean|\collectionClassName
     * @throws Projektor_Model_Exception
     */
    public function dejReferencovanouKolekci($nazevVlastnosti) {
        try {
            $strukturaSloupce = self::dejStrukturuSloupce($nazevVlastnosti);
            if ($strukturaSloupce) {
                if ($strukturaSloupce->klic=="FK") {
                    $collectionClassName = $this->dejNazevTridyCollectionZTabulky($strukturaSloupce->referencovanaTabulka);
                    if ($collectionClassName) {
                        $refCollection = new $collectionClassName();
                        return $refCollection;
                    } else {
                        throw new Projektor_Model_Exception("neexistuje třída Collection: ".$collectionClassName." pro referencovanou tabulka: ".$strukturaSloupce->referencovanaTabulka.". Zadaná vlastnost objektu: ".$nazevVlastnosti." odpovídá sloupci v db tabulce: ".$strukturaSloupce->controllerName.
                                " což je cizí klíč: ".$strukturaSloupce->klic." referencované tabulky.");
                    }
                } else {
                    return  FALSE;  //sloupec není FK
                }
            } else {
                if (is_subclass_of($this, "Projektor_Model_HlavniObjektItem")) {
                    return FALSE; //class je potomek Projektor_Model_HlavniObjektItem - podrízená vlastnost hlavního objektu nemá odpovídající sloupec v tabulce
                } else {
                    throw new Projektor_Model_Exception("Zadaná vlastnost objektu: ".$nazevVlastnosti." neodpovídá sloupci v db tabulce: ".static::TABULKA);
                }
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    public function dejReferencovanýItem($nazevVlastnosti) {
        try {
            $strukturaSloupce = $this->dejStrukturuSloupce($nazevVlastnosti);
            if ($strukturaSloupce) {
                if ($strukturaSloupce->klic=="FK") {
                    $itemClassName = $this->dejNazevTridyItemZTabulky($strukturaSloupce->referencovanaTabulka);
                    if ($itemClassName){
                        return new $itemClassName($this->$nazevVlastnosti);// pokud $nazevVlastnosti odpovida sloupoci s FK, $datovyObjekt->$nazevVlastnosti je hodnota tohoto FK, tedy id referencovaného objektu
                    } else {
                        throw new Projektor_Model_Exception("neexistuje třída Collection: ".$itemClassName." pro referencovanou tabulka: ".$strukturaSloupce->referencovanaTabulka.". Zadaná vlastnost objektu: ".$nazevVlastnosti." odpovídá sloupci v db tabulce: ".$strukturaSloupce->controllerName.
                                " což je cizí klíč: ".$strukturaSloupce->klic." referencované tabulky.");
                    }
                } else {
                    return  FALSE;  //sloupec není FK
                }
            } else {
                if (is_subclass_of($this, "Projektor_Model_HlavniObjektItem")) {
                    return FALSE; //class je potomek Projektor_Model_HlavniObjektItem - podrízená vlastnost hlavního objektu nemá odpovídající sloupec v tabulce
                } else {
                    throw new Projektor_Model_Exception("Zadaná vlastnost objektu: ".$nazevVlastnosti." neodpovídá sloupci v db tabulce: ".static::TABULKA);
                }
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }
    }
################ PRIVÁTNÍ METODY ############################

    private function dejNazevTridyCollectionZTabulky($tabulka) {
        try {
            if (is_string($tabulka) AND $tabulka) {
                $calledClass = get_called_class();
                $collectionClassName = substr($calledClass, 0, strrpos($calledClass, "_"));  //strrpos = zprava
                $collectionClassName .= "_".str_replace(" ", "", ucwords(str_replace("_", " ", $tabulka)))."Collection";
                return $collectionClassName;
            } else {
                    throw new Projektor_Model_Exception("Zadaný název tabulky ".$tabulka." není řetězec nebo je prázdný.");
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    private function dejNazevTridyItemZTabulky($tabulka) {
        try {
            if (is_string($tabulka) AND $tabulka) {
                $calledClass = get_called_class();
                $collectionClassName = substr($calledClass, 0, strrpos($calledClass, "_"));  //strrpos = zprava
                $collectionClassName .= "_".str_replace(" ", "", ucwords(str_replace("_", " ", $tabulka)))."Item";
                return $collectionClassName;
            } else {
                    throw new Projektor_Model_Exception("Zadaný název tabulky ".$tabulka." není řetězec nebo je prázdný.");
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }
    }
    
#################### getIterator ##############################################
    /**
     * Metoda předepsaná rozhraním IteratorAggregate, vrací PHP SPL objekt ArrayIterator, který implementuje rozhraní Iterator. 
     * Metoda je volána při pokusu o iterování objektu Item. To nastáva typicky při použití objektu v cyklu foreach. 
     * (Např: foreach ($item as $name=>$value) ... ) Metoda zajišťuje lazy load dat z databáze. Data z databáze se načítají
     * až v okamžiku pokusu o přístup k datům iterátoru.
     * <p>Pokud je k dispozici výsledek databázového dotazu (datový objekt již měl záznam v databázi), metoda načítá data z řádku databázové 
     * tabulky. Metoda přidá pouze dosud neexistující položky v poli položek a naplní je právě načtenými hodnotami z databáze. Hodnoty již 
     * dříve nastavených vlastností tedy v okamžiku kdy skutečně (lazy) dojde k načtení dat z databáze metoda nepřepisuje, zachová je.</p>
     * <p>Pokud objekt byl vytvořen prázdný (nový), metoda načte do iterátoru default hodnoty sloupců 
     * databázové tabulky (nevytváří nový řádek, záznam v databázové tabulce).</p>
     * @return \ArrayIterator
     */
    public function getIterator() {
        if ($this->arrayIterator) {
            $newArrayIterator = clone $this->arrayIterator;
            $newArrayIterator->rewind();
            return $newArrayIterator;
        } else {
            if ($this->createHydratedIterator()) {
                return $this->arrayIterator;
            } else {
                return FALSE;
            }
        }
    }

    private function createHydratedIterator () {
        $this->arrayIterator = new ArrayIterator();
        if (!$this->novyObjekt) {
            return $this->hydrateIteratorByData();
        } else {
            return $this->hydrateIteratorByDefaults();
        }        
    }
    
    private function hydrateIteratorByData() {
        try {
            $prep = $this->sqlSelect->getPreparedStatement();
            //execute
            $success = $prep->execute();
            //kontrola a přidání položek do iterátoru
            $numRows = $prep->rowCount();
            if ($numRows == 1) {
                $radek = $prep->fetch(PDO::FETCH_ASSOC);  //radek
                if ($radek) {
                    foreach ($radek as $key => $value) {
                        $this->arrayIterator->offsetSet($key, $value);
                    }
                }
            } elseif ($numRows>1) {
                throw new Projektor_Model_Exception("Nelze vytvořit iterátor objektu Item, výsledek dotazu ".$prep->queryString." má počet řádků: ".$numRows.".");
            }
            return TRUE;  //TODO: vráceno 0 řádek - ??? destroy Item
        } catch (Projektor_Model_Exception $e) {
            echo $e;
        }        
    }
    
    private function hydrateIteratorByDefaults() {
        $sloupce = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce;
        foreach ($sloupce as $sloupec) {
            $this->arrayIterator->offsetSet($sloupec->controllerName, $sloupec->default);
        }    
        return TRUE;
    }
    
    /**
     * Metoda přidá další položku do připravených dat budoucího iterátoru IteratorAggregate, již existující položku (položku se stejným klíčem) přepíše.
     * @param type $key Název iterovatelné vlastnosti
     * @param type $value Hodnota iterovatelné vlastnosti
     */
    protected function addOrReplace($key, $value) {
        $this->attributes[$key] = $value;
        return $value;
    }    
}

?>
