<?php
/**
 * @author Petr Svoboda
 *
 */
abstract class Projektor_Data_CiselnikItem extends Projektor_Data_Item
{

    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°text";


    public function __construct($id = NULL) {
        parent::__construct($id);
        $this->jeCiselnikOK();
    }

    /**
        * Zkontroluje, zda v DB existuje tabulka s nazvem zadanym jako parametr $nazevCiselniku, v tabulce
        * existují jen povolené sloupce číselníku a existuje sloupec s nazvem PREFIX_NAZEV_ID.$nazevCiselniku, který je primárním klíčem tabulky,
        * metoda nekontroluje jestli jsou v tabulce všechny povolené sloupce
        * Poznámka: Tato verze v případě chyby ukončí běh programu.
        * @param unknown_type $nazevCiselniku
        * @return boolean
        */
    private static function jeCiselnikOK()
    {
        try
        {
            $strukturaTabulky = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA);
            if (!$strukturaTabulky->primaryKeyFieldName) throw new Projektor_Data_Exception(
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
                    if ($sloupec->nazev!=$strukturaTabulky->primaryKeyFieldName)
                        throw new Projektor_Data_Exception(
                    "Chybná struktura číselníku - db tabulka: ".static::TABULKA." obsahuje sloupec: ".$sloupec->nazev.", který je v číselníku nepřípustný.");
                }
            }
            foreach ($ciselnikColumns as $nazev=>$hodnota)
            {
                if (!$hodnota)   throw new Projektor_Data_Exception(
                    "Chybná struktura číselníku - db tabulka: ".static::TABULKA." neobsahuje sloupec: ".$nazev.", který je v číselníku nutný.");
            }
            return TRUE;
//                $iterator = $this->getIterator();  //načte item
//            foreach ($iterator as $n=>$v)
//            {
//                if ($n==$nazevSloupceDb) return $v;
//            }
//            return NULL;

        } catch (Projektor_Data_Exception $e)
        {
            echo $e;
            return FALSE;
        }
    }

}
?>