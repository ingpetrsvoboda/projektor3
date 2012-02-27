<?php
/**
 * Kontajner na globalni promenne
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

abstract class App_Kontext
{
	const INFORMATION_SCHEMA = 'InformationSchema';
        const PROJEKTOR = 'Projektor';
        const PERSONAL_SERVICE = 'PersonalService';
        const CRM = 'test_projektor';
    
        private static $DbMySQLInformationSchema;
        private static $DbMySQLProjektor;
	private static $DbMySQLPersonalService;
	private static $dbMSSQLCRM;
        private static $kontext;
        private static $jeDebug;

	public static function getDbh($databaze)
        {
            if (!$databaze) $databaze = 'Projektor';
            switch($databaze){
            case self::INFORMATION_SCHEMA :
                return self::getDbMySQLInformationSchema();
                break;
            case self::PROJEKTOR :
                return self::getDbMySQLProjektor();
                break;
            case self::PERSONAL_SERVICE :
                return self::getDbMySQLPersonalService();
                break;
            case self::CRM :
                return self::getDbMSSQLCRM();
                break;
            }
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
                    (($kon->projekt->id AND $nazevIdProjekt) ? " AND `{$nazevIdProjekt}` = {$kon->projekt->id}" : "").
                    (($kon->kancelar->id AND $nazevIdKancelar) ? " AND `{$nazevIdKancelar}` = {$kon->kancelar->id}" : "").
                    (($kon->beh->id AND $nazevIdBeh) ? " AND `{$nazevIdBeh}` = {$kon->beh->id}" : "").
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
