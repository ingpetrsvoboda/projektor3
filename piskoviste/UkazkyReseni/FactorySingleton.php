<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactorySingleton
 *
 * @author http://stackoverflow.com/questions/1818199/singleton-in-conjunction-with-the-factory-pattern-in-php5
 */

class DBFactory
{
    public static function getConnection($type)
    {
    	switch ($type) {
    		case 'pdo':
    			return DbPdo::getInstance('user', 'pass');
    		case 'mssql':
    			//same for other connections
    		//...
    	}
    }
}

class DbPdo
{
    private static $_instance;

    private function __construct($user, $pass){  }//instantiate object

    public static function getInstance($user = null, $pass = null)
    {
        if (!(self::$_instance instanceof DbPdo)) self::$_instance = new DbPdo($user, $pass);
        return self::$_instance;
    }
}
?>
