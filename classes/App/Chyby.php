<?php

class App_Chyby {

    public $pocet = 0;
    public $promnenna = array();
    public $hodnota = array();
    public $chyba_cislo = array();
    public $chyba_text = array();

    /**
     * 
     * @param <type> $promnenna
     * @param <type> $hodnota
     * @param <type> $chyba_cislo
     */
    public function write($promnenna, $hodnota, $chyba_cislo) {

        $this->promnenna[$this->pocet] = $promnenna;
        $this->hodnota[$this->pocet] = $hodnota;
        $this->chyba_cislo[$this->pocet] = $chyba_cislo;
        $this->pocet++;
    }

}

?>