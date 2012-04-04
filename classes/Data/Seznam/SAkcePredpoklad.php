<?php
/**
 * @author Marek Petko =o)
 * @since Sat, 17 Oct 2009 15:01:55 +0200
 *  
 */

//Není to otestovaný, protože mi v tabulce chyběl sloupec valid

class Data_Seznam_SAkcePredpoklad extends Data_Iterator
{
    public $id;
    public $text;
    public $fullText;
    public $idSTypAkceFK;
    public $idSTypAkcePredFK;
    public $idSStavUcastnikAkcePredFK;
    public $valid;

    // Nazev tabulky a sloupcu v DB
    const TABULKA = "s_akce_predpoklady";
    const ID = "id_s_akce_predpoklady";
    const TEXT = "text";
    const FULL_TEXT = "full_text";
    const ID_S_TYP_AKCE_FK = "id_s_typ_akce_FK";
    const ID_S_TYP_AKCE_PRED_FK = "id_s_typ_akce_pred_FK";
    const ID_S_STAV_UCASTNIK_AKCE_PRED_FK = "id_s_stav_ucastnik_akce_pred_FK";
    const VALID = "valid";

    public function __construct($text, $fullText, $idSTypAkceFK, $idSTypAkcePredFK, $idSStavUcastnikAkcePredFK, $valid, $id = null)
    {
        $this->id = $id;
        $this->text = $text;
        $this->fullText = $fullText;
        $this->idSTypAkceFK = $idSTypAkceFK;
        $this->idSTypAkcePredFK = $idSTypAkcePredFK;
        $this->idSStavUcastnikAkcePredFK = $idSStavUcastnikAkcePredFK;
        $this->valid = $valid;

        parent::__construct(__CLASS__);
    }

    /**
        * Najde a vrati jeden radek tabulky v DB s prislusnym ID.
        * @param int $id Identifikator radku tabulky
        * @return Akce Instance tridy obsahujici data z radku v tabulce
        */

    public static function najdiPodleId($id)
    {
        $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
        $query = "SELECT * FROM ~1 WHERE ~2 = :3 AND valid = 1";
        $radek = $dbh->prepare($query)->execute(self::TABULKA, self::ID, $id)->fetch_assoc();

        if(!$radek)
        return false;

        return new Data_Seznam_SAkcePredpoklad($radek[self::TEXT], $radek[self::FULL_TEXT],
        $radek[self::ID_S_TYP_AKCE_FK], $radek[self::ID_S_TYP_AKCE_PRED_FK],
        $radek[self::ID_S_STAV_UCASTNIK_AKCE_PRED_FK], $radek[self::VALID], $radek[self::ID]);
    }

    /**
        * Najde a vrati vsechny radky tabulky v DB odpovidajici prislusnemu filtru.
        * @param string $filtr Filtr odpovidajici SQL dotazu za WHERE
        * @return array() Pole instanci tridy odpovidajici radkum v DB
        */

    public static function vypisVse($filtr = "", $orderBy = "", $order = "")
    {
        $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
        $query = "SELECT ~1 FROM ~2".
                ($filtr == "" ? " WHERE (valid = 1)" : " WHERE (valid = 1 AND {$filtr})").
                ($orderBy == "" ? "" : " ORDER BY `{$orderBy}`")." ".$order;
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
        $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);

        if($this->id == null)
        {
            $query = "INSERT INTO ~1 (~2, ~3, ~4, ~5, ~6, ~7) VALUES (:8, :9, :10, :11, :12, :13)";
            return $dbh->prepare($query)->execute(
                self::TABULKA, self::TEXT, self::FULL_TEXT, self::ID_S_TYP_AKCE_FK, self::ID_S_TYP_AKCE_PRED_FK, self::ID_S_STAV_UCASTNIK_AKCE_PRED_FK, self::VALID,
                $this->text, $this->fullText, $this->idSTypAkceFK, $this->idSTypAkcePredFK, $this->idSStavUcastnikAkcePredFK, $this->valid
            )->last_insert_id();
        }
        else
        {
            $query = "UPDATE ~1 SET ~2=:3, ~4=:5, ~6=:7, ~8=:9, ~10=:11, ~12=:13 WHERE ~14=:15";
            $dbh->prepare($query)->execute(
                self::TABULKA, 
                self::TEXT, $this->text,
                self::FULL_TEXT, $this->fullText,
                self::ID_S_TYP_AKCE_FK, $this->idSTypAkceFK,
                self::ID_S_TYP_AKCE_PRED_FK, $this->idSTypAkcePredFK,
                self::ID_S_STAV_UCASTNIK_AKCE_PRED_FK, $this->idSStavUcastnikAkcePredFK,
                self::VALID, $this->valid,
                self::ID, $this->id
            );
            return true;
        }
    }

    /**
        * Vymaze radek v databazi odpovidajici parametru $id tridy
        * @return unknown_type
        */

    public static function smaz($sAkcePredpoklad)
    {
        $dbh = App_Kontext::getDbh(App_Config::DATABAZE_PROJEKTOR);
        $query = "UPDATE ~1 SET valid = 0 WHERE ~2=:3";
        $dbh->prepare($query)->execute(self::TABULKA, self::ID, $sAkcePredpoklad->id);
    }

    /**
        * Vrati vsechny predpoklady pro prihlaseni k akci.
        * @param Data_Seznam_STypAkce Typ akce pro vypsani
        * @return array Pole instanci predpokladu
        */

    public static function vypisPro($sTypAkce, $orderBy, $order)
    {
        return self::vypisVse(self::ID_S_TYP_AKCE_FK." = {$sTypAkce->id}", $orderBy, $order);
    }


    /**
        * Vrati pole neslpnenych predpokladu.
        * @param Ucastnik $ucastnik Ucastnik projektu.
        * @param Data_Seznam_STypAkce $sTypAkce Typ akce na kterou se ucastnik chce prihlasit.
        * @return array Vraci pole instanci predpokladu nebo nic pokud jsou vsechny predpoklady splneny.
        */

    public static function nesplnene($ucastnik, $sTypAkce)
    {
        /* Nema-li typ akce predpoklady, pak jsou vsechny splneny.*/
        if(!($predpoklady = self::vypisPro($sTypAkce)))
        return array();

        /* Neni-li ucastnik prihlasen na zadnou akci, potom nemel sanci
            * predpoklady splnit a tudiz ma nesplnene vsechny.	*/
        if(!($akceUcastnika = Akce::vsechnyUcastnika($ucastnik)))
        return $predpoklady;

        $stavyAkciUcastnika = Data_Vzb_UcastnikAkce::dejStavy($ucastnik, $akceUcastnika);

        $nesplnene = array();
        foreach($predpoklady as $predpoklad)
        {
            $pocitadlo = 0;
            $splnen = false;
            foreach($akceUcastnika as $akce)
            {
                if(($predpoklad->idSTypAkcePredFK == $akce->idSTypAkceFK) && ($predpoklad->idSStavUcastnikAkcePredFK == $stavyAkciUcastnika[$pocitadlo]->id))
                $splnen = true;

                $pocitadlo++;
            }

            if(!$splnen)
            $nesplnene[] =  $predpoklad;
        }

        return $nesplnene;
    }
}
?>