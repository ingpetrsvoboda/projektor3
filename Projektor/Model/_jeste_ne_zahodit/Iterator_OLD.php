<?php
/**
 * Description of Data
 *
 * @author Marek Petko
 */
abstract class Projektor_Model_Iterator implements Iterator
{
    private $pozice;
    public $vlastnostiIterator;  //TODO: ??private

    /**
     * Přidá objektu vlastnost vlastnosti typu array, pole vlastnosti obashuje názvy všech veřejných vlastností objektu,
     * v okamžiku volání metody objektu
     * Vlastnosti jsou do pole vlastnosti zapsany v poradi ??, ve kterém byly naplněny nějakou hodnotou
     * Jednotlivé vlastnosti jsou vraceny metodami rozhraní Iterator.
     *
     * @param string $nazevTridy
     */
//    public function __construct()   //TODO: odstranit všude parametr $nazevTridy
//    {
//        $this->rewind();
//    }

    /**
     * Metoda vrací vlastnost objektu odpovídající pozici ukazatele iterátor
     * Pokud příslušná vlastnost neexistuje, metoda vrací prázdný řetězec
     * @return string
     */
    public function current()
    {
        $current = $this->key();
        // kotrola existence vlastnosti slouží k tomu, aby se itreátor nepokoušel z této metody volat lazy load vlastnosti, které nebyly vytvořeny,
        // protože databázový sloupec neobsahoval data. Volání lazy load vlastností datových objektů mimo kontroler (mimo objekt Stranka), např. v době
        // vykonávání template (zevnitř z PHPTAL) nemusí být funkční
//        if (isset($this->$current))
//        {
            return $this->$current;
//        } else {
//            return "";
//        }
    }

    public function key()
    {
        return $this->vlastnostiIterator[$this->pozice];
    }

    public function next()
    {
        $this->pozice++;
    }

    public function rewind()
    {
        $this->pozice = 0;
    }

    public function valid()
    {
        return array_key_exists($this->pozice, $this->vlastnostiIterator);
    }

    /**
     * Metoda odebere název lastnosi z pole vlastnosti, nemaže vlastnost objektu
     */
    public function odeberVlastnostIterator($nazevVlastnosti)
    {
    if ($this->vlastnostiIterator) $index = array_search($nazevVlastnosti, $this->vlastnostiIterator);
    if ($index) {
        unset($this->vlastnostiIterator[$index]);
        $this->vlastnostiIterator = array_slice($this->vlastnostiIterator, 0);
    }
    return $this;

    }
    /**
     * Meoda přidá položku s názvem vlastnosti do pole vlatnosti, nevytváří vlastnost objektu
     * @param type $nazevVlastnosti
     * @return \Projektor_Model_Iterator
     */
    public function pridejVlastnostIterator($nazevVlastnosti)
    {
        $this->vlastnostiIterator[] = $nazevVlastnosti;
        return $this;
    }

    /**
     * Metoda odebere všechny vlastnosti z pole vlastnosti
     */
    public function odeberVsechnyVlastnostiIterator()
    {
        $this->vlastnostiIterator = NULL;
        return $this;
    }

    /**
     * Metoda odebere všechny vlastnosti objektu, které nejsou obsaženy v poli vlastnosti, tedy nejsou iterovatelné s použitím rozhranní Iterator
     * Výjimkou je vlastnost id, ta je vžday zachovány (typicky je nutná pro použotí při generování tlačítel v seznamech - generování uri)
     */
    public function odeberVsechnyNeiterovatelneVlastnostiObjektu()
    {
        $vars = $this->my_get_object_vars($this);
        foreach ($vars as $name => $var)
        {
            if ( array_search($name, $this->vlastnostiIterator)===FALSE AND $name<>"vlastnostiIterator" AND $name<>"id") unset ($this->$name);
        }
        return $this;
    }

    /**
     * Metoda přidá všechny tisknutelné vlastnosti objektu do pole vlastnosti, vlastnosi objektu, které jsou objekty metoda odstraní
     */
    public function pridejVsechnyVlastnostiIterator()
    {
    /**
     * Přidá objektu vlastnost vlastnosti, pole vlastnosti obashuje názvy všech vlastností objektu,
     * které nejsou objekt (tisknutelné vlastnosti)
     * a jsou viditelné v okamžiku instancování objektu, tedy deklarované ve třídě objektu. Typicky tedy pole vlastnosti obsahuje všechny
     * vlasnosti deklarované jako public a protected, vlastnosti deklarované jako private přídány nejsou.
     * Vlastnosti jsou do pole vlastnosti zapsany v poradi, ve kterem byly deklarovány ve třídě, nikoli v pořadí, ve kterém byly naplněny nějakou hodnotou
     */
       //        $this->vlastnosti = get_class_vars($nazevTridy);
    /**
     * Přidá objektu vlastnost vlastnosti, pole vlastnosti obashuje názvy všech veřejných vlastností objektu,
     * v okamžiku volání metody objektu
     * Vlastnosti jsou do pole vlastnosti zapsany v poradi ??, ve kterém byly naplněny nějakou hodnotou
     */
        $this->vlastnostiIterator = $this->my_get_object_vars($this);


  // s metodou  my_get_object_vars není co unsetovat
//        unset($this->vlastnosti["vlastnosti"]);
//        unset($this->vlastnosti["pozice"]);

        //odstranění vlastnosté, které jsou objekt a tedy nejsou konvertovatelné na string v PHPTALu
        foreach ($this->vlastnostiIterator as $key => $val) {
            if (is_object($this->$key)){
                unset($this->vlastnostiIterator[$key]);
            }
        }
        $this->vlastnostiIterator = array_keys($this->vlastnostiIterator);
        return $this;
    }

    /*
     * http://http://cz2.php.net/manual/en/function.get-object-vars.php joelhy 12-Jun-2010 12:56
     * Metoda vrací pouze public vlastnosti
     * Here is a function for getting only public properties even if you are in the class:
     */
        private function my_get_object_vars($obj) {
        $ref = new ReflectionObject($obj);
        $pros = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = array();
        foreach ($pros as $pro) {
            false && $pro = new ReflectionProperty();
            $result[$pro->getName()] = $pro->getValue($obj);
        }

        return $result;
    }

}
?>
