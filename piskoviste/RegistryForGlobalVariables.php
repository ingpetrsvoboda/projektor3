<?php
class RegistryException extends Exception {}
 
/**
* Uschovna globalnich promennych
*
* Trida slouzi pro uchovavani glovalnich promennych. Jedna se o nahradu
* globalnich promennych v proceduralnim programovani.
*/
final class Registry implements ArrayAccess
{
    /**
     * Soubor se vsemi globalnimi daty
     *
     * @var array
     */
    private $_data = array();
 
    /**
     * Uchovava instanci objektu
     *
     * @var Registry|null
     */
    private static $_instance = null;
 
    /**
     * Singleton
     *
     * @param array|null $data Predane pole globalnich dat
     * @return Registry
     */
    public function getInstance($data = null)
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self($data);
        }
        return self::$_instance;
    }
 
    /**
     * Privatni konstruktor nastavuje predana data (pokud nejake jsou:)
     *
     * @param array|null $data Predane pole globalnich dat
     */
    private function __construct($data = null)
    {
        if ($data) {
            $this->_data = (array) $data;
        }
    }
 
    /**
     * Vklada globalni promennou
     *
     * @param string $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->_data[$key] = $value;
    }
 
    /**
     * Vraci globalni promennou
     *
     * @param string $key
     * @return mixed
     * @throws RegistryException Vyhodi se v pripade, ze neesituje zadna takova globalni promenna
     */
    public function offsetGet($key)
    {
        $this->_check($key, true);
        return $this->_data[$key];
    }
 
    /**
     * Odstrani globalni promennou
     *
     * @param string $key
     * @throws RegistryException Vyhodi se v pripade, ze neesituje zadna takova globalni promenna
     */
    public function offsetUnset($key)
    {
        $this->_check($key, true);
        unset($this->_data[$key]);
    }
 
    /**
     * Zkonstroluje, zda globalni promenna existuje
     *
     * @param string $key
     */
    public function offsetExists($key)
    {
        return $this->_check($key);
    }
 
    /**
     * Zkontroluje, zda existuje dany klic
     *
     * @param string $key
     * @param bool $exception Ma se vyhodit vyjimka nebo vratit false v pripade nepravdy?
     */
    private function _check($key, $exception = false)
    {
        if (!array_key_exists($key, $this->_data)) {
            if ($exception) {
                throw new RegistryException('Hodnota <strong>' . $key . '</strong> nebyla nastavena!');
            } else {
                return false;
            }
        }
        return true;
    }
}
 
try {
    $global = Registry::getInstance(array('name' => 'Jakub',
            'surname' => 'Mrozek'));
    //vlozeni nove globalni promenne
    $global['magazin'] = 'Interval';
 
    //ziskani glb. promenne, vypise Interval
    echo $global['magazin'];
 
    //kontroluje existenci promenne, vypise 1
    echo (int) isset($global['magazin']);
 
    //odstrani globalni promennou
    unset($global['magazin']);
 
    //konstroluje existuenci promenne, tentokrat vypise 0
    echo (int) isset($global['magazin']);
 
    //globalni promenna byla smazana, vyhodi se vyjimka
    echo $global['magazin'];
 
} catch (RegistryException $e) {
    //Zpracovani vyjimky
}
?>