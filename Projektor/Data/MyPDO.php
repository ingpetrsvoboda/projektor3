<?php

/*
 * homework.ini:
 * [database]
 * driver = mysql
 * host = localhost
 * ;port = 3306
 * schema = db_schema
 * username = user
 * password = secret
 * http://cz.php.net/manual/en/class.pdo.php#89019
 */

class Projektor_Data_MyPDO extends PDO
{
    const INI_FILE = 'homework.ini';
    
    private static $myDbo;
   
    private function __construct($file = null)
    {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Nelze otevřít soubor s nastavením: ' . $file . '.');
       
        $dns =  $settings['database']['driver'] . ':host=' . $settings['database']['host'] .
                    ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
                ';dbname=' . $settings['database']['schema'];
       
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }
    
    private static function setMyDbo(Projektor_Data_MyPDO $dbo)
    {
        self::$myDbo = new Projektor_Data_MyPDO (self::INI_FILE);
    }    
    
    public static function getMyDbo()
    {
        if(!self::$myDbo) self::setMyDbo();
        return self::$myDbo;
    }



}
?>
