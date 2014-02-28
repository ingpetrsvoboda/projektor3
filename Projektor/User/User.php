<?php
/**
 * Description of User
 *
 * @author pes2704
 */
class Projektor_User_User implements Projektor_User_BaseInterface {

    public $identity;
    public $item;

    public function __construct(Projektor_User_IdentityInterface $identity, Projektor_Model_Auto_SysUsersItem $item) {
        $this->identity = $identity;
        $this->item = $item;
    }

    /**
        * Vraci záznamy z vazební tabulky uživatel-projekt sys_acc_usr_projekt,
        * odpovídající zadanému id uživatele.
        */
    public function getPovoleneProjektyCollection()
    {
        $usrProjektCollection = new Projektor_Model_Auto_SysAccUsrProjektCollection();
        if (!$this->identity->getIdentity()) return NULL;
        $usrProjektCollection->where("dbField°id_sys_users", "=", $this->identity->getIdentity());
        foreach ($usrProjektCollection as $item) {
            $list[] = $item->dbField°id_c_projekt;
        }
        $projekty = new Projektor_Model_Auto_CProjektCollection();
        $projekty->where("dbField°id_c_projekt", "IN", $list);
        return $projekty;
    }
    
    /**
     * Vraci záznamy z tabulky přiřazení uživatel-projekt sys_acc_usr_projekt,
     * odpovídající zadanému id uživatele a id projektu.     * @param type $idProjekt
     * @return \Projektor_Model_Auto_CKancelar3Collection|null
     */
    public function getPovoleneKancelare3VProjektuCollection($idProjekt)
    {
        if (!$this->identity->getIdentity()) return NULL;
        $usrKancelarCollection = new Projektor_Model_Auto_SysAccUsrKancelar3Collection;
        $usrKancelarCollection->vyberPovoleneKancelare($this->identity->getIdentity());
        foreach ($usrKancelarCollection as $item) {
            $list[] = $item->dbField°id_c_kancelar3;
        }
        $kancelare = new Projektor_Model_Auto_CKancelar3Collection();
        $kancelare->where("dbField°id_c_kancelar3", "IN", $list);
        $kancelare->where("dbField°id_c_projekt_FK", "=", $idProjekt);

        return $kancelare;
    }
    
    /**
        * Vraci záznamy z tabulky přiřazení uživatel-projekt sys_acc_usr_projekt,
        * odpovídající zadanému id uživatele a id projektu.
        */
    public function getPovoleneKancelareVProjektuCollection()
    {
        $usrKancelarCollectiom = new Projektor_Model_Auto_SysAccUsrKancelarCollection;
        if (!$this->identity->getIdentity()) return NULL;
        $usrKancelarCollectiom->where("dbField°id_sys_users", "=", $this->identity->getIdentity());
        foreach ($usrKancelarCollectiom as $item) {
            $list[] = $item->dbField°id_c_kancelar;
        }
        $kancelare = new Projektor_Model_Auto_CKancelarCollection();
        $kancelare->where("dbField°id_c_kancelar", "IN", $list);
        return $kancelare;
    }

    /**
     *
     * @param type $name jméno užiatele (hodnota zapisovaná do
     * @param type $password
     * @return boolean
     */
    public function signIn($name,$password) {
        $this->item->najdiPodleJmena($name);
        if($this->item->dbField°authtype!=NULL){
            switch ($this->item->dbField°authtype){
                case "password":
                    if($this->item->dbField°password==md5($password)) {
                        $this->identity->setIdentity($this->item->id);
                        return TRUE;
                    } else {
                        return FALSE;
                    }
            }
        }
    }

    public function signOut() {
        $this->identity->unsetIdentity();
    }
    
    public function isSignedIn() {
        if ($this->identity->getIdentity()) {
            return TRUE;
        } else {
            return FALSE;
        }                
    }
}

?>
