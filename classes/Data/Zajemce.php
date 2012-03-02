<?php
/**
 * třída vytváří objekt tak, že vytvoří hlavní (kořenový) objekt , 
 * který má vlastnosti odpovídající sloupcům db tabulky TABULKA dále a vlastnosti typu object, odpovídající jednotlivým db podřízeným tabulkám,
 * podřízené tabulky (flat table) mají všechny název začínající PREFIXem a data z nich jsou načítána třídou FlatTable
 *
 * vlastnost objektu Ucastnik->id obsahuje ID záznamu v tabulce ucastnik a 
 * podřízené tabulky mají sloupec id_ucastnik obsahující jako cizí klíč id záznamu v tabulce ucastnik
 * podřízené objekty (odpovídající podřízeným tabulkám) mají vlastnost $this->id_hlavniho_objektu obsahující totéž id jako Ucastnik->id,
 *
 * vlastnosti objektu Ucastnik jsou nastaveny v konstruktoru tak, že
 * vlastnosti odpovídající sloupcům db tabulky TABULKA (ucastnik) obsahujícím přímo data jsou v konstruktoru naplněny těmito daty,
 * vlastnosti odpovídající sloupcům db tabulky TABULKA (ucastnik) obsahujícím vizí klíce (cizí klíče tabulek s císelníky) jsou naplněny objekty obsahujícími příslušnou řádku číselníku (projekt, beh, kancelar)
 * podřízené objekty jsou vlastnostmi objektu Ucastnik, ale jsou v protected poli a dostupné pouze přes getter __get(), tyto prízeneé objekty jsou
 * načítány lazy load až v okamžiku přístupu k některé vlastnosti příslušného podřízeného objektu - v takové případě je pak načten celý podřízený objektobjekt
 *
 * @author Petr Svoboda
 */

class Data_Zajemce extends Data_HlavniObjekt
{
    const HLAVNI_OBJEKT = "Zajemce";
    const TABULKA = "zajemce"; 
//TODO: přejmenovat sloupec v db na id_zajemce_FK a změnit konstantu
    const ID = "id_zajemce";
    const CISLO_OBJEKTU = "cislo_zajemce";
    //tento prefix musi mit nazvy vsech db tabulek s vlastnostmi objektu
    const PREFIX = "za_";
    const KONTEXT_IDENTIFIKATORU = 3;

    //pole obsahujici mapovani (prirazeni) nazvu objektu (vlastnosti Ucastnika) na tabulky v databazi,
    //nazev tabulky se sklada z PREFIXu a nazvu z _tableMap
    protected $_mapovaniObjektTabulka = array(
             'smlouva' => 'flat_table',
             'dotaznik' => 'flat_table',
             'plan' => 'plan_flat_table',
             'ukonceni' => 'ukonc_flat_table',
             'zamestnani' => 'zam_flat_table',
             'test' => 'test_flat_table',

        );
    
    public $celeJmeno;
    public $zamestnani_pozice1;


    public function __construct($cisloHlavnihoObjektu = 0, $identifikator =0, $idCProjektFK = null, $idSBehProjektuFK = null, $idCKancelarFK = null,
                                $updated = 0, $id = null)
    {
            parent::__construct(__CLASS__, self::TABULKA, self::PREFIX, self::ID, self::KONTEXT_IDENTIFIKATORU, 
                                $cisloHlavnihoObjektu, $identifikator, $idCProjektFK, $idSBehProjektuFK, $idCKancelarFK,
                                $updated, $id);

            //pouze pro účely vytvoření dalších vlastností pro zobrazení v seznamu se vytvoří a pak zruší vlastnost smlouva
            $this->celeJmeno = $this->smlouva->prijmeni." ".$this->smlouva->jmeno
                        .($this->smlouva->titul ? ", ".$this->smlouva->titul : "")
                        .($this->smlouva->titul_za ? ", ".$this->smlouva->titul_za : "");
            $this->zamestnani_pozice1 = ((strlen($this->smlouva->zamestnani_pozice1)>41 ? substr($this->smlouva->zamestnani_pozice1, 0, 35)." ..." : $this->smlouva->zamestnani_pozice1));
            unset($this->_vlatnostiObjekty['smlouva']);  
    }

    public static function najdiPodleId($id) {
        return parent::najdiPodleId($id, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);
    }
    
    public static function vypisVse($filtr = "", $orderBy = "", $order = "") {
        return parent::vypisVse($filtr, $orderBy, $order, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);        
    }   
    
    /**
     * Najde a vrati vsechny Ucastniky prihlasene k Akce
     * @return array() Pole instanci Ucastnik
     */
    public static function vypisVhodneNaPozici($iscoKod)
    {
        $prefix = substr($iscoKod, 0, 2);
        $delkaPrefixu = strlen($prefix);
//        $seznamisco = Data_Seznam_SISCO::vypisVse("LENGTH(`".Data_Seznam_SISCO::KOD."`)=".$delkaKodu." AND LEFT(`".Data_Seznam_SISCO::KOD."`, ".$delkaPrefixu.")='".$prefix."'");
// natvrdo zadané hodnoty v vypisVhodneNaPozici - nevhodné - předělat
        $tabulka = 'za_flat_table';
        $filtr = "LENGTH(`KZAM_cislo1`)=5 AND LEFT(`KZAM_cislo1`, ".$delkaPrefixu.")='".$prefix."'".
                " OR LENGTH(`KZAM_cislo2`)=5 AND LEFT(`KZAM_cislo2`, ".$delkaPrefixu.")='".$prefix."'".
                " OR LENGTH(`KZAM_cislo3`)=5 AND LEFT(`KZAM_cislo3`, ".$delkaPrefixu.")='".$prefix."'".
                " OR LENGTH(`KZAM_cislo4`)=5 AND LEFT(`KZAM_cislo4`, ".$delkaPrefixu.")='".$prefix."'".
                " OR LENGTH(`KZAM_cislo5`)=5 AND LEFT(`KZAM_cislo5`, ".$delkaPrefixu.")='".$prefix."'"
                ;
        $vhodneDotazniky = Data_Flat_FlatTable::vypisVse($tabulka, $filtr, "", "", TRUE, self::TABULKA, self::ID, "", "", "", FALSE, $databaze);
        $vhodniZajemci = array();
        foreach ($vhodneDotazniky as $vhodnyDotaznik) {
            $vhodnyZajemce = parent::najdiPodleId($vhodnyDotaznik->id, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);
            if ($vhodnyZajemce) $vhodniZajemci[] = $vhodnyZajemce;      // pro nevalidní zájemce vrací FALSE
        }
        return $vhodniZajemci;
    }    
    
    /**
     * Najde a vrati vsechny Ucastniky prihlasene k Akce
     * @return array() Pole instanci Ucastnik
     */
    public static function vypisPrihlaseneNaAkci($idAkce)
    {
        return parent::najdiPodleId($idAkce, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);
    }    
}
?>
