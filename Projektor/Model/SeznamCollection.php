<?php
/**
 * Speciiální třída pro objekt iterovatelný PHPTALem obsahující data pro zobrazení v řádku seznamu
 *
 * @author pes2704
 */
class Projektor_Model_SeznamCollection implements IteratorAggregate
{
        
    /**
     * Iterátor vracený metodou getIterator (implementace IteratorAggregate).
     * @var ArrayIterator
     */
    protected $arrayIterator;
    
#################### getIterator ##############################################
    /**
     * Metoda předepsaná rozhraním IteratorAggregate, vrací PHP SPL objekt ArrayIterator, který implementuje rozhraní Iterator. 
     * Metoda je volána při pokusu o iterování objektu Collection. To nastáva typicky při použití objektu v cyklu foreach. 
     * (Např: foreach ($item as $name=>$value) ... ) 
     * @return \ArrayIterator
     */
    public function getIterator() {
        if ($this->arrayIterator) {
            $newArrayIterator = clone $this->arrayIterator;
            $newArrayIterator->rewind;
            return $newArrayIterator;
        } else {
            $this->arrayIterator = new ArrayIterator();
            return $this->arrayIterator;
        }
    }
}

?>
