<?php
class Router_Cesta_Krok
{
	public $trida;
	public $metoda;
	public $parametry;

	public function __construct($trida, $metoda, $parametry)
	{
		$this->trida = $trida;
		$this->metoda = $metoda;
		$this->parametry = $parametry;
	}
	
	public function pridejParametr($parametr, $hodnota)
	{
		$this->parametry[$parametr] = $hodnota; 	
	}
}