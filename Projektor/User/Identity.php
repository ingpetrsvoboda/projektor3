<?php
/**
 * Description of Identity
 *
 * @author pes2704
 */
class Projektor_User_Identity implements Projektor_User_IdentityInterface {
    const IDENTITY_NAME = 'identity';
    
    private $identity;
    /**
     * Instanční proměnná
     * @var Framework_StatusStorage_StatusStorageInterface
     */
    private $storage1;
    /**
     * Instanční proměnná
     * @var boolean
     */
    private $storage2;

    /**
     * 
     * Příklady:
     * </p>- identita se ukládá pouze do session - konstuktor se volá jen s prvním parametrem typu Framework_Storage_Session, 
     * při přihlášení ulož do session voláním metody ->setIdentity a při odhlášení smaž ze session voláním metody ->unsetIdentity 
     * </p>- identita se ukládá do session a pro kontrolu ještě do zašifrované cookie - - konstuktor se volá s oběma parametry, první typu 
     * Framework_Storage_Session a druhý typu Framework_Cookie_CryptCookie, 
     * při přihlášení ulož do session i do cookie voláním metody ->setIdentity a při odhlášení smaž ze session i z cookie voláním metody ->unsetIdentity 
     * @param Framework_StatusStorage_StatusStorageInterface $storage1
     * @param Framework_StatusStorage_StatusStorageInterface $storage2
     */
    public function __construct(Framework_StatusStorage_StatusStorageInterface $storage1, Framework_StatusStorage_StatusStorageInterface $storage2 = NULL) {
        $this->storage1 = $storage1;
        $this->storage2 = $storage2;
        }

    public function getIdentity() {
        try {
            if (!isset($this->identity)) {
                $sessionIdentity = $this->storage1->read(self::IDENTITY_NAME);
                if ($this->storage2) {
                    $cookieIdentity = $this->storage2->read(self::IDENTITY_NAME);
                }
                return $this->checkIdentity($sessionIdentity, $cookieIdentity);
            }
            return $this->identity;
        } catch (Framework_StatusStorage_Exception $e) {  //TODO: nepracuje se s exception - má nastat fatální chyba nebo login? (při loginu loguj!
                return NULL;
        }
    }

    /**
     * Metoda zapíše parametr $identity do storage zadané jako instanční proměnná objektu (parametr konstruktoru)
     * a v přépadě, že nastavena instační proměnná checkAuthCookie na TRUE, zapíše parametr $identity také do Framework_Auth_Cookie
     * @param type $identity
     * @return void
     */
    public function setIdentity($identity) {
        try {
            $sessionIdentity = $this->storage1->write(self::IDENTITY_NAME, $identity);
            if ($this->storage2) {
                $cookieIdentity = $this->storage2->write(self::IDENTITY_NAME, $identity);
            }
            return $this->checkIdentity($sessionIdentity, $cookieIdentity);
        } catch (Framework_StatusStorage_Exception $e) {  //TODO: nepracuje se s exception - má nastat fatální chyba nebo login? (při loginu loguj!
                return NULL;
        }
    }

    public function unsetIdentity() {
        try {
            $sessionIdentity = $this->storage1->destroy(self::IDENTITY_NAME);
            if ($this->storage2) {
                $cookieIdentity = $this->storage2->destroy(self::IDENTITY_NAME);
            }
            return $this->checkIdentity($sessionIdentity, $cookieIdentity);
        } catch (Framework_StatusStorage_Exception $e) {  //TODO: nepracuje se s exception - má nastat fatální chyba nebo login? (při loginu loguj!
                return NULL;
        }
    }
    
    private function checkIdentity($sessionIdentity, $cookieIdentity) {
        if (!$this->storage2) {
            if (isset($sessionIdentity) AND $sessionIdentity) {
                $this->identity = $sessionIdentity;
                return $this->identity;
            }
        } else {
            if (isset($sessionIdentity) AND $sessionIdentity  AND isset($cookieIdentity) AND $cookieIdentity AND $sessionIdentity==$cookieIdentity) {
                $this->identity = $sessionIdentity;
                return $this->identity;                
            }
        }
        unset($this->identity);
        return FALSE;
    }
}

?>
