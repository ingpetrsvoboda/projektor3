<?php 
class Framework_DBPDO_Mysql extends Framework_DBPDO_PDO {
    protected $user;
    protected $pass;
    protected $dbHost;
    protected $dbPort;
    protected $dbName;
    protected $dbType;
    
    protected $bindedIdentificators;

    protected $dsn;

    /**
     * Konstruktor. Nastaví instanční proměnné uadané jako prametry, sestaví řetězec DSN a volá rodičovský konstruktor.
     * Metoda tedy vrací rozšířený objekt PDO. DSN je sestaven tak, že je nastavena znaková sata UTF-8 a PDO je vytvořeno tak, že je 
     * nastaven příkaz, který se provede při inicializaci na SET NAMES 'utf8'. Vrácený objekt tedy lze požít pouze pro tabulky v kódování UTF-8
     * a vstupní(výstupní kódování je také vždy UTF-8.
     * @param type $user
     * @param type $pass
     * @param type $dbHost
     * @param type $dbName
     * @param type $dbPort
     */
    public function __construct($user, $pass, $dbHost, $dbName, $dbPort=NULL) {
        $this->user = $user;
        $this->pass = $pass;
        $this->dbName = $dbName;
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbType = Framework_Config::DB_TYPE_MYSQL;  
        $this->dsn =  $this->dbType . ':host=' . $this->dbHost .
                      ((!empty($this->dbPort)) ? (';port=' . $this->dbPort) : '') .
                      ';dbname=' . $this->dbName .
                      ';charset=UTF-8';
    //    try {
        parent::__construct($this->dsn, $this->user, $this->pass,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    //    } catch (PDOException $e) {
    //        echo 'Connection failed: ' . $e->getMessage();
    //    }
    }
    
    public function getFormattedIdentificator($identificator) {
        return "`".str_replace("`","``",$identificator)."`";;
    }
}
?>