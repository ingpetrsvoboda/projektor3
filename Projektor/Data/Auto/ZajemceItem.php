<?php
/**
 * Description of ZajemceItem
 *
 * @author pes2704
 */
class Projektor_Data_Auto_ZajemceItem extends Projektor_Data_HlavniObjektItem
{
    const DATABAZE = Projektor_App_Config::DATABAZE_PROJEKTOR;
    const TABULKA = "zajemce";
    const NAZEV_ZOBRAZOVANE_VLASTNOSTI = "dbField°id_zajemce";

    /**
     * Mapování - přiřazení vlastnosti hlavního objektu a názvů tříd Item
     * @var array
     */
    protected $_mapovaniVlastnostItem = array(
             'smlouva' => 'Projektor_Data_Auto_ZaFlatTableItem',
             'dotaznik' => 'Projektor_Data_Auto_ZaFlatTableItem',
             'plan' => 'Projektor_Data_Auto_ZaPlanFlatTableItem',
             'ukonceni' => 'Projektor_Data_Auto_ZaUkoncFlatTableItem',
             'zamestnani' => 'Projektor_Data_Auto_ZaZamFlatTableItem',
             'test' => 'Projektor_Data_Auto_ZaTestFlatTableItem',
        );

###START_AUTOCODE
    // Nový kód pro databázi Projektor a tabulku zajemce
    // Kód obsahuje definice všech vlastností odpovídajících názvům sloupců v db tabulce. Názvy vlastností jsou vytvořeny s prefixem dbField°
    // následovaným názvem sloupce db tabulky a jsou deklarovány jako public, to zajistí fungování autokompletace (napovídání) v editoru.
    // Vlastnost odpovídající primárnímu klíči tabulky takto vytvořena není, místo ní je vytvořena vlastnost se jménem id.
    // S touto vlastností aplikace pacuje odlišně, předpokládá se, že primární klíč tabulky je vždy autoincrement.
    // Dále kód obsahuje definici konstruktoru, ve které se všechny proměnné pro automaticky generované vlastnosti zruší - unset.
    // To zajistí, že i pro tyto vlastnosti jsou volány magické metody __set a __get, ale pozor, jen poprvé. Obecně v php platí, že pokud je public
    // proměnná nastavená, vložení hodnoty do takové proměnné již přímo vloží hodnotu (pro viditelné proměnné se nevolají magické metody).

    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec id_zajemce. Vlatnosti sloupce: typ=int, sloupec je primární klíč a je auto_increment
     * je vygenerována public vlastnost se jménem $id
     */
    public $id;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec cislo_zajemce. Vlatnosti sloupce: typ=int
     */
    public $dbField°cislo_zajemce;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec identifikator. Vlatnosti sloupce: typ=int
     */
    public $dbField°identifikator;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec id_c_projekt_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky c_projekt a sloupce id_c_projekt
     */
    public $dbField°id_c_projekt_FK;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec id_s_beh_projektu_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky s_beh_projektu a sloupce id_s_beh_projektu
     */
    public $dbField°id_s_beh_projektu_FK;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec id_c_kancelar_FK. Vlatnosti sloupce: typ=int
     * , sloupec je cizí klíč z tabulky c_kancelar a sloupce id_c_kancelar
     */
    public $dbField°id_c_kancelar_FK;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec updated. Vlatnosti sloupce: typ=tinyint
     */
    public $dbField°updated;
    /**
     * Generovaná vlastnost pro tabulku zajemce a sloupec valid. Vlatnosti sloupce: typ=tinyint, default=1
     */
    public $dbField°valid;

    /**
     * vlastnost, která je podřízeným objektem hlavního objektu a je typu Projektor_Data_Auto_ZaFlatTableItem
     */
    public $smlouva;

    /**
     * vlastnost, která je podřízeným objektem hlavního objektu a je typu Projektor_Data_Auto_ZaFlatTableItem
     */
    public $dotaznik;

    /**
     * vlastnost, která je podřízeným objektem hlavního objektu a je typu Projektor_Data_Auto_ZaPlanFlatTableItem
     */
    public $plan;

    /**
     * vlastnost, která je podřízeným objektem hlavního objektu a je typu Projektor_Data_Auto_ZaUkoncFlatTableItem
     */
    public $ukonceni;

    /**
     * vlastnost, která je podřízeným objektem hlavního objektu a je typu Projektor_Data_Auto_ZaZamFlatTableItem
     */
    public $zamestnani;

    /**
     * vlastnost, která je podřízeným objektem hlavního objektu a je typu Projektor_Data_Auto_ZaTestFlatTableItem
     */
    public $test;

    public function reset()
    {
        unset($this->id);
        unset($this->dbField°cislo_zajemce);
        unset($this->dbField°identifikator);
        unset($this->dbField°id_c_projekt_FK);
        unset($this->dbField°id_s_beh_projektu_FK);
        unset($this->dbField°id_c_kancelar_FK);
        unset($this->dbField°updated);
        unset($this->dbField°valid);
        unset($this->smlouva);
        unset($this->dotaznik);
        unset($this->plan);
        unset($this->ukonceni);
        unset($this->zamestnani);
        unset($this->test);
    }
    /**
     * Metoda vrací vlastnost hlavního objektu smlouva typu Projektor_Data_Auto_ZaFlatTableItem
     * @param Projektor_Data_Auto_ZaFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaFlatTableItem
     */
    public function Smlouva(Projektor_Data_Auto_ZaFlatTableItem &$object=NULL){
        if (isset($this->smlouva))
        {
            $object = $this->smlouva;
        } else {
            $object = new Projektor_Data_Auto_ZaFlatTableItem(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_zajemce
            $object->where("id_zajemce", "=", $this->id);
            $this->smlouva = $object;   //uloží object do vlastnosti
        }
        return $object;
    }

    /**
     * Metoda vrací vlastnost hlavního objektu smlouva typu Projektor_Data_Auto_ZaFlatTableItem
     * @param Projektor_Data_Auto_ZaFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaFlatTableItem
     */
    public function Dotaznik(Projektor_Data_Auto_ZaFlatTableItem &$object=NULL){
        if (isset($this->dotaznik))
        {
            $object = $this->dotaznik;
        } else {
            $object = new Projektor_Data_Auto_ZaFlatTableItem(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_zajemce
            $object->where("id_zajemce", "=", $this->id);
            $this->dotaznik = $object;   //uloží object do vlastnosti
        }
        return $object;
    }

    /**
     * Metoda vrací vlastnost hlavního objektu smlouva typu Projektor_Data_Auto_ZaPlanFlatTableItem
     * @param Projektor_Data_Auto_ZaPlanFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaPlanFlatTableItem
     */
    public function Plan(Projektor_Data_Auto_ZaPlanFlatTableItem &$object=NULL){
        if (isset($this->plan))
        {
            $object = $this->plan;
        } else {
            $object = new Projektor_Data_Auto_ZaPlanFlatTableItem(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_zajemce
            $object->where("id_zajemce", "=", $this->id);
            $this->plan = $object;   //uloží object do vlastnosti
        }
        return $object;
    }

    /**
     * Metoda vrací vlastnost hlavního objektu smlouva typu Projektor_Data_Auto_ZaUkoncFlatTableItem
     * @param Projektor_Data_Auto_ZaUkoncFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaUkoncFlatTableItem
     */
    public function Ukonceni(Projektor_Data_Auto_ZaUkoncFlatTableItem &$object=NULL){
        if (isset($this->ukonceni))
        {
            $object = $this->ukonceni;
        } else {
            $object = new Projektor_Data_Auto_ZaUkoncFlatTableItem(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_zajemce
            $object->where("id_zajemce", "=", $this->id);
            $this->ukonceni = $object;   //uloží object do vlastnosti
        }
        return $object;
    }

    /**
     * Metoda vrací vlastnost hlavního objektu smlouva typu Projektor_Data_Auto_ZaZamFlatTableItem
     * @param Projektor_Data_Auto_ZaZamFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaZamFlatTableItem
     */
    public function Zamestnani(Projektor_Data_Auto_ZaZamFlatTableItem &$object=NULL){
        if (isset($this->zamestnani))
        {
            $object = $this->zamestnani;
        } else {
            $object = new Projektor_Data_Auto_ZaZamFlatTableItem(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_zajemce
            $object->where("id_zajemce", "=", $this->id);
            $this->zamestnani = $object;   //uloží object do vlastnosti
        }
        return $object;
    }

    /**
     * Metoda vrací vlastnost hlavního objektu smlouva typu Projektor_Data_Auto_ZaTestFlatTableItem
     * @param Projektor_Data_Auto_ZaTestFlatTableItem $object
     * @return \Projektor_Data_Auto_ZaTestFlatTableItem
     */
    public function Test(Projektor_Data_Auto_ZaTestFlatTableItem &$object=NULL){
        if (isset($this->test))
        {
            $object = $this->test;
        } else {
            $object = new Projektor_Data_Auto_ZaTestFlatTableItem(); //factory na Item podřízené vlastnosti - očekává název sloupce s FK id_zajemce
            $object->where("id_zajemce", "=", $this->id);
            $this->test = $object;   //uloží object do vlastnosti
        }
        return $object;
    }

    public function __get($nazevVlastnosti) {
        if (array_key_exists(strtolower($nazevVlastnosti), $this->_mapovaniVlastnostItem))
        {
            if (isset($this->$nazevVlastnosti))
            {
                return $this->$nazevVlastnosti;
            } else {
                $factoryFunction = ucfirst(strtolower($nazevVlastnosti));
                return $this->$factoryFunction();
            }
        }
        return parent::__get($nazevVlastnosti);
    }

    public function __set($nazevVlastnosti, $value) {
        if (array_key_exists(strtolower($nazevVlastnosti), $this->_mapovaniVlastnostItem))
        {
            $this->$nazevVlastnosti = $value;
            return $value;
        }
        return parent::__set($nazevVlastnosti);
    }


###END_AUTOCODE



}

?>
