<?php
class Stranka_Logout extends Stranka implements Stranka_Interface
{
	const JMENO = "Stranka_Logout";
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