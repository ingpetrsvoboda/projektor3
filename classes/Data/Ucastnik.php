<?php
/**
 * třída vytváří objekt Ucatnik tak, že vytvoří hlavní (kořenový) objekt Ucastnik, 
 * který má vlastnosti odpovídající sloupcům db tabulky TABULKA (ucastnik) a vlastnosti odpovídající jednotlivým db podřízeným tabulkám,
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

class Data_Ucastnik extends Data_HlavniObjekt
{
    const HLAVNI_OBJEKT = "Ucastnik";
    const TABULKA = "ucastnik"; 
//TODO: přejmenovat sloupec v db na id_ucastnik_FK a změnit konstantu    
    const ID = "id_ucastnik";
    const CISLO_OBJEKTU = "cislo_ucastnika";
    //tento prefix musi mit nazvy vsech db tabulek s vlastnostmi objektu
    const PREFIX = "uc_";
    const KONTEXT_IDENTIFIKATORU = 2;

    //pole obsahujici mapovani (prirazeni) nazvu objektu (vlastnosti Ucastnika) na tabulky v databazi,
    //nazev tabulky se sklada z PREFIXu a nazvu z _tableMap
    protected $_mapovaniObjektTabulka = array(
             'smlouva' => 'flat_table',
             'dotaznik' => 'flat_table',
             'plan' => 'plan_flat_table',
             'doporucenirk' => 'doporucenirk_flat_table',
             'ukonceni' => 'ukonc_flat_table',
             'testpc' => 'testpc_flat_table',
             'zamestnani' => 'zam_flat_table',
             'doplnujici' => 'doplnujici'

        );
    
    public $turnusText;
    public $Projekt;
    public $Beh;
    public $Kancelar;
    public $behCislo;
    public $projektKod;
    public $kancelarText;
    public $celeJmeno;


    public function __construct($cisloHlavnihoObjektu = 0, $identifikator =0, $idCProjektFK = null, $idSBehProjektuFK = null, $idCKancelarFK = null,
                                $updated = 0, $id = null)
    {
            parent::__construct(__CLASS__, self::TABULKA, self::PREFIX, self::ID, self::KONTEXT_IDENTIFIKATORU, 
                                $cisloHlavnihoObjektu, $identifikator, $idCProjektFK, $idSBehProjektuFK, $idCKancelarFK,
                                $updated, $id);

            //poze pro účely vytvoření celého jména včetně titulů se vytvoří a pak zruší vlastnost smlouva
            $this->celeJmeno = $this->smlouva->prijmeni." ".$this->smlouva->jmeno
                        .($this->smlouva->titul ? ", ".$this->smlouva->titul : "")
                        .($this->smlouva->titul_za ? ", ".$this->smlouva->titul_za : "");
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
    public static function vypisPrihlaseneNaAkci($idAkce)
    {
        return parent::vypisPrihlaseneNaAkci($idAkce, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);
    }    
}
?>
