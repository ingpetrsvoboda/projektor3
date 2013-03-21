<?php
class Projektor_Stranka_Logout extends Projektor_Stranka_Base implements Projektor_Stranka_Interface
{
	const JMENO = "Projektor_Stranka_Logout";
	const MAIN = "main";

	const SABLONA_MAIN = "index.xhtml";

	public $html;
	public $promenne;
	public $kontext;

	public static function priprav($cesta)
	{
            		return new self($cesta, __CLASS__);
	}

	public function main($parametry = null)
	{
		return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry);
	}

	protected function main°vzdy()
	{
                $this->novaPromenna("nadpis", "Přihlášení");

	}

	protected function main°potomekNeni()
	{

	}


}