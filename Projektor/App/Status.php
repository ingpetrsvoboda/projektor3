<?php
/**
 * Description of Status
 *
 * @author pes2704
 */
class Projektor_App_Status implements Projektor_App_StatusInterface {

    const STATUS = 'projektor_status';

    private $storage;
    private $status = array();

    public function __construct(Projektor_App_Storage_StorageInterface $storage) {
        $this->storage = $storage;
    }

    public function store() {
        $this->storage->write(self::STATUS, serialize($this->status));
    }

    public function restore() {
        $this->status = unserialize($this->storage->read(self::STATUS));
    }

    public function __get($name) {
        if (!$this->status) $this->restore();
        return $this->status[$name];
    }

    public function __set($name, $value) {
        if (!$this->status) $this->restore();
        $this->status[$name] = $value;
        return $value;
    }
}

?>
