<?php

/**
 *
 * @author pes2704
 */
interface Projektor_Stranka_GeneratorInterface {
    public function generuj();
    public function volejMetoduVychozi();
      //$uzelPotomek je potřeba v potomkovské metodě stránek, které pro potomek není generují seznam
      // a v potomkovské metodě generují položku. Pro generování položky je potřeba znát parametry potomka
    public function volejMetoduPotomkovskou(Projektor_Stranka_Base $strankaPotomek);
    public function volejMetoduPotomekNeni();
        /* volame privatni metodu, ktera nam generuje promenne pro obsah, ktery zobrazujeme vzdy, bez ohledu na potomka */
    public function volejMetoduVzdy();
}

?>
