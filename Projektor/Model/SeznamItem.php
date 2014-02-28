<?php
/**
 * Speciiální třída pro objekt použitý jako položka v seznamu, iterovatelný PHPTALem a obsahující data pro zobrazení v řádku seznamu
 *
 * @author pes2704
 */
class Projektor_Model_SeznamItem implements IteratorAggregate
{
    
    /**
     * Hodnoty načtené z databáze. Asociativní pole, klíče odpovídají názvům vlastností.
     * @var array 
     */
    protected $attributes = array();
    
    /**
     * Iterátor vracený metodou getIterator (implementace IteratorAggregate).
     * @var ArrayIterator
     */
    protected $arrayIterator;

    /**
     * Metoda předepsaná rozhraním IteratorAggregate, vrací PHP SPL objekt ArrayIterator, který implementuje rozhraní Iterator. 
     * Metoda je volána při pokusu o iterování objektu Item. To nastáva typicky při použití objektu v cyklu foreach. 
     * (Např: foreach ($item as $name=>$value) ... )
     * @return ArrayIterator
     */
    public function getIterator() {
        if ($this->arrayIterator) {
            $newArrayIterator = clone $this->arrayIterator;
            $newArrayIterator->rewind();
            return $newArrayIterator;
        } else {
            $this->arrayIterator = new ArrayIterator();
            return $this->arrayIterator;
        }
    }
    
    /**
     * Metoda přidá další položku do připravených dat budoucího iterátoru IteratorAggregate, již existující položku (položku se stejným klíčem) přepíše.
     * @param type $key Název iterovatelné vlastnosti
     * @param type $value Hodnota iterovatelné vlastnosti
     */
    protected function addOrReplace($key, $value) {
        $this->attributes[$key] = $value;
    }    
}

?>
