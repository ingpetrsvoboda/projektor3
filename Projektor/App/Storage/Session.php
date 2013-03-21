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
class Projektor_App_Storage_Session implements Projektor_App_Storage_StorageInterface {

    const DEFAULT_SESSION_NAME = 'PROJEKTOR_SESSION';
    private static $sessionNames=array();

    private $sessionName;
    private $oldSessionName;
    private $oldSessionStatus;

    public function __construct($sessionName = self::DEFAULT_SESSION_NAME) {
        if (is_numeric($sessionName)) throw new Projektor_App_Storage_Exception('Nelze vytvořit session s identifikátorem: ',$sessionName,'. Identifikátor nesmí obsahovat poze číslice.');
        if (array_search($sessionName, self::$sessionNames)===FALSE) {
            $this->sessionName = $sessionName;
            self::$sessionNames[] = $sessionName;
        } else {
            throw new Projektor_App_Storage_Exception('CHYBA - opakované vytvoření instance třídy '.__CLASS__.' se stejným parametrem. '.
                    'Nelze vytvořit session storage s identifikátorem: ',$sessionName,'. Session storage s tímto identifikátorem již existuje.');
        }
    }

    public function read($name) {
        $this->flipOldToNewSession();
        $value = $_SESSION[$name];
        $this->flopNewToOldSession();
        return $value;
    }

    public function write($name, $value) {
        $this->flipOldToNewSession();
        $_SESSION[$name] = $value;
        $this->flopNewToOldSession();
        return $value;
    }

    private function flipOldToNewSession() {
        $this->oldSessionName = session_name($this->sessionName);
        if ($this->oldSessionName!=$this->sessionName) {        //stará je jiná než nová
            $this->oldSessionStatus = session_status();
            if ($this->oldSessionStatus==PHP_SESSION_ACTIVE) {  // stará je aktivní //konstanta je definována až od php 5.4
                session_write_close();                          // tak starou zavři
            }
        }
        if (session_status()==PHP_SESSION_NONE) session_start (); // pokud není nová aktivní, tak ji otevři
    }

    private function flopNewToOldSession() {
        if ($this->oldSessionStatus==PHP_SESSION_ACTIVE) {  // stará byla aktivní //konstanta je definována až od php 5.4
            session_write_close();                          // zavři novou
            session_name($this->oldSessionName);
            session_start();                                // otevři starou
        }
    }
}

?>
