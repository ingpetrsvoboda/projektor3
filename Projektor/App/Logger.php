<?php
/**
 * Logger
 * @author Petr Svoboda
 * @since Sat, 17 Oct 2011 15:01:55 +0200
 */

abstract class Projektor_App_Logger
{
	private static $log = array();


	public static function getLogArray()
	{
		return self::$log;
	}
        
	public static function getLogText()
	{
		return print_r(self::$log, TRUE);
	}        

        public static function resetLog()
	{
		self::$log = NULL;
		return self::$log;
	}

        public static function setLog($zaznam = NULL)
	{
		if ($zaznam) 
                {
                    self::$log[] = $zaznam;
                    return TRUE;
                } else {
                    return FALSE;
                }
	}

}
?>
