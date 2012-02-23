<?php
/**
 * @author Svoboda Petr
 */

class Data_Seznam_SPrezentace extends Data_Iterator
{

//select `s_prezentace`.`id_s_prezentace` AS `id_s_prezentace`,`s_prezentace`.`hlavni_objekt` AS `hlavni_objekt`,
//`s_prezentace`.`objekt_vlastnost` AS `objekt_vlastnost`,`s_prezentace`.`nazev_sloupce` AS `nazev_sloupce`,
//`s_prezentace`.`titulek` AS `titulek`,`s_prezentace`.`zobrazovat` AS `zobrazovat`,`s_prezentace`.`valid` AS `valid`
//from `s_prezentace`

    public $id;
    public $hlavniObjekt;
    public $objektVlastnost;
    public $nazevSloupce;
    public $titulek;
    public $zobrazovat;
    public $valid;

    // pseudo název hlavního objektu pro objekty načítané třídou Data_FlatTable
    const FLAT_TABLE = "flat table";


    // Nazev tabulky a sloupcu v DB
    const TABULKA = "s_prezentace";
    const ID = "id_s_prezentace";
    const HLAVNI_OBJEKT = "hlavni_objekt";
    const OBJEKT_VLASTNOST = "objekt_vlastnost";
    const NAZEV_SLOUPCE = "nazev_sloupce";
    const TITULEK = "titulek";
    const ZOBRAZOVAT = "zobrazovat";
    const VALID = "valid";

    public function __construct($hlavniObjekt, $objektVlastnost, $nazevSloupce, $titulek, $zobrazovat, $valid, $id = null)
    {
    $this->id = $id;
    $this->hlavniObjekt = $hlavniObjekt;
    $this->objektVlastnost = $objektVlastnost;
    $this->nazevSloupce = $nazevSloupce;
    $this->titulek = $titulek;
    $this->zobrazovat = $zobrazovat;
    $this->valid = $valid;

        parent::__construct(__CLASS__);
    }

    /**
        * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
        * @param int $id Identifikator radku tabulky
        * @return Beh Instance tridy obsahujici data z radku v tabulce
        */

    public static function najdiPodleId($id, $vsechnyRadky = FALSE)
    {
            $dbh = App_Kontext::getDbMySQLProjektor();
            $query = "SELECT * FROM ~1 WHERE ~2 = :3" . ($vsechnyRadky ? "" : " AND valid = 1");
            $radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

            if(!$radek)
            return false;

            return new Data_Seznam_SPrezentace($radek[self::HLAVNI_OBJEKT], $radek[self::OBJEKT_VLASTNOST],
                                                $radek[self::NAZEV_SLOUPCE], $radek[self::TITULEK],
                                                $radek[self::ZOBRAZOVAT], $radek[self::VALID],
                                                $radek[self::ID]);
    }

    /**
        * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
        * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
        * @return array() Pole instanci tridy odpovidajici radkum v DB
        */

    public static function vypisVse($filtr = "", $orderBy = "", $order = "", $vsechnyRadky = FALSE)
    {
            $dbh = App_Kontext::getDbMySQLProjektor();
            $query = "SELECT ~1 FROM ~2".
                    ($filtr == "" ? ($vsechnyRadky ? "" : " WHERE valid = 1") : ($vsechnyRadky ? " WHERE {$filtr} " : " WHERE valid = 1 AND {$filtr}")).
                    ($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;
            $radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

            foreach($radky as $radek)
                    $vypis[] = self::najdiPodleId($radek[self::ID], $vsechnyRadky);
            return $vypis;
    }

    /**
        * Ulozi parametry tridy jako radek do DB.
        * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
        */

    public function uloz()
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $this->id;
        $this->hlavniObjekt;
        $this->objektVlastnost;
        $this->nazevSloupce;
        $this->titulek;
        $this->zobrazovat;
        $this->valid;
        if($this->id == null)
        {
            $query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7) VALUES (:8, :9, :10, :11, :12, :13)";
            return $dbh->prepare($query)->execute(
            self::TABULKA, 
            self::HLAVNI_OBJEKT, self::OBJEKT_VLASTNOST, self::NAZEV_SLOUPCE, self::TITULEK, self::ZOBRAZOVAT, self::VALID,
            $this->hlavniObjekt, $this->objektVlastnost, $this->nazevSloupce, $this->titulek, $this->zobrazovat, $this->valid
            )->last_insert_id();
        }
        else
        {
            $query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11, ~12=:13 WHERE ~14=:15";
            $dbh->prepare($query)->execute(
            self::TABULKA,
            self::HLAVNI_OBJEKT, $this->hlavniObjekt,
            self::OBJEKT_VLASTNOST, $this->objektVlastnost,
            self::NAZEV_SLOUPCE, $this->nazevSloupce,
            self::TITULEK, $this->titulek,
            self::ZOBRAZOVAT, $this->zobrazovat,
            self::VALID, $this->valid,
            self::ID, $this->id
            );
            return $this->id;
        }
    }


    /**
        * Nastavi v radku v databaze odpovidajici parametru $id tridy hodnotu valid = 0
        * @return unknown_type
        */
    public static function smaz()
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
        $dbh->prepare($query)->execute(self::TABULKA, self::ID, $this->id);
    }        

    public static function nactiNazvy($jmenaHlavnichObjektu = NULL, $jmenaFlatTabulek = NULL) {
        //načtení hlavních objektů
        foreach ($jmenaHlavnichObjektu as $jmenoHlavnihoObjektu) {
            $jmenoDatovehoObjektu = "Data_".$jmenoHlavnihoObjektu;
            $datovyObjekt = new $jmenoDatovehoObjektu;
            $mapovani = $datovyObjekt->_mapovaniObjektTabulka;
            $prefix = $datovyObjekt->prefix;

            $dbh = App_Kontext::getDbMySQLProjektor();
            foreach ($mapovani as $jmenoObjektuVlastnosti => $tabulka) {
                // Kontrola existence tabulky v databázi
                switch($dbh->dbType){
                case 'MySQL':
                    $dbhi = App_Kontext::getDbMySQLInformationSchema();
                    $query = Helper_SqlQuery::getShowTablesQueryMySQL();            
                    break;
                case 'MSSQL':
                    $dbhi = App_Kontext::getDbh($dbh->dbName);
                    $query = Helper_SqlQuery::getShowTablesQueryMSSQL();
                    break;
                default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.':<BR>'."Typ databáze ".$dbh->dbType." neexistuje.");
                }
                if (!$dbhi->prepare($query)->execute($dbh->dbName, $prefix . $tabulka)) {
                    throw new Exception("V databázi ".$dbh->dbName." neexistuje tabulka ".$prefix . $tabulka);
                };
                //Nacteni struktury tabulky, datovych typu a ostatnich parametru tabulky
                switch($dbh->dbType){
                case 'MySQL':
                    $dbhi = App_Kontext::getDbMySQLInformationSchema();
                    $query = Helper_SqlQuery::getShowColumnsQueryMySQL();            
                    break;
                case 'MSSQL':
                    $dbhi = App_Kontext::getDbh($dbh->dbName);
                    $query = Helper_SqlQuery::getShowColumnsQueryMSSQL();
                    break;
                default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.':<BR>'."Typ databáze ".$dbh->dbType." neexistuje.");
                }
                $querySelectCount = "SELECT ".self::ID." FROM ".self::TABULKA.
                            " WHERE ".self::HLAVNI_OBJEKT."='".$jmenoHlavnihoObjektu.
                                    "' AND ".self::OBJEKT_VLASTNOST."='".$jmenoObjektuVlastnosti.
                                    "' AND ".self::NAZEV_SLOUPCE."= :1";
                $sloupce= $dbhi->prepare($query)->execute($dbh->dbName, $prefix . $tabulka)->fetchall_assoc();
                if ($sloupce) {
                    foreach ($sloupce as $sloupec) {
                        if (!$sloupec['PK']) {
                            //pokud v tabulce prezentace neexistuje záznam pro hlavní objekt, objekt vlastnost, název sloupce, vloží se nový záznam
                            if (!$dbh->prepare($querySelectCount)->execute($sloupec['Nazev'])->fetch_assoc()) {
                                $query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7) VALUES (:8, :9, :10, :11, :12, :13)";
                                //zobrazovat je nastaveno na 1, aby se obsah zobrazoval ve formuláři, 
                                //valid je nastaveno na 0, aby se v objektu stránky použila defaultní hodnota titulku 
                                $dbh->prepare($query)->execute(
                                        self::TABULKA,
                                        self::HLAVNI_OBJEKT, self::OBJEKT_VLASTNOST, self::NAZEV_SLOUPCE, self::TITULEK, self::ZOBRAZOVAT, self::VALID,
                                        $jmenoHlavnihoObjektu, $jmenoObjektuVlastnosti, $sloupec['Nazev'], NULL, 1, 0
                                        )->last_insert_id();

                            }
                        }    
                    }
                }    
            }
        }

    } 
}
?>
