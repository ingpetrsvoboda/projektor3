<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author pes2704
 */
class Framework_StatusStorage_Session implements Framework_StatusStorage_StatusStorageInterface {

    const DEFAULT_SESSION_NAME = 'FRAMEWORK_STORAGE_SESSION';
    
    /**
     * Statická proměnná. Pokud je nastavena nelze již instancovat objekt Framework_Storage_Session.
     * @var type 
     */
    private static $session;

    public static function getInstance() {
        if (self::$session) {
            return self::$session;
        } else {
            return new self;
        }
    }
    
    /**
     * Konstriktor Framework_Storage_Session. Objekt Framework_Storage_Session je singleton.
     * @param type $sessionName
     * @throws Framework_StatusStorage_Exception
     */
    private function __construct() {
            self::$session = $this;
    }

    /**
     * 
     * @param type $name
     * @return mixed/null
     * @throws Framework_StatusStorage_Exception
     */
    public function read($name) {
        if (!$this->_isCorrectName($name)) throw new Framework_StatusStorage_Exception("Nepřípustné jméno pro uložení hodnoty");
        if (session_status()==PHP_SESSION_NONE) {
            session_start (); 
        }
        if (isset($_SESSION[$name])) {
            $value = $_SESSION[$name];
            return $value;
        } else {
            return FALSE;            
        }
    }

    public function write($name, $value) {
        if (!$this->_isCorrectName($name)) throw new Framework_StatusStorage_Exception("Nepřípustné jméno pro uložení hodnoty");       
        if (!is_scalar($value)) throw new Framework_StatusStorage_Exception("Nepřípustná (neskalární) hodnota pro uložení"); 
        if (session_status()==PHP_SESSION_NONE) {
            session_start (); 
        }
        $_SESSION[$name] = $value;
        return $_SESSION[$name];
    }

    /**
     * Metoda odstraní (unset) hodnotu ze session.
     * @param type $name Název hodnoty
     * @return mixed Výsledná hodnota v session. Pokud je metoda úspěšná vrací NULL.
     * @throws Framework_StatusStorage_Exception
     */
    public function destroy($name) {
        if (!$this->_isCorrectName($name)) throw new Framework_StatusStorage_Exception("Nepřípustné jméno pro odstranění hodnoty");       
        unset($_SESSION[$name]);
        return $_SESSION[$name];
    }
    
    private function _isCorrectName($name) {
        $nameString = (string) $name;
        if (!is_string($nameString)) return FALSE;
        return TRUE;
    }
    
    public function __destruct() {
//        if (session_status()!=PHP_SESSION_NONE) session_write_close(); 
        session_write_close(); 
    }
}

?>
