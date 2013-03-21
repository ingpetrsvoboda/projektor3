<?php
/**
 * Description of User
 *
 * @author pes2704
 */
class Projektor_User_Base implements Projektor_User_BaseInterface {

    public $identity;
    public $item;

    public function __construct(Projektor_User_IdentityInterface $identity, Projektor_Data_Auto_SysUsersItem $item) {
        $this->identity = $identity;
        $this->item = $item;
    }

    /**
        * Vraci záznamy z vazební tabulky uživatel-projekt sys_acc_usr_projekt,
        * odpovídající zadanému id uživatele.
        */
    public function getPovoleneProjektyCollection()
    {
        $usrProjektCollection = new Projektor_Data_Auto_SysAccUsrProjektCollection();
        if (!$this->identity->getIdentity()) return NULL;
        $usrProjektCollection->where("dbField°id_sys_users", "=", $this->identity->getIdentity());
        foreach ($usrProjektCollection as $item) {
            $list[] = $item->dbField°id_c_projekt;
        }
        $projekty = new Projektor_Data_Auto_CProjektCollection();
        $projekty->where("dbField°id_c_projekt", "IN", $list);
        return $projekty;
    }

    /**
        * Vraci záznamy z tabulky přiřazení uživatel-projekt sys_acc_usr_projekt,
        * odpovídající zadanému id uživatele a id projektu.
        */
    public function getPovoleneKancelareCollection()
    {
        $usrKancelarCollectiom = new Projektor_Data_Auto_SysAccUsrKancelarCollection;
        if (!$this->identity->getIdentity()) return NULL;
        $usrKancelarCollectiom->where("dbField°id_sys_users", "=", $this->identity->getIdentity());
        foreach ($usrKancelarCollectiom as $item) {
            $list[] = $item->dbField°id_c_kancelar;
        }
        $kancelare = new Projektor_Data_Auto_CKancelarCollection();
        $kancelare->where("dbField°id_c_kancelar", "IN", $list);
        return $kancelare;
    }

    /**
     *
     * @param type $name jméno užiatele (hodnota zapisovaná do
     * @param type $password
     * @return boolean
     */
    public function login($name,$password) {
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

    public function logout() {
        $this->identity->unsetIdentity();
    }
}

?>
