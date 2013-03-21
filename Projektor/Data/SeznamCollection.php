<?php
/**
 * Speciiální třída pro objekt iterovatelný PHPTALem obsahující data pro zobrazení v řádku seznamu
 *
 * @author pes2704
 */
class Projektor_Data_SeznamCollection implements IteratorAggregate
{
    private $items = array();
    private $count = 0;


    public function getIterator() {
        return new Projektor_Data_Iterator($this->items);
    }

    public function add($value) {
        $this->items[$this->count++] = $value;
    }

    public function count()
    {
        return $this->count;
    }
}

?>
