<?php
/**
 *
 * @author pes2704
 */
interface Projektor_User_BaseInterface {
    public function getPovoleneProjektyCollection();
    public function getPovoleneKancelareVProjektuCollection();
    public function signIn($name,$password);
    public function signOut();
}

?>
