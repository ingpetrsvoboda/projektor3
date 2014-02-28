<?php
 require_once('HTML/QuickForm/ElementGrid.php');

class Projektor_Controller_Page_Element_BlokRadioButton
{
	private $form;
	private $elementGrid;
	private $sloupce;
	private $collection;
	private $nazev;
	private $typ;

        const TYP = "radio";
	const STATICKY_ELEMENT = "static";

	/**
	 * Konstruktor TabulkaElementu.
	 * @param QuickForm $form Ukazatel na instanci QuickFormu.
	 * @param string $typ Typ formulářového prvku (radio/checkbox/...)
	 * @param unknown_type $collection Ukazatel na instanci datové třídy.
	 * @param string $nazev Název tabulky elementů (tj. elementů!)
	 * @param string $popisek Popisek tabulky elementů.
	 */
	public function __construct(HTML_QuickForm $form, $collection, $nazev, $popisek)
	{
		$this->form = $form;
		$this->elementGrid = $this->form->addElement("elementGrid", $nazev, $popisek, array("actAsGroup" => 1));
		$this->collection = $collection;
		$this->nazev = $nazev;
		$this->typ = self::TYP;
	}

	/**
	 * Nastavi sloupce tabulky a naplni tabulku daty podle sloupcu.
	 * @param array $sloupce Asociativni pole s definici sloupcu ("nazev_hodnoty" => "Popisek hodnoty")
	 * @return void
	 */
	public function nastavSloupce($sloupce)
	{
		$this->sloupce = $sloupce;
		$this->pridejNazvySloupcu();
		$this->pridejRadky();
	}

	/**
	 * Prida nazvy sloupcu.
	 * @return void
	 */
	private function pridejNazvySloupcu()
	{
		$this->elementGrid->addColumnName("");
		foreach($this->sloupce as $nazev => $popis)
			$this->elementGrid->addColumnName($popis);
	}

	/**
	 * Vygeneruje a prida radky do tabulky;
	 * @return void
	 */
	private function pridejRadky()
	{
		$radky = array();
		$radek = array();
		foreach($this->collection as $radekDat)
		{
			$radek[] = HTML_QuickForm::createElement($this->typ, $this->nazev, "", "", $radekDat->id);
			foreach($this->sloupce as $nazev => $popis)
				$radek[] = HTML_QuickForm::createElement(Projektor_Controller_Page_Element_BlokRadioButton::STATICKY_ELEMENT, "", "", $radekDat->$nazev);

			$radky[] = $radek;
			$radek = array();
		}
		$this->elementGrid->setRows($radky);
	}
}
