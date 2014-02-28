<?php
class Projektor_Model_Identifikator {
	public $cislo_kontextu;
	public $cislo_rozsahu;
 	public $cislo_projektu;
        public $cislo_behu;
	public $cislo_polozky;
	public $identifikator;


	public function __construct($cislo_kontextu = NULL, $cislo_rozsahu = NULL, $cislo_projektu = NULL, $cislo_behu = null, $cislo_polozky = NULL, $identifikator = null) {
		$this->cislo_kontextu = $cislo_kontextu;
		$this->cislo_rozsahu = $cislo_rozsahu;
		$this->cislo_projektu = $cislo_projektu;
		$this->cislo_behu = $cislo_behu;
		$this->cislo_polozky = $cislo_polozky;
                $this->identifikator = $identifikator;
	}
        /*
         * Metoda sestaví desetimístný číselný identifikátor jako posloupnost 
         * jednimistneho čisla kontextu, dvoumistných čísel tozsahu, projektu, behu
         * a trimistneho cisla polozky
         */
	public function generuj($cislo_kontextu = NULL, $cislo_rozsahu = NULL, $cislo_projektu = NULL, $cislo_behu = null, $cislo_polozky = NULL){

		//Kontrola zadanych parametru
		if($cislo_projektu &&  $cislo_rozsahu && $cislo_kontextu && $cislo_behu && $cislo_polozky) {
                    $retez = "";
                    if (Projektor_Model_Identifikator::pridejCislo($retez, $cislo_kontextu,1)){
                            if (Projektor_Model_Identifikator::pridejCislo($retez, $cislo_rozsahu,2)){
                                if (Projektor_Model_Identifikator::pridejCislo($retez, $cislo_projektu,2)){
                                    if (Projektor_Model_Identifikator::pridejCislo($retez, $cislo_behu,2)){
                                        if (Projektor_Model_Identifikator::pridejCislo($retez, $cislo_polozky,3)){
                                            return new Projektor_Model_Identifikator($cislo_kontextu, $cislo_rozsahu, $cislo_projektu, $cislo_behu, $cislo_polozky, $retez);
                                        }
                                    }
                                }
                            }
                        }
		}
		return FALSE;
	}

    /*
     * Metoda na konec řetězce čislo ve formátu se zadanym počtem míst
     */
    private function pridejCislo( &$retezec, $cislo,$pocet_mist)
    {
        if(is_int($pocet_mist) && $pocet_mist>0) {
            $r = strval($cislo);
            if (strlen($r) <= $pocet_mist) {
                $retezec = $retezec . str_pad($r, $pocet_mist, "0", STR_PAD_LEFT);
                return true;
            } else {
                return false;
            }
        } else {
            return false;            
        }
    }
    
}
?>