<?php
abstract class Framework_DBPDO_PDO extends PDO implements Framework_DBPDO_PDOInterface {

    public function __get($name) {
        return $this->$name;
    }  
}

?>
