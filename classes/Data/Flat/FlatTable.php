<?php
class Data_Flat_FlatTable extends Data_Iterator {
    public $id;
    public $jmenoTabulky;
    public $databaze;
    public $objektJeVlastnostiHlavnihoObjektu;
    public $jmenoTabulkyHlavnihoObjektu;
    public $jmenoSloupceIdHlavnihoObjektu;
    //protected

    protected $value = array();         //hodnoty ve sloupcích
    // identifikátor a hodnota primárního klíče - pro snazsi pristup
    protected $primaryKeyFieldValue;

    //semafor pro lazy load načítání z databáze
    protected $precteno_z_db;
    public $chyby;
    private $vsechnyRadky;

    //název sloupce v db, který označuje zda řádek je platný
    const VALID = "valid";
    
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
    public function __construct($databaze, $jmenoTabulky, $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoTabulkyHlavnihoObjektu=NULL, $jmenoSloupceIdHlavnihoObjektu=NULL, $vsechnyRadky=0, $id=NULL)
    {
        $this->databaze = $databaze;
        $this->jmenoTabulky = $jmenoTabulky;
        $this->objektJeVlastnostiHlavnihoObjektu = $objektJeVlastnostiHlavnihoObjektu;
        $this->jmenoTabulkyHlavnihoObjektu = $jmenoTabulkyHlavnihoObjektu;
        $this->jmenoSloupceIdHlavnihoObjektu = $jmenoSloupceIdHlavnihoObjektu;
        $this->id = $id;
        $this->precteno_z_db = false;
        $this->vsechnyRadky = $vsechnyRadky;
        
        $this->chyby = new App_Chyby();
        


        if (!$this->precteno_z_db)
        {
//TODO: try $this->precti_zaznam(); -> výjimka
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
     * @param boolean $vsechnyRadky Pokud je TRUE, metoda vrací objekty pro všechny řádky tabulky bez ohledu na hodnotu ve sloupci valid
     * @param $dbh handler databáze
     * @return object Data_Flat_FlatTable 
     */
    public static function najdiPodleId($databaze, $jmenoTabulky, $id, $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoTabulkyHlavnihoObjektu="", $jmenoSloupceIdHlavnihoObjektu=NULL, $vsechnyRadky = FALSE)
    {
        return new Data_Flat_FlatTable($databaze, $jmenoTabulky, $objektJeVlastnostiHlavnihoObjektu, $jmenoTabulkyHlavnihoObjektu, $jmenoSloupceIdHlavnihoObjektu, $vsechnyRadky, $id); 
    }
    
    /**
     * Metoda vrací pole objektů Data_Flat_FlatTable pro všechny řádky tabulky v DB odpovidajíci zadanému filtru.
     * @param string $jmenoTabulky Název db tabulky
     * @param string $filtr SQL výraz použitý v klauzuli where pro výběr řádků db tabulky, pokud parametr není zadán vrací objekty jen podle hodnoty parametru $vsechnyRadky
     * @param string $orderBy Název sloupce db tabulky, polde kterého se řadí výsledky
     * @param string $order ASC nebo DESC - použit pro řazení výsledků
     * @param boolean $objektJeVlastnostiHlavnihoObjektu TRUE id vytvořených objektů je id hlavního objektu, FALSE id vytvořených objektů je id tabulky
     * @param string $jmenoSloupceIdHlavnihoObjektu Pokud $objektJeVlastnostiHlavnihoObjektu=TRUE musi obsahovat název db sloupce s primárním klíčem v tabulce hlavního objektu
     * @param boolean $vsechnyRadky Pokud je TRUE, metoda vrací objekty pro všechny řádky tabulky bez ohledu na hodnotu ve sloupci valid
     * @param $dbh handler databáze
     * @return array() Pole objektů Data_Flat_FlatTable odpovidajicich radkum v DB
     */    
    public static function vypisVse($databaze, $jmenoTabulky, $filtr = "",  $orderBy = "", $order = "", $objektJeVlastnostiHlavnihoObjektu=FALSE, $jmenoTabulkyHlavnihoObjektu="", $jmenoSloupceIdHlavnihoObjektu=NULL, $nazevIdProjekt = NULL, $nazevIdKancelar = NULL, $nazevIdBeh = NULL,  $vsechnyRadky = FALSE)
    	//TODO: sjednotot pořadí argumentů metod vypisVse v Ciselnik, FlatTable, HlavniObjekt
    {
        $dbh = App_Kontext::getDbh($databaze);
        if ($objektJeVlastnostiHlavnihoObjektu)
        {
            $jmenoId = $jmenoSloupceIdHlavnihoObjektu;
            // tabulky objektů, které jsou vlastností hlavního objektu neobsahují sloupec valid (ten má je tabulka hlavního objektu)
            $kontextFiltr = App_Kontext::getKontextFiltrSQL($nazevIdProjekt, $nazevIdKancelar, $nazevIdBeh, $filtr, $orderBy, $order, TRUE);
        } else {            
            $jmenoId = Data_Flat_CacheStruktury::getStrukturu($databaze, $jmenoTabulky)->primaryKeyFieldName;            
            $kontextFiltr = App_Kontext::getKontextFiltrSQL($nazevIdProjekt, $nazevIdKancelar, $nazevIdBeh, $filtr, $orderBy, $order, $vsechnyRadky);
        } 

        $query = "SELECT ~1 FROM ~2".$kontextFiltr;
        $radky = $dbh->prepare($query)->execute($jmenoId, $jmenoTabulky)->fetchall_assoc();
        foreach($radky as $radek)
            $vypis[] = new Data_Flat_FlatTable($databaze, $jmenoTabulky, $objektJeVlastnostiHlavnihoObjektu, $jmenoTabulkyHlavnihoObjektu, $jmenoSloupceIdHlavnihoObjektu, $vsechnyRadky, $radek[$jmenoId]);		 
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
        $columnId = array_search($name,Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->nazvy);
        if($columnId===false)
        {
        // TODO: chyby - dočasně zrušeno
        // $this->chyby->write($name,'',108);  //neexistující vlastnost $name
            return false;
        }
        if (array_key_exists($columnId, $this->value))
        {
            return $this->value[$columnId];
        } else {
            return FALSE;   //hodnota ve sloupci se zadaným názvem v databázi byla NULL - položka v poli hodnot value nebyla vytvořena
        }

    }

     /**
     * Setter - 
     * hodnota vlastnosti, ktera ma odpovidajici sloupec v db tabulce je zapsana do pole $this->value[],
     * hodnota ostatnich existujicich vlastnosti objektu je zapsana standardne do vlastnosti,
     * neexistující vlastnost objektu, ktera nema odpovidajici sloupec v db tabulce je nove vytvorena (standard php) a hodnota do ni ulozena.
     * @param type $name
     * @param type $value
     * @return type 
     */
    public function __set($name,$value)
    {   //hodnota ve sloupci autoIncrementFieldName je id zaznamu, hodnota se uklada do vlastnosti autoIncrementFieldValue pro snazsi pristup
        if ($name == Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->primaryKeyFieldName)
        {
            $this->primaryKeyFieldValue = $value;
        }
        else
        {   
            //kontrola existence sloupoe se zadaným názvem v db tabulce
            $columnid = array_search($name,Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->nazvy);
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

//            switch($this->type[$columnid])
//            {
//                case 'int':
//                    if ($value !== "" AND !is_numeric($value)){
//                        $this->chyby->write($name,$value,110);
//                        $this->value[$columnid] = $this->default[$columnid];    //není číslo - náhradní hodnota je default hodnota sloupce
//                    }
//                    try{
//                        settype($value,"integer");
//                    }
//                    catch (Exception $e){
//                        $this->chyby->write($name,$value,111);
//                        $this->value[$columnid] = $this->default[$columnid];
//                    }
//                    if(!$this->type_unsigned[$columnid] && $value < 0){
//                        $this->chyby->write($name,$value,112);
//                        $this->value[$columnid] = intval($value);    //je číslo, ale není integer - náhradní hodnota je integer hodnota
//                    }
//                    break;
//                case 'varchar':
//                    $value_length = strlen($value);
//                    if($value_length > $this->type_length[$columnid]){
//                       $this->chyby->write($name,$value,120);
//                       $this->value[$columnid] = substr($value,0,$this->type_length[$columnid]);    //řetězec je dlouhý - náhradní hodnota je oříznutý řetězec
//                    }
//            }
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
            $query_column_names = "";       //část SQL příkazu INSERT se jmény sloupců
            $query_values = "";             //část SQL příkazu INSERT s daty
            foreach(Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->nazvy as $key=>$column_name) {
                if ($column_name != Data_Flat_CacheStruktury::getStrukturu($this->dbh, $this->jmenoTabulky)->primaryKeyFieldName) {    //neukládá se do sloupce, který je primary key
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
            $query="UPDATE ".$this->jmenoTabulky." SET ";
            foreach(Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->nazvy as $column_name) {
                if ($column_name != Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->primaryKeyFieldName) {
                    $value= $this->$column_name ;
                    if($value) {
                        $query.=$column_name." = '".$value."',";
                    }
                }
            }
            $query=substr($query,0,strlen($query)-1);
            $query.=" WHERE ".Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->primaryKeyFieldName." = ".$this->primaryKeyFieldValue;
        }
        $dbh = App_Kontext::getDbh($this->databaze);
        $dbh->prepare($query)->execute("");
        return true;
    }

    /*
     * Metoda nacte hodnoty z radku db tabulky do pole $this->value[] 
     */
    private function precti_zaznam() {
    //SVOBODA metoda přečte záznam z db, ale nezamyká záznam v db - kdokoli může mezi přečtením a zápisem zapsat bo db a při zápisu se pak takováto data přepíší
    //metoda by mohla zamykat, ale asi to by znamenalo zamknou (?transakci) celý hlavní objekt
        $dbh = App_Kontext::getDbh($this->databaze);
        $query="SELECT * FROM ~1 WHERE ~2 = :3";
        if ($this->objektJeVlastnostiHlavnihoObjektu) {
            $data = $dbh->prepare($query)->execute($this->jmenoTabulky, $this->jmenoSloupceIdHlavnihoObjektu, $this->id)->fetch_assoc();                    
        } else {
            $data = $dbh->prepare($query)->execute($this->jmenoTabulky, Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->primaryKeyFieldName, $this->id)->fetch_assoc();
        }
        if ($data) {
            foreach(Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->nazvy as $columnID => $columnName)
            {
                $this->value[$columnID] = $data[$columnName];
            }
            $this->primaryKeyFieldValue = $data[Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->primaryKeyFieldName]; 
            $this->precteno_z_db = true;
            return TRUE;
        } else {
            return FALSE;
        }                
    }
    
    /**
     * Metoda vrací pole názvů sloupců db tabulky
     * @return array() Pole názvů sloupců db tabulky
     */
    public function dejNazvy() {
    return Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->nazvy;
    }
    
    /**
     * Metoda vrací pole hodnot obsahující TRUE pokud sloupec db tabulky je primární klíč, jinak FALSE
     * @return array() Pole hodnot příznaku KEY sloupců db tabulky
     */
    public function dejKlice() {
    return Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->pk;
    }

    /**
     * Metoda vrací pole hodnot obsahující datové typy sloupců db tabulky. Vrací pouze řetězec odpovídající názvu typu.
     * @return array() Pole hodnot typů sloupců db tabulky
     */    
    public function dejTypy() {
    return Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->typy;
    }

    /**
     * Metoda vrací pole hodnot obsahující délky datových typů sloupců db tabulky. 
     * Vrací pouze celé číslo odpovídající celkové délce.
     * @return array() Pole celočíselných délek sloupců db tabulky
     */    
    public function dejDelky() {
    return Data_Flat_CacheStruktury::getStrukturu($this->databaze, $this->jmenoTabulky)->delky;
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
    
    /**
    * Tato metoda se požívá jen v projektoru2 a to v ind_xxxxxx.inc
    * @return <type>
    */
//    public function get_values_assoc() {
//        if(!$this->idHlavnihoObjektu){
//        //objekt flat table dosud nemá záznam v databázi - nový objekt
//            //nastavi vsechny vlastnosti objektu na prazdnou hodnotu daneho typu sloupce v tabulce
//            foreach ($this->nazvy as $columnID => $columnName)
//            {
//                $this->$columnname = default_hodnota($this->typy[$columnID]);
//            }
//        }
//        else
//        {
//        // má id => objekt flat table má záznam v databázi - metoda vrací hodnotu z db
//           //lazy load - načte z databáze všechny vlastnosti objektu při prvním přístupu k jakékoli vlastnosti
//           if (!$this->precteno_z_db)
//           {
//               $this->precti_zaznam();
//           }
//        }
//
//        $assoc_value = array();
//        foreach($this->nazvy as $columnID => $columnName) {
//            $assoc_value[$columnName] = $this->value[$columnID];
//        }
//        return $assoc_value;
//    }    
}

?>