<?php
/**
 * Kontejner na globalni promenne
 * @author Petr Svoboda
 */

class Projektor_Container
{
    private static $dbObjekty;
    /**
     * @var Framework_StatusStorage_Session 
     */
    private static $storageSession;
    /**
     * @var Projektor_User_User 
     */
    private static $user;
    /**
     * @var Projektor_User_Identity 
     */
    private static $userIdentity;
    /**
     * @var Framework_View_PHPTALTemplateObject 
     */
    private static $PHPTALTemplateObject;
    /**
     * @var Framework_View_TwigTemplateObject 
     */
    private static $twigTemplateObject;
    /**
     *
     * @var boolean 
     */
    private static $debug;

    /**
     * Parametr \$databaze je obvykle převzat z konstanty třídy s konfigurací aplikace. 
     * Příklad volání: $dbh = Framework_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);
     * @param string $databaze Název databáze
     * @return type
     * @throws Exception
     */
    public static function getDbh($databaze = null)
    {
//            if (!$databaze) $databaze = Framework_Config::DATABAZE_PROJEKTOR;
        if (!$databaze) throw new Exception(__CLASS__." ".__METHOD__." Metoda byla zavolána bez uvedení hodnoty parametru \$databaze");
        if (!self::$dbObjekty OR !array_key_exists($databaze, self::$dbObjekty) OR !self::$dbObjekty[$databaze]) {
            self::seDbh($databaze);
        }
        return self::$dbObjekty[$databaze];
    }
    
    private static function seDbh($databaze) {
        $dbConfig = self::getDbConfig($databaze);
        switch ($dbConfig->dbtype) {
            case Framework_Config::DB_TYPE_MSSQL :
                $dbh = new Framework_DBPDO_Mssql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;               
                break;
            case Framework_Config::DB_TYPE_MYSQL :
                $dbh = new Framework_DBPDO_Mysql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;
                break;

            default:
                throw new Exception(__CLASS__." ".__METHOD__." V konfigutaci (Framework_Config) neexistuje zadaný typ databáze: ".$dbConfig->dbtype);
        }
        self::$dbObjekty[$databaze] = $dbh;
        return $dbh;        
    }
    
    public static function getDbConfig($databaze) {
        $dbConfig = Framework_Config::najdiPolozkuPodleAtributu(Framework_Config::SEKCE_DB, Framework_Config::ATRIBUT_SEKCE_DATABAZE, $databaze);
        if (!$dbConfig)
            throw new Exception(__CLASS__." ".__METHOD__." Nenalezena položka v XML souboru s konfiguračními informacemi. Název sekce: ".
                                Framework_Config::SEKCE_DB.", název atributu: ".Framework_Config::ATRIBUT_SEKCE_DATABAZE.", atribut:".$databaze);
        if (!$dbConfig->user OR !$dbConfig->pass OR !$dbConfig->dbhost OR !$dbConfig->dbname OR !$dbConfig->dbtype)
            throw new Exception(__CLASS__." ".__METHOD__.
                                " Sekce s konfiguračními informacemi. Název sekce: ".
                                Framework_Config::SEKCE_DB.", název atributu: ".Framework_Config::ATRIBUT_SEKCE_DATABAZE.", hodnota atribut:".$databaze.
                                " neobsahuje všechny potřebné informace: user, pass, dbhost, dbname, dbtype");
        return $dbConfig;
    }

    /**
     * @return Framework_StatusStorage_Session
     */
    public static function getStorageSession() {
        return Framework_StatusStorage_Session::getInstance();
    }

    /**
     * @return Projektor_User_User
     */
    public static function getUser()
    {
        if(!self::$user) return self::setUser();
        return self::$user;
    }

    private static function setUser()
    {
        $userIdentity = self::getUserIdentity();
        if ($userIdentity) {
            $userItem = new Projektor_Model_Auto_SysUsersItem($userIdentity->getIdentity());
            self::$user = new Projektor_User_User($userIdentity, $userItem);
        } else {
            unset(self::$user);
        }
        return self::$user;
    }

    private static function getUserIdentity() {
        if (!self::$userIdentity) return self::setUserIdentity ();
        return self::$userIdentity;
    }

    private static function setUserIdentity() {
        $authConfig = Framework_Config::najdiSekciPodleJmena(Framework_Config::SEKCE_AUTH);
        if ($authConfig->useauthcookie) {
            $userIdentityStorage2 = new Framework_StatusStorage_CryptCookie();
            $userIdentity = new Projektor_User_Identity(self::getStorageSession(), $userIdentityStorage2);
        } else {
            $userIdentity = new Projektor_User_Identity($userIdentityStorage1);            
        }
        self::$userIdentity = $userIdentity;
        return $userIdentity;
    }

    public static function getPhptalTemplateObject() {
        if (!self::$PHPTALTemplateObject) return self::setPhptalTemplateObject ();
        return self::$PHPTALTemplateObject;
    }

    private static function setPhptalTemplateObject() {
        $directories = Framework_Config::najdiSekciPodleJmena(Framework_Config::SEKCE_DIRECTORIES);
        $phptal = new PHPTAL();
        $phptal->setTemplateRepository(PROJEKTOR_ROOT.$directories->phphtaltemplates);
        self::$PHPTALTemplateObject = new Framework_View_PHPTALTemplateObject($phptal);
        return self::$PHPTALTemplateObject;
    }

    public static function getTwigTemplateObject() {
        if (!self::$twigTemplateObject) return self::setTwigTemplateObject ();
        return self::$twigTemplateObject;
    }

    private static function setTwigTemplateObject() {
        $directories = Framework_Config::najdiSekciPodleJmena(Framework_Config::SEKCE_DIRECTORIES);
        $loader   = new Twig_Loader_Filesystem(PROJEKTOR_ROOT.$directories->twigtemplates);
        $twig  = new Twig_Environment($loader, array('cache' => sys_get_temp_dir()));
        self::$twigTemplateObject = new Framework_View_TwigTemplateObject($twig);
        return self::$twigTemplateObject;
    }

    public static function getKontextFiltrSQL($nazevIdProjekt = NULL, $nazevIdKancelar = NULL, $nazevIdBeh = NULL, $filtr = NULL, $orderBy = NULL, $order = NULL, $vsechnyRadky = FALSE)
    {
        $user = self::getUser();
        $kon = $user->kontext;
        $kontextFiltr =
            ($filtr == "" ? ($vsechnyRadky ? "" : " WHERE valid = 1") : ($vsechnyRadky ? " WHERE {$filtr} " : " WHERE valid = 1 AND {$filtr}")).
            (($kon->projekt AND $nazevIdProjekt) ? " AND `{$nazevIdProjekt}` = {$kon->projekt->id}" : "").
            (($kon->kancelar AND $nazevIdKancelar) ? " AND `{$nazevIdKancelar}` = {$kon->kancelar->id}" : "").
            (($kon->beh AND $nazevIdBeh) ? " AND `{$nazevIdBeh}` = {$kon->beh->id}" : "").
            ($orderBy ? " ORDER BY `{$orderBy}` {$order}" : "");
        return $kontextFiltr;
    }

    public static function setDebug($value)
    {
        self::$debug = (bool) $value;
        return self::$debug;
    }

    public static function getDebug()
    {
        return self::$debug;
    }
}
?>
