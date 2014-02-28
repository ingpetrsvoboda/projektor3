<?php
class Projektor2_PDF_Bunka
{
	public $sirka;
	public $vyska;
	public $textUTF8;
	public $promennaUTF8;
	public $ohraniceni;
	public $odradkovani;
	public $zarovnani;
	public $vypln;
	public $id;
	public $debugPrazdna;

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
	public function __construct($sirka=false, $vyska=false, $textUTF8='', $promennaUTF8=false, $ohraniceni=0, $odradkovani=0, $zarovnani='', $vypln=false, $link='', $id)
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
