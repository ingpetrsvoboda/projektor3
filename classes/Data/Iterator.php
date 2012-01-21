<?php
/**
 * Description of Data
 *
 * @author Marek Petko
 */
abstract class Data_Iterator implements Iterator
{
    private $pozice = 0;
    private $vlastnosti;
    
    /**
     * Přidá objektu private vlastnost vlastnosti typu array, pole vlastnosti obashuje názvy všech veřejných vlastností objektu,
     * v okamžiku volání metody objektu
     * Vlastnosti jsou do pole vlastnosti zapsany v poradi ??, ve kterém byly naplněny nějakou hodnotou
     * Jednotlivé vlastnosti jsou vraceny metodami rozhraní Iterator.
     *
     * @param string $nazevTridy 
     */
    public function __construct($nazevTridy)
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
        $this->vlastnosti = $this->my_get_object_vars($this);

        
  // s metodou  my_get_object_vars není co unsetovat
//        unset($this->vlastnosti["vlastnosti"]);
//        unset($this->vlastnosti["pozice"]);
        foreach ($this->vlastnosti as $key => $val) {
            if (is_object($this->$key)){
                unset($this->vlastnosti[$key]);
            }
        }
        $this->vlastnosti = array_keys($this->vlastnosti);
    }

    public function current()
    {
        $current = $this->key();
        return $this->$current;
    }

    public function key()
    {
        return $this->vlastnosti[$this->pozice];
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
        return array_key_exists($this->pozice, $this->vlastnosti);
    }

    /**
     * Metoda odebere vlastnost z pole vlastnosti
     */
    public function odeberVlastnost($nazevVlastnosti)
    {
    $index = array_search($nazevVlastnosti, $this->vlastnosti);
    if ($index) {
        unset($this->vlastnosti[$index]);
        $this->vlastnosti = array_slice($this->vlastnosti, 0);
    }
    return $this;

    }
    public function pridejVlastnost($nazevVlastnosti)
    {
        $this->vlastnosti[] = $nazevVlastnosti;
        return $this;
    }

    /**
     * Metoda odebere všechny vlastnosti z pole vlastnosti
     */
    public function odeberVsechnyVlastnosti()
    {
        $this->vlastnosti = NULL;
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
