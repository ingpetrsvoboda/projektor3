<?php

/*
 * http://cz.php.net/manual/en/ref.pdo-mysql.php
 */

$pdo = new PDO(
    'mysql:host=hostname;dbname=defaultDbName',
    'username',
    'password',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
);

?>
