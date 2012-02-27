<?php
/**
 * @author Svoboda Petr
 */

class Data_Seznam_SBehProjektu extends Data_Iterator
{
	public $id;
	public $behCislo;
	public $oznaceniTurnusu;
	public $idCProjekt;
	public $text;
	public $zacatek;
	public $konec;
	public $closed;
	public $valid;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_beh_projektu";
	const ID = "id_s_beh_projektu";
	const BEH_CISLO = "beh_cislo";
        const OZNACENI_TURNUSU = "oznaceni_turnusu";
	const ID_C_PROJEKT_FK = "id_c_projekt";
	const TEXT = "text";
	const ZACATEK = "zacatek";
	const KONEC = "konec";
	const CLOSED = "closed";
	const VALID = "valid";

	public function __construct($BehCislo, $oznaceniTurnusu, $IdCProjekt, $Text, $Zacatek, $Konec, $Closed, $Valid, $id = null)
	{
	 $this->id = $id;
	 $this->behCislo = $BehCislo;
         $this->oznaceniTurnusu = $oznaceniTurnusu;
	 $this->idCProjekt = $IdCProjekt;
	 $this->text = $Text;
	 $this->zacatek = $Zacatek;
	 $this->konec = $Konec;
	 $this->closed = $Closed;
	 $this->valid = $Valid;
         //nestandardní volání pro třídy, které mají kontextově závislá data (projekt, kancelar, beh) a jsou potomkem Data_Kontext
         parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Beh Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
                $kontextFiltr = App_Kontext::getKontextFiltrSQL(self::ID_C_PROJEKT_FK, "", "", "~2 = :3");
		$query = "SELECT * FROM ~1".$kontextFiltr;
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_SBehProjektu($radek[self::BEH_CISLO], $radek[self::OZNACENI_TURNUSU], $radek[self::ID_C_PROJEKT_FK], $radek[self::TEXT], $radek[self::ZACATEK],
                                                    $radek[self::KONEC], $radek[self::CLOSED], $radek[self::VALID],
                                                    $radek[self::ID]);
	}

	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "", $orderBy = "", $order = "")
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
                $kontextFiltr = App_Kontext::getKontextFiltrSQL(self::ID_C_PROJEKT_FK, NULL, NULL, $filtr, $orderBy, $order);
		$query = "SELECT * FROM ~1".$kontextFiltr;
		$radky = $dbh->prepare($query)->execute(self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
			$vypis[] = new Data_Seznam_SBehProjektu($radek[self::BEH_CISLO], $radek[self::OZNACENI_TURNUSU], $radek[self::ID_C_PROJEKT_FK], $radek[self::TEXT], $radek[self::ZACATEK],
                                                    $radek[self::KONEC], $radek[self::CLOSED], $radek[self::VALID],
                                                    $radek[self::ID]);
		return $vypis;
	}

	/**
	 * Ulozi parametry tridy jako radek do DB.
	 * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
	 */

	public function uloz()
	{
		$dbh = App_Kontext::getDbMySQLProjektor();

		if($this->id == null)
		{
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7, ~8) VALUES (:9, :10, :11, :12, :13, :14, :15)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::BEH_CISLO, self::ID_C_PROJEKT_FK, self::TEXT, self::ZACATEK, self::KONEC, self::CLOSED, self::VALID,
			$this->behcislo, $this->idcprojekt, $this->text, $this->zacatek, $this->konec, $this->closed, $this->valid
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11, ~12=:13, ~14=:15, WHERE ~18=:19";
			$dbh->prepare($query)->execute(
			self::TABULKA,
			self::BEH_CISLO, $this->behcislo,
			self::ID_C_PROJEKT_FK, $this->idcprojekt,
			self::TEXT,  $this->text,
			self::ZACATEK,  $this->zacatek,
			self::KONEC,  $this->konec,
			self::CLOSED,  $this->closed,
			self::VALID, $this->valid,
			self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vymaze hodnotu ve sloupci valid v radku odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($sBehProjekt)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $sBehProjekt->id);
	}


}
?>
