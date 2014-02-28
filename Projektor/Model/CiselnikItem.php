<?php
/**
 * @author Petr Svoboda
 *
 */
abstract class Projektor_Model_CiselnikItem extends Projektor_Model_Item
{

    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°text";


    public function __construct($id = NULL) {
        parent::__construct($id);
        $this->jeCiselnikOK();
    }

    /**
     * Zkontroluje, zda v DB existuje tabulka s nazvem zadanym jako parametr $nazevCiselniku, v tabulce
     * existují povolené nebo jen povolené sloupce číselníku a existuje sloupec s nazvem PREFIX_NAZEV_ID.$nazevCiselniku, který je primárním klíčem tabulky,
     * @param type $strict Pokud parament není zadán nebo je FALSE, metoda kontroluje, jestli tabulka číselníku obsahuje primární klíč a povinné sloupce. 
     * Pokud je TRUE metoda kontroluje i jestli tabulka neobsahuje sloupce, které nejsou povinné (jsou navíc).
     * @return boolean
     * @throws Projektor_Model_Exception
     */
    private static function jeCiselnikOK($strict=NULL)
    {
        try
        {
            $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA);
            if (!$strukturaTabulky->primaryKeyFieldName) throw new Projektor_Model_Exception(
                    "Chybná struktura číselníku - db tabulka: ".static::TABULKA." nemá primární klíč.");

            $ciselnikColumns = array(
                                    "razeni"=>FALSE,
                                    "kod"=>FALSE,
                                    "text"=>FALSE,
                                    "plny_text"=>FALSE,
                                    "valid"=>FALSE
                                    );
//            foreach ($strukturaTabulky->nazvy as $nazev)
            foreach ($strukturaTabulky->sloupce as $sloupec)
            {
                if (key_exists($sloupec->nazev, $ciselnikColumns))
                {
                    $ciselnikColumns[$sloupec->nazev] = TRUE;
                } else {
                    if ($strict AND $sloupec->nazev!=$strukturaTabulky->primaryKeyFieldName)
                        throw new Projektor_Model_Exception(
                    "Chybná struktura číselníku - db tabulka: ".static::TABULKA." obsahuje sloupec: ".$sloupec->nazev.", který je v číselníku nepřípustný.");
                }
            }
            foreach ($ciselnikColumns as $nazev=>$hodnota)
            {
                if (!$hodnota)   throw new Projektor_Model_Exception(
                    "Chybná struktura číselníku - db tabulka: ".static::TABULKA." neobsahuje sloupec: ".$nazev.", který je v číselníku nutný.");
            }
            return TRUE;
//                $iterator = $this->getIterator();  //načte item
//            foreach ($iterator as $n=>$v)
//            {
//                if ($n==$nazevSloupceDb) return $v;
//            }
//            return NULL;

        } catch (Projektor_Model_Exception $e)
        {
            echo $e;
            return FALSE;
        }
    }

}
?>