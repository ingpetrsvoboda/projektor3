<?php
/**
 * Description of Collection
 *
 * @author pes2704
 */
abstract class Projektor_Data_Collection extends Projektor_Data_CollectionIterator
{
    /**
     * Vlastnost rozlišuje nově vytvořený prázdný objekt, který nebude načítán z databáze (hodnota je FALSE)
     *  a již dříve uložený, "starý" objekt, který bude z databáze načten
     * @var type
     */
    protected $nactiData;

    /**
     * vlastnost pro uložení objektu Projektor_Data_Sql s připraveným sql dotazem pro načítání dat
     * Je public, je nastavována metodami Collection a uplatní se při načítání dat v CollectionIterator
     * @var type
     */
    protected $preparedSql;

    /**
     * Semafor
     * @var type
     */
    protected $dataNactena;

    /**
     * Konstruktor vytvoří a vrátí novou prázdnou kolekci a součsně připraví načtení položek iterátoru z databáze (lazy load v metodě CollectionIterator getIterator()).
     * Je však možné volat metody kolekce where(), filter(), order() kdykoli až do okamžiku skutečného načtení dat z databáze, tedy do prvního pokusu o čtení
     * některé vlastnosti objektu, data z db jsou načítána lazy load až v tomto okamřiku a filtry a řazení nastavené těmito metodami se uplatní
     * @return \static
     */
    public function __construct()
    {
        $this->nactiData = TRUE;  //semafor pro lazy load z databáze
        // získání struktury db tabulky z údajů třídy item
        $itemClassName = static::NAZEV_TRIDY_ITEM;
        $strukturaTabulky = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA);
        $nazevSloupcePK = $strukturaTabulky->primaryKeyFieldName;
        $this->preparedSql = new Projektor_Data_Sql();
        $this->preparedSql->select = $nazevSloupcePK;
        $this->preparedSql->from = $itemClassName::TABULKA;
        if (isset($strukturaTabulky->sloupce["valid"]))
        {
            $psqlClassName = get_class($this->preparedSql);
            $this->preparedSql->validFilter = $psqlClassName::DEFAULT_VALID_FILTER;
        }
    }

    /**
     * Metoda vytvoří a vrátí novou prázdnou kolekci, která se nebude načítat z databáze
     * Položky do kolekce je možno přidávat metodou CollectionIterator add()
     * @return \static
     */
    public static function factory()
    {
        $class = new static;  //vytvoří nyvý POTOMKOVSKÝ objekt
        $class->nactiData = FALSE;  //semafor pro lazy load z databáze
        return $class;
    }

    /**
     *
     * @return \static
     */
    public function nacti()
    {
        $this->nactiData = TRUE;  //semafor pro lazy load z databáze
        $this->dataNactena = FALSE;  //semafor pro lazy load z databáze
    }

    /**
     * Pokud parametr je TRUE nebo není zadán metoda nastaví filtr tak, že při načítání dat do Collection nebo Item budou načítány i řádky s hodnotou valid=0 (všechny řádky tabulky,
     * bez nastavení filtru jsou načítány jen řádky s hodnotou valid=1 (nesmazané).
     * Pokud parametr je FALSE metoda vrátí nastavení filtru na výchozí a budou načítaány jen řádky s hodnotou valid=1 (nesmazané).
     * @param type $vsechnyRadky
     */
    public function vsechnyRadky($vsechnyRadky = TRUE)
    {
        if ($vsechnyRadky)
        {
            $this->preparedSql->validFilter = "";
        } else {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            if (isset(Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce["valid"]))
            {
                $psqlClassName = get_class($this->preparedSql);
                $this->preparedSql->validFilter = $psqlClassName::DEFAULT_VALID_FILTER;
            }
        }
    }

    /**
     * Metoda nastaví filtr WHERE a vrací řetězec právě vytvořeného filtru.
     * Pokud byl již filtr nastaven, přidá další podmínku s operátorem AND, například: podmínka1 AND podmínka2.
     * Nové nastavení filtru způsobí, že každé další čtení/zápis vlastnosti objektu vyvolá nové načtení dat z databáze s novým filtrem
     * Filtr je vytvořen prostým zřetězením názvu sloupce a podmínky. Použití: ->where("prijmeni", "= 'Novák'") vytvoří filtr "WHERE prijmeni = 'Novák',
     * ->where ("vek", "> 20") vytvoří filtr "WHERE vek > 20". Metoda kontroluje existenci názvu sloupce v db tabulce, pro neexistující sloupec vyhodí výjimku.
     * @param type $nazevVlastnosti Název sloupce db tabulky nebo vlastnosti autocode generované vlastnosti
     * @param type $podminka
     * @return type
     * @throws Projektor_Data_Exception
     */
    public function where($nazevVlastnosti = NULL, $podminka = NULL, $hodnota = NULL, $otevreneZleva=NULL, $otevreneZprava=NULL)
    {
        try
        {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            if (  ($nazevVlastnosti AND is_string($nazevVlastnosti))
                AND ($podminka AND is_string($podminka))
                AND (isset($hodnota))    )
            {
                $nazev = $itemClassName::dejNazevSloupceZVlastnosti($nazevVlastnosti);
                if ($nazev)
                {
                    $this->preparedSql->where($nazev, $podminka, $hodnota, $otevreneZleva, $otevreneZprava);
                    return TRUE;
                } else {
                    throw new Projektor_Data_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
                }
            } else {
                throw new Projektor_Data_Exception("Klausule WHERE nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti
                        ." není řetězec nebo zadaná podmínka: ".$podminka." není řetězec nebo není zadaná hodnota: ".$hodnota.".");
            }
        } catch (Projektor_Data_Exception $e)
        {
            echo $e;
        }
    }

    public function filter($filter = NULL)
    {
        if ($filter) $this->preparedSql->filter = $filter;
    }
//    public function where($nazevVlastnosti = NULL, $list = array())
//    {
//        try
//        {
//            $itemClassName = static::NAZEV_TRIDY_ITEM;
//            if (  ($nazevVlastnosti AND is_string($nazevVlastnosti))
//                AND (isset($list))    )
//            {
//                $strukturaSloupce = $itemClassName::dejStrukturuSloupce($nazevVlastnosti);
//                if ($strukturaSloupce)
//                {
//                    $this->preparedSql->where($nazev, $podminka, $hodnota, $otevreneZleva, $otevreneZprava);
//                    return TRUE;
//                } else {
//                    throw new Projektor_Data_Exception("Klausule WHERE IN nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
//                }
//            } else {
//                throw new Projektor_Data_Exception("Klausule WHERE IN nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti
//                        ." není řetězec nebo není zadaná hodnota: ".$list.".");
//            }
//        } catch (Projektor_Data_Exception $e)
//        {
//            echo $e;
//        }
//
//    }

    public function order($nazevVlastnosti = NULL, $order = 'ASC')
    {
        try
        {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
            if ($nazevVlastnosti AND is_string($nazevVlastnosti) AND $order AND is_string($order) AND ($order=='ASC' OR $order=='DESC'))
            {
                $strukturaSloupce = $itemClassName::dejStrukturuSloupce($nazevVlastnosti);
                if ($strukturaSloupce)
                {
                    $this->preparedSql->order = "~".$strukturaSloupce->nazev." ".$order;
                    $this->preparedSql->params["~".$strukturaSloupce->nazev] = $strukturaSloupce->nazev;
                    return $this->preparedSql->order;
                } else {
                    throw new Projektor_Data_Exception("Klausule ORDER nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." nemá odpovídající sloupec v tabulce: ".$itemClassName::TABULKA."." );
                }
            } else {
                throw new Projektor_Data_Exception("Klausule ORDER nebyla vytvořena, zadaná vlastnost: ".$nazevVlastnosti." není řetězec nebo zadané řazení: ".$order." není řetězec nebo má jinou hodnotu než ASC/DESC." );
            }
        } catch (Projektor_Data_Exception $e)
        {
            echo $e;
        }
    }

############### STATICKÉ PUBLIC METODY (INFORMAČNÍ) #############################################################

    /**
     * Metoda pro vlastnost generovanou autocode (vlastnost s prefixem dbFieldPrefix) vytvoří název sloupce,
     * pro ostatní vlastnosti použije zadaný název beze změny jako název sloupce.
     * Vrací kolekci Collection řádkových objektů Item pro db tabulku referencovanou zadaným sloupcem, pro sloupec který není referencí (není cizí klíč) vrací FALSE
     * Pro neexistující název sloupce nebo pokud se nepodaří nalézt třídu pro vytvoření kolekce odpovídající referencované tabulce metoda vyhodí výjimku
     * @param type $nazevVlastnosti
     * @return boolean|\collectionClassName
     * @throws Projektor_Data_Exception
     */
    public static function dejReferencovanouKolekci($nazevVlastnosti)
    {
        $nazevItem = static::NAZEV_TRIDY_ITEM;
        $item = $nazevItem::factory();
        return $item->dejReferencovanouKolekci($nazevVlastnosti);
    }

    /**
     * Metoda vrací název sloupce db tabulky odpovídající zadané vlastnosti, pokud neexistuje odpovídající sloupec, vrací FALSE.
     * Pro vlastnost generovanou jako id (viz konfigurace aplikace Projektor_App_Config) vrací název primárního klíče.
     * Příklad: prefix="dbField°", tabulka s primárním klíčem "id_akce": pro název "dbField°text" vrací "text", pro název "text" vrací "text", pro název "id" vrací "id_akce"
     * @param type $nazevVlastnosti Název vlastnosti objektu
     * @return string Název sloupce db tabulky
     */
    public static function dejNazevSloupceZVlastnosti($nazevVlastnosti)
    {
            $itemClassName = static::NAZEV_TRIDY_ITEM;
        if ($nazevVlastnosti==$itemClassName::dejObjectIdName())
        {
            return Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->primaryKeyFieldName;
        } else
        {
            $nazevSloupceDb = str_replace($itemClassName::dejDbFieldPrefix(), "", $nazevVlastnosti);
            if (isset(Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce[$nazevSloupceDb])) return $nazevSloupceDb;
            return FALSE;
        }
    }
}
?>
