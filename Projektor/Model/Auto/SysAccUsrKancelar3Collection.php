<?php
/**
 * Description of SysAccUsrKancelarCollection
 *
 * @author pes2704
 */
class Projektor_Model_Auto_SysAccUsrKancelar3Collection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SysAccUsrKancelar3Item";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SysAccUsrKancelar3Item, prvek kolekce Projektor_Model_Auto_SysAccUsrKancelar3Collection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SysAccUsrKancelar3Item $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SysAccUsrKancelar3Item
     */
    public function Item($id, Projektor_Model_Auto_SysAccUsrKancelar3Item &$object=NULL){
        $object = new Projektor_Model_Auto_SysAccUsrKancelar3Item($id); //factory na Item
        return $object;
    }

###END_AUTOCODE
    
    public function vyberPovoleneKancelare($idUser) {
        $this->where("dbField°id_sys_users", "=", $idUser);
    }
}

?>
