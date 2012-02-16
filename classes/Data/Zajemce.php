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
    
    public $turnusText;
    public $Projekt;
    public $Beh;
    public $Kancelar;
    public $behCislo;
    public $projektKod;
    public $kancelarText;
    
    public $celeJmeno;
    public $vzdelani1;
    public $KZAM_cislo1;
    public $KZAM_cislo2;
    public $KZAM_cislo3;
    public $KZAM_cislo4;
    public $KZAM_cislo5;
    public $zamestnani_pozice1;


    public function __construct($cisloHlavnihoObjektu = 0, $identifikator =0, $idCProjektFK = null, $idSBehProjektuFK = null, $idCKancelarFK = null,
                                $updated = 0, $id = null)
    {
            parent::__construct(__CLASS__, self::TABULKA, self::PREFIX, self::ID, self::KONTEXT_IDENTIFIKATORU, 
                                $cisloHlavnihoObjektu, $identifikator, $idCProjektFK, $idSBehProjektuFK, $idCKancelarFK,
                                $updated, $id);
            //TODO: vyřešit konflikt projekt z behu se liší od projekt
            //TODO: zrušit sloupec id_c_projekt_FK v tabulkce ucastnik a přejmenovat id_c_projekt v tabulce s_beh_projektu na id_c_projekt_FK
//        if ()
            // projekt se vytvori na zaklade id_c_projekt z tabulky s_beh_projektu (sloupec id_c_projekt_FK v tabulce ucastnik se nepouzije)
//            $this->Projekt = Ciselnik_CiselnikB::najdiPodleId("projekt", $this->Beh->idCProjekt);
            $this->Projekt = Data_Ciselnik::najdiPodleId("projekt", $idCProjektFK);            
            $this->Kancelar = Data_Ciselnik::najdiPodleId("kancelar", $idCKancelarFK);
            $this->Beh = Data_Seznam_SBehProjektu::najdiPodleId($idSBehProjektuFK);
            
//            $this->behCislo = $this->Beh->behCislo;
            $this->turnusText = $this->Beh->text;
            $this->kancelarText = $this->Kancelar->text;
            $this->projektKod = $this->Projekt->kod;       
//            $this->Beh = Data_Seznam_SBehProjektu::najdiPodleId($idSBehProjektuFK);
            //pouze pro účely vytvoření dalších vlastností pro zobrazení v seznamu se vytvoří a pak zruší vlastnost smlouva
            $this->celeJmeno = $this->smlouva->prijmeni." ".$this->smlouva->jmeno;
            if ($this->smlouva->titul != '') $this->celeJmeno = $this->celeJmeno.", ".$this->smlouva->titul;
            if ($this->smlouva->titul_za != '') $this->celeJmeno = $this->celeJmeno.", ".$this->smlouva->titul_za;
            $this->zamestnani_pozice1 = substr($this->smlouva->zamestnani_pozice1, 0, 40).((strlen($this->smlouva->zamestnani_pozice1)>41 ? " ..." : ""));
            unset($this->_vlatnostiObjekty['smlouva']);  
    }
    //($id, $jmenoHlavnihoObjektu, $tabulka="", $nazevIdTabulky="", $nazevCislaCbjektu="")
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
        return parent::najdiPodleId($idAkce, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);
    }    
}
?>
