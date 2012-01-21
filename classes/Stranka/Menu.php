<?php
//require_once("classes/PHPTAL.PHP");

class Stranka_Menu extends Stranka implements Stranka_Interface
{
	const JMENO = "Stranka_Menu";
	const MAIN = "main";
	const SABLONA_MAIN = "index.xhtml";

	public $html;
	public $promenne;

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
            $this->novaPromenna("nadpis", "Menu");
            $this->novaPromenna("zprava", $this->parametry["zprava"]);


            /* Ovladaci tlacitka stranky */
            $tlacitka = array
            (
                new Stranka_Element_Tlacitko("Index", "index.php"),
                new Stranka_Element_Tlacitko("Akce", "index.php?cesta=Stranka_AkceM"),
                new Stranka_Element_Tlacitko("Účastníci", "index.php?cesta=Stranka_Ucastnici"),
                new Stranka_Element_Tlacitko("Typy akce", "index.php?cesta=Stranka_TypyAkce"),
                new Stranka_Element_Tlacitko("Předpoklady", "index.php?cesta=Stranka_Predpoklady"),
                new Stranka_Element_Tlacitko("ISCO", "index.php?cesta=Stranka_ISCOM"),
                new Stranka_Element_Tlacitko("Prezentace", "index.php?cesta=Stranka_PrezentaceM"),
            );
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function main°potomekNeni()
	{

	}
}