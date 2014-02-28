<?php
/**
 * Description of Collection
 * Kolekce je naplněna vždy jen načtením z db. Třífa nepodporuje manipulaci s jednotlivými prvky kolekce.
 * Není možné měnit prvek kolekce zápisem jiné položky Item, mazat jednotlivé položky kolekce a přidat jednotlivou položky Item do kolekce.
 * Samozřejmě je možné měnit vlastnosti položek Item, ale třída nepodporuje ukládání celé kolekce.
 * @author pes2704
 */
abstract class Projektor_Model_Collection implements IteratorAggregate {
    
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
     * Tato vlastnost je použita vždy, když se vytváří objekt - tedy v metodě __construct() a __wakeup().
     * @var string 
     */
    protected $database;
    
    /**
     * vlastnost pro uložení objektu Projektor_Model_Sql s připraveným sql dotazem pro načítání dat
     * Je public, je nastavována metodami Collection a uplatní se při načítání dat v CollectionIterator
     * @var type
     */
    protected $sqlSelect;
   
    /**
     * Vlastnost pro uložení řetězce select pro jednotlivé Item. Select pro Item určuje seznam sloupců objektu Item načítaných z db.
     * @var string 
     */
    protected $selectAttributes;
    
    /**
     * Iterátor vracený metodou getIterator (implementace IteratorAggregate).
     * Současně tato vlastnost slouží jako semafor pro lazy load dat z databáze v metodě ItemIterator->getIterator().
     * @var ArrayIterator
     */
    protected $arrayIterator;
    
    /**
     * Vlastnost rozlišuje nově vytvořený prázdný objekt, který nebude načítán z databáze (hodnota je FALSE)
     *  a již dříve uložený, "starý" objekt, který bude z databáze načten
     * @var type
     */
    protected $nactiData;
    
    /**
     * Semafor
     * @var type
     */
    protected $dataNactena;

    /**
     * Konstruktor vytvoří a vrátí novou prázdnou kolekci a součsně připraví načtení položek iterátoru z databáze (lazy load v metodě CollectionIterator getIterator()).
     * Je však možné volat metody kolekce where(), filter(), order() kdykoli až do okamžiku skutečného načtení dat z databáze, tedy do prvního pokusu o čtení
     * některé vlastnosti objektu, data z db jsou načítána lazy load až v tomto okamřiku a filtry a řazení nastavené těmito metodami se uplatní
     * @return \static
     */
    public function __construct(Framework_Application_StatusInterface $appStatus=NULL) {
        $this->nactiData = TRUE;  //semafor pro lazy load z databáze
        // získání struktury db tabulky z údajů třídy item
        $itemClassName = static::NAZEV_TRIDY_ITEM;
        $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA); //Projektor_Model_Auto_Cache_StrukturaTabulky
        $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;
        $this->database = $itemClassName::DATABAZE;
        $this->dbh = Projektor_Container::getDbh($this->database);                
        $this->sqlSelect = new Projektor_Model_Sql_Select($this->database);        
        $this->sqlSelect->select = $nazevSloupcePK;
        $this->sqlSelect->from = $itemClassName::TABULKA;
        if (isset($strukturaTabulky->sloupce["valid"])) {
            $this->sqlSelect->validFilter = Projektor_Model_Sql_Select::DEFAULT_VALID_FILTER;
        }
        
        if ($appStatus->userKontext) {
            
        }
        
    }
    
    /**
     * Zablokování serializace objektu - pokus o serializaci vrací serializovanou podobu NULL, tj. "N;"
     * Objekt obsahuje potomka PDO, PDO není serializovatelné a objekt obsahuje CITLIVÁ data - údaje o připojekní k databázi
     * @return null
     */
    public function __sleep() {       
        return array('database','sqlSelect','selectAttributes','arrayIterator','nactiData','dataNactena');
    }
    
    public function __wakeup() {
        $this->dbh = Projektor_Container::getDbh($this->database);
    }
    
    /**
     * Metoda vytvoří a vrátí novou prázdnou kolekci, která se nebude načítat z databáze
     * Položky do kolekce je možno přidávat metodou CollectionIterator add()
     * @return \static
     */
    public static function factory() {
        $class = new static;  //vytvoří nyvý POTOMKOVSKÝ objekt
        $class->nactiData = FALSE;  //semafor pro lazy load z databáze
        return $class;
    }

    /**
     * Metoda nastaví semafory pro lazy load dat z databáze, tak aby došlo k načtení dat.
     * Nastaví $this->nactiData = TRUE; ( (semafor pro lazy load z databáze) a 
     * $this->dataNactena = FALSE; (semafor pro lazy load z databáze)
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
            $this->sqlSelect->validFilter = "";
        } else {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            if (isset(Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce["valid"])) {
                $psqlClassName = get_class($this->sqlSelect);
                $this->sqlSelect->validFilter = $psqlClassName::DEFAULT_VALID_FILTER;
            }
        }
    }

    /**
     * Metoda nastaví filtr WHERE a vrací řetězec právě vytvořeného filtru.
     * Pokud byl již filtr nastaven, přidá další podmínku s operátorem AND, například: podmínka1 AND podmínka2.
     * Nové nastavení filtru způsobí, že každé další čtení/zápis vlastnosti objektu vyvolá nové načtení dat z databáze s novým filtrem
     *
     * Příklady:
     * where("vek", "=", 2) => "(~vek = :vek)" a parametry ~vek="vek", :vek=2
     * where("jmeno", "LIKE", "Adam", FALSE, TRUE) => "(~jmeno LIKE :jmeno)" a parametry ~jmeno="jmeno", :jmeno="Adam%"
     * where("pismeno", "IV", array("A","B")) => "(~pismeno IN (:pismeno1, :pismeno2))" a parametry ~pismeno="pismeno", :pismeno1="A", :pismeno2="B"
     * 
     * @param string $nazevVlastnosti Název sloupce db tabulky nebo vlastnosti autocode generované vlastnosti
     * @param string $podminka Řetězec SQL logického operátoru nebo klíčové slovo LIKE (např. "=" nebo ">" nebo "<=" nebo "LIKE")
     * @param mixed $hodnota Skalární hodnota
     * @param bool $otevreneZleva Parametr se uplatní v podmínce LIKE, pokud je TRUE je retězec za klíčovým slovem LIKE doplněn o znak% vpravo (např. LIKE Adam%)
     * @param bool $otevreneZprava Parametr se uplatní v podmínce LIKE, pokud je TRUE je retězec za klíčovým slovem LIKE doplněn o znak% vlevo (např. LIKE %Adam)
     * @return string Hodnota aktuálního filtru where
     * @throws Projektor_Model_Exception
     */
    public function where($nazevVlastnosti = NULL, $podminka = NULL, $hodnota = NULL, $otevreneZleva=NULL, $otevreneZprava=NULL) {
        try {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            if (  ($nazevVlastnosti AND is_string($nazevVlastnosti))
                   AND ($podminka AND is_string($podminka))
                   AND (isset($hodnota)) ) {
                $nazev = $itemClassName::dejNazevSloupceZVlastnosti($nazevVlastnosti);
                if ($nazev) {
                    $this->sqlSelect->where($nazev, $podminka, $hodnota, $otevreneZleva, $otevreneZprava);
                    return $this->sqlSelect->where;
                } else {
                    throw new Projektor_Model_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
                }
            } else {
                throw new Projektor_Model_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti
                        ." není řetězec nebo zadaná podmínka: ".$podminka." není řetězec nebo není zadaná hodnota: ".$hodnota.".");
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
        }
    }

    public function filter($filter = NULL) {
        if ($filter) $this->sqlSelect->filter = $filter;
    }

    public function validFilter($validFilter = NULL) {
        if ($validFilter) $this->sqlSelect->validFilter = $validFilter;
    }
    
    public function order($nazevVlastnosti = NULL, $order = 'ASC') {
        try {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            //TODO: kontrola na string atd. patří do objektu sql - přesuň a výjimka bude asi jiná (invalid parameter)
            if ($nazevVlastnosti AND is_string($nazevVlastnosti) AND $order AND is_string($order) AND ($order=='ASC' OR $order=='DESC')) {
                $strukturaSloupce = $itemClassName::dejStrukturuSloupce($nazevVlastnosti);
                if (c)
                {
                    $this->sqlSelect->order($strukturaSloupce->controllerName, $order);
                    return $this->sqlSelect->order;
                } else {
                    throw new Projektor_Model_Exception("Klausule ORDER nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
                }
            } else {
                throw new Projektor_Model_Exception("Klausule ORDER nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." není řetězec nebo zadané řazení: ".$order." není řetězec nebo má jinou hodnotu než ASC/DESC." );
            }
        } catch (Projektor_Model_Exception $e)
        {
            echo $e;
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
     * ->select(): Objekty Item budou obsahovat pouze sloupec id_tabulka
     * ->select(array('aaa', 'bbb')): Objekty Item budou obsahovat sloupce id_tabulka, aaa, bbb  \n
     * ->select(array('*')): Objekt Item bude obsahovat všechny sloupce db tabulky. Je třeba použít jen pokud jste předtím nastavili jiný select a nyní chcete, aby Item měl všechny vlastnosti. 
     * Pokud metodu selectAttributes() nevoláte vůbec, objekty Item mají všechny vlastnosti defaulně.\n
     * Metoda automaticky převádí názvy vlastností na názvy sloupců. Pokud parametr $nazvy není zadán, je použita 
     * default hodnota *, tedy select vybírá všechny sloupce db tabulky (SELECT * FROM ...). Zadané názvy sloupců nebo vlastností jsou převedeny na korektnmí název 
     * identifikátory v SQL automaticky podle typu databáze, ze které je objekt Item načítán (např. pro MySQL je obalen databázovými apostrofy, 
     * pro MSSQL uzavřen do hranatých závorek), jako náze může být teedy použit žetězec s mezerami.
     * @param array $nazvy
     * @return string
     */
    public function selectAttributes(array $nazvy=NULL) {
        if ($nazvy) {
            $itemClassName = static::NAZEV_TRIDY_ITEM;            
            if ($nazvy == array('*')) {
                $nazvySloupcuDb = $nazvy;
            } else {
                $nazvySloupcuDb = array();                
                if (!array_key_exists($itemClassName::dejPrimaryKeyFieldName(), $nazvy)) {
                    $nazvy = array_merge (array(0=>$itemClassName::dejPrimaryKeyFieldName()), $nazvy);
                }
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
        $this->nactiData = TRUE;  //semafor pro lazy load z databáze
        $this->selectAttributes = implode(", ", $nazvySloupcuDb);
        return $this->selectAttributes;        
    }

############### STATICKÉ PUBLIC METODY (INFORMAČNÍ) #############################################################

    /**
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) vytvoří název sloupce,
     * pro ostatní vlastnosti použije zadaný název beze změny jako název sloupce.
     * Vrací kolekci Collection řádkových objektů Item pro db tabulku referencovanou zadaným sloupcem, pro sloupec který není referencí (není cizí klíč) vrací FALSE
     * Pro neexistující název sloupce nebo pokud se nepodaří nalézt třídu pro vytvoření kolekce odpovídající referencované tabulce metoda vyhodí výjimku
     * @param type $nazevVlastnosti
     * @return boolean|\collectionClassName
     * @throws Projektor_Model_Exception
     */
    public static function dejReferencovanouKolekci($nazevVlastnosti) {
        $nazevItem = static::NAZEV_TRIDY_ITEM;
        $item = $nazevItem::factory();
        return $item->dejReferencovanouKolekci($nazevVlastnosti);
    }

    /**
     * Metoda vrací název sloupce db tabulky odpovídající zadané vlastnosti, pokud neexistuje odpovídající sloupec, vrací FALSE.
     * Pro vlastnost generovanou jako id (viz konfigurace aplikace Framework_Config) vrací název primárního klíče.
     * Příklad: prefix="dbField°", tabulka s primárním klíčem "id_akce": pro název "dbField°text" vrací "text", pro název "text" vrací "text", pro název "id" vrací "id_akce"
     * @param type $nazevVlastnosti Název vlastnosti objektu
     * @return string Název sloupce db tabulky
     */
    public static function dejNazevSloupceZVlastnosti($nazevVlastnosti) {
        $itemClassName = static::NAZEV_TRIDY_ITEM;
        return $itemClassName::dejNazevSloupceZVlastnosti($nazevVlastnosti);
    }
############### getIterator ###############################################    
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
            $newArrayIterator->rewind;
            return $newArrayIterator;
        } else {
            $this->createHydratedIterator();
            return $this->arrayIterator;
        }
    }

    private function createHydratedIterator () {
        $this->arrayIterator = new ArrayIterator();
        $this->hydrateIteratorByData();     
    }
    //                if ($success) {
//                    $this->items = array();
//                    $this->count = 0;
//                    $radkyId = $prep->fetchAll(PDO::FETCH_ASSOC);  //pole radku s polozkou $nazevSloupcePK (hodnoty primárního klíče)
//                    //připraví položky s datovými objekty Item připravenými pro lazy load
//                    foreach($radkyId as $r => $radekId) {
//                        $item = new $itemClassName($radekId[$nazevSloupcePK]);
//                        if ($this->selectAttributes) {
//                            $item->sqlSelect->select = $this->selectAttributes;
//                        }
//                        $vf = $this->sqlSelect->validFilter;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
//                        $item->sqlSelect->validFilter = $vf;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
//                        $this->add($item);
//                    }
//                    $this->dataNactena = TRUE;
//                }
    private function hydrateIteratorByData() {
        $prep = $this->sqlSelect->getPreparedStatement();
        //execute
        $success = $prep->execute();
        //kontrola a přidání položek do iterátoru
        $numRows = $prep->rowCount();
        $radkyId = $prep->fetchAll(PDO::FETCH_ASSOC);  //radek
        if ($radkyId) {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA);
            $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;
            foreach ($radkyId as $i => $radekId) {
                $item = new $itemClassName($radekId[$nazevSloupcePK]);
                if ($this->selectAttributes) {
                    $item->sqlSelect->select = $this->selectAttributes;
                }
                $item->sqlSelect->validFilter = $this->sqlSelect->validFilter;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
                $this->arrayIterator->append($item);
            }
        }     
    }

//    /**
//     * 
//     * @return \ArrayIterator
//     */
//    public function getIterator() {
//        if ($this->nactiData) {
//            // získání struktury db tabulky z údajů třídy item
//            $itemClassName = static::NAZEV_TRIDY_ITEM;
//            $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA);
//            $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;
//
//            // sestavení dotazu
//            $query = $this->sqlSelect->getSql();
//            //prepare
//            $prep = $this->dbh->prepare($query);
//            //bind params
//            if ($this->sqlSelect->params) {
//                foreach ($this->sqlSelect->params as $param => $value) {
//                    $prep->bindParam($param, $value);
//                }
//            }
//            //execute - jen pro tabulky s primárním klíčem
//            if ($nazevSloupcePK) {   //kupodivu jsou i tabulky bez primárního klíče
//                $success = $prep->execute();
//                //kontrola a naplnění položek IteratorAggregate
//                if ($success) {
//                    $this->items = array();
//                    $this->count = 0;
//                    $radkyId = $prep->fetchAll(PDO::FETCH_ASSOC);  //pole radku s polozkou $nazevSloupcePK (hodnoty primárního klíče)
//                    //připraví položky s datovými objekty Item připravenými pro lazy load
//                    foreach($radkyId as $r => $radekId) {
//                        $item = new $itemClassName($radekId[$nazevSloupcePK]);
//                        if ($this->selectAttributes) {
//                            $item->sqlSelect->select = $this->selectAttributes;
//                        }
//                        $vf = $this->sqlSelect->validFilter;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
//                        $item->sqlSelect->validFilter = $vf;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
//                        $this->add($item);
//                    }
//                    $this->dataNactena = TRUE;
//                }
////                    unset($result);
//            }
//        } else {
//            //prázdná kolekce
//        }
////        return new Projektor_Model_Iterator($this->items);
//        return new ArrayIterator($this->items);  //tohle je asi o 15% rychlejší
//    }
//
//    public function add($value) {
//        try {
//            if (get_class($value)==static::NAZEV_TRIDY_ITEM) {
//                $this->items[$this->count++] = $value;
//            } else {
//                throw new Projektor_Model_Exception("Nelze přidat položku do IteratorAggregate třídy ".__CLASS__.
//                        ", lze přidávat pouze položky typu ".static::NAZEV_TRIDY_ITEM.". Zadaná hodnotu. je typu: ".get_class($value).".");
//            }
//        } catch (Projektor_Model_Exception $e) {
//            echo $e;
//        }
//    }

    public function __get($nazevVlastnosti) {
        if (isset($this->$nazevVlastnosti)) {
            return $this->$nazevVlastnosti;
        } 
    }
}
?>
