<?php
/**
 * Description of Projektor_Data_Iterator
 *
 * @author http://www.php.net/manual/en/language.oop5.iterations.php, pes2704
 */
class Projektor_Data_Iterator implements Iterator
{
    /**
     * Pole vlastností objektu odpovídající sloupcům databázové tabulky a procházené metodami iterátoru
     * Jedná se o asociativní pole, indexy odpovídají názvům vlastností objektu
     * @var array
     */
//    protected $var = array();
    public  $var = array();  //TODO: dočasně public pro ladění - vrátit private (viz __get() v Item!!

    /**
     * Konstruktor iterátoru
     * Konstuktoru může být zadán parametr typu array a tento parametr konstruktor vloží do pole vlastností objektu odpovídající sloupcům databázové tabulky a procházené metodami iterátoru.
     * Konstruktor nezjišťuje shodu indexů pole zadaného jako parametr s názvy sloupcůdatabázové tabulky.
     * @param array $array
     */
    public function __construct(array $array = NULL)
    {
        if (is_array($array))
        {
            $this->var = $array;
        }
    }

//    /**
//     * Metoda očekávaí parametr typu array a vloží tento parametr do pole vlastností objektu odpovídající sloupcům databázové tabulky a procházené metodami iterátoru.
//     * Metoda nezjišťuje shodu indexů pole zadaného jako parametr s názvy sloupců databázové tabulky, za správnost odpovídá ten, kdo metodu load volá.
//     * @param type $array
//     */
//    public function load(array $array = NULL)
//    {
//        if (is_array($array))
//        {
//            $this->var = $array;
//        }
//    }

    /**
     * Getter -
     * Metoda vrací položku z pole vlastnostností iterátoru $var s indexem zadaným jako parametr $name, pro neexistující položku vrací NULL
     * @param type $name
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->var))
        {
            return $this->var[$name];
        } else {
            return NULL;
        }
    }

    public function rewind()
    {
//        echo "rewinding\n";
        reset($this->var);
    }

    public function current()
    {
        $var = current($this->var);
//        echo "current: $var\n";
        return $var;
    }

    public function key()
    {
        $var = key($this->var);
//        echo "key: $var\n";
        return $var;
    }

    public function next()
    {
        $var = next($this->var);
//        echo "next: $var\n";
        return $var;
    }

    public function valid()
    {
        $key = key($this->var);
        $var = ($key !== NULL && $key !== FALSE);
//        echo "valid: $var\n";
        return $var;
    }}

?>
