<?php
/**
 *
 * @author pes2704
 */
interface Projektor_User_BaseInterface {
    public function getPovoleneProjektyCollection();
    public function getPovoleneKancelareCollection();
    public function login($name,$password);
    public function logout();
}

?>
