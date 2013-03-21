<?php
class Projektor_Stranka_Element_Tlacitko
{
	public $popisek;
	public $odkaz;
        public $cssClass;
	
	public function __construct($popisek, $odkaz, $cssClass = null)
	{
		$this->popisek = $popisek;
		$this->odkaz = $odkaz;
                $this->cssClass = $cssClass;
	}
}