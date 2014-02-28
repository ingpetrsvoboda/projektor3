<?php

abstract class Projektor_Model_Auto_Cache_Struktury
{
    private static $cache = array( );

    public static function getStrukturuTabulky($databaze = "", $nazevTabulky = "") {
        if ($databaze AND $nazevTabulky)
        {
            if(!array_key_exists($databaze, self::$cache) OR !self::$cache[$databaze]) self::nactiStrukturu($databaze);
            if (!isset(self::$cache[$databaze]))
            {
                throw new Exception('*** Chyba v '.__CLASS__.'->'.__METHOD__.'. Nepodařilo se připojit k databázi: '.$databaze.
                        '. Databáze s tímto identifikátorem v konfiguraci neexistuje nebo přihlašovací informace jsou chybné. Sekce s konfiguračními informacemi: název sekce: '.Framework_Config::SEKCE_DB.', název atributu: '.Framework_Config::ATRIBUT_SEKCE_DATABAZE.', hodnota atribut:'.$databaze );
            }
            if (!isset(self::$cache[$databaze][$nazevTabulky]))
                throw new Exception('*** Chyba v '.__CLASS__.'->'.__METHOD__.'. V databázi neexistuje tabulka: '.$nazevTabulky.'.');
            return self::$cache[$databaze][$nazevTabulky];
        } else {
            throw new Exception('*** Chyba v '.__CLASS__.'->'.__METHOD__.'. Není zadána databáze: '.$databaze.' nebo název tabulky: '.$nazevTabulky.'.');
        }
    }

    public static function getStrukturyTabulek($databaze = "") {
        if ($databaze)
        {
            if(!array_key_exists($databaze, self::$cache) OR !self::$cache[$databaze]) self::nactiStrukturu($databaze);
            return $tables = array_keys(self::$cache[$databaze]);
        } else {
            throw new Exception('*** Chyba v '.__CLASS__.'->'.__METHOD__.'. Není zadána databáze: '.$databaze.'.');
        }
    }

    private static function nactiStrukturu($databaze)
    {
        //TODO: !! dodělat pro MSSQL!
        //TODO: zrychlení - možnosti v komentáři níže:
//        dotaz Projektor_Helper_SqlQuery::getShowStructureQueryMySQL(); trvá asi 1,7 vteřiny (notebook), volání při každém spuštění skriptu
//         prodlužuje každou odezvu
//        možnosti:
//            a) uložit strukturu do souboru a načítat ze souboru + přidat příkaz (do getu ?refresh=1) a načítat strukturu jen na příkaz
//            b) načítat strukturu jen na začátku session -> přidat práci se session, načítat strukturu jen při změně (neexistenci) SID, ukládat
//               strukturu do cache (např Cache Lite (PEAR) a pro každé SID vytvořit cache pro toto SID ($id cache = SID) a automaticky, pokud
//               není cache pro toto SID - načte se struktura a uloží se s platným SID)
//            c) ukládat strukturu do souboru (nebo cache), zachytávat exception databáze, pokud exception -> načíst strukturu a provést redirect
//               na stejný REQUEST, pokud dojde znovu k exception, tak tuto exception pustit výše

        $dbh = Projektor_Container::getDbh($databaze);
        $tabulky = array(); //názvy tabulek
    //Nacteni struktury tabulek
        switch($dbh->dbType){
        case 'mysql':
            $dbhInformationSchema = Projektor_Container::getDbh(Framework_Config::DATABAZE_INFORMATION_SCHEMA);
            $queryInformationSchema = Projektor_Helper_SqlQuery::getShowStructureQueryMySQL();
            break;
        case 'sqlsrv':
            $dbhInformationSchema = Projektor_Container::getDbh($dbh->dbName);
            $queryInformationSchema = Projektor_Helper_SqlQuery::getShowColumnsQueryMSSQL();
            break;
        default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Typ databáze ".$dbh->dbType." neexistuje.");
        }
        // parametr je :1
        $statementInformationSchema = $dbhInformationSchema->prepare($queryInformationSchema);
        $dbName = $dbh->dbName;  // bindParam předává parametr referencí - přímé použití $dbh->dbName jako parametru vyvolává pokus o přiřazení hodnoty do $dbh->dbName v bindParam()
        $statementInformationSchema->bindParam(':1', $dbName);
        $statementInformationSchema->execute();
//        $res=$dbhi->prepare($query)->execute($dbh->dbName);
        while ($data = $statementInformationSchema->fetch(PDO::FETCH_ASSOC))
        {
                $tabulky[$data['Tabulka']]['Sloupce'][$data['Nazev']] = new Projektor_Model_Auto_Cache_StrukturaSloupce($data['Nazev'], $data['Default'], $data['Typ'], $data['Delka'], $data['Klic'], $data['Extra'], $data['Referencovana_tabulka'], $data['Referencovany_sloupec']);
                if ($data['Klic']=='PK')
                {
                    $tabulky[$data['Tabulka']]['PK'] = $data['Nazev'];
                }
        }
        foreach ($tabulky as $tabulka=>$pole)
        {
            if (isset($pole['PK']))
            {
                self::$cache[$databaze][$tabulka] = new Projektor_Model_Auto_Cache_StrukturaTabulky($tabulka, $pole['Sloupce'], $pole['PK']);
            } else {
                self::$cache[$databaze][$tabulka] = new Projektor_Model_Auto_Cache_StrukturaTabulky($tabulka, $pole['Sloupce'], NULL);
            }
        }
    }

    private static function nactiTitulky()
    {
        $prezentaceCollection = new Projektor_Model_Auto_SPrezentaceCollection();
        $prezentaceCollection->vsechnyRadky();
    }
}
?>
