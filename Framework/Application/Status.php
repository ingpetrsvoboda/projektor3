<?php
/**
 * Description of Status
 *
 * @author pes2704
 */
class Framework_Application_Status implements Framework_Application_StatusInterface {

    const NAME = 'framework_application_status';

    private $storage;
    private $status = array();

    public function __construct(Framework_StatusStorage_StatusStorageInterface $storage=NULL) {
        if (isset($storage)) {
            $this->storage = $storage;            
        } else {
            $this->storage = Framework_StatusStorage_Session::getInstance();
        }
    }

    public function store() {
        $this->storage->write(static::NAME, serialize($this->status));
    }

    public function restore() {
        $this->status = unserialize($this->storage->read(static::NAME));
    }

    public function __get($name) {
        if (!$this->status) {
            $this->restore();
        }
        return $this->status[$name];
    }

    public function __set($name, $value) {
        if (!$this->status) {
            $this->restore();
        }
        $this->status[$name] = $value;
        return $value;
    }
}

?>
