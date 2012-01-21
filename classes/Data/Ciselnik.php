<?php
/**
 * @author Tomáš Černý, Petr Svoboda
 *
 */
class Data_Ciselnik extends Data_Iterator
{

	public $nazev;
	public $nazevId;
	public $id;
	public $razeni;
	public $kod;
	public $text;
	public $plny_text;
	public $valid;

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
	public function __construct($nazevCiselniku, $razeni, $kod, $text, $plny_text, $valid, $id=NULL)
	{
		$this->nazev = $nazevCiselniku;
		$this->nazevTabulkyCiselniku = self::PREFIX_NAZEV_C.$nazevCiselniku;
		$this->nazevId = self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku;
                $this->id = $id;
		$this->razeni = $razeni;
		$this->kod = $kod;
		$this->text = $text;
		$this->plny_text = $plny_text;
		$this->valid = $valid;

                parent::__construct(__CLASS__);

	}

	/**
	 * Najde a vrátí jeden řádek tabulky v DB se zadaným ID,
	 * vrací jen řádky kde hodnota valid = 1.
	 * @param int $id
	 * @return boolean|Ciselnik
	 */
	public static function najdiPodleId($nazevCiselniku, $id, $vsechnyRadky = FALSE)
	{
//TODO: testování, zda je ciselnikOK trvá asi 5ms - chtělo by to ?? hlídat poslední změnu tabulky ?? možnost vypnout kontrolu pro produkční verzi ?? něco
            try
		{
			self::jeCiselnikOK($nazevCiselniku);
		}
		catch (Data_Exception $e)
		{

			//        echo "Caught TestException ('{$e->getMessage()}')\n{$e}\n";
			echo $e."<BR>";
		}
		$dbh = App_Kontext::getDbMySQL();
		$query = "SELECT * FROM ~1 WHERE ~2 LIKE :3" . ($vsechnyRadky ? "" : " AND valid = 1");
		$radek = $dbh->prepare($query)->execute(self::PREFIX_NAZEV_C.$nazevCiselniku,
                                                        self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku,
                                                        $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Ciselnik($nazevCiselniku, $radek[self::RAZENI], $radek[self::KOD], $radek[self::TEXT], $radek[self::PLNY_TEXT],
		$radek[self::VALID], $radek[self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku]);
	}

	/**
	 * Vrátí pole objektů obsahujících řádky číselníku,
	 * vrací jen řádky odpovídající kontext filtru a případně zadanému filtru.
	 * @param $nazevCiselniku
	 * @param $filtr řetězec, který bude dosazen do klauzule WHERE
         * @param type $nazevIdProjekt Zde je možno zadat název sloupce v číselníku obsahujícího jako cizí klíč id tabulky c_projekt pro vytvoření kontext filtru
         * @param type $nazevIdKancelar Zde je možno zadat název sloupce v číselníku obsahujícího jako cizí klíč id tabulky c_kancelar pro vytvoření kontext filtru
         * @param type $nazevIdBeh Zde je možno zadat název sloupce v číselníku obsahujícího jako cizí klíč id tabulky s_beh_projektu pro vytvoření kontext filtru
         * @return array() Pole instanci tridy odpovidajici radkum v číselníku v DB
	 */
        public static function vypisVse($nazevCiselniku,$filtr = "", $nazevIdProjekt = NULL, $nazevIdKancelar = NULL, $nazevIdBeh = NULL)
	{
		try
		{
			self::jeCiselnikOK($nazevCiselniku);
		}
		catch (Data_Exception $e)
		{
			echo $e.'<BR>';
		}
		$dbh = App_Kontext::getDbMySQL();
                $kontextFiltr = App_Kontext::getKontextFiltrSQL($nazevIdProjekt, $nazevIdKancelar, $nazevIdBeh, $filtr);
                $query = "SELECT * FROM ~1".($kontextFiltr ? " WHERE ".$kontextFiltr : "")." ORDER BY razeni ASC";
//                $query = "SELECT * FROM ~1 WHERE ".($filtr == "" ? "valid = 1" : "(valid = 1 AND {$filtr})")." ORDER BY razeni ASC";
		$radky = $dbh->prepare($query)->execute(self::PREFIX_NAZEV_C.$nazevCiselniku)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = new Data_Ciselnik($nazevCiselniku, $radek[self::RAZENI], $radek[self::KOD], $radek[self::TEXT], $radek[self::PLNY_TEXT],
		$radek[self::VALID], $radek[self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku]);
                    
                //$vypis[] =     self::najdiPodleId($nazevCiselniku, $radek[self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku]);
		 
		return $vypis;
	}

	/**
	 * Zkontroluje, zda v DB existuje tabulka s nazvem zadanym jako parametr $nazevCiselniku, v tabulce
	 * existují jen povolené sloupce číselníku a existuje sloupec s nazvem PREFIX_NAZEV_ID.$nazevCiselniku, který je primárním klíčem tabulky
	 * Poznámka: Tato verze v případě chyby ukončí běh programu.
	 * @param unknown_type $nazevCiselniku
	 * @return boolean
	 */
	private static function jeCiselnikOK($nazevCiselniku)
	{
		//        $this->chyby = new chyby();

		$OK = true;

		// Musí být zadán název DB tabulky číselníku
		if (!isset($nazevCiselniku))
		{
			$OK = false;
			throw new Data_Exception('*** Chyba v '.__METHOD__.':<BR>'."Parametr název  číselniku musí být zadán");
		}
		// Musí existovat tabulka číselníku v DB
		$dbh = App_Kontext::getDbMySQL();
		$query = "SHOW TABLES LIKE :1";
		$data = $dbh->prepare($query)->execute(self::PREFIX_NAZEV_C.$nazevCiselniku)->fetch_row();
		if ($data[0] != self::PREFIX_NAZEV_C.$nazevCiselniku)
		{
			$OK = false;
			throw new Data_Exception('*** Chyba v '.__METHOD__.':<BR>'."Tabulka s nazvem ".self::PREFIX_NAZEV_C.$nazevCiselniku." v databazi neexistuje.");
		}
		// DB tabulka číselníku musí obsahovat pouze povolené sloupce a název sloupce s id musí začínat PREFIX_NAZEV_ID
		$dbh = App_Kontext::getDbMySQL();
		$query = "SHOW COLUMNS FROM ".self::PREFIX_NAZEV_C.$nazevCiselniku;
		$result= $dbh->prepare($query)->execute('');
		/*  Příkaz SHOW vrací informace o sloupcích a v případě sloupce s indexem (primary key) je v $Data toto:
		 *  $data = Array [6]
		 *	Field = id_c_jazyk
		 *	Type = int(11) unsigned
		 * 	Null = NO
		 *	Key = PRI
		 *	Default = <Uninitialized>
		 *	Extra = auto_increment
		 *
		 *	Testuji jestli v některém sloupci číselníku prvek Field je PREFIX_NAZEV_ID.$nazevCiselniku
		 *  a prvek Key je PRI (primární klíč tabulky)
		 */
		while ($data = $result->fetch_assoc())
		{
			if ($data['Field'] != self::RAZENI and
			$data['Field'] != self::KOD and
			$data['Field'] != self::TEXT and
			$data['Field'] != self::PLNY_TEXT and
			$data['Field'] != self::VALID)
			{
				if ($data['Field'] == self::PREFIX_NAZEV_ID.self::PREFIX_NAZEV_C.$nazevCiselniku and $data['Key'] == "PRI")
				{
					$OK = false;
				}
				else
				{
					throw new Data_Exception('*** Chyba v '.__METHOD__.':<BR>'."Tabulka ".self::PREFIX_NAZEV_C.$nazevCiselniku." obsahuje sloupec ".$data['Field'].", ktery je v číselniku nepřípustný.");
				}
			}
		}
		return $OK;
	}
	 
}
?>