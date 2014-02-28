<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CollectionIterator
 *
 * @author pes2704
 */
class Projektor_Model_CollectionIterator  implements IteratorAggregate {
    // proměnné iterátoru
    private $items = array();   //TODO: splObjectStorage
    private $count = 0;

    /**
     * string, SQL dotaz s pojmenovanými prametry pro metodu getIterator, která provádí lazy load načtení dat
     * @var type
     */
//    protected $templateSql;

    /**
     *
     * @return \Projektor_Model_Iterator
     */
    public function getIterator() {
        if ($this->nactiData) {
            // získání struktury db tabulky z údajů třídy item
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            $strukturaTabulky = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA);
            $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;

            // sestavení dotazu
            $query = $this->templateSql->getSql();
            //prepare
            $prep = $this->dbh->prepare($query);
            //bind params
            if ($this->templateSql->controllerParams) {
                foreach ($this->templateSql->controllerParams as $param => $value) {
                    $prep->bindParam($param, $value);
                }
            }
            //execute - jen pro tabulky s primárním klíčem
            if ($nazevSloupcePK) {   //kupodivu jsou i tabulky bez primárního klíče
                $success = $prep->execute();
                //kontrola a naplnění položek IteratorAggregate
                if ($success) {
                    $this->items = array();
                    $this->count = 0;
                    $radkyId = $prep->fetchAll(PDO::FETCH_ASSOC);  //pole radku s polozkou $nazevSloupcePK (hodnoty primárního klíče)
                    //připraví položky s datovými objekty Item připravenými pro lazy load
                    foreach($radkyId as $r => $radekId) {
                        $item = new $itemClassName($radekId[$nazevSloupcePK]);
                        if ($this->selectAttributes) {
                            $item->sqlSelect->select = $this->selectAttributes;
                        }
                        $vf = $this->templateSql->validFilter;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
                        $item->sqlSelect->validFilter = $vf;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly ani při volbě všechnyRadky
                        $this->add($item);
                    }
                    $this->dataNactena = TRUE;
                }
//                    unset($result);
            }
        } else {
            //prázdná kolekce
        }
//        return new Projektor_Model_Iterator($this->items);
        return new ArrayIterator($this->items);  //tohle je asi o 15% rychlejší
    }

    public function add($value) {
        try {
            if (get_class($value)==static::NAZEV_TRIDY_ITEM) {
                $this->items[$this->count++] = $value;
            } else {
                throw new Projektor_Model_Exception("Nelze přidat položku do IteratorAggregate třídy ".__CLASS__.
                        ", lze přidávat pouze položky typu ".static::NAZEV_TRIDY_ITEM.". Zadaná hodnotu. je typu: ".get_class($value).".");
            }
        } catch (Projektor_Model_Exception $e) {
            echo $e;
        }
    }

    public function __get($name) {
        if (property_exists($this, $name)) return $this->$name;
    }
}
?>
