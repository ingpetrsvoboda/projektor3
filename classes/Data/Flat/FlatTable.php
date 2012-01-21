<?php
class Data_Flat_FlatTable extends Data_Iterator {
    public $id;
    public $jmenoTabulky;
    public $objektJeVlastnostiHlavnihoObjektu;
    public $jmenoTabulkyHlavnihoObjektu;
    public $jmenoSloupceIdHlavnihoObjektu;
    //protected
    // vlastnosti databázové tabulky vracené příkazem SHOW COLUMNS
    protected $field = array();         //názvy sloupců tabulky
    protected $type = array();          //datové typy sloupců tabulky
    protected $type_length = array();   //délky datových typů sloupců tabulky
    protected $type_unsigned = array(); //příznak UNSIGNED
    protected $null = array();          //příznak - negace NOT NULL
    protected $key = array();           //příznak KEY - primární klíč PRI, cizí klíč MUL
    protected $default = array();       //default hodnota sloupce
    protected $extra = array();         //další příznaky - typicky auto_increment
    protected $value = array();         //hodnoty ve sloupcích
    // identifikátor a hodnota sloupce, který je auto_increment . pro snazsi pristup
    protected $primaryKeyFieldName;
    protected $primaryKeyFieldValue;

    //semafor pro lazy load načítání z databáze
    protected $precteno_z_db;
    public $chyby;
    private $vsechnyRadky;


    /**
     * Konstruktor objektu Data_Flat_FlatTable - 
     * načte struktury db tabulky z databáze a vytvoří objekt odpovídající zadané db tabulce. 
     * Objekt vytvoří tak, že názvy sloupců, typy, hodnoty ve sloupcích a další jsou uloženy v protected vlastnostech objektu
     * a jsou dostupné prostřednictvím getteru a setteru.
     * @param string $jmenoTabulky Název db tabulky
     * @param boolean $objektJeVlastnostiHlavnihoObjektu TRUE id vytvořených objektů je id hlavního objektu, FALSE id vytvořených objektů je id tabulky
     * @param string $jmenoSloupceIdHlavnihoObjektu Pokud $objektJeVlastnostiHlavnihoObjektu=TRUE musi obsahovat název db sloupce s primárním klíčem v tabulce hlavního objektu
     * @param boolean $vsechnyRadky Konstuktor vytvoří objekt pro všechny řádky tabulky bez ohledu na hodnotu ve sloupci valid
     * @param int $id V závislosti na $objektJeVlastnostiHlavnihoObjektu primární klíč tabulky nebo cizí klíč - id hlavního objektu
     */
    public function __construct($jmenoTabulky, $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoTabulkyHlavnihoObjektu, $jmenoSloupceIdHlavnihoObjektu=NULL, $vsechnyRadky, $id=NULL)
    {
        $this->jmenoTabulky = $jmenoTabulky;
        $this->objektJeVlastnostiHlavnihoObjektu = $objektJeVlastnostiHlavnihoObjektu;
        $this->jmenoTabulkyHlavnihoObjektu = $jmenoTabulkyHlavnihoObjektu;
        $this->jmenoSloupceIdHlavnihoObjektu = $jmenoSloupceIdHlavnihoObjektu;
        $this->id = $id;
        $this->precteno_z_db = false;
        $this->vsechnyRadky = $vsechnyRadky;
        
        $this->chyby = new App_Chyby();
        
        $dbh = App_Kontext::getDbMySQL();
    // Kontrola existence tabulky v databázi
        $query = "SHOW TABLES LIKE :1";
        if (!$dbh->prepare($query)->execute($this->jmenoTabulky)){
            throw new Exception("V databázi neexistuje tabulka ".$this->jmenoTabulky);
        }
    //Nacteni struktury tabulky, datovych typu a ostatnich parametru tabulky
        $query = "SHOW COLUMNS FROM ~1";
        $res= $dbh->prepare($query)->execute($this->jmenoTabulky);
        while ($data = $res->fetch_assoc()){
            array_push($this->field,$data['Field']);
            $type = preg_split("/[()]/",$data['Type']);  // priklady: datetime, int(10) unsigned zerofill, double(8,2) unsigned zerofill
            array_push($this->type,$type[0]);
            if(array_key_exists(1, $type)) {
                array_push($this->type_length,$type[1]);
                if(array_key_exists(2, $type))
                {
                    array_push($this->type_unsigned,true);
                } else {
                    array_push($this->type_unsigned,false);
                }
            } else {
                array_push($this->type_length, 0);  //pro tytpy sloupců bez údaje o délce v závorce, např. datetime
            }
            array_push($this->null,$data['Null']);
            array_push($this->key,$data['Key']);
            array_push($this->default,$data['Default']);
            array_push($this->extra,$data['Extra']);
            if ($data['Key'] == "PRI")
            {
                $this->primaryKeyFieldName = $data['Field'];
            }
        }

           if (!$this->precteno_z_db)
           {
               $this->precti_zaznam();
               parent::__construct(__CLASS__);   //doplni objektu flat table rozhrani iterator
           }
    }
    
    /**
     * Metoda vrací objekt Data_Flat_FlatTable pro řádek tabulky v DB odpovidajíci zadanému id a filtru.
     * @param string $jmenoTabulky Název db tabulky
     * @param int $id V závislosti na $objektJeVlastnostiHlavnihoObjektu primární klíč tabulky nebo cizí klíč - id hlavního objektu
     * @param boolean $objektJeVlastnostiHlavnihoObjektu TRUE zadaní id je chápáno jako id hlavního objektu, FALSE zadané id je chápáno jako id tabulky
     * @param string $jmenoSloupceIdHlavnihoObjektu Pokud $objektJeVlastnostiHlavnihoObjektu=TRUE musi obsahovat název db sloupce s primárním klíčem v tabulce hlavního objektu
     * @param boolean $vsechnyRadky Metoda vrací objekty pro všechny řádky tabulky bez ohledu na hodnotu ve sloupci valid
     * @return object Data_Flat_FlatTable 
     */
    public static function najdiPodleId($jmenoTabulky, $id, $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoTabulkyHlavnihoObjektu, $jmenoSloupceIdHlavnihoObjektu=NULL, $vsechnyRadky = FALSE)
    {
        $dbh = App_Kontext::getDbMySQL();
        // Kontrola existence tabulky v databázi
        $query = "SHOW TABLES LIKE :1";
        if (!$dbh->prepare($query)->execute($jmenoTabulky)){
            throw new Exception("V databázi neexistuje tabulka ".$jmenoTabulky);
        }
        return new Data_Flat_FlatTable($jmenoTabulky, $objektJeVlastnostiHlavnihoObjektu, $jmenoTabulkyHlavnihoObjektu, $jmenoSloupceIdHlavnihoObjektu, $vsechnyRadky, $id); 
    }
    
    /**
     * Metoda vrací pole objektů Data_Flat_FlatTable pro všechny řádky tabulky v DB odpovidajíci zadanému filtru.
     * @param string $jmenoTabulky Název db tabulky
     * @param string $filtr SQL výraz použitý v klauzuli where pro výběr řádků db tabulky, pokud parametr není zadán vrací objekty pro všechny řádky
     * @param string $orderBy Název sloupce db tabulky, polde kterého se řadí výsledky
     * @param string $order ASC nebo DESC - použit pro řazení výsledků
     * @param boolean $objektJeVlastnostiHlavnihoObjektu TRUE id vytvořených objektů je id hlavního objektu, FALSE id vytvořených objektů je id tabulky
     * @param string $jmenoSloupceIdHlavnihoObjektu Pokud $objektJeVlastnostiHlavnihoObjektu=TRUE musi obsahovat název db sloupce s primárním klíčem v tabulce hlavního objektu
     * @param boolean $vsechnyRadky Metoda vrací objekty pro všechny řádky tabulky bez ohledu na hodnotu ve sloupci valid
     * @return array() Pole objektů Data_Flat_FlatTable odpovidajicich radkum v DB
     */    
    public static function vypisVse($jmenoTabulky, $filtr = "",  $orderBy = "", $order = "", $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoTabulkyHlavnihoObjektu="", $jmenoSloupceIdHlavnihoObjektu=NULL, $vsechnyRadky = FALSE)
    {
        $dbh = App_Kontext::getDbMySQL();
        $query = "SELECT ~1 FROM ~2".
                ($filtr == "" ? ($vsechnyRadky ? "" : " WHERE valid = 1") : ($vsechnyRadky ? "WHERE {$filtr} " : "WHERE valid = 1 AND {$filtr}")).
                ($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;            
        if ($objektJeVlastnostiHlavnihoObjektu)
        {
            $jmenoId = $jmenoSloupceIdHlavnihoObjektu;
        } else {            
            //Nacteni názvu sloupce s primárním klíčem tabulky
            $columnsQuery = "SHOW COLUMNS FROM ~1";
            $res= $dbh->prepare($columnsQuery)->execute($jmenoTabulky);
            while ($data = $res->fetch_assoc()){
                if ($data['Key'] == "PRI")
                {
                    $jmenoId = $data['Field'];
                }
            }            
        }
            $radky = $dbh->prepare($query)->execute($jmenoId, $jmenoTabulky)->fetchall_assoc();
            foreach($radky as $radek)
            $vypis[] = new Data_Flat_FlatTable($jmenoTabulky, $objektJeVlastnostiHlavnihoObjektu, $jmenoTabulkyHlavnihoObjektu, $jmenoSloupceIdHlavnihoObjektu, $vsechnyRadky, $radek[$jmenoId]);		 
        return $vypis;
    }      
        
    /**
     * Getter -
     * pro vlastnost je přímo vlastností objektu (nikoli v polích s databázovou tabulkou) vrací hodnotu vlastnosti,
     * pro vlastnost odpovídající sloupci db tabulky (má název a hodnotu v polích s databázovou tabulkou) vrací hodnotu z pole $this->value[],
     * @param type $name
     * @return type 
     */
    public function __get($name)
    {
        //vlastnost je přímo vlastností objektu (nikoli v polích s databázovou tabulkou
         if (property_exists($this, $name)){
            return $this->$name;
         }
        //kontrola existence sloupce se zadaným názvem v tabulce
        $columnId = array_search($name,$this->field);
        if($columnId===false)
        {
// TODO: chyby - dočasně zrušeno
//            $this->chyby->write($name,'',108);
            return false;
        }
        return $this->value[$columnId];  //SVOBODA ?? vrací pro neexistující položku false (jak by mělo)?
    }

     /**
     * Setter - 
     * hodnota vlastnosti, ktera ma odpovidajici sloupec v tabulce je zapsana do pole $this->value[],
     * hodnota ostatnich existujicich vlastnosti objektu je zapsana standardne do vlastnosti,
     * neexistující vlastnost objektu, ktera nema odpovidajici sloupec v db tabulce je nove vytvorena (standard php) a hodnota do ni ulozena.
     * @param type $name
     * @param type $value
     * @return type 
     */
    public function __set($name,$value)
    {   //hodnota ve sloupci autoIncrementFieldName je id zaznamu, hodnota se uklada do vlastnosti autoIncrementFieldValue pro snazsi pristup
        if ($name == $this->primaryKeyFieldName)
        {
            $this->primaryKeyFieldValue = $value;
        }
        else
        {   

            //kontrola existence sloupoe se zadaným názvem v db tabulce
            $columnid = array_search($name,$this->field);
            if($columnid===false)
            {
                if (property_exists($this, $name))
                {
                    $this->$name = $value;
                    return;
                }
                else 
                {
            //neexistující vlastnost objektu, ktera nema odpovidajici sloupec v db tabulce je nove vytvorena (standard php) a hodnota do ni ulozena.
            //ZRUŠENO - navic je ovsem zapsana chba do objektu $this->chyby
//                    $this->chyby->write($name,$value,120);
                    $this->$name = $value;
                    return;
                }
    
            }
            //typové kontroly
            //pokud hodnotu $value nelze do sloupce tabulky v db uložit, nastaví se jako hodnota náhradní hodnota a zapíše se chyba do vlastnosti chyby
            //pokud není default hodnota (neexistuje index v poli default) nastaví se hodnota false
            //SVOBODA - když ani jeden case nenastane, hodnota se nastaví bez kontroly
            //TODO: - typové kontroly buď zahodit (pak nepotřebuješ některé vlastnosti) nebo upravit na pouze ladicí mód - podle vlastnosti debug objektu a pak buď vracet chby a warningy nebo zkusit zapsat, přečíst a porovnat (nebo obojí)
                /**
                 * Typy sloupců MySQL:
                 * tinyint(4), smallint(6), mediumint(9), int(11), int(11), bigint(20), bit(1), double, float, decimal(10,0),
                 * char(255), varchar(55), date, time, year(4), timestamp, datetime, tinyblob, blob, mediumblob, longblob,
                 * tinytext, text, mediumtext, longtext, enum(''), set(''), binary(255), varbinary(255)
                **/

            switch($this->type[$columnid])
            {
                case 'int':
                    if ($value !== "" AND !is_numeric($value)){
                        $this->chyby->write($name,$value,110);
                        $this->value[$columnid] = $this->default[$columnid];    //není číslo - náhradní hodnota je default hodnota sloupce
                    }
                    try{
                        settype($value,"integer");
                    }
                    catch (Exception $e){
                        $this->chyby->write($name,$value,111);
                        $this->value[$columnid] = $this->default[$columnid];
                    }
                    if(!$this->type_unsigned[$columnid] && $value < 0){
                        $this->chyby->write($name,$value,112);
                        $this->value[$columnid] = intval($value);    //je číslo, ale není integer - náhradní hodnota je integer hodnota
                    }
                    break;
                case 'varchar':
                    $value_length = strlen($value);
                    if($value_length > $this->type_length[$columnid]){
                       $this->chyby->write($name,$value,120);
                       $this->value[$columnid] = substr($value,0,$this->type_length[$columnid]);    //řetězec je dlouhý - náhradní hodnota je oříznutý řetězec
                    }
            }
        $this->value[$columnid] = $value;
        }
        return;            
    }
    /**
     * Metoda uloží vlatnosti objektu odpovídající sloupcům db tabulky do databáze. 
     * Pokud je nastavena vlastnost objektu primaryKeyFieldValue, ukládá do řádku s primárním klíčem rovným primaryKeyFieldValue metodou UPDATE (již existující záznam v db),
     * Pokud není nastavena vlastnost objektu primaryKeyFieldValue, ukládá do nového řádku metodou INSERT (nový záznam v db).
     * @return type 
     */
    public function uloz() {
        if($this->objektJeVlastnostiHlavnihoObjektu AND !$this->id) {
             throw new Exception(__CLASS__." ".__METHOD__." Pokousite se ulozit objekt, ktery je vlastnosti hlavniho objektu a neexistuje id hlavniho objektu - data není možno uložit: ".print_r($this->jmenoTabulky));
        }
        if($this->chyby->pocet!=0) {            //SVOBODA - tohle vůbec nevím co dělá
            throw new Exception(__CLASS__." ".__METHOD__." Nalezeny chyby v datech, data není možno uložit: ".print_r($this->chyby));
        }
        if(!$this->primaryKeyFieldValue) {
        // INSERT
            $dbh = App_Kontext::getDbMySQL();
            $query_column_names = "";       //část SQL příkazu INSERT se jmény sloupců
            $query_values = "";             //část SQL příkazu INSERT s daty
            foreach($this->field as $key=>$column_name) {
                if ($column_name != $this->primaryKeyFieldName) {    //neukládá se do sloupce, který je primary key
                    $value = $this->value[$key];                //neukládá se do sloupce, kde není hodnota vlastnosti objektu //TODO: ??? hodnota === NULL ?? abys mohl ukládat nulu nebo prázdný string
                    if($value) {
                        $query_column_names.=$column_name.",";
                        $query_values.="'".$value."',";
                    }
                }
            }
            $query_column_names=substr($query_column_names,0,strlen($query_column_names)-1);
            $query_values=substr($query_values,0,strlen($query_values)-1);
            $query="INSERT INTO ".$this->jmenoTabulky." (".$query_column_names.") VALUES (".$query_values.");";
        } else {
        // UPDATE
            $dbh = App_Kontext::getDbMySQL();
            $query="UPDATE ".$this->jmenoTabulky." SET ";
            foreach($this->field as $column_name) {
                if ($column_name != $this->primaryKeyFieldName) {
                    $value= $this->$column_name ;
                    if($value) {
                        $query.=$column_name." = '".$value."',";
                    }
                }
            }
            $query=substr($query,0,strlen($query)-1);
            $query.=" WHERE ".$this->primaryKeyFieldName." = ".$this->primaryKeyFieldValue;
        }
        $dbh->prepare($query)->execute("");
        return true;
    }

    private function precti_zaznam() {
        // Metoda nacte hodnoty z radku db tabulky do pole $this->value[]
    //SVOBODA metoda přečte záznam z db, ale nezamyká záznam v db - kdokoli může mezi přečtením a zápisem zapsat bo db a při zápisu se pak takováto data přepíší
    //metoda by mohla zamykat, ale asi to chce transakci na celý hlavní objekt
	
        if (!$this->precteno_z_db) {
            $dbh = App_Kontext::getDbMySQL();

            if ($this->objektJeVlastnostiHlavnihoObjektu) {
                if ($this->vsechnyRadky) {
                    $data["valid"] = TRUE;
                } else {
                    $hlavniObjektOuery="SELECT valid FROM ~1 WHERE ~2 = :3";
                    $dataValid = $dbh->prepare($hlavniObjektOuery)->execute($this->jmenoTabulkyHlavnihoObjektu, $this->jmenoSloupceIdHlavnihoObjektu, $this->id)->fetch_assoc();
                }
                if ($dataValid["valid"]) {
                $query="SELECT * FROM ~1 WHERE ~2 = :3";
                    $data = $dbh->prepare($query)->execute($this->jmenoTabulky, $this->jmenoSloupceIdHlavnihoObjektu, $this->id)->fetch_assoc();                    
                }
            } else {
                $query="SELECT * FROM ~1 WHERE ~2 = :3" . ($this->vsechnyRadky ? "" : " AND valid = 1");
                $data = $dbh->prepare($query)->execute($this->jmenoTabulky, $this->primaryKeyFieldName, $this->id)->fetch_assoc();
            }
            if ($data) {
                foreach($this->field as $columnID => $columnName)
                {
                    $this->value[$columnID] = $data[$columnName];
                }
                $this->primaryKeyFieldValue = $data[$this->primaryKeyFieldName]; 
                $this->precteno_z_db = true;
            }                
        }
    }
    
    /**
    * Tato metoda se požívá jen v projektoru2 a to v ind_xxxxxx.inc
    * @return <type>
    */
    public function get_values_assoc() {
        if(!$this->idHlavnihoObjektu){
        //objekt flat table dosud nemá záznam v databázi - nový objekt
            //nastavi vsechny vlastnosti objektu na prazdnou hodnotu daneho typu sloupce v tabulce
            foreach ($this->field as $columnID => $columnName)
            {
                $this->$columnname = default_hodnota($this->type[$columnID]);
            }
        }
        else
        {
        // má id => objekt flat table má záznam v databázi - metoda vrací hodnotu z db
           //lazy load - načte z databáze všechny vlastnosti objektu při prvním přístupu k jakékoli vlastnosti
           if (!$this->precteno_z_db)
           {
               $this->precti_zaznam();
           }
        }

        $assoc_value = array();
        foreach($this->field as $columnID => $columnName) {
            $assoc_value[$columnName] = $this->value[$columnID];
        }
        return $assoc_value;
    }
    
    /**
     * Metoda vrací pole názvů vlastností oodpovídajících sloupcům db tabulky, tedy pole názvů sloupců db tabulky
     * @return array() Pole názvů sloupců db tabulky
     */
    public function dejNazvy() {
    return $this->field;
    }
    
    /**
     * Metoda vrací pole hodnot příznaku KEY (primární klíč PRI, cizí klíč MUL) sloupců db tabulky
     * @return array() Pole hodnot příznaku KEY sloupců db tabulky
     */
    public function dejKlice() {
    return $this->key;
    }

    /**
     * Metoda vrací pole hodnot příznaku TYPE obsahující datové typy sloupců db tabulky. Vrací pouze řetězec odpovídající názvu typu.
     * Typy sloupců MySQL:
     * tinyint(4), smallint(6), mediumint(9), int(11), int(11), bigint(20), bit(1), double, float, decimal(10,0),
     * char(255), varchar(55), date, time, year(4), timestamp, datetime, tinyblob, blob, mediumblob, longblob,
     * tinytext, text, mediumtext, longtext, enum(''), set(''), binary(255), varbinary(255)
     * @return array() Pole hodnot typů sloupců db tabulky
     */    
    public function dejTypy() {
    return $this->type;
    }

    /**
     * Metoda vrací pole hodnot příznaku TYPE_LENGTH obsahující délky datových typů sloupců db tabulky. 
     * Vrací pouze celé číslo odpovídající celkové délce.
     * Typy sloupců MySQL:
     * tinyint(4), smallint(6), mediumint(9), int(11), int(11), bigint(20), bit(1), double, float, decimal(10,0),
     * char(255), varchar(55), date, time, year(4), timestamp, datetime, tinyblob, blob, mediumblob, longblob,
     * tinytext, text, mediumtext, longtext, enum(''), set(''), binary(255), varbinary(255)
     * @return array() Pole celočíselných délek sloupců db tabulky
     */    
    public function dejDelky() {
    return $this->type_length;
    }
    
    
    
    //staré metody - na smazání
//    public function existuje_zaznam_s_context_id() {
//        $ex = false;
//        if(!$this->idHlavnihoObjektu){
//        }
//        else {
//            $dbh = AppContext::getDB();
//            $query="SELECT * FROM ".$this->jmenoTabulky." WHERE id_ucastnik = :1";
//            $data = $dbh->prepare($query)->execute($this->context->id)->fetch_assoc();
//            if  ($data) {
//                 echo "<br>id " . $this->idHlavnihoObjektu  . " existuje v tabulce " . $this->jmenoTabulky;
//                $ex = true;
//            }
//            else {
//                  echo "<br>id " . $this->idHlavnihoObjektu  . " neeexistuje v tabulce " . $this->jmenoTabulky;
//                $ex = false;
//            }
//        }
//        return $ex;
//    }
    
//        private function default_hodnota($type)
//    {
//            //vraci prazdnou hodnotu daneho typu
//            //když ani jeden case nenastane, vlastnost se nastaví na hodnotu 0
//            switch($this->type[$varkey]){
//            case 'int':
//                return 0;
//                break;
//            case 'float':
//                return 0;
//                break;
//            case 'char':
//                return '';
//                break;
//            case 'datetime':
//                return '';
//                break;
//            default:
//                return 0;
//            }
//    }
}

?>