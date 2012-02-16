<?php
/**
 * @author Petr Svoboda
 * @since Fri, 12 Oct 20011 19:01:55 +0200
 */

class Data_Sys_Users extends Data_Iterator
{

	public $id;

        public $username;
        public $name;
        public $authtype;
        public $povolen_zapis;

	const TABULKA = "sys_users";
        const ID = "id_sys_users";
        const USERNAME = "username";
        const NAME = "name";
        const AUTHTYPE = "authtype";
        const DEBUG = "debug";
        const POVOLEN_ZAPIS = "povolen_zapis";

    public function __construct($username, $name, $authtype, $debug, $povolen_zapis, $id=NULL)
    {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->authtype = $authtype;
        $this->debug = $debug;
        $this->povolen_zapis = $povolen_zapis;
        parent::__construct(__CLASS__);
    }

    /**
    * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
    * @param int $id Identifikator radku tabulky
    * @return Data_Akce Instance tridy obsahujici data z radku v tabulce
    */

    public static function najdiPodleId($id)
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $query = "SELECT * FROM ~1 WHERE ~2 = :3";
        $radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

        if(!$radek) return false;

        return new Data_Sys_Users($radek[self::USERNAME], $radek[self::NAME], $radek[self::AUTHTYPE], $radek[self::DEBUG], $radek[self::POVOLEN_ZAPIS], $radek[self::ID]);
    }

    /**
    * Najde a vrati jeden (prvni nalezeny) radek tabulky v DB s prislusnym jmenem a healem.
    * @param int $id Identifikator radku tabulky
    * @return Data_Akce Instance tridy obsahujici data z radku v tabulce
    */
   public static function najdiPodleJmena($name)
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $query = "SELECT * FROM ~1 WHERE username= :2";
        $radek = $dbh->prepare($query)->execute(self::TABULKA, $name)->fetch_assoc();

        if(!$radek) return false;

        return new Data_Sys_Users($radek[self::USERNAME], $radek[self::NAME], $radek[self::AUTHTYPE], $radek[self::DEBUG], $radek[self::POVOLEN_ZAPIS], $radek[self::ID]);
    }
    
    /**
    * Najde a vrati jeden (prvni nalezeny) radek tabulky v DB s prislusnym jmenem a healem.
    * @param int $id Identifikator radku tabulky
    * @return Data_Akce Instance tridy obsahujici data z radku v tabulce
    */
   public static function najdiPodleJmenaHesla($name, $password)
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $query = "SELECT * FROM ~1 WHERE username= :2 AND password =:3";
        $radek = $dbh->prepare($query)->execute(self::TABULKA, $name,md5($password))->fetch_assoc();

        if(!$radek) return false;

        return new Data_Sys_Users($radek[self::USERNAME], $radek[self::NAME], $radek[self::AUTHTYPE], $radek[self::DEBUG], $radek[self::POVOLEN_ZAPIS], $radek[self::ID]);
    }
    
    /**
    * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
    * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
    * @return array() Pole instanci tridy odpovidajici radkum v DB
    */
    public static function vypisVse($filtr = "", $orderBy = "", $order = "")
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $query = "SELECT ~1 FROM ~2".
            ($filtr == "" ? "" : " WHERE ({$filtr})").
            ($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;

        $radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

        foreach($radky as $radek)
        $vypis[] = self::najdiPodleId($radek[self::ID]);

        return $vypis;
    }

    /**
    * Vymaze radek v databazi odpovidajici parametru $id tridy
    * @return unknown_type
    */
    public static function smaz()
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        $query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
        $dbh->prepare($query)->execute(self::TABULKA, self::ID, $this->id);
    }

    /**
    * Ulozi parametry tridy jako radek do DB.
    * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
    */
    public function uloz()
    {
        $dbh = App_Kontext::getDbMySQLProjektor();
        if($this->id == null)
        {
            $query = "INSERT INTO ~1 (~2, ~3, ~4, ~5) VALUES (:6, :7, :8, :9)";
            return $dbh->prepare($query)->execute(
                    self::TABULKA, self::USERNAME, self::NAME, self::DEBUG, self::POVOLEN_ZAPIS,
                    $this->username, $this->name, $this->debug, $this->povolen_zapis
                )->last_insert_id();
        }
        else
        {
            $query = "UPDATE ~1 SET ~2=:6, ~3=:7, ~4=:8, ~5=:9 WHERE ~10=:11";
            $dbh->prepare($query)->execute(
                    self::TABULKA, self::USERNAME, $this->username, self::NAME, $this->name, self::DEBUG, $this->debug, self::POVOLEN_ZAPIS, $this->povolen_zapis,
                    self::ID, $this->id);
            return true;
        }
    }


}
?>
