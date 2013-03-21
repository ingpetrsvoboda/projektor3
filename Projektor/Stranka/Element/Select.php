<?php
 require_once('HTML/QuickForm/ElementGrid.php');

class Projektor_Stranka_Element_Select
{
	private $form;
	private $elementGrid;
	private $sloupce;
	private $data;
	private $nazev;

	const STATICKY_ELEMENT = "static";

	/**
	 * Konstruktor TabulkaElementu.
	 * @param QuickForm $form Ukazatel na instanci QuickFormu.
	 * @param string $typ Typ formulářového prvku (radio/checkbox/...)
	 * @param unknown_type $collection Ukazatel na instanci datové třídy.
	 * @param string $nazev Název tabulky elementů (tj. elementů!)
	 * @param string $popisek Popisek tabulky elementů.
	 */
	public function __construct(HTML_QuickForm $form, Projektor_Data_Collection $collection, $zobrazovanaVlastnost, $nazev, $popisek)
	{
		$this->form = $form;
                $poleSelect[""] = "";
                foreach($collection as $item) $poleSelect[$item->id] = $item->$zobrazovanaVlastnost;
                $this->form->addElement("select", $nazev, $popisek, $poleSelect);

                $this->data = $collection;
		$this->nazev = $nazev;
	}
        public function submit($nazev, $popisek)
        {
            /* element submit */
            $form->addElement("submit", $nazev, $popisek);
        }
}
