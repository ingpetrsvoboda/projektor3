<?php
/**
 * @author PHP_UML
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 */

class Data_Seznam_SPersonal extends Data_Iterator
{
	public $id;
	public $jmeno;
	public $prijmeni;
	public $telefonPrace;
	public $telefonMobil;
	public $emailGrafia;
	public $emailOsobni;
	public $infoPublic;
	public $infoManager;
	public $idCRoleFK;
	public $cRole;
	public $idCZamestnavatelPersonalFK;
	public $cZamestnavatelPersonal;

	const TABULKA = "s_personal";
	const ID = "id_s_personal";
	const JMENO = "jmeno";
	const PRIJMENI = "prijmeni";
	const TELEFON_PRACE = "telefon_prace";
	const TELEFON_MOBIL = "telefon_mobil";
	const E_MAIL_GRAFIA = "e-mail_Grafia";
	const E_MAIL_OSOBNI = "e-mail_osobni";
	const INFO_PUBLIC = "info_public";
	const INFO_MANAGER = "info_manager";
	const ID_C_ROLE_FK = "id_c_role_FK";
	const TABULKA_C_ROLE = "c_role";
	const ID_C_ZAMESTNAVATEL_PERSONAL_FK = "id_c_zamestnavatel_personal_FK";
	const TABULKA_C_ZAMESTNAVATEL_PERSONAL_FK = "c_zamestnavatel_personal";

	public function __construct($jmeno, $prijmeni, $telefonPrace, $telefonMobil, $emailGrafia, $emailOsobni, $infoPublic, $infoManager, $idCRoleFK, $idCZamestnavatelPersonalFK, $id = null)
	{
		$this->id = $id;
		$this->jmeno = $jmeno;
		$this->prijmeni = $prijmeni;
		$this->telefonPrace = $telefonPrace;
		$this->telefonMobil = $telefonMobil;
		$this->emailGrafia = $emailGrafia;
		$this->emailOsobni = $emailOsobni;
		$this->infoPublic = $infoPublic;
		$this->infoManager = $infoManager;
		$this->idCRoleFK = $idCRoleFK;
		$this->cRole = Ciselnik::quickValue(self::TABULKA_C_ROLE, $idCRoleFK);
		$this->idCZamestnavatelPersonalFK = $idCZamestnavatelPersonalFK;
		$this->cZamestnavatelPersonal = Ciselnik::quickValue(self::TABULKA_C_ZAMESTNAVATEL_PERSONAL_FK, $idCZamestnavatelPersonalFK);

                parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Akce Instance tridy obsahujici data z radku v tabulce
	 */

	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
		return false;

		return new Data_Seznam_SPersonal($radek[self::JMENO], $radek[self::PRIJMENI],
		$radek[self::TELEFON_PRACE], $radek[self::TELEFON_MOBIL],
		$radek[self::E_MAIL_GRAFIA], $radek[self::E_MAIL_OSOBNI],
		$radek[self::INFO_PUBLIC], $radek[self::INFO_MANAGER],
		$radek[self::ID_C_ROLE_FK], $radek[self::ID_C_ZAMESTNAVATEL_PERSONAL_FK],
		$radek[self::ID]);
	}


	/**
	 * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
	 * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
	 * @return array() Pole instanci tridy odpovidajici radkum v DB
	 */

	public static function vypisVse($filtr = "")
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT ~1 FROM ~2 WHERE ".($filtr == "" ? "valid = 1" : "(valid = 1 AND {$filtr})");
		$radky = $dbh->prepare($query)->execute(self::ID, self::TABULKA)->fetchall_assoc();

		foreach($radky as $radek)
		$vypis[] = self::najdiPodleId($radek[self::ID]);
		 
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
			$query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7, ~8, ~9, ~10, ~11) VALUES (:12, :13, :14, :15, :16, :17, :18, :19, :20, :21)";
			return $dbh->prepare($query)->execute(
			self::TABULKA, self::JMENO, self::PRIJMENI, self::TELEFON_PRACE, self::TELEFON_MOBIL, self::E_MAIL_GRAFIA, self::E_MAIL_OSOBNI, self::INFO_PUBLIC, self::INFO_MANAGER, self::ID_C_ROLE_FK, self::ID_C_ZAMESTNAVATEL_PERSONAL_FK,
			$this->jmeno, $this->prijmeni, $this->telefonPrace, $this->telefonMobil, $this->emailGrafia, $this->emailOsobni, $this->infoPublic, $this->infoManager, $this->idCRoleFK, $this->idCZamestnavatelPersonalFK
			)->last_insert_id();
		}
		else
		{
			$query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11, ~12=:13, ~14=:15, ~16=:17, ~18=:19, ~20=:21, WHERE ~22=:23";
			$dbh->prepare($query)->execute(
			self::TABULKA, self::JMENO, $this->jmeno,
			self::PRIJMENI, $this->prijmeni,
			self::TELEFON_PRACE, $this->telefonPrace,
			self::TELEFON_MOBIL, $this->telefonMobil,
			self::E_MAIL_GRAFIA, $this->emailGrafia,
			self::E_MAIL_OSOBNI, $this->emailOsobni,
			self::INFO_PUBLIC, $this->infoPublic,
			self::INFO_MANAGER, $this->infoManager,
			self::ID_C_ROLE_FK, $this->idCRoleFK,
			self::ID_C_ZAMESTNAVATEL_PERSONAL_FK, $this->idCZamestnavatelPersonalFK,
			self::ID, $this->id
			);
			return true;
		}
	}

	/**
	 * Vymaze radek v databazi odpovidajici parametru $id tridy
	 * @return unknown_type
	 */

	public static function smaz($SPersonal)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $SPersonal->id);
	}


}
?>
