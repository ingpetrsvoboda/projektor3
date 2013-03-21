<?php

/**
 * Abstraktní vlastní třída výjimek, ke každé "package" je vytvořena odvozená třída výjimek,
 * každá odvozená třída obsluhující výjimky příslušné "package" je v souboru Exception.php umístěném ve složce dané "package"
 * @author Petr Svoboda, ask at nilpo dot com http://www.php.net/manual/en/language.exceptions.php
 *
 */
abstract class Projektor_Exception extends PEAR_Exception implements Projektor_InterfaceException
{
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
?>