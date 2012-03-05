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
        $isco1 = substr($iscoKod, 0, 1);
//        $seznamisco = Data_Seznam_SISCO::vypisVse("LENGTH(`".Data_Seznam_SISCO::KOD."`)=".$delkaKodu." AND LEFT(`".Data_Seznam_SISCO::KOD."`, ".$delkaPrefixu.")='".$prefix."'");
// natvrdo zadané hodnoty v vypisVhodneNaPozici - nevhodné - předělat
        $tabulka = 'za_flat_table';
        //filtr vybírá dozazníky, kde je alespoň jedno KZAM se stejnou hlavní skupinou jako hledané isco - vychází predpoklad alespoň 100
//        $filtr = "LENGTH(`KZAM_cislo1`)=5 AND LEFT(`KZAM_cislo1`, 1)='".$isco1."'".
//                " OR LENGTH(`KZAM_cislo2`)=5 AND LEFT(`KZAM_cislo2`, 1)='".$isco1."'".
//                " OR LENGTH(`KZAM_cislo3`)=5 AND LEFT(`KZAM_cislo3`, 1)='".$isco1."'".
//                " OR LENGTH(`KZAM_cislo4`)=5 AND LEFT(`KZAM_cislo4`, 1)='".$isco1."'".
//                " OR LENGTH(`KZAM_cislo5`)=5 AND LEFT(`KZAM_cislo5`, 1)='".$isco1."'"
//                ;
        //filtr vybírá dozazníky, kde je alespoň jedno KZAM se stejnou hlavní skupinou jako hledané isco - ostatním vyjde hodnota předpoklady nula
        $filtr = "LENGTH(`KZAM_cislo1`)=5".
                " OR LENGTH(`KZAM_cislo2`)=5".
                " OR LENGTH(`KZAM_cislo3`)=5".
                " OR LENGTH(`KZAM_cislo4`)=5".
                " OR LENGTH(`KZAM_cislo5`)=5"
                ;
        $dotazniky = Data_Flat_FlatTable::vypisVse($tabulka, $filtr, "", "", TRUE, self::TABULKA, self::ID, "", "", "", FALSE, $databaze);

        $vhodniZajemci = array();
        foreach ($dotazniky as $dotaznik) {
            $zajemce = parent::najdiPodleId($dotaznik->id, self::HLAVNI_OBJEKT, self::TABULKA, self::ID, self::CISLO_OBJEKTU);// pro nevalidní zájemce vrací FALSE
            if ($zajemce) 
            {
                $zajemce->predpoklad = self::predpoklady($iscoKod, $dotaznik);   //přidá objektu zajemce novou vlastnost predpoklad
                $vhodniZajemci[] = $zajemce; 
            }
        }

         usort($vhodniZajemci, "self::porovnejPredpoklady");


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
    
    /**
     * funkce vypočítá hodnotu předpokladů plynoucích ze zadaného dotazníku pro zadané ISCO
     * @param string $iscoKod
     * @param objekt $dotaznik
     * @return integer  
     */
    private static function predpoklady($iscoKod, $dotaznik)
        {
            $isco1 = substr($iscoKod, 0, 1);
            $filtr = "pozadovane_kzam1 = ".$isco1;
            $kzamKvalikacniPredpoklady = Data_Flat_FlatTable::vypisVse("s_kzam_kvalifikacni_predpoklady", $filtr, "", "", FALSE, "", "", "", "", "", TRUE, $databaze);
            $predpoklad = 0;
            for ($i = 1; $i <= 5; $i++) {
                $vlastnostKzam = "KZAM_cislo".$i;
                $kz1 = substr($dotaznik->$vlastnostKzam, 0, 1);
                $vlastnostPredpoklad = "predpoklad_kz".$kz1;
                $p = $kzamKvalikacniPredpoklady[0]->$vlastnostPredpoklad;
                $predpoklad = $predpoklad + pow($p, 4);
            }
            $predpoklad = intval(pow($predpoklad, 1/4));  //čtrvtá odmocnina ze součtu čtvrtých mocnin kvalifikačních předpokladů 
            return $predpoklad;    
        }    

     /*
      * callback funkce pro usort - funkce pro porovnání dvou zájemců podle předpokladů
      */
     function porovnejPredpoklady($a,$b) {
            if ($a->predpoklad == $b->predpoklad) {
                return 0;
            }
//            return ($a->predpoklad < $b->predpoklad) ? -1 : 1;         // řadí vzestupně 
            return ($a->predpoklad > $b->predpoklad) ? -1 : 1;         // řadí sestupně
    }        
        
}
?>
