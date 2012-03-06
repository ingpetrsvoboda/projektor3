<?php

abstract class Data_Flat_CacheStruktury
{
    private static $cache = array( );
        
    public static function getStrukturu($databaze = "", $nazevTabulky = "") {
        if ($databaze AND $nazevTabulky) 
        {
                if(!self::$cache[$databaze][$nazevTabulky])
                self::$cache[$databaze][$nazevTabulky] = self::nactiStrukturu($databaze, $nazevTabulky);
		return self::$cache[$databaze][$nazevTabulky];
        } else {
            return FALSE;
        }
    }
    
    private static function nactiStrukturu($databaze, $nazevTabulky)
    {
            $dbh = App_Kontext::getDbh($databaze);
        //názvy sloupců tabulky, datové typy sloupců tabulky, TRUE pokud slopupec je primární klíč, délky datových typů sloupců tabulky typu char, varchar atd.
        $nazvy = array();         
        $typy = array();          
        $pk = array();       
        $delky = array();

        // Musí existovat tabulka číselníku v DB
        switch($dbh->dbType){
        case 'MySQL':
            $dbhi = App_Kontext::getDbMySQLInformationSchema();
            $query = Helper_SqlQuery::getShowTablesQueryMySQL();            
            break;
        case 'MSSQL':
            $dbhi = App_Kontext::getDbh($dbh->dbName);
            $query = Helper_SqlQuery::getShowTablesQueryMSSQL();
            break;
        default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Typ databáze ".$dbh->dbType." neexistuje.");
        }
        if (!$dbhi->prepare($query)->execute($dbh->dbName, $nazevTabulky)){
            throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V databázi ".$dbh->dbName." neexistuje tabulka ".$nazevTabulky);
        }
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
        default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Typ databáze ".$dbh->dbType." neexistuje.");
        }
        $res=$dbhi->prepare($query)->execute($dbh->dbName, $nazevTabulky);
        while ($data = $res->fetch_assoc()){
            array_push($nazvy,$data['Nazev']);
            array_push($typy,$data['Typ']);            
            array_push($delky,$data['Delka']);            
            array_push($pk,$data['PK']);            
            if ($data['PK'])
            {
                $primaryKeyFieldName = $data['Nazev'];
            }
        }
        return new Data_Flat_Struktura($databaze, $nazevTabulky, $nazvy, $typy, $delky, $pk, $primaryKeyFieldName);
    }
            
}
?>
