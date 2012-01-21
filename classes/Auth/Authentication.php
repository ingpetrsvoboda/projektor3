<?php
//require_once "DB.inc";

//class Authentication {
class Auth_Authentication {
    function check_credentials($name,$password) {
        $dbh = App_Kontext::getDbMySQL();
        $query = "SELECT authtype,id_sys_users FROM sys_users WHERE username=:1";
        $data = $dbh->prepare($query)->execute($name)->fetch_assoc();
        if($data['authtype']!=NULL){
            switch ($data['authtype']){
                case "password":
                    $query = "SELECT id_sys_users FROM sys_users
                                WHERE username= :1
                                AND password =:2";
                    $data = $dbh->prepare($query)->execute($name,md5($password))->fetch_assoc();
                    if($data) {
                        return $data['id_sys_users'];
                    }
                    else {
                        return false;
                    }
            }
        }
    }
}
            
?>