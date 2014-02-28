<?php
class Projektor_Controller_Page_Element_Hlavicka
{
	public $sloupce;
        private $nazevTridyDataItem;
	private $uzel;

	public function __construct($nazevTridyDataItem, Projektor_Controller_Page_AbstractPage $pageController)
	{
            $this->nazevTridyDataItem = $nazevTridyDataItem;  //TODO: nepředávat název třídy - předávat třídu, přidat typ (dle interface) asi Projektor_Model_Auto_ItemInterface nebo tak něco
            $this->uzel = $pageController;
	}

	/**
         * Metoda přidá do pole sloupců hlavičky seznamu další prvek - objekt sloupec.
         * Objekt sloupec má vlastnosti, které se používají pro vytvoření iterovatelných vlastností datových objektů v seznamu a pro řazení a filtrování.
         * Řadit a filtrovat lze pouze vlatnosti odpovídající sloupcům db tabulky (používá se SQL příkaz).
         * Pokud není zadán parametr $nazevSloupceDb, parametry $nazevVlastnosti a $popisek se použijí pro vytvoření sloupce seznamu neumožňujícího řazení a filtrování.
         * Pokud je zadán parametr $nazevSloupceDb, parametry $nazevVlastnosti a $popisek se použijí pro vytvoření sloupce seznamu umožňujícího řazení a filtrování.
         * Pokud $nazevSloupceDb odpovídá sloupci db, který neobsahuje cizí klíč, není třeba zadávat parametry $prikazGenerujiciPoleReferencovanychObjektu a $nazevVlastnostiReferencovanehoObjektu,
         * ve formuláři filtru se vytvoří element typu text.
         * Pokud $nazevSloupceDb odpovídá sloupci db, který obsahuje cizí klíč, je třeba zadávat parametry $prikazGenerujiciPoleReferencovanychObjektu a $nazevVlastnostiReferencovanehoObjektu,
         * ve formuláři filtru se vytvoří element typu select zobrazující hodnoty z referencované db tabulky a umožňující vybrat hodnotu cizího klíče.
         *
         * @param string $nazevVlastnosti Název vlastnosti objektu odpovídající sloupci seznamu, požije se pro vytvoření iterovatelné vlastností datových objektů v seznamu
         * @param string $popisek Text do titulku sloupce seznamu
         * @param string $nazevSloupceDb Název sloupce db tabulky odpovídající sloupci seznamu, pokud je zadán, pak sepodle tohoto sloupce provádí řazení a filtrování
         * @param type $prikazGenerujiciPoleReferencovanychObjektu Pokud $nazevSloupceDb obsahuje cizí klíč, pak zde je třeba zadat PHP kód, který při vykonání vrací pole objektů odpovídajících tabulce v db jejíž klíč je jako cizí klíč obsažen ve sloupci zadaném v parametu $nazevSloupceDb
         * @param type $nazevVlastnostiReferencovanehoObjektu Název vlastnosti objektu z pole objektů, jejíž hodnota se zobrazuje v HTML příkazu select (option)
         */
        public function pridejSloupec($nazevVlastnosti, $titulek=NULL, $nazevVlastnostiReferencovanehoObjektu = NULL)
	{
            $nazevTridyDataItem = $this->nazevTridyDataItem;
            $nazevSloupceDb = $nazevTridyDataItem::dejNazevSloupceZVlastnosti($nazevVlastnosti);
            if ($nazevSloupceDb)
            {
                //uschování hodnot parametrů
                $razeniPodle = $this->uzel->getParameter("razeniPodle");
                $razeni = $this->uzel->getParameter("razeni");
                if ($this->uzel->getParameter("razeniPodle")==$nazevSloupceDb)
                {
                    if($this->uzel->getParameter("razeni")=="ASC") $razeniAscPouzito = TRUE;
                    if($this->uzel->getParameter("razeni")=="DESC") $razeniDescPouzito = TRUE;
                } else {
                    $razeniAscPouzito = FALSE;
                    $razeniDescPouzito = FALSE;
                }
                $this->sloupce[] = new Projektor_Controller_Page_Element_Hlavicka_Sloupec
                (
                    $nazevVlastnosti,
                    $titulek,
                    $nazevVlastnostiReferencovanehoObjektu,
                    $this->uzel->setParameter("razeniPodle", $nazevSloupceDb)->setParameter("razeni", "DESC")->formAction(),
                    $razeniDescPouzito,
                    $this->uzel->setParameter("razeniPodle", $nazevSloupceDb)->setParameter("razeni", "ASC")->formAction(),
                    $razeniAscPouzito
                );
                //vrácení hodnot parametrů
                $this->uzel->setParameter("razeniPodle", $razeniPodle)->setParameter("razeni", $razeni)->formAction();
            } else {
                //prazdné parametry vzestupne a sestupne zpusobí neexistenci vlastnosti sloupec->vzestupne a sloupec->sestupne
                //v šabloně seznam.xhtml je podminka tal:condition a vynechaji se šipky s odkazy pro řazení, zobrazí se jen popisek
                $this->sloupce[] = new Projektor_Controller_Page_Element_Hlavicka_Sloupec
                (
                    $nazevVlastnosti,
                    $titulek
                );
            }
        }
}