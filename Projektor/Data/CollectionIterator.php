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
abstract class Projektor_Data_CollectionIterator  implements IteratorAggregate
{
    // proměnné iterátoru
    private $items = array();   //TODO: splObjectStorage
    private $count = 0;

    /**
     * string, SQL dotaz s pojmenovanými prametry pro metodu getIterator, která provádí lazy load načtení dat
     * @var type
     */
    protected $preparedSql;

    /**
     *
     * @return \Projektor_Data_Iterator
     */
    public function getIterator() {
        if ($this->nactiData)
        {
            if (!$this->dataNactena)
            {
                // získání struktury db tabulky z údajů třídy item
                $itemClassName = static::NAZEV_TRIDY_ITEM;
                $strukturaTabulky = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA);
                $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;

                $dbh = Projektor_App_Container::getDbh($itemClassName::DATABAZE);
                // sestavení dotazu
                $query = $this->preparedSql->sql;

                //prepare
                $prep = $dbh->prepare($query);
                //bind params
                if ($this->preparedSql->params)
                {
                    foreach ($this->preparedSql->params as $param => $value) {
                        $prep->bindParam($param, $value);
                    }
                }
                //execute - jen pro tabulky s primárním klíčem
                if ($nazevSloupcePK)   //kupodivu jsou i tabulky bez primárního klíče
                {
                    $result = $prep->execute();
                    //kontrola a naplnění položek IteratorAggregate
                    if ($result)
                    {
                        $radkyId = $result->fetchall_assoc();  //pole radku s polozkou $nazevSloupcePK (hodnoty primárního klíče)
                        //připraví položky s datovými objekty Item připravenými pro lazy load
                        foreach($radkyId as $r => $radekId)
                        {
                            $item = new $itemClassName($radekId[$nazevSloupcePK]);
                            $item->preparedSql->validFilter = $this->preparedSql->validFilter;  //nastaví valid filtr i pro item, jinak by se nevalidní itemy nenačetly
                            $this->add($item);
                        }
                        $this->dataNactena = TRUE;
                    }
                    unset($result);
                }
            }
        } else {
            //prázdná kolekce
        }
        return new Projektor_Data_Iterator($this->items);
    }

    public function add($value) {
        try
        {
            if (get_class($value)==static::NAZEV_TRIDY_ITEM)
            {
                $this->items[$this->count++] = $value;
            } else {
                throw new Projektor_Data_Exception("Nelze přidat položku do IteratorAggregate třídy ".__CLASS__.
                        ", lze přidávat pouze položky typu ".static::NAZEV_TRIDY_ITEM.". Zadaná hodnotu. je typu: ".get_class($value).".");
            }
        } catch (Projektor_Data_Exception $e) {
            echo $e;
        }
    }

    public function __get($name) {
        if (property_exists($this, $name)) return $this->$name;
    }
}

?>
