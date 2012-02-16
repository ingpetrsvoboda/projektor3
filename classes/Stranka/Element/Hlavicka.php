<?php
class Stranka_Element_Hlavicka
{
	public $sloupce;
	private $cesta;
	
	public function __construct($cesta)
	{
		$this->cesta = $cesta;
	}

	/**
         * Metoda přidá do pole sloupců hlavičky seznamu objekt sloupec.
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
        public function pridejSloupec($nazevVlastnosti, $popisek, $nazevSloupceDb = NULL, $prikazGenerujiciPoleReferencovanychObjektu = NULL, $prikazGenerujiciReferencovanyObjekt = NULL, $nazevVlastnostiReferencovanehoObjektu = NULL)
	{
            if ($nazevSloupceDb) 
            {
                $this->sloupce[] = new Stranka_Element_Hlavicka_Sloupec
                (
                    $nazevVlastnosti,
                    $popisek,
                    $nazevSloupceDb,
                    $prikazGenerujiciPoleReferencovanychObjektu,
                    $prikazGenerujiciReferencovanyObjekt,
                    $nazevVlastnostiReferencovanehoObjektu,
                    $this->cesta->pridejParametr("razeniPodle", $nazevSloupceDb)->pridejParametr("razeni", "DESC")->generujUri(),
                    $this->cesta->pridejParametr("razeniPodle", $nazevSloupceDb)->pridejParametr("razeni", "ASC")->generujUri()
                );
            } else {
                //prazdné parametry vzestupne a sestupne zpusobí neexistenci vlastnosti sloupec->vzestupne a sloupec->sestupne
                //v šabloně seznam.xhtml je podminka tal:condition a vynechaji se šipky s odkazy pro řazení, zobrazí se jen popisek
                $this->sloupce[] = new Stranka_Element_Hlavicka_Sloupec
                (
                    $nazevVlastnosti,
                    $popisek
                );                    
            }
        }
}