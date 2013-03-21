<?php
/**
 * Kontejner na globalni promenne
 * @author Petr Svoboda
 */

class Projektor_App_Container
{
    private static $dbObjekty;
    private static $user;
    private static $userIdentity;
    private static $phptal;
    private static $twig;
    private static $debug;


    public static function getDbh($databaze = null)
    {
//            if (!$databaze) $databaze = Projektor_App_Config::DATABAZE_PROJEKTOR;
        if (!$databaze) throw new Exception(__CLASS__." ".__METHOD__." Metoda byla zavolána bez uvedení hodnoty parametru \$databaze");
        if (!self::$dbObjekty OR !array_key_exists($databaze, self::$dbObjekty) OR !self::$dbObjekty[$databaze])
        {
            $dbConfig = Projektor_App_Config::najdiPolozkuPodleAtributu(Projektor_App_Config::SEKCE_DB, Projektor_App_Config::ATRIBUT_SEKCE_DATABAZE, $databaze);
            if (!$dbConfig)
                throw new Exception(__CLASS__." ".__METHOD__." Nenalezena položka v XML souboru s konfiguračními informacemi. Název sekce: ".
                                    Projektor_App_Config::SEKCE_DB.", název atributu: ".Projektor_App_Config::ATRIBUT_SEKCE_DATABAZE.", atribut:".$databaze);
            if (!$dbConfig->user OR !$dbConfig->pass OR !$dbConfig->dbhost OR !$dbConfig->dbname OR !$dbConfig->dbtype)
                throw new Exception(__CLASS__." ".__METHOD__.
                                    " Sekce s konfiguračními informacemi. Název sekce: ".
                                    Projektor_App_Config::SEKCE_DB.", název atributu: ".Projektor_App_Config::ATRIBUT_SEKCE_DATABAZE.", hodnota atribut:".$databaze.
                                    " neobsahuje všechny potřebné informace: user, pass, dbhost, dbname, dbtype");
            switch ($dbConfig->dbtype) {
                case Projektor_App_Config::DB_TYPE_MYSQL :
                    self::$dbObjekty[$databaze] = new Projektor_DB_Mysql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;
                    break;
                case Projektor_App_Config::DB_TYPE_MSSQL :
                    self::$dbObjekty[$databaze] = new Projektor_DB_Mssql($dbConfig->user, $dbConfig->pass, $dbConfig->dbhost, $dbConfig->dbname) ;
                    break;
                default:
                    throw new Exception(__CLASS__." ".__METHOD__." V konfigutaci (Projektor_App_Config) neexistuje zadaný typ databáze: ".$dbConfig->dbtype);
            }
        }
        return self::$dbObjekty[$databaze];
    }

    public static function getUser()
    {
        if(!self::$user) self::setUser();
        return self::$user;
    }

    private static function setUser()
    {
        $userIdentity = self::getUserIdentity();
        $userItem = new Projektor_Data_Auto_SysUsersItem($userIdentity->getIdentity());
        $u = new Projektor_User_Base($userIdentity, $userItem);
        self::$user = $u;
        return $u;
    }

    private static function getUserIdentity() {
        if (!self::$userIdentity) self::setUserIdentity ();
        return self::$userIdentity;
    }

    private static function setUserIdentity() {
        $userIdentityStorage = new Projektor_App_Storage_Session('PROJEKTOR_USER_IDENTITY');
        $authConfig = Projektor_App_Config::najdiSekciPodleJmena(Projektor_App_Config::SEKCE_AUTH);
        $userIdentity = new Projektor_User_Identity($userIdentityStorage, $authConfig->useauthcookie);
        self::$userIdentity = $userIdentity;
        return $userIdentity;
    }

    public static function getPhptal() {
        return self::setPhptal ();
    }

    public static function setPhptal() {
        $directories = Projektor_App_Config::najdiSekciPodleJmena(Projektor_App_Config::SEKCE_DIRECTORIES);
        $phptal = new PHPTAL();
        $phptal->setTemplateRepository($directories->phphtaltemplates);
        self::$phptal = $phptal;
        return self::$phptal;
    }

    public static function getTwig() {
        return self::setTwig ();
    }

    public static function setTwig() {
        $directories = Projektor_App_Config::najdiSekciPodleJmena(Projektor_App_Config::SEKCE_DIRECTORIES);
        $loader   = new Twig_Loader_Filesystem($directories->twigtemplates);
        $twig  = new Twig_Environment($loader, array('cache' => sys_get_temp_dir()));
        self::$twig = $twig;
        return self::$twig;
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
        self::$debug = $value;
        return self::$debug;
    }

    public static function getDebug()
    {
        return self::$debug;
    }
}
?>
