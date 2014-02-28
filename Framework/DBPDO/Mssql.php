<?php 
class Framework_DBPDO_Mssql extends Framework_DBPDO_PDO {
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
     * Metoda tedy vrací rozšířený objekt PDO. DSN je sestaven a PDO je vytvořeno tak, že je zachováno použití znakové sady UTF-8, která je defaultní. 
     * Vrácený objekt tedy ple požít poue pro tabulk v kódování UTF-8 a vstupní(výstupní kódování je také vždy UTF-8.
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
        $this->dsn =  $this->dbType . ':Server=' . $this->dbHost .
                      ((!empty($this->dbPort)) ? (', ' . $this->dbPort) : '') .
                      ';Database=' . $this->dbName;
    //    try {
        parent::__construct($this->dsn, $this->user, $this->pass);
    //    } catch (PDOException $e) {
    //        echo 'Connection failed: ' . $e->getMessage();
    //    }
    }
    
    public function getFormattedIdentificator($identificator) {
        return "[".$identificator."]";
    }
}
?>