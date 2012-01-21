<?php
class Data_Sys_AccUsrKancelar extends Data_Iterator
{
	const TABULKA = "sys_acc_usr_kancelar";
	const ID = "id_sys_acc_usr_kancelar";
	const ID_SYS_USERS = "id_sys_users";
	const ID_C_KANCELAR = "id_c_kancelar";

	public $id;
        public $idSysUsers;
        public $idCKancelar;

     
	public function __construct($idSysUsers, $idCKancelar, $id = null)
	{
		$this->id = $id;
		$this->idSysUsers = $idSysUsers;
		$this->idCKancelar = $idCKancelar;

                parent::__construct(__CLASS__);
	}

	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Data_Sys_AccUsrProjekt Instance tridy obsahujici data z radku v tabulce
	 */
	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQL();
		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
                    return false;

		return new Data_Sys_AccUsrKancelar($radek[self::ID_SYS_USERS], $radek[self::ID_C_KANCELAR], $radek[self::ID]);
	}        
     
        /**
	 * Vraci záznamy z tabulky přiřazení uživatel-kancelář sys_acc_usr_kancelar,
         * odpovídající zadanému id uživatele a id kanceláře.
	 * @param $userid Identifikátor uživatele
	 * @param $kancelarid Identifikátor kanceláře
	 * @return array Pole identifikátorů řádků s povolenou kanceláří uživatele.
	 */
	public static function dejPovoleneKancelare($userid)
	{
		$dbh = App_Kontext::getDbMySQL();
		$query = "SELECT ~1 FROM ~2 WHERE ~3 = :4";
                $povoleneKancelare = $dbh->prepare($query)->execute(Data_Sys_AccUsrKancelar::ID, Data_Sys_AccUsrKancelar::TABULKA,
                Data_Sys_AccUsrKancelar::ID_SYS_USERS, $userid
                )->fetchall_assoc();
		foreach($povoleneKancelare as $povolenaKancelar)
                {
                    $k = Data_Ciselnik::najdiPodleId("kancelar", Data_Sys_AccUsrKancelar::najdiPodleId ($povolenaKancelar[Data_Sys_AccUsrKancelar::ID])->idCKancelar);
                    if ($k) $kancelare[] = $k;      //vrací jen validní
                }


		return $kancelare;
	}
}