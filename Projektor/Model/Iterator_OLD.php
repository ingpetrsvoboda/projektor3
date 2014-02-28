<?php
/**
 * Třída Projektor_Model_Iterator implemtuje rozhranní php Iterator. Navíc implementuje magickou metodu __get().
 * Metodami rozhranní Iterator iteruje pole zadané jako parametr konstruktoru. Třída očekává, že zadané pole bude asociativní.
 * Pokud je zadané pole asociativní, pak magická metoda __get() vrací jednotlivé prvky terovatelného pole.
 *
 * @author http://www.php.net/manual/en/language.oop5.iterations.php, pes2704
 */
class Projektor_Model_Iterator implements Iterator
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
    public function __construct(array $array = NULL) {
        if (is_array($array)) {
            $this->var = $array;
        }
    }

    /**
     * Getter -
     * Metoda vrací položku z pole vlastnostností iterátoru $var s indexem zadaným jako parametr $name, pro neexistující položku vrací NULL
     * @param type $name
     */
    public function __get($name) {
        if (array_key_exists($name, $this->var)) {
            return $this->var[$name];
        } else {
            return NULL;
        }
    }

    public function rewind() {
        reset($this->var);
    }

    public function current() {
        $var = current($this->var);
        return $var;
    }

    public function key() {
        $var = key($this->var);
        return $var;
    }

    public function next() {
        $var = next($this->var);
        return $var;
    }

    public function valid() {
        $key = key($this->var);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}

?>
