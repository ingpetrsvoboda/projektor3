<?php
interface DB_Statement {
  public function execute();
  public function bind_param($key, $value);
  public function fetch_row();
  public function fetch_assoc();
  public function fetchall_assoc();
}

?>