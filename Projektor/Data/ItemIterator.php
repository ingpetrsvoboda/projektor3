<?php
/**
 * Description of CollectionItemIterator
 *
 * @author http://www.php.net/manual/en/language.oop5.iterations.php, pes2704
 */
abstract class Projektor_Data_ItemIterator implements IteratorAggregate
{
    private $items = array();
    private $count = 0;

    /**
     * string, SQL dotaz s pojmenovanými prametry pro metodu getIterator, která provádí lazy load načtení dat
     * @var type
     */
    protected $preparedSql;

    /**
     * Metoda předepsaná definicí interface IteratorAggregate. Metoda je volána při pokusu o volání metod iterátoru a zajišťuje lazy load dat z databáze.
     * Pokud je k dispozici výledek databázového dotazu, metoda načítá data z řádku databázové tabulky.
     * a přidá pouze dosud neexistující položky v poli položek naplněné právě načtenámi hodnotami z databáze. Hodnoty předtím nastavených vlastností tedy metoda nepřepisuje, zachová je.
     * Pokud není k dispozici výledek databázového dotazu, tedy objekt byl vytvořen prázdný (nový), metoda načte default hodnoty řádku databázové tabulky
     * a přidá pouze dosud neexistující položky v poli položek naplněné default hodnotami.
     * Pak vytvoří iterátor Projektor_Data_Iterator s vlastnostmi iterátoru naplněnými položkami IteratorAggregate.
     * @return \Projektor_Data_Iterator
     */
    public function getIterator() {

// Pro budoucí použití PDO malé info!!!
//When fetching an object, the constructor of the class is called after the fields are populated by default.
//
//PDO::FETCH_PROPS_LATE is used to change the behaviour and make it work as expected - constructor be called _before_ the object fields will be populated with the data.
//
//sample:
//
//$a = $PDO->query('select id from table');
//$a->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'ClassName');
//$obj = $a->fetch();
        if ($this->nactiData)
        {
            if (!$this->dataNactena)
            {
                try
                {
                    $dbh = Projektor_App_Container::getDbh(static::DATABAZE);
                    //sestavení dotazu
                    $query = $this->preparedSql->sql;
                    //prepare
                    $prep = $dbh->prepare($query);
                    //bund params
                    if ($this->preparedSql->params)
                    {
                        foreach ($this->preparedSql->params as $param => $value) {
                            $prep->bindParam($param, $value);
                        }
                    }
                    //execute
                    $result = $prep->execute();
                    //kontrola a přidání položek IteratorAggregate
                    $numRows = $result->affectedRows();
                    if ($numRows>0)
                    {
                        if ($numRows>1) throw new Projektor_Data_Exception("Nelze vytvořit iterátor objektu, výsledek dotazu ".$result->executedQuery.
                                " má počet řádků: ".$numRows.".");
                        $radek = $result->fetch_assoc();  //radek

                        if($radek)
                        {
                            foreach ($radek as $key => $value)
                            {
                                if(!array_key_exists($key, $this->items))
                                {
                                    $this->addOrReplace($key, $value);
                                }
                            }
                            $this->dataNactena = TRUE;
                        }
                    }
                    unset($result);
                } catch (Projektor_Data_Exception $e)
                {
                    echo $e;
                }
            }
        } else {
            if (!$this->dataNactena)
            {
                $sloupce = Projektor_Data_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce;
                foreach ($sloupce as $sloupec)
                {
                    if(!array_key_exists($sloupec->nazev, $this->items))
                    {
                        $this->addOrReplace($sloupec->nazev, $sloupec->default);
                    }
                }
                $this->dataNactena = TRUE;
            }
        }
        return new Projektor_Data_Iterator($this->items);
    }

    /**
     * Metoda přidá další položku IteratorAggregate, již existující položku přepíše
     * Třída používá jako iterátor Projektor_Data_Iterator [viz metoda třídy getIterator() ], kde pole vlastností iterátoru je asociativní
     *  a indexy pole vlastností odpovádají názvům vlastností objektu.
     *  Pole položek IteratorAggregate je také asociativní a touto metodou přidávaná položka je přidána s indexem zadaným jako parametr $key a kodnotou parametru $value.
     * @param type $key Index iterovatelné vlastnosti
     * @param type $value Hodnota iterovatelné vlastnosti
     */
    public function addOrReplace($key, $value) {
        if(!array_key_exists($key, $this->items))
        {
            $this->count++;
        }
        $this->items[$key] = $value;
    }
}

?>
