<?php
/**
 * Description of HlavniObjektItem
 *
 * @author pes2704
 */
abstract class Projektor_Model_HlavniObjektItem extends Projektor_Model_Item {
//POZOR!! před případným přejmenováním této třídy je třeba provést prohledání kódu - název třídy se používá jako řetězec (is_subclass())
    /**
     * Metoda vrací protected array _mapovaniVlastnostItem. Toto pole musí být definováno ve všech potomkovských třídách,
     * které mají mít funkčnost hlavního objektu. Současně také všechny třídy, které mají mít funkčnost hlavního objektu musí být potomky této třídy.
     * Tím je zajištěno, že všechny potomkovské třídy mají metodu getMapovani(), která je využívána při generování autocode a prezentací
     * @return array
     */
    public function getMapovani()
    {
        return $this->_mapovaniVlastnostItem;
    }
}

?>
