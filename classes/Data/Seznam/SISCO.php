<?php
/**
 * @author Svoboda Petr
 */

class Data_Seznam_SISCO extends Data_Iterator
{
	public $id;
	public $kod;
	public $nazev;

	// Nazev tabulky a sloupcu v DB
	const TABULKA = "s_isco";
	const ID = "id_s_isco";
	const KOD = "kod";
        const NAZEV = "nazev";

        public function __construct($kod, $nazev, $id = null)
        {
            $this->id = $id;
            $this->kod = $kod;
            $this->nazev = $nazev;

            parent::__construct(__CLASS__);
        }

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Beh Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_SISCO($radek[self::KOD], $radek[self::NAZEV],
                                                    $radek[self::ID]);
	}

	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "", $orderBy = "", $order = "")
	{
		$dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
		$query = "SELECT * FROM ~1".
			($filtr == "" ? "" : " WHERE ({$filtr})").
			($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;
		$radky = $dbh->prepare($query)->execute(self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
                        $vypis[] = new Data_Seznam_SISCO($radek[self::KOD], $radek[self::NAZEV], $radek[self::ID]);
		return $vypis;
	}

	/**
	 * Ulozi parametry tridy jako radek do DB.
	 * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
	 */

//	public function uloz()
//	{
//		$dbh = AppContext::getDB();
//
//		if($this->id == null)
//		{
//			$query = "INSERT INTO ~1 (~2, ~3) VALUES (:4, :5)";
//			return $dbh->prepare($query)->execute(
//			self::TABULKA, self::KOD, self::NAZEV,
//			$this->kod, $this->nazev
//			)->last_insert_id();
//		}
//		else
//		{
//			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, WHERE ~18=:19";
//			$dbh->prepare($query)->execute(
//			self::TABULKA,
//			self::KOD, $this->kod,
//			self::NAZEV, $this->nazev,
//			self::ID, $this->id
//			);
//			return true;
//		}
//	}
}
?>
