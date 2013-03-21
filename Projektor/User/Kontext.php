<?php
/*
 *
 */

class Projektor_User_Kontext
{
    public $projekt;
    public $kancelar;
    public $beh;

    public function __construct() {
        unset($this->projekt);  //pro našeptávání je vlastnost public, pro fungování __get ji tady unsetuji
        unset($this->kancelar);
        unset($this->beh);
//        $this->nactiKontextZCookie();
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
//    private function nactiKontextZCookie() {
//        //Kontrola oprávnění
//        $povoleneProjekty = $this->identity->getPovoleneProjekty();
//        $povoleneKancelare = $this->identity->getPovoleneKancelare();
//
//        //TODO: OPRAVIT COOKIES - kydyž se hlásí jiný uživatel, nastavit cookies projekt, kencelar, beh, debug
//        //TODO: session
//        if(isset($_COOKIE)) {
//            if (array_key_exists('kancelarId', $_COOKIE)) $kancelarZCookie = new Projektor_Data_Auto_CKancelarItem($_COOKIE['kancelarId']);
//            if (array_key_exists('projektId', $_COOKIE)) $projektZCookie = new Projektor_Data_Auto_CProjektItem($_COOKIE['projektId']);
//            if (array_key_exists('behId', $_COOKIE)) $behZCookie = new Projektor_Data_Auto_SBehProjektuItem($_COOKIE['behId']);
//        }
//
//        if ($povoleneProjekty) {
//            if (isset($projektZCookie)) {
//                foreach ($povoleneProjekty as $povolenyProjekt) {
//                    if ($projektZCookie == $povolenyProjekt) {
//                        $projekt = $projektZCookie;
//                        if ($povoleneKancelare) {
//                            if (isset($kancelarZCookie)) {
//                                foreach ($povoleneKancelare as $povolenaKancelar) {
//                                    if ($kancelarZCookie == $povolenaKancelar) $kancelar = $kancelarZCookie;
//                                }
//                            }
//                        } else {
//                            $zprava = "Přihlášený uživatel nemá žádné povolené Kanceláře v projektu ". $projekt->text.". Výpis: ".  print_r($projekt, TRUE);
//                        }
//            //            $behyProjektu = Projektor_Data_Seznam_SBehProjektu::vypisVse();   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
//                        $behyProjektu = new Projektor_Data_Auto_SBehProjektuCollection();
//                        if ($behyProjektu) {
//                            if (isset($behZCookie)) {
//                                foreach ($behyProjektu as $behProjektu) {
//                                    if ($behZCookie == $behProjektu) $beh = $behZCookie;
//                                }
//                            }
//                        } else {
//                            $zprava = "V projektu ". $projekt->text." nejsou nastaveny žádné běhy. Výpis: ".  print_r($projekt, TRUE);
//                        }
//                    }
//                }
//            }
//        } else {
//            $zprava = "Přihlášený uživatel nemá žádné povolené projekty.";
//        }
//
//        if (isset($projekt)) $this->projekt = $projekt;
//        if (isset($kancelar)) $this->kancelar = $kancelar;
//        if (isset($beh))$this->beh = $beh;
//    }
//
//    public function ulozKontextdoCookie () {
//        if ($this->projekt) setcookie("projektId",$this->projekt->id);
//        if ($this->kancelar) setcookie("kancelarId",$this->kancelar->id);
//        if ($this->beh) setcookie("behId",$this->beh->id);
//    }
}

?>
