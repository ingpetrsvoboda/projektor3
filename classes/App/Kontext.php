<?php
/**
 * Kontejner na globalni promenne
 * @author Petr Svoboda
 */

abstract class App_Kontext
{

    
//        private static $DbMySQLInformationSchema;
//        private static $DbMySQLProjektor;
//	private static $DbMySQLPersonalService;
//	private static $dbMSSQLCRM;
        private static $dbObjekty;
        private static $kontext;
        private static $jeDebug;
//
//	public static function getDbh($databaze)
//        {
//            if (!$databaze) $databaze = 'Projektor';
//            switch($databaze){
//            case self::INFORMATION_SCHEMA :
//                return self::getDbMySQLInformationSchema();
//                break;
//            case self::PROJEKTOR :
//                return self::getDbMySQLProjektor();
//                break;
//            case self::PERSONAL_SERVICE :
//                return self::getDbMySQLPersonalService();
//                break;
//            case self::CRM :
//                return self::getDbMSSQLCRM();
//                break;
//            }
//        }


	public static function getDbh($databaze)
        {
//            if (!$databaze) $databaze = App_Config::DATABAZE_PROJEKTOR;
            if (!$databaze) return; FALSE;
            if (!self::$dbObjekty[$databaze])
            {
                $dbConfig = App_Config::najdiPolozkuPodleAtributu(App_Config::NAZEV_SEKCE_DB_V_XML, App_Config::NAZEV_ATRIBUTU_DATABAZE, $databaze);
                if (!$dbConfig) 
                    throw new Exception(__CLASS__." ".__METHOD__." Nenalezena sekce s konfiguračními informacemi. Název sekce: ".
                                        App_Config::NAZEV_SEKCE_DB_V_XML.", název atributu: ".App_Config::NAZEV_ATRIBUTU_DATABAZE.", atribut:".$databaze);
                if (!$dbConfig->user OR !$dbConfig->pass OR !$dbConfig->dbhost OR !$dbConfig->dbname OR !$dbConfig->dbtype) 
                    throw new Exception(__CLASS__." ".__METHOD__.
                                        " Sekce s konfiguračními informacemi. Název sekce: ".
                                        App_Config::NAZEV_SEKCE_DB_V_XML.", název atributu: ".App_Config::NAZEV_ATRIBUTU_DATABAZE.", atribut:".$databaze.
                                        " neobsahuje všechny potřebné informace: user, pass, dbhost, dbname, dbtype");
                switch ($dbConfig->dbtype) {
                    case App_Config::DB_TYPE_MYSQL :
                        self::$dbObjekty[$databaze] = new DB_Mysql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;
                        break;
                    case App_Config::DB_TYPE_MSSQL :
                        self::$dbObjekty[$databaze] = new DB_Mssql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;
                        break;
                    default: 
                        throw new Exception(__CLASS__." ".__METHOD__." V konfigutaci (App_Config) neexistuje zadyný typ databáze: ".$dbConfig->dbtype);
                } 
            }
            return self::$dbObjekty[$databaze];

//            
//            switch($databaze){
//            case self:: :
//                return self::getDbMySQLInformationSchema();
//                break;
//            case self::PROJEKTOR :
//                return self::getDbMySQLProjektor();
//                break;
//            case self::PERSONAL_SERVICE :
//                return self::getDbMySQLPersonalService();
//                break;
//            case self::CRM :
//                return self::getDbMSSQLCRM();
//                break;
//            }
        }        


        public static function getDbMySQLInformationSchema()
	{
		if(!self::$DbMySQLInformationSchema)
		self::$DbMySQLInformationSchema = new DB_Mysql_InformationSchema();
		return self::$DbMySQLInformationSchema;
	}
        
	public static function getDbMySQLProjektor()
	{
		if(!self::$DbMySQLProjektor)
		self::$DbMySQLProjektor = new DB_Mysql_Projektor();
		return self::$DbMySQLProjektor;
	}

	public static function getDbMySQLPersonalService()
	{
		if(!self::$DbMySQLPersonalService)
		self::$DbMySQLPersonalService = new DB_Mysql_PersonalService();
		return self::$DbMySQLPersonalService;
	}
        
	public static function getDbMSSQLCRM()
	{
		if(!self::$dbMSSQLCRM){
                    $a = 0;
		self::$dbMSSQLCRM = new DB_Mssql_CRM();
                    
                }
		return self::$dbMSSQLCRM;
	}
        
        public static function setUserKontext(User_Kontext $userKontext = NULL)
	{
		if ($userKontext) self::$kontext = $userKontext;
		return self::$kontext;
	}

        public static function getUserKontext()
	{
		if(!self::$kontext)
		self::$kontext = new User_Kontext ();  // prázdný kontext
		return self::$kontext;
	}
        
        public static function getKontextFiltrSQL($nazevIdProjekt = NULL, $nazevIdKancelar = NULL, $nazevIdBeh = NULL, $filtr = NULL, $orderBy = NULL, $order = NULL, $vsechnyRadky = FALSE)
        {
                $kon = self::getUserKontext();
                $kontextFiltr = 
                    ($filtr == "" ? ($vsechnyRadky ? "" : " WHERE valid = 1") : ($vsechnyRadky ? " WHERE {$filtr} " : " WHERE valid = 1 AND {$filtr}")).
                    (($kon->projekt AND $nazevIdProjekt) ? " AND `{$nazevIdProjekt}` = {$kon->projekt->id}" : "").
                    (($kon->kancelar AND $nazevIdKancelar) ? " AND `{$nazevIdKancelar}` = {$kon->kancelar->id}" : "").
                    (($kon->beh AND $nazevIdBeh) ? " AND `{$nazevIdBeh}` = {$kon->beh->id}" : "").
                    ($orderBy ? " ORDER BY `{$orderBy}` {$order}" : "");
                return $kontextFiltr;
        }

        public static function setJeDebug()
	{
		self::$jeDebug = TRUE;
		return self::$jeDebug;
	}

        public static function unsetJeDebug()
	{
		self::$jeDebug = FALSE;
		return self::$jeDebug;
	}
        public static function getDebug()
	{
		return self::$jeDebug;
	}
}
?>
