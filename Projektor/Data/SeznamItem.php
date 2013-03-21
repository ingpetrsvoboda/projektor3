<?php
/**
 * Speciiální třída pro objekt použitý jako položka v seznamu, iterovatelný PHPTALem a obsahující data pro zobrazení v řádku seznamu
 *
 * @author pes2704
 */
class Projektor_Data_SeznamItem implements IteratorAggregate
{
    private $items = array();
    private $count = 0;


    public function getIterator() {
        return new Projektor_Data_Iterator($this->items);
    }

    public function addOrReplace($key, $value) {
        if(!array_key_exists($key, $this->items))
        {
            $this->count++;
        }
        $this->items[$key] = $value;
    }
}

?>
