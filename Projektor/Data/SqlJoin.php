<?php
/**
 * Description of SqlJoin
 *
 * @author pes2704
 */
class SqlJoin {

    public $sqlJoin;

    public $leftSql;
    public $rightSql;
    public $join = "JOIN";

    public function __construct()
    {
        unset($this->sqlJoin);      //unsetování způsobí volání getteru a zachová napovídání v IDE
    }

    public function __get($name) {
        if ($name="sqlJoin")
        {
            return " ".  $this->leftSql." ".$this->join." ".$this->rightSql;
        }
    }
}

?>
