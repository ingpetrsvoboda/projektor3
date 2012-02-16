<?php
/*
 * Abstraktní třída, od které jsou odvozovány hlavní objekty (stromové)
 * implemetnuje rozhraní Iterator, které je tak dostupné v každém hlavním objektu
 * obsahuje konkrétní metodu _get, která lazy load vytváří podřízené objekty (objekty vlastnosti)
 *
 * @author Petr Svoboda
 */
abstract class Data_HlavniObjekt extends Data_Iterator
{
    const IDENTIFIKATOR = "identifikator";
    const UPDATED = "updated";
    const VALID = "valid";
    const TABULKA_C_PROJEKT = "projekt";
    const TABULKA_C_KANCELAR = "kancelar";
    const OBJEKT_S_BEH_PROJEKTU = "Data_Seznam_SBehProjektu";
    const ID_C_PROJEKT_FK = "id_c_projekt_FK";
    const ID_S_BEH_PROJEKTU_FK = "id_s_beh_projektu_FK";
    const ID_C_KANCELAR_FK = "id_c_kancelar_FK";

    public $id;
    public $nazevTridy;
    public $tabulka;
    public $jmenoId;
    public $prefix;
    
    public $identifikator;
    public $prvniCisliceIdentifikatoru;
    public $cisloHlavnihoObjektu;
    public $idSBehProjektuFK;
    public $idCProjektFK;
    public $idCKancelarFK;
    public $updated;
    //pole pro uložení "podřízených" objektů = objektu s vlastnostmi, vlastnosti jsou dostupné pouze přes getter __get() a jsou lazy initiation
    protected $_vlatnostiObjekty = array();

    public function __construct($nazevTridy, $tabulka, $prefix, $jmenoId, $kontextIdentifikatoru,
                                $cisloHlavnihoObjektu, $identifikator, $idCProjektFK, $idSBehProjektuFK, $idCKancelarFK,
                                $updated, $id) {
        $this->nazevTridy = $nazevTridy;
        $this->tabulka = $tabulka;
        $this->prefix = $prefix;
        $this->jmenoId = $jmenoId;
        $this->prvniCisliceIdentifikatoru = $kontextIdentifikatoru;
        $this->cisloHlavnihoObjektu = $cisloHlavnihoObjektu;
        $this->identifikator = $identifikator;
        $this->idCProjektFK = $idCProjektFK;
        $this->idSBehProjektuFK = $idSBehProjektuFK;
        $this->idCKancelarFK = $idCKancelarFK;
        $this->updated = $updated;

        $this->id = $id;

        parent::__construct($nazevTridy);      
    }
    /**
     *
     */
    public function __get($vlastnost)
    {
         //vlastnost je přímo vlastností objektu (nikoli v poli pro "lazy load" vlastnosti $this->_vlatnostiObjekty)
         if (property_exists($this, $vlastnost)){
            return $this->$vlastnost;
        }
        // Lazy initialization
        if(!array_key_exists($vlastnost, $this->_vlatnostiObjekty))       //"lazy load" vlastnost (objekt) ještě nebyla instancována
        {
            if (!array_key_exists($vlastnost, $this->_mapovaniObjektTabulka))
            {
                // požadovaná "lazy load" vlastnost (objekt) není v poli mapování objektů na tabulky
                // v poli $_mapovaniObjektTabulka neexistuje db tabulka přiřazená k zadané vlastnosti
                // - metoda vrací FASLE
                return FALSE;
            }
            else
            {
                //instancuje "lazy load" vlastnost
                $tabulka = $this->prefix.$this->_mapovaniObjektTabulka[$vlastnost];
                $ft = Data_Flat_FlatTable::najdiPodleId($tabulka, $this->id, TRUE, $this->tabulka, $this->jmenoId);
                $this->_vlatnostiObjekty[$vlastnost] = $ft;
            }
        }
        return $this->_vlatnostiObjekty[$vlastnost];        //vrací objekt, který je vlastností
    }

    /**
     * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
     * @param int $id Identifikator radku tabulky
     * @return Instance tridy hlavniho objektu zadaneho v paranetru $nazevTridy, obsahujici data z radku v tabulce hlavniho objektu
     */
    public static function najdiPodleId($id, $jmenoHlavnihoObjektu, $tabulka="", $nazevIdTabulky="", $nazevCislaCbjektu="")
    {
//TODO: nekontrolují se parametry jmenolavnihoObjektu, tabulka, nazevIdTabulky, nazevCislaObjektu            
            $dbh = App_Kontext::getDbMySQLProjektor();
                $kontextFiltr = App_Kontext::getKontextFiltrSQL(self::ID_C_PROJEKT_FK, self::ID_C_KANCELAR_FK, self::ID_S_BEH_PROJEKTU_FK);
		$query = "SELECT * FROM ~1 WHERE ~2 = :3".($kontextFiltr ? " AND ".$kontextFiltr : "");
                $radek = $dbh->prepare($query)->execute($tabulka, $nazevIdTabulky, $id)->fetch_assoc();

            if(!$radek) return false;
            $tridaHlavnihoObjektu = "Data_".$jmenoHlavnihoObjektu;
            return new $tridaHlavnihoObjektu($radek[$nazevCislaCbjektu], $radek[self::IDENTIFIKATOR], $radek[self::ID_C_PROJEKT_FK],
                    $radek[self::ID_S_BEH_PROJEKTU_FK], $radek[self::ID_C_KANCELAR_FK], $radek[self::UPDATED], $radek[$nazevIdTabulky]);
    }


    /**
     * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru a soucasne kontextu.
     * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
     * @return array() Pole instanci tridy odpovidajici radkum v DB
     */
    public static function vypisVse($filtr = "", $orderBy = "", $order = "", $jmenoHlavnihoObjektu="", $tabulka="", $nazevIdTabulky="", $nazevCislaCbjektu="")
    {
//TODO: nekontrolují se parametry jmenolavnihoObjektu, tabulka, nazevCislaObjektu            
            if ($jmenoHlavnihoObjektu AND $tabulka) {
                $dbh = App_Kontext::getDbMySQLProjektor();
                $kontextFiltr = App_Kontext::getKontextFiltrSQL(self::ID_C_PROJEKT_FK, self::ID_C_KANCELAR_FK, self::ID_S_BEH_PROJEKTU_FK, $filtr, $orderBy, $order);
    //TODO: tento query do všech datových tříd - metoda vypisVse
                $query = "SELECT * FROM ~1".($kontextFiltr ? " WHERE ".$kontextFiltr : "");
                $radky = $dbh->prepare($query)->execute($tabulka)->fetchall_assoc();

                $tridaHlavnihoObjektu = "Data_".$jmenoHlavnihoObjektu;
                foreach($radky as $radek)
                        $vypis[] = new $tridaHlavnihoObjektu($radek[$nazevCislaCbjektu], $radek[self::IDENTIFIKATOR], $radek[self::ID_C_PROJEKT_FK],
                        $radek[self::ID_S_BEH_PROJEKTU_FK], $radek[self::ID_C_KANCELAR_FK], $radek[self::UPDATED], $radek[$nazevIdTabulky]);

                return $vypis;
            } else {
                return FALSE;
            }
    }


    /**
     * Ulozi parametry tridy jako radek do DB.
     * @return int ID naposledy vlozeneho radku, -1 pokud doslo k chybe.
     */
    public function uloz($tabulka="")
    {
//        return parent::najdiPodleId($id, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);

        // je kontroly povinných vlastnistí (projekt, beh, kancelar + ??)
        $kontext = App_Kontext::getUserKontext();
        $objektLzeUlozit = true;
        $ulozeno = false;
        try {
            if (!$tabulka)
            {
                throw new Data_Exception("Nebyla zadana tabulka pro uložení objektu - objekt neni mozno ulozit.");
            }
            if ($kontext->projekt AND !($this->Projekt == $kontext->projekt))
            {
                throw new Data_Exception("Vlastnost Projekt objektu".__CLASS__." neodpovida kontextu: projekt ".$kontext->projekt->text." - objekt neni mozno ulozit.");
            }
            if ($kontext->beh AND !($this->Beh == $kontext->beh))
            {
                throw new Data_Exception("Vlastnost Beh objektu".__CLASS__." neodpovida kontextu: beh ".$kontext->beh->text." - objekt neni mozno ulozit.<BR>");
            }
            if ($kontext->kancelar AND !($this->Kancelar == $kontext->kancelar))
            {
                throw new Data_Exception("Vlastnost Kancelar objektu".__CLASS__." neodpovida kontextu: kancelar ".$kontext->kancelar->text." - objekt neni mozno ulozit.<BR>");
            }
        } catch (Data_Exception $e) {
            $objektLzeUlozit = false;
            echo "Zachycena výjimka Data_Exception ('{$e}')\n";
        }
        if($this->id == null)
        { // nový hlavní objekt
            $noveCisloObjektu = Data_NoveCisloObjektu::dejNoveCislo($this);
            // generování identifikátoru hlavního objektu
            $identifikator = Data_Identifikator::generuj(self::PRVNI_CISLICE_IDENTIFIKATORU, $this->Kancelar->id, $this->Projekt->id, $this->Beh->behCislo, $noveCisloObjektu);
            if (!$identifikator)
            {
                echo ("Při pokusu o uložení nového objektu se nepodařilo vygenerovat identifikátor - objekt pro tabulku ".self::TABULKA." neni mozno ulozit.<BR>");
                $objektLzeUlozit = false;
            }

            // objekt nelze uložit -> konec
            if (!$objektLzeUlozit)
            {
                return false;
            }

            $this->cisloHlavnihoObjektu = $noveCisloObjektu;
            $this->identifikator = $identifikator->identifikator;
            
            $query = "INSERT INTO  ~1 (cislo_ucastnika, identifikator, id_c_projekt_FK, id_c_kancelar_FK,id_s_beh_projektu_FK ) VALUES (:2, :3, :4, :5, :6)" ;
            $pocet = $dbh->prepare($query)->execute($tabulka, $this->cisloHlavnihoObjektu, $this->identifikator ,$this->Projekt->id,$this->Kancelar->id,$this->Beh->id)->result;
            if ($pocet == 1) $ulozeno = true;

        } else {
            // starý účastník
            $ulozeno = true;
        }
            // uložení vlastností-objektů
            if ($ulozeno) {
                foreach ($this->_vlatnostiObjekty as $key=>$obj) {
                    if ( !$obj->uloz()) $ulozeno = false;
                }
            }
            return $ulozeno;

    }

	/**
	 * Nastavi v radku v databaze odpovidajici parametru $id tridy hodnotu valid = 0
	 * @return unknown_type
	 */
	public static function smaz()
	{
		$dbh = App_Kontext::getDbMySQLProjektor();
		$query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
		$dbh->prepare($query)->execute(self::TABULKA, self::ID, $this->id);
	}      

    /**
     * Najde a vrati vsechny Ucastniky prihlasene k Akce
     * @return array() Pole instanci Ucastnik
     */
    public static function vypisPrihlaseneNaAkci($idAkce="", $jmenoHlavnihoObjektu, $tabulka="", $nazevIdTabulky="", $nazevCislaCbjektu="")
    {
            $dbh = App_Kontext::getDbMySQLProjektor();
            $query = "SELECT ~1 FROM ~2 WHERE ~3=:4";
            $radky = $dbh->prepare($query)->execute(Data_Vzb_UcastnikAkce::ID_UCASTNIK_FK, Data_Vzb_UcastnikAkce::TABULKA,
            Data_Vzb_UcastnikAkce::ID_AKCE_FK, $idAkce)->fetchall_assoc();
            
            if(!$radky) return false;
            $tridaHlavnihoObjektu = "Data_".$jmenoHlavnihoObjektu;
            foreach($radky as $radek)
                    $vypis[] = $tridaHlavnihoObjektu::najdiPodleId($radek[Data_Vzb_UcastnikAkce::ID_UCASTNIK_FK], $tridaHlavnihoObjektu::HLAVNI_OBJEKT, $tridaHlavnihoObjektu::TABULKA, $tridaHlavnihoObjektu::ID, $tridaHlavnihoObjektu::CISLO_OBJEKTU);
            return $vypis;
    }    
}
?>
