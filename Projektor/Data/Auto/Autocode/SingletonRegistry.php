<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Singleton
 *
 * @author http://stackoverflow.com/questions/1818199/singleton-in-conjunction-with-the-factory-pattern-in-php5
 */
class Registry
{
    private static $_objects;

    public static function set($key, $object)
    {
        if (!array_key_exists($key, self::$_objects)) self::$_objects[$key] = $object;
    }

    public static function get($key)
    {
        if (array_key_exists($key, self::$_objects)) return self::$_objects[$key];
        else return false;
    }
}

class DBFactory
{
    public static function getConnection($type)
    {
        switch ($type) {
            case 'pdo':
                if (!(Registry::get('DB_PDO') instaceof DbPdo)) Registry::set('DB_PDO', new DbPdo('user', 'pass', ...));
                return Registry::get('DB_PDO')
            case 'mssql':
                //same for other connections
            //...
        }
    }
}

?>
