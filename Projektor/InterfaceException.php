<?php

/**
 * Interface pro vlastní třídy výjimek
 * @author Petr Svoboda, ask at nilpo dot com http://www.php.net/manual/en/language.exceptions.php
 *
 */
interface Projektor_InterfaceException
{
//	/* Protected metody zděděné z třídy Exception */
//	public function getMessage();                 // Exception message
//	public function getCode();                    // User-defined Exception code
//	public function getFile();                    // Source filename
//	public function getLine();                    // Source line
//	public function getTrace();                   // An array of the backtrace()
//	public function getTraceAsString();           // Formated string of trace
//
//	/* Metody zděděné z třídy Exception implementované v potomkovských třídách */
//	public function __toString();                 // formated string for display
	public function __construct($message = null, $code = 0);
}
?>