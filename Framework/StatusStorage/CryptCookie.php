<?php
class Framework_StatusStorage_CryptCookie implements Framework_StatusStorage_StatusStorageInterface {
    private static $cookies=array();

    // Proměnné ukládané do cookie se jménem COOKIENAME metodou _pack() a načítané z cookie metodou _unpack()
    private $created;
    private $content;
    private $version;

    //Parametry šifrování
    private $td;

    const CIPHER = 'blowfish';
    const MODE = 'cfb';
    const KEY = '8Fsfr9Ksxxc0008jj81';

    //Nastavení formátu Cookie
    const MY_VERSION = '0.1';
    const EXPIRATION_TIME = '6000'; //doba vypršení cookie '6000'
    const REISSUE_TIME = '300'; //doba po které se obnoví-znovu vydá cookie '300'
    const GLUE = '|';

    /**
     * Konstruktor.
     * Konstruktor nastaví parametry šifrování podle konstant třídy.
     */
    public function __construct() {   
        $this->td = mcrypt_module_open (self::CIPHER, '', self::MODE, '');        
    }

    /**
     * Metoda přečte obsah crypt cookie. Pokud byla cookie se zadaným jménem nově vytvořena v aktuálním běhu skriptu, metoda přečte 
     * obsah této nové cookie. Jinak metoda přečte obsah z pole $_COOKIE.
     * @param type $name Jméno cookie
     * @return mixed/boolean Vpřípadě úspěšného přečtení crypt cookue metoda vrací obsah cookie, jinak FALSE.
     */
    public function read($name) {
        if (!$this->_isCorrectName($name)) throw new Framework_StatusStorage_Exception("Nepřípustné jméno cookie");  
        if (isset(self::$cookies[$name])) {
            $this->_unpack(self::$cookies[$name]);
            return $this->content;
        } elseif (isset($_COOKIE[$name])) {
            $this->_unpack($_COOKIE[$name]);
            return $this->content;
        }
        return FALSE;
    }

    /**
     * Metoda zapíše crypt cookie se zadaným jménem a hodnotou
     * Metoda vytvoří cookie voláním php funkce setcookie(). Metoda používá pouze parametry funkce setcookie() name, value a httponly.
     * parametr name - je nastaven na hodnotu konstanty třídy COOKIENAME,
     * parametr value - je nastaven na hodnotu vrácená metodou třídy _pack().
     * parametr httponly - je nastaven na TRUE pro drobné zvýšení bezpečnosti (viz dokumentace setcookie() )
     * Metoda nepoužívá ostatní parametry funkce setcookie(), nastavuje defaultní hodnoty (viz dokumentace setcookie() ). Vytvořená cookie
     * tedy má platnost do konce session (zavření prohlížeče), platí pro doménu ze které byla odeslána a posílá i nezabezpečeným připojením.
     * @param string $name
     * @param type $value
     * @return bool TRUE když funkce setcookie() proběhla úspěšně a vytvořená cookie je připravena na výstupu, jinak FALSE
     * @throws Framework_StatusStorage_Exception
     */
    public function write($name, $value) {
        if (!$this->_isCorrectName($name)) throw new Framework_StatusStorage_Exception("Nepřípustné jméno cookie");      
        if (!is_scalar($value)) throw new Framework_StatusStorage_Exception("Nepřípustná (neskalární) hodnota pro uložení");         
        $this->content = $value;
        $this->version = self::MY_VERSION;
        $this->created = time();
        $cookie = $this->_pack();
        //function setcookie ($name, $value = null, $expire = 0, $path = null, $domain = null, $secure = false, $httponly = false) {}
        $res = setcookie($name, $cookie, 0, NULL, NULL, FALSE, TRUE);
        if ($res) self::$cookies[$name] = $cookie;
        return $res;
    }

    /**
     * Metoda odstraní cookie. Pokud existuje odstraní (unset) předtím nově vytvořenou cookie v aktuálním běhui skriptu, 
     * odstraní (unset) prvek z pole $_COOKIE, odešle do prohůlížeče cookie s časem expirace v minulosti a tím vynutí smazání cookie 
     * v prohlížeči po příjetí response prohlížečem.
     * @param string $name
     * @return bool TRUE když funkce setcookie() proběhla úspěšně a vytvořená cookie je připravena na výstupu, jinak FALSE
     * @throws Framework_StatusStorage_Exception
     */
    public function destroy($name) {
        if (!$this->_isCorrectName($name)) throw new Framework_StatusStorage_Exception("Nepřípustné jméno cookie");
        if (isset(self::$cookies[$name])) unset(self::$cookies[$name]);
        if(isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
            return setcookie($name, '', time() - 3600); // čas vypršení cookie v minulosti způsobí smazání cookie v prohlížeči při příštím requestu
        }
    }
            
    private function _isCorrectName($name) {
        $nameString = (string) $name;
        if (!is_string($nameString)) return FALSE;
        return TRUE;
    }
    
    /**
     * Metoda sloučí jednotlivé hodnoty ukládané do cookie - version, created a content a zašifruje je.
     * @return string Zašifrovaná hodnota pro uložení do cookie.
     */
    private function _pack() {
        $parts = array($this->version, $this->created, $this->content);
        $cookie = implode(self::GLUE, $parts);
        return $this->_encrypt($cookie);
    }

    /**
     * Metoda rozšifruje cookie použitím metody $this->_decrypt(), rozloží obsah cookie na jednotlivé hodnoty 
     * ukládané do cookie - version, created a content. Metoda kontroluje zda všechny hodnoty mají nějaký obsah (jsou vyhodnoceny jako  true), 
     * zda verze cookie odpovídá a zda nevypršela platnost cookie. Pokud některá kontrola selže, metoda vyhodí příslušnou výjimku.
     * Pokud doba života cookie překročila dobu pro znovuvydání cookie (self::REISSUE_TIME), metoda cookie obnoví voláním metody $this->_reiisue()
     * @param type $cookie Cookie, prvek pole $_COOKIE
     * @return boolean
     * @throws Framework_StatusStorage_Exception
     */
    private function _unpack($cookie) {
        $buffer = $this->_decrypt($cookie);
        list($this->version,$this->created,$this->content) = explode(self::GLUE, $buffer);
        if(!$this->version || !$this->created || !$this->content) {
            throw new Framework_StatusStorage_Exception("Poškozená cookie");
        }
        if($this->version != self::MY_VERSION) {
            throw new Framework_StatusStorage_Exception("Version cookie neodpovídá");
        }
        if (time() - $this->created > self::EXPIRATION_TIME) {
            throw new Framework_StatusStorage_Exception("Vypršel čas platnosti cookie");
        }
        else if (time() - $this->created > self::REISSUE_TIME) {
            $this->_reissue();
        }
        return TRUE;
    }

    private function _encrypt($plaintext) {
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($this->td), MCRYPT_RAND);
        mcrypt_generic_init ($this->td, self::KEY, $iv);
        $crypttext = mcrypt_generic ($this->td, $plaintext);
        mcrypt_generic_deinit ($this->td);
        return $iv.$crypttext;
    }

    private function _decrypt($crypttext) {
        $ivsize = mcrypt_enc_get_iv_size($this->td);
        $iv = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        mcrypt_generic_init ($this->td, self::KEY, $iv);
        $plaintext = mdecrypt_generic ($this->td, $crypttext);
        mcrypt_generic_deinit ($this->td);
        return $plaintext;
    }

    private function _reissue() {
        $this->created = time();
    }
}




?>