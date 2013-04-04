<?php

/**
 * @author George Schlossnagle, kniha Pokročilé programování v PHP5, Zoner Press
 */
class Projektor_App_Auth_Cookie {
    // Proměnné ukládané do cookie se jménem $cookiename metodou _pack() a načítané z cookie metodou _unpack()
    private $created;
    private $userid;
    private $version;

    //Parametry šifrování
    private $td;

    static $cypher = 'blowfish';
    static $mode = 'cfb';
    static $key = '8Fsfr9Ksxxc0008jj81';

    //Nastavení formátu Cookie
    static $cookiename = 'USERAUTH';
    static $myversion = '0.1';
    static $expiration = '6000'; //doba vypršení cookie '6000'
    static $warning = '300'; //doba po které se obnoví-znovu vydá cookie '300'
    static $glue = '|';

    /**
     * Konstruktor.
     * <p>Pokud je volán s parametrem $userid, vytváří se nový objekt Projektor_Auth_Cookie. Konstruktor nastaví vlastnost objektu userid na zadanou hodnotu,
     * ostatní vlastnosti nenastavuje.</p>
     * <p>Pokud je volán bez parametru $userid, obnobuje se objekt Projektor_Auth_Cookie z pole $_COOKIE. Konstruktor zjistí zda již existuje cookie (zjišťuje zda existuje cookie s názvem odpovídajícím konstantě
     * třídy self::$cookiename) a pokud neexistuje vyhodí výjimku Projektor_Auth_Exception. Pokud cookie s daným názvem existuje pokusí
     * se nastavit vlastnosti objektu voláním metody objektu _unpack(). Pokud metoda _unpack() proběhne, vzniklý objekt má všechny vlastnosti
     * ukládané do cookie (created, userid, version).</p>
     * <p>Konstruktor vždy nastaví parametry šifrování podle konstant třídy. </p>
     * @param type $userid Hodnota vlastnosti userid. Je-li zadána vznikne nový objekt Projektor_Auth_Cookie, pokud není načte se objekt Projektor_Auth_Cookie
     *  z pole $_COOKIE
     * @return type
     * @throws Projektor_App_Auth_Exception
     */
    public function __construct($userid = null) {
        $this->td = mcrypt_module_open (self::$cypher, '', self::$mode, '');
        if($userid) {
            $this->userid = $userid;
            return;
        } else {
            if(array_key_exists(self::$cookiename, $_COOKIE)) {
               $this->_unpack($_COOKIE[self::$cookiename]);
            } else {
                throw new Projektor_App_Auth_Exception ("Není cookie");
            }
        }
    }
    public function get_userid() {
        return $this->userid;
    }

    /**
     * Metoda vytvoří cookie voláním php funkce setcookie(). Metoda používá pouze parametry funkce setcookie() name, value a httponly.
     * parametr name - je nastaven na hodnotu konstanty třídy $cookiename,
     * parametr value - je nastaven na hodnotu vrácená metodou třídy _pack().
     * parametr httponly - je nastaven na TRUE pro drobné zvýšení bezpečnosti (viz dokumentace setcookie() )
     * Metoda nepoužívá ostatní parametry funkce setcookie(), nastavuje defaultní hodnoty (viz dokumentace setcookie() ). Vytvořená cookie
     * tedy má platnost do konce session (zavření prohlížeče), platí pro doménu ze které byla odeslána a posílá i nezabezpečeným připojením.
     */
    public function set() {
        $cookie = $this->_pack();
        //function setcookie ($name, $value = null, $expire = 0, $path = null, $domain = null, $secure = false, $httponly = false) {}
        return setcookie(self::$cookiename, $cookie, 0, NULL, NULL, FALSE, TRUE);
    }

    /**
     * Metoda kontroluje zda objekt má nastaveny (true) vlastnosti ukládané do cookie - version, created a userid
     * @throws Projektor_App_Auth_Exception
     * @throws Projektor_Auth_Eception
     */
    public function validate() {
        if(!$this->version || !$this->created || !$this->userid) {
            throw new Projektor_App_Auth_Exception("Poškozená cookie");
        }
        if($this->version != self::$myversion) {
            throw new Projektor_Auth_Eception("Version cookie neodpovídá");
        }
        if (time() - $this->created > self::$expiration) {
            throw new Projektor_App_Auth_Exception("Vypršl čas platnosti cookie");
        }
        else if (time() - $this->created > self::$warning) {
            return $this->set();
        }
    }

    public function reset() {
        return setcookie(self::$cookiename,"",0);
    }

    private function _pack() {
        $parts = array(self::$myversion, time(), $this->userid);
        $cookie = implode(self::$glue, $parts);
        return $this->_encrypt($cookie);
    }

    private function _unpack($cookie) {
        $buffer = $this->_decrypt($cookie);
        list($this->version,$this->created,$this->userid) = explode(self::$glue, $buffer);
        if($this->version != self::$myversion || !$this->created || !$this->userid) {
            throw new Projektor_App_Auth_Exception();
        }
    }

    private function _encrypt($plaintext) {
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($this->td), MCRYPT_RAND);
        mcrypt_generic_init ($this->td, self::$key, $iv);
        $crypttext = mcrypt_generic ($this->td, $plaintext);
        mcrypt_generic_deinit ($this->td);
        return $iv.$crypttext;
    }

    private function _decrypt($crypttext) {
        $ivsize = mcrypt_enc_get_iv_size($this->td);
        $iv = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        mcrypt_generic_init ($this->td, self::$key, $iv);
        $plaintext = mdecrypt_generic ($this->td, $crypttext);
        mcrypt_generic_deinit ($this->td);
        return $plaintext;
    }
    // to sem Schlossnagle napsal, sice se to nepoužívá, ale už to tady zůstalo
    private function _reissue() {
        $this->created = time();
    }
}




?>