<?php

/*
 * Varianty:
 * - identita se ukládá pouze do session - při přihlášení ulož do session, při odhlášení smaž ze session
 * - identita se ukládá do session a pro kontrolu ještě do auth cookie - při přihlášení ulož do session a nastav auth cookie
 */

/**
 * Description of Identity
 *
 * @author pes2704
 */
class Projektor_User_Identity implements Projektor_User_IdentityInterface {

    private $identity;
    /**
     * Instanční proměnná
     * @var Projektor_App_Storage_StorageInterface
     */
    private $storage;
    /**
     * Instanční proměnná
     * @var boolean
     */
    private $checkAuthCookie;

    public function __construct(Projektor_App_Storage_StorageInterface $storage, $checkAuthCookie = TRUE) {
        $this->storage = $storage;
        $this->checkAuthCookie = $checkAuthCookie;
        }

    public function getIdentity() {
        if (!$this->identity) $this->identity = $this->storage->read('identity');
        if ($this->checkAuthCookie) {
            try {
                $authCookie = new Projektor_App_Auth_Cookie();  //volání bez parametru = třída se pokusí vytvořit objent s pole $_COOKIE, pokud neuspěje vyhodí Projektor_Auth_Exception
                $authCookie->validate();  //při neúspěšné validaci cookie vyhodí exception Projektor_Auth_Exception
                $authCookieIdentity = $authCookie->get_userid();
                if ($this->identity == $authCookieIdentity) return $this->identity;
                return NULL;
            } catch (Projektor_App_Auth_Exception $e) {  //TODO: nepracuje se s exception - fatální chyba nebo login?
                return NULL;
            }
        }
        return $this->identity;
    }

    /**
     * Metoda zapíše parametr $identity do storage zadané jako instanční proměnná objektu (parametr konstriktoru)
     * a v přépadě, že nastavena instační proměnná checkAuthCookie na TRUE, zapíše parametr $identity také do Projektor_App_Auth_Cookie
     * @param type $identity
     * @return void
     */
    public function setIdentity($identity) {
        $this->identity = $identity;
        $this->storage->write('identity', $identity);
        if ($this->checkAuthCookie) {
            $cookie = new Projektor_App_Auth_Cookie($identity);
            $cookie->set();
        }
        return;
    }

    public function unsetIdentity() {
        $this->storage->write('identity', NULL);
        return;
    }
}

?>
