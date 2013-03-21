<?php
class Projektor_Pdf_Bunka
{
	var $sirka;
	var $vyska;
	var $textUTF8;
	var $promennaUTF8;
	var $ohraniceni;
	var $odradkovani;
	var $zarovnani;
	var $vypln;
	var $id;
	var $debugPrazdna;

	/**
	 * Vytvoří objekt Bunka obsahující text $textUTF8 a string hodnotu $promennaUTF8 a formátování
	 * @param real $sirka
	 * @param real $vyska
	 * @param string $textUTF8
	 * @param string $promennaUTF8
	 * @param integer $ohraniceni
	 * @param boolean $odradkovani
	 * @param character $zarovnani
	 * @param boolean $vypln
	 * @param string $link
	 * @param integer $id
	 * @return Bunka
	 */
	function __construct($sirka=false, $vyska=false, $textUTF8='', $promennaUTF8=false, $ohraniceni=0, $odradkovani=0, $zarovnani='', $vypln=false, $link='', $id)
	{
		$this->sirka = $sirka;
		$this->vyska = $vyska;
		$this->textUTF8 = $textUTF8;
		$this->promennaUTF8 = $promennaUTF8;
		$this->ohraniceni = $ohraniceni;
		$this->odradkovani = $odradkovani;
		$this->zarovnani = $zarovnani;
		$this->vypln = $vypln;
		$this->id = $id;
		$this->debugPrazdna = "prazdna";
	}
}
