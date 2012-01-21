<?php
/**
 * Kontajner na globalni promenne
 * @author Marek Petko
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

abstract class App_Kontext
{
	private static $dbMySQL;
	private static $dbMSSQL;
        private static $kontext;
        private static $jeDebug;

	public static function getDbMySQL()
	{
		if(!self::$dbMySQL)
		self::$dbMySQL = new DB_Mysql_Projektor();
		return self::$dbMySQL;
	}

	public static function getDbMSSQL()
	{
		if(!self::$dbMSSQL){
                    $a = 0;
		self::$dbMSSQL = new DB_Mssql_Projektor();
                    
                }
		return self::$dbMSSQL;
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
        
        public static function getKontextFiltrSQL($nazevIdProjekt = NULL, $nazevIdKancelar = NULL, $nazevIdBeh = NULL, $filtr = NULL, $orderBy = NULL, $order = NULL)
        {
                $kon = self::getUserKontext();
                $kontextFiltr = " valid = 1 ".
                    (($kon->projekt->id AND $nazevIdProjekt) ? " AND `{$nazevIdProjekt}` = {$kon->projekt->id}" : "").
                    (($kon->kancelar->id AND $nazevIdKancelar) ? " AND `{$nazevIdKancelar}` = {$kon->kancelar->id}" : "").
                    (($kon->beh->id AND $nazevIdBeh) ? " AND `{$nazevIdBeh}` = {$kon->beh->id}" : "");
                $kontextFiltr .= ($filtr ? " AND {$filtr}" : "").
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
