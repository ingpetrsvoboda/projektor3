<?php
/**
 * Description of Sql
 *
 * @author pes2704
 */
abstract class Projektor_Model_Sql_Base {
    const DEFAULT_VALID_FILTER = "valid=1";

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

    // vlastnosti, ke kterým se přistupuje z metod getIterator
    public $identificators = array();
    public $params = array();

    public function __construct($database)
    {
        $this->database = $database;
        $this->dbh = Projektor_Container::getDbh($this->database);                
    }
    
    /**
     * Zablokování serializace objektu - pokus o serializaci vrací serializovanou podobu NULL, tj. "N;"
     * Objekt obsahuje potomka PDO, PDO není serializovatelné a objekt obsahuje CITLIVÁ data - údaje o připojekní k databázi
     * @return null
     */
    public function __sleep() {       
        return array('database');
    }
    
    public function __wakeup() {
        $this->dbh = Projektor_Container::getDbh($this->database);
    }

    protected abstract function getSql();
    
    /**
     * Metoda vrací objekt PDOStatement vytvořený na základě dotazu vytvořeného potomkovskou metodou getSql() a s navázanými parametry. 
     * (metodou PDOStatement bindPraram() )
     * @return PDOStatement
     */
    public function getPreparedStatement() {
        //sestavení dotazu
        $query = $this->getSql();
        //prepare
        $prep = $this->dbh->prepare($query);
        //bind params
        if ($this->params) {
            // bindParam requires a reference. It binds the variable to the statement, not the value
            foreach ($this->params as $param => &$value) {
                $prep->bindParam($param, $value);
            }
        } 
        return $prep;
    }
    
    /**
     * Metoda vytvoří řetězec do klauzule WHERE s pojmenovanými parametry pro prepare a doplní pole parametrů pro bindParams
     * Příklady:
     * where("vek", "=", 2) => "(~vek = :vek)" a parametry ~vek="vek", :vek=2
     * where("jmeno", "LIKE", "Adam", FALSE, TRUE) => "(~jmeno LIKE :jmeno)" a parametry ~jmeno="jmeno", :jmeno="Adam%"
     * where("pismeno", "IV", array("A","B")) => "(~pismeno IN (:pismeno1, :pismeno2))" a parametry ~pismeno="pismeno", :pismeno1="A", :pismeno2="B"
     * @param type $nazev
     * @param type $podminka
     * @param type $hodnota
     * @param type $otevreneZleva
     * @param type $otevreneZprava
     */
    public function where($nazev, $podminka, $hodnota, $otevreneZleva=NULL, $otevreneZprava=NULL) {
        try {
            if ($nazev AND is_string($nazev) AND $podminka AND is_string($podminka) AND isset($hodnota)) {
                $podminka = strtoupper(str_replace(" ", "", $podminka));  //vynechá mezery a převede na velká písmena
                switch ($podminka) {
                    case "LIKE":
                        if ($otevreneZleva) $hodnota = "%".$hodnota;
                        if ($otevreneZprava) $hodnota = $hodnota."%";
                        $identifikatorHodnoty = ":".$nazev;
                        $this->params[$identifikatorHodnoty] = $hodnota;
                        break;
                        
                    case "IN":                       
                        if (!is_array($hodnota))
                        {
                            $list[0] = $hodnota;
                        } else {
                                $list = $hodnota;
                        }
                        $poprve = TRUE;
                        foreach ($list as $key=>$val) {
                            if ($poprve) {
                                $poprve = FALSE;
                                $identifikatorHodnoty = ":".$nazev.$key;
                            } else {
                                $identifikatorHodnoty .= ", :".$nazev.$key;
                            }
                            $this->params[":".$nazev.$key] = $val;

                        }
                        if (isset($identifikatorHodnoty)) {
                            $identifikatorHodnoty = "(".$identifikatorHodnoty.")";
                        } else {
                            $identifikatorHodnoty = "(NULL)";  //bylo zadáno prázdné pole hodonot
                        }
                        break;
                        
                    case "=":
                    case "!=":
                    case "<>":
                    case "<":
                    case "<=":
                    case ">":
                    case ">=":                
                        $identifikatorHodnoty = ":".$nazev;
                        $this->params[$identifikatorHodnoty] = $hodnota;
                        break;
                    default:
                        throw new Projektor_Model_Exception("Klausule WHERE nebyla vytvořena, zadaná podmínka: ".$podminka." nebyla rozpoznána.");
                }
                if ($this->where) {
                    $this->where .= " AND (~".$nazev." ".$podminka." ".$identifikatorHodnoty.")";
                } else {
                    $this->where = "(~".$nazev." ".$podminka." ".$identifikatorHodnoty.")";
                }
                $this->identificators["~".$nazev] = $nazev;
            } else {
                throw new Projektor_Model_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti
                        ." není řetězec nebo zadaná podmínka: ".$podminka." není řetězec nebo není zadaná hodnota: ".$hodnota.".");
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
            return FALSE;
        }            
    }

    public function order($nazevVlastnosti = NULL, $order = 'ASC') {
        if ($this->order)
        {
            $this->order .= ", ~".$nazevVlastnosti." ".$order;
        } else {
            $this->order = "~".$nazevVlastnosti." ".$order;
        }
        $this->identificators["~".$nazevVlastnosti] = $nazevVlastnosti;
    }
}

?>
