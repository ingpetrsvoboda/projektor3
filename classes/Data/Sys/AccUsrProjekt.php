<?php
class Data_Sys_AccUsrProjekt extends Data_Iterator
{
	const TABULKA = "sys_acc_usr_projekt";
	const ID = "id_sys_acc_usr_projekt";
	const ID_SYS_USERS = "id_sys_users";
	const ID_C_PROJEKT = "id_c_projekt";
        
	public $id;
        public $idSysUsers;
        public $idCProjekt;

     
	public function __construct($idSysUsers, $idCProjekt, $id = null)
	{
		$this->id = $id;
		$this->idSysUsers = $idSysUsers;
		$this->idCProjekt = $idCProjekt;

                parent::__construct(__CLASS__);
	}


	/**
	 * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
	 * @param int $id Identifikator radku tabulky
	 * @return Data_Sys_AccUsrProjekt Instance tridy obsahujici data z radku v tabulce
	 */
	public static function najdiPodleId($id)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT * FROM ~1 WHERE ~2 = :3";
		$radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

		if(!$radek)
                    return false;

		return new Data_Sys_AccUsrProjekt($radek[self::ID_SYS_USERS], $radek[self::ID_C_PROJEKT], $radek[self::ID]);
	}        

	/**
	 * Vraci záznamy z tabulky přiřazení uživatel-projekt sys_acc_usr_projekt,
         * odpovídající zadanému id uživatele a id projektu.
	 * @param $userid identifikátor uživatele
	 * @param $kancelarid Identifikátor projektu
	 * @return array Identifikátor řádku s povoleným projektem uživatele.
	 */
	public static function dejPovoleneProjekty($userid)
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "SELECT ~1 FROM ~2 WHERE ~3 = :4";
                $povoleneProjekty = $dbh->prepare($query)->execute(Data_Sys_AccUsrProjekt::ID, Data_Sys_AccUsrProjekt::TABULKA,
                                    Data_Sys_AccUsrProjekt::ID_SYS_USERS, $userid
                                    )->fetchall_assoc();
                foreach($povoleneProjekty as $povolenyProjekt) 
                {
                    $p = Data_Ciselnik::najdiPodleId("projekt", Data_Sys_AccUsrProjekt::najdiPodleId ($povolenyProjekt[Data_Sys_AccUsrProjekt::ID])->idCProjekt);
                    if ($p) $projekty[] = $p;       //vrací jen validní
                }    


		return $projekty;
	}
}