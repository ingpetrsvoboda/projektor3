<?php
/**
 * @author Tomáš Černý, Petr Svoboda
 *
 */
class Data_Ciselnik extends Data_Iterator
{

	public $dbh;
        public $nazev;
	public $nazevId;
	public $id;
	public $razeni;
	public $kod;
	public $text;
	public $plny_text;
	public $valid;
        
        private $vsechnyRadky;


	const PREFIX_NAZEV_ID = "id_";	//správný nazev sloupce s id je PREFIX_NAZEV_ID a nazev ciselniku
	const PREFIX_NAZEV_C = "c_";	//počáteční písmena nazvu tabulek typu ciselnik v DB

	// Název sloupců DB tabulky typu číselník
	const RAZENI = "razeni";
	const KOD = "kod";
	const TEXT = "text";
	const PLNY_TEXT = "plny_text";
	const VALID = "valid";

	/**
	 *
	 * @param unknown_type $nazevCiselniku
	 * @return unknown_type
	 */
	public function __construct($nazevCiselniku, $dbh, $razeni, $kod, $text, $plny_text, $valid, $vsechnyRadky, $id=NULL)
	{
                $this->nazev = $nazevCiselniku;
		$this->dbh;
		$this->nazevTabulkyCiselniku = self::PREFIX_NAZEV_C.$nazevCiselniku;
		$this->nazevId = self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku;
                $this->id = $id;
		$this->razeni = $razeni;
		$this->kod = $kod;
		$this->text = $text;
		$this->plny_text = $plny_text;
		$this->valid = $valid;
                $this->vsechnyRadky = $vsechnyRadky;


                parent::__construct(__CLASS__);

	}

	/**
	 * Najde a vrátí jeden řádek tabulky v DB se zadaným ID,
	 * vrací jen řádky kde hodnota valid = 1.
         * @param type $nazevCiselniku
         * @param int $id
         * @param boolean $vsechnyRadky
         * @param boolean $bezKontroly hodnota TRUE vypne kontrolu struktury tabulky číselníku, metoda vrací objekt obsahující pouze defaultní sloupce číselníku
         * @param $databaze identifikátor databáze (viz App_Kontext::getDbh()
         * @return object Data_Ciselnik 
         */
	public static function najdiPodleId($nazevCiselniku, $id, $vsechnyRadky = FALSE, $bezKontroly = FALSE, $databaze=NULL)
	{
//TODO: testování, zda je ciselnikOK trvá asi 5ms - chtělo by to ?? hlídat poslední změnu tabulky ?? možnost vypnout kontrolu pro produkční verzi ?? něco
            if (!$bezKontroly)
            {
                try
		{
			self::jeCiselnikOK($nazevCiselniku, $databaze);
		}
		catch (Data_Exception $e)
		{

			//        echo "Caught TestException ('{$e->getMessage()}')\n{$e}\n";
			echo $e."<BR>";
		}
            }
            $dbh = App_Kontext::getDbh($databaze);
            $query = "SELECT * FROM ~1 WHERE ~2 LIKE :3" . ($vsechnyRadky ? "" : " AND valid = 1");
            $radek = $dbh->prepare($query)->execute(self::PREFIX_NAZEV_C.$nazevCiselniku,
                                                    self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku,
                                                    $id)->fetch_assoc();
            if(!$radek)
            return false;

            return new Data_Ciselnik($nazevCiselniku, $dbh, $radek[self::RAZENI], $radek[self::KOD], $radek[self::TEXT], $radek[self::PLNY_TEXT],
            $radek[self::VALID], $vsechnyRadky, $radek[self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku]);
	}

	/**
	 * Vrátí pole objektů obsahujících řádky číselníku,
	 * vrací jen řádky odpovídající kontext filtru a případně zadanému filtru.
	 * @param string $nazevCiselniku
	 * @param string $filtr řetězec, který bude dosazen do klauzule WHERE
         * @param string $nazevIdProjekt Zde je možno zadat název sloupce v číselníku obsahujícího jako cizí klíč id tabulky c_projekt pro vytvoření kontext filtru
         * @param string $nazevIdKancelar Zde je možno zadat název sloupce v číselníku obsahujícího jako cizí klíč id tabulky c_kancelar pro vytvoření kontext filtru
         * @param string $nazevIdBeh Zde je možno zadat název sloupce v číselníku obsahujícího jako cizí klíč id tabulky s_beh_projektu pro vytvoření kontext filtru
         * @param boolean $bezKontroly hodnota TRUE vypne kontrolu struktury tabulky číselníku, metoda vrací objekt obsahující pouze defaultní sloupce číselníku
         * @param $databaze identifikátor databáze (viz App_Kontext::getDbh()
         * @return array() Pole instanci tridy odpovidajici radkum v číselníku v DB
	 */
        public static function vypisVse($nazevCiselniku, $filtr = "", $nazevIdProjekt = NULL, $nazevIdKancelar = NULL, $nazevIdBeh = NULL, $vsechnyRadky = FALSE, $bezKontroly = FALSE, $databaze=NULL)
	//TODO: sjednotot pořadí argumentů metod vypisVse v Ciselnik, FlatTable, HlavniObjekt
        {
            if (!$bezKontroly)
            {
                try
		{
			self::jeCiselnikOK($nazevCiselniku, $databaze);
		}
		catch (Data_Exception $e)
		{
			echo $e.'<BR>';
		}
            }
            $dbh = App_Kontext::getDbh($databaze);
            $kontextFiltr = App_Kontext::getKontextFiltrSQL($nazevIdProjekt, $nazevIdKancelar, $nazevIdBeh, $filtr, "", "", $vsechnyRadky);
//                $query = "SELECT * FROM ~1".($kontextFiltr ? " WHERE ".$kontextFiltr : "")." ORDER BY razeni ASC";
            $query = "SELECT * FROM ~1".$kontextFiltr;
            $radky = $dbh->prepare($query)->execute(self::PREFIX_NAZEV_C.$nazevCiselniku)->fetchall_assoc();

            foreach($radky as $radek)
            $vypis[] = new Data_Ciselnik($nazevCiselniku, $dbh, $radek[self::RAZENI], $radek[self::KOD], $radek[self::TEXT], $radek[self::PLNY_TEXT],
            $radek[self::VALID], $vsechnyRadky, $radek[self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku]);

            return $vypis;
	}

	/**
	 * Zkontroluje, zda v DB existuje tabulka s nazvem zadanym jako parametr $nazevCiselniku, v tabulce
	 * existují jen povolené sloupce číselníku a existuje sloupec s nazvem PREFIX_NAZEV_ID.$nazevCiselniku, který je primárním klíčem tabulky,
         * metoda nekontroluje jestli jsou v tabulce všechny povolené sloupce
	 * Poznámka: Tato verze v případě chyby ukončí běh programu.
	 * @param unknown_type $nazevCiselniku
	 * @return boolean
	 */
	private static function jeCiselnikOK($nazevCiselniku, $databaze=NULL)
	{
		// Musí být zadán název DB tabulky číselníku
		if (!isset($nazevCiselniku))
		{
			throw new Data_Exception('*** Chyba v '.__METHOD__.':<BR>'."Parametr název  číselniku musí být zadán");
		}
		// Musí existovat tabulka číselníku v DB
                $dbh = App_Kontext::getDbh($databaze);
                switch($dbh->dbType){
                case 'MySQL':
                    $dbhi = App_Kontext::getDbMySQLInformationSchema();
                    $query = Helper_SqlQuery::getShowTablesQueryMySQL();            
                    break;
                case 'MSSQL':
                    $dbhi = App_Kontext::getDbh($dbh->dbName);
                    $query = Helper_SqlQuery::getShowTablesQueryMSSQL();
                    break;
                default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.':<BR>'."Typ databáze ".$dbh->dbType." neexistuje.");
                }
		$data = $dbhi->prepare($query)->execute($dbh->dbName, self::PREFIX_NAZEV_C.$nazevCiselniku)->fetch_assoc();
		if ($data['Nazev'] != self::PREFIX_NAZEV_C.$nazevCiselniku)
		{
			throw new Data_Exception('*** Chyba v '.__METHOD__.':<BR>'."Tabulka s nazvem ".self::PREFIX_NAZEV_C.$nazevCiselniku." v databazi neexistuje.");
		}
		// DB tabulka číselníku musí obsahovat pouze povolené sloupce a název sloupce s id musí začínat PREFIX_NAZEV_ID
                //Nacteni struktury tabulky, datovych typu a ostatnich parametru tabulky
                switch($dbh->dbType){
                case 'MySQL':
                    $dbhi = App_Kontext::getDbMySQLInformationSchema();
                    $query = Helper_SqlQuery::getShowColumnsQueryMySQL();            
                    break;
                case 'MSSQL':
                    $dbhi = App_Kontext::getDbh($dbh->dbName);
                    $query = Helper_SqlQuery::getShowColumnsQueryMSSQL();
                    break;
                default: throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.':<BR>'."Typ databáze ".$dbh->dbType." neexistuje.");
                }
		$result= $dbhi->prepare($query)->execute($dbh->dbName, self::PREFIX_NAZEV_C.$nazevCiselniku);

		while ($data = $result->fetch_assoc())
		{
			if ($data['Nazev'] != self::RAZENI AND
			$data['Nazev'] != self::KOD AND
			$data['Nazev'] != self::TEXT AND
			$data['Nazev'] != self::PLNY_TEXT AND
			$data['Nazev'] != self::VALID)
			{
				if ($data['Nazev'] !== self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku OR !$data['PK'])
				{
					throw new Data_Exception('*** Chyba v '.__METHOD__.':<BR>'."Tabulka ".self::PREFIX_NAZEV_C.$nazevCiselniku." obsahuje sloupec ".$data['Nazev'].", ktery je v číselniku nepřípustný.");
				}
			}
		}
		return TRUE;
	}
	 
}
?>