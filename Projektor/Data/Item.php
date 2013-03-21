<?php
/**
 * Projektor_Data_Item
 *
 * @author pes2704
 */
abstract class Projektor_Data_Item extends Projektor_Data_ItemIterator implements Projektor_Data_Auto_ItemInterface
{
    /**
     * Statická vlastnost pro uložení preficu názvů vlastností objektu odpovídajících sloupcům db tabulky
     * Je nastavována v metodě dejDbFieldPrefix().
     * @var type
     */
    protected static $dbFieldPrefix;

    protected static $objectIdName;

    /**
     * Vlastnost rozlišuje nově vytvořený prázdný objekt, který nebude načítán z databáze (hodnota je FALSE)
     *  a již dříve uložený, "starý" objekt, který bude z databáze načten
     * @var type
     */
    protected $nactiData;

    /**
     * Vlastnost pro uložení objektu Projektor_Data_Sql s připraveným sql dotazem pro načítání dat
     * Je public, je nastavována metodami Item a uplatní se při načítání dat v ItemIterator
     * @var type
     */
    protected $preparedSql; //?public

    /**
     * Semafor pro lazy load v metodě ItemIterator->getIterator()
     * @var type
     */
    protected $dataNactena;

    /**
     * Semafor pro metodu $this->uloz()
     * @var type
     */
    protected $dataZmenena;

    /**
     * Konstruktor vytvoří a vrátí objekt pro jeden radek tabulky v DB, volání konstruktoru je určeno pro vytvoření objektu, který vždy bude načítán z databáze.
     * Pokud je zadán parametr $is, konstruktor vytvoří objekt s prislusnym id.
     * Pokud $id není zadáno, je nutné před prvním pokusem o čtení dat z objektu zadat vhodný filt where, který zajistí načtení je jednoho řádku db tabulky.
     * Jinak nedojde k načtení dat.
     * @param int $id Identifikator radku tabulky
     * @return Instance tridy potomka Projektor_Data_Item připravená na lazy load načtení dat z databáze
     */
    public function __construct($id = NULL) {
        $this->reset();

        $this->nactiData = False;  //semafor pro lazy load z databáze, na TRUE se nastavuje v metodě where
        $this->preparedSql = new Projektor_Data_Sql();
        $this->preparedSql->select = "*";
        $this->preparedSql->from = static::TABULKA;
        $strukturaTabulky = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA);
        if ($id) {
            $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;
            $this->where($nazevSloupcePK, "=", $id);
        }
        if (isset($strukturaTabulky->sloupce["valid"])) {
            $psqlClassName = get_class($this->preparedSql);
            $this->preparedSql->validFilter = $psqlClassName::DEFAULT_VALID_FILTER;
        }
    }

    /**
     * Wakeup funkce
     * Při deserializaci se vytváří nový objekt Item a proto je třeba zavolat metodu reset(), aby u deserializovaného objektu
     * docházelo k volání metody __get(). Vlastnosti deserializovaného objektu jsou public pro našeptávání a bez zavolání reset()
     * se __get() nevolá.
     */
    public function __wakeup() {
        $this->reset();
    }

    /**
     * Metoda vytvoří a vrátí nový prázdný item, který se nebude načítat z databáze
     * @return \static
     */
    public static function factory() {
        $class = new static;  //vytvoří nyvý POTOMKOVSKÝ objekt
        $class->nactiData = FALSE;  //semafor pro lazy load z databáze
        return $class;
    }

    /**
     *
     * @return \static
     */
    public function nacti() {
        $this->nactiData = TRUE;  //semafor pro lazy load z databáze
        $this->dataNactena = FALSE;  //semafor pro lazy load z databáze
    }

    /**
     * Pokud parametr je TRUE nebo není zadán metoda nastaví filtr tak, že při načítání dat do Collection nebo Item budou načítány i řádky s hodnotou valid=0 (všechny řádky tabulky,
     * bez nastavení filtru jsou načítány jen řádky s hodnotou valid=1 (nesmazané).
     * Pokud parametr je FALSE metoda vrátí nastavení filtru na výchozí a budou načítaány jen řádky s hodnotou valid=1 (nesmazané).
     * @param type $vsechnyRadky
     */
    public function vsechnyRadky($vsechnyRadky = TRUE) {
        if ($vsechnyRadky) {
            $this->preparedSql->validFilter = "";
        } else {
            if (isset(Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce["valid"])) {
                $psqlClassName = get_class($this->preparedSql);
                $this->preparedSql->validFilter = $psqlClassName::DEFAULT_VALID_FILTER;
            }
        }
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
     * @throws Projektor_Data_Exception
     */
    public function where($nazevVlastnosti = NULL, $podminka = NULL, $hodnota = NULL, $otevreneZleva=NULL, $otevreneZprava=NULL)
    {
        try {
            if (  ($nazevVlastnosti AND is_string($nazevVlastnosti))
                   AND ($podminka AND is_string($podminka))
                   AND (isset($hodnota))
                ) {
                $nazev = self::dejNazevSloupceZVlastnosti($nazevVlastnosti);
                if ($nazev) {
                    $this->preparedSql->where($nazev, $podminka, $hodnota, $otevreneZleva, $otevreneZprava);
                    $this->nactiData = TRUE;  //semafor pro lazy load z databáze
                    return TRUE;
                } else {
                    throw new Projektor_Data_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
                }
            } else {
                throw new Projektor_Data_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti
                        ." není řetězec nebo zadaná podmínka: ".$podminka." není řetězec nebo není zadaná hodnota: ".$hodnota.".");
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    public function filter($filter = NULL) {
        if ($filter) $this->preparedSql->filter = $filter;
    }

    public function uloz()
    {
        if (!$this->dataZmenena) return FALSE;

        $nazecSloupcePK = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->primaryKeyFieldName;
        if(!$this->nactiData) {
            try {
                // INSERT
                $query_column_names = "";       //část SQL příkazu INSERT se jmény sloupců
                $query_values = "";             //část SQL příkazu INSERT s daty
                $params = array();
                foreach ($this as $nazevSloupceDb => $hodnota)
                {
                    $strukturaSloupce = self::dejStrukturuSloupce($nazevSloupceDb);
                    if ($strukturaSloupce->klic!="PK" OR $strukturaSloupce->extra!="auto_increment") { // do sloupce s PK typu autoincrement nezapisuje
                        if ($hodnota) {
                            if ($query_column_names) {
                                $query_column_names .= ",";
                                $query_values .=",";
                            }
                            $query_column_names .= " ~".$nazevSloupceDb;
                            $query_values .=" :".$nazevSloupceDb;
                        }
                    }
                    $params["~".$nazevSloupceDb] = $nazevSloupceDb;
                    $params[":".$nazevSloupceDb] = $hodnota;
                }
            } catch (Projektor_Data_Exception $e) {
                echo $e;
            }
            $query="INSERT INTO ".static::TABULKA." (".$query_column_names.") VALUES (".$query_values.");";
        } else {
            if (!$this->dataNactena) {
                $iterator = $this->getIterator();  //načte item
            }
            // UPDATE
            $query = "";
            $params = array();
            foreach ($this as $nazevSloupceDb=>$hodnota) {
                if ($hodnota AND $nazevSloupceDb!=$nazecSloupcePK) {
                    if ($query) {
                        $query .= ",";
                    }
                    $query .= " ~".$nazevSloupceDb."=:".$nazevSloupceDb;
                    $params["~".$nazevSloupceDb] = $nazevSloupceDb;
                    $params[":".$nazevSloupceDb] = $hodnota;
                }
            }

            $query ="UPDATE ".static::TABULKA." SET ".$query;
            $query .=" WHERE ".$nazecSloupcePK." = ".$this->$nazecSloupcePK;
        }
        $dbh = Projektor_App_Container::getDbh(static::DATABAZE);
        $prep = $dbh->prepare($query);
        if ($params) {
            foreach ($params as $param => $value) $prep->bindParam($param, $value);
        }
        $res = $prep->execute("");
        return $res->affectedRows();
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
                $nazevSloupceDb = $strukturaSloupce->nazev;
                $this->addOrReplace($nazevSloupceDb, $value);
                $this->dataZmenena = TRUE;
                return $value;
            } else {
                throw new Projektor_Data_Exception("Není možné nastavovat vlastnost objektu, která neodpovídá sloupci v db tabulce: ".$name);
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    /**
     * Getter - hodnota vlastnosti, která má odpovídající sloupec v db tabulce je načtena z Iterátoru a vrací se.
     * Pokud dosud nebyla načtena data z dtabáze (lazy load) volání této metody je načte.
     * Pro vlastnost generovanou jako id (viz konfigurace aplikace Projektor_App_Config) vrací hodnotu primárního klíče.
     * @param type $nazevVlastnosti
     * @return null
     */
    public function __get($nazevVlastnosti) {
        if (isset($this->$nazevVlastnosti)) {
            return $this->$nazevVlastnosti;
        } else {
            $nazevSloupceDb = self::dejNazevSloupceZVlastnosti($nazevVlastnosti);
            $iterator = $this->getIterator();  //načte item
            return $iterator->$nazevSloupceDb;
        }
    }

############### PUBLIC METODY (INFORMAČNÍ) #############################################################

    /**
     * Metoda vrací název sloupce db tabulky odpovídající zadané vlastnosti, pokud neexistuje odpovídající sloupec, vrací FALSE.
     * Pro vlastnost generovanou jako id (viz konfigurace aplikace Projektor_App_Config) vrací název primárního klíče.
     * Příklad: prefix="dbField°", tabulka s primárním klíčem "id_akce": pro název "dbField°text" vrací "text", pro název "text" vrací "text", pro název "id" vrací "id_akce"
     * @param type $nazevVlastnosti Název vlastnosti objektu
     * @return string Název sloupce db tabulky
     */
    public static function dejNazevSloupceZVlastnosti($nazevVlastnosti) {
        if ($nazevVlastnosti==self::dejObjectIdName()) {
            return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->primaryKeyFieldName;
        } else {
            $nazevSloupceDb = str_replace(self::dejDbFieldPrefix(), "", $nazevVlastnosti);
            if (isset(Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce[$nazevSloupceDb])) return $nazevSloupceDb;
            return FALSE;
        }
    }

    /**
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) vytvoří název sloupce,
     * ostatní vlastnosti považuje přímo za název sloupce.
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) vytvoří název sloupce,
     * pro vlastnost generovanou jako id (viz konfigurace aplikace Projektor_App_Config) použije sloupec primárního klíče.
     * pro ostatní vlastnosti použije zadaný název beze změny jako název sloupce.
     * Vrací strukturu sloupce db tabulky (objekt Projektor_Data_Auto_Cache_StrukturaSloupce), pro neexistující sloupec vrací FALSE
     * @param type $nazevVlatnosti
     * @return Projektor_Data_Auto_Cache_StrukturaSloupce/boolean
     */
    public static function dejStrukturuSloupce($nazevVlatnosti) {
        $nazevSloupceDb = self::dejNazevSloupceZVlastnosti($nazevVlatnosti);
        // kontrola existence sloupoe se zadaným názvem v db tabulce
        if ($nazevSloupceDb) {
            $strukturaSloupce = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce[$nazevSloupceDb];
            if (isset($strukturaSloupce) AND $strukturaSloupce) return $strukturaSloupce;
        }
        return FALSE;
    }

    /**
     * Metoda vrací hodnotu uloženou ve statické vlastnosti $objectIdName, pokud není uložena hodnotu nejprve načte a do vlastnosti uloží.
     * Pokud vlastnost nelze načíst, metoda vyhodí výjimku.
     * @throws Exception
     */
    public static function dejObjectIdName() {
        if (!self::$dbFieldPrefix) {
            $autocodeConfig = Projektor_App_Config::najdiSekciPodleJmena(Projektor_App_Config::SEKCE_AUTOCODE);
            if (!$autocodeConfig->objectidname) throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Projektor_App_Config::XML_INI." v sekci ".Projektor_App_Config::SEKCE_AUTOCODE." není definován objectidname: ".$autocodeConfig->objectidname);
            self::$objectIdName = $autocodeConfig->objectidname;
        }
        return self::$objectIdName;
    }

    /**
     * Metoda vrací hodnotu uloženou ve statické vlastnosti $dbfieldprefix, pokud není uložena hodnotu nejprve načte a do vlastnosti uloží.
     * Pokud vlastnost nelze načíst, metoda vyhodí výjimku.
     * @return string
     * @throws Projektor_Data_Exception
     */
    public static function dejDbFieldPrefix() {
        if (!self::$dbFieldPrefix) {
            $autocodeConfig = Projektor_App_Config::najdiSekciPodleJmena(Projektor_App_Config::SEKCE_AUTOCODE);
            if (!$autocodeConfig->dbfieldprefix) throw new Projektor_Data_Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Projektor_App_Config::XML_INI." v sekci ".Projektor_App_Config::SEKCE_AUTOCODE." není definován dbfieldprefix: ".$autocodeConfig->dbfieldprefix);
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
     * @throws Projektor_Data_Exception
     */
    public function dejReferencovanouKolekci($nazevVlastnosti) {
        try {
            $strukturaSloupce = self::dejStrukturuSloupce($nazevVlastnosti);
            if ($strukturaSloupce) {
                if ($strukturaSloupce->klic=="FK") {
                    $collectionClassName = self::dejNazevTridyCollectionZTabulky($strukturaSloupce->referencovanaTabulka);
                    if ($collectionClassName) {
                        $refCollection = new $collectionClassName();
                        return $refCollection;
                    } else {
                        throw new Projektor_Data_Exception("neexistuje třída Collection: ".$collectionClassName." pro referencovanou tabulka: ".$strukturaSloupce->referencovanaTabulka.". Zadaná vlastnost objektu: ".$nazevVlastnosti." odpovídá sloupci v db tabulce: ".$strukturaSloupce->nazev.
                                " což je cizí klíč: ".$strukturaSloupce->klic." referencované tabulky.");
                    }
                } else {
                    return  FALSE;  //sloupec není FK
                }
            } else {
                if (is_subclass_of($this, "Projektor_Data_HlavniObjektItem")) {
                    return FALSE; //class je potomek Projektor_Data_HlavniObjektItem - podrízená vlastnost hlavního objektu nemá odpovídající sloupec v tabulce
                } else {
                    throw new Projektor_Data_Exception("Zadaná vlastnost objektu: ".$nazevVlastnosti." neodpovídá sloupci v db tabulce: ".static::TABULKA);
                }
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    public function dejReferencovanýItem($nazevVlastnosti)
    {
        try {
            $strukturaSloupce = self::dejStrukturuSloupce($nazevVlastnosti);
            if ($strukturaSloupce) {
                if ($strukturaSloupce->klic=="FK") {
                    $itemClassName = self::dejNazevTridyItemZTabulky($strukturaSloupce->referencovanaTabulka);
                    if ($itemClassName){
                        return new $itemClassName($this->$nazevVlastnosti);// pokud $nazevVlastnosti odpovida sloupoci s FK, $datovyObjekt->$nazevVlastnosti je hodnota tohoto FK, tedy id referencovaného objektu
                    } else {
                        throw new Projektor_Data_Exception("neexistuje třída Collection: ".$itemClassName." pro referencovanou tabulka: ".$strukturaSloupce->referencovanaTabulka.". Zadaná vlastnost objektu: ".$nazevVlastnosti." odpovídá sloupci v db tabulce: ".$strukturaSloupce->nazev.
                                " což je cizí klíč: ".$strukturaSloupce->klic." referencované tabulky.");
                    }
                } else {
                    return  FALSE;  //sloupec není FK
                }
            } else {
                if (is_subclass_of($this, "Projektor_Data_HlavniObjektItem")) {
                    return FALSE; //class je potomek Projektor_Data_HlavniObjektItem - podrízená vlastnost hlavního objektu nemá odpovídající sloupec v tabulce
                } else {
                    throw new Projektor_Data_Exception("Zadaná vlastnost objektu: ".$nazevVlastnosti." neodpovídá sloupci v db tabulce: ".static::TABULKA);
                }
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
            return FALSE;
        }
    }
################ PRIVÁTNÍ METODY ############################

    private static function dejNazevTridyCollectionZTabulky($tabulka) {
        try {
            if (is_string($tabulka) AND $tabulka) {
                $calledClass = get_called_class();
                $collectionClassName = substr($calledClass, 0, strrpos($calledClass, "_"));
                $collectionClassName .= "_".str_replace(" ", "", ucwords(str_replace("_", " ", $tabulka)))."Collection";
                return $collectionClassName;
            } else {
                    throw new Projektor_Data_Exception("Zadaný název tabulky ".$tabulka." není řetězec nebo je prázdný.");
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
            return FALSE;
        }
    }

    private static function dejNazevTridyItemZTabulky($tabulka) {
        try {
            if (is_string($tabulka) AND $tabulka) {
                $calledClass = get_called_class();
                $collectionClassName = substr($calledClass, 0, strrpos($calledClass, "_"));
                $collectionClassName .= "_".str_replace(" ", "", ucwords(str_replace("_", " ", $tabulka)))."Item";
                return $collectionClassName;
            } else {
                    throw new Projektor_Data_Exception("Zadaný název tabulky ".$tabulka." není řetězec nebo je prázdný.");
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
            return FALSE;
        }
    }
}

?>
