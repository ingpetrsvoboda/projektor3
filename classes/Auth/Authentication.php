<?php
//require_once "DB.inc";

//class Authentication {
class Auth_Authentication {
    function check_credentials($name,$password) {
        $user = Data_Sys_Users::najdiPodleJmena($name);
                if($user->authtype!=NULL){
            switch ($user->authtype){
                case "password":
                    $userSHeslem = Data_Sys_Users::najdiPodleJmenaHesla($name, $password);
                    if($userSHeslem) 
                    {
                        return $userSHeslem->id;
                    }
                    else 
                    {
                        return false;
                    }
            }
        }
    }
}
            
?>