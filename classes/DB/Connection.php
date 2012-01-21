<?php
interface DB_Connection {
  public function prepare($query);
  public function execute($query);
}

?>