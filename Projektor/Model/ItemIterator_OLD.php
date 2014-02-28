<?php
/**
 * Description of CollectionItemIterator
 *
 * @author http://www.php.net/manual/en/language.oop5.iterations.php, pes2704
 */
abstract class Projektor_Model_ItemIterator implements IteratorAggregate
{
    private $items = array();
    private $count = 0;

    /**
     * Metoda předepsaná rozhraním IteratorAggregate, vrací objekt Projektor_Model_Iterator, který implementuje rozhraní Iterator. 
     * Metoda je volána při pokusu o volání metod iterátoru a zajišťuje lazy load dat z databáze. Data a databáze se načítají
     * až v okamžiku pokusu o přístup k datům iterátoru.
     * <p>Pokud je k dispozici výsledek databázového dotazu (datový objekt již měl záznam v databázi), metoda načítá data z řádku databázové 
     * tabulky. Metoda přidá pouze dosud neexistující položky v poli položek a naplní je právě načtenými hodnotami z databáze. Hodnoty již 
     * dříve nastavených vlastností tedy v okamžiku kdy skutečně (lazy) dojde k načtení dat z databáze metoda nepřepisuje, zachová je.</p>
     * <p>Pokud není k dispozici výledek databázového dotazu, tedy objekt byl vytvořen prázdný (nový), metoda načte default hodnoty sloupců 
     * databázové tabulky (nevutváří nový řádek, záznam v databázové tabulce) a datovému objektu přidá pouze dosud neexistující položky 
     * v poli položek a naplní je default hodnotami.</p>
     * <p>Pak metoda vytvoří iterátor Projektor_Model_Iterator s vlastnostmi iterátoru naplněnými položkami IteratorAggregate.
     * @return \Projektor_Model_Iterator
     */
    public function getIterator() {
        if (!$this->dataNactena) {
            if ($this->staryObjekt) {
                try {
                    //sestavení dotazu
                    $query = $this->templateSql->getSql();
                    //prepare
                    $prep = $this->dbh->prepare($query);
                    //bind params
                    if ($this->templateSql->controllerParams) {
                        foreach ($this->templateSql->controllerParams as $param => $value) {
                            $prep->bindParam($param, $value);
                        }
                    }
                    //execute
                    $success = $prep->execute();
                    //kontrola a přidání položek IteratorAggregate
                    $numRows = $prep->rowCount();
                    if ($numRows>0) {  // =0 => neexistijí záznam se zadaným id ??destroy Item ??
                        if ($numRows>1) throw new Projektor_Model_Exception("Nelze vytvořit iterátor objektu Item, výsledek dotazu ".$prep->queryString.
                                " má počet řádků: ".$numRows.".");
                        $radek = $prep->fetch(PDO::FETCH_ASSOC);  //radek
                        if($radek) {
                            $this->items = array(); //pro případy, kdy se načtení dat opakuje
                            $this->count = 0;
                            foreach ($radek as $key => $value) {
                                $this->addOrReplace($key, $value);
                            }
                            $this->dataNactena = TRUE;
                        }
                    }
                } catch (Projektor_Model_Exception $e) {
                    echo $e;
                }
            } else {
                $sloupce = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky(static::DATABAZE, static::TABULKA)->sloupce;
                foreach ($sloupce as $sloupec) {
                    $this->addOrReplace($sloupec->controllerName, $sloupec->default);
    //                    if(!array_key_exists($sloupec->nazev, $this->items))
    //                    {
    //                        if (isset($sloupec->default)) $this->addOrReplace($sloupec->nazev, $sloupec->default); //nenastavuje položky iterátoru pro sloupce s default hodnotou NULL
    //                    }
                }
                $this->dataNactena = TRUE;
            }
        }
//        return new Projektor_Model_Iterator($this->items);
        return new ArrayIterator($this->items);  //tohle je asi o 15% rychlejší

    }

    /**
     * Metoda přidá další položku do připravených dat budoucího iterátoru IteratorAggregate, již existující položku (položku se stejným klíčem) přepíše.
     * Třída používá jako iterátor Projektor_Model_Iterator [viz metoda třídy getIterator() ], kde pole vlastností iterátoru je asociativní
     *  a indexy pole vlastností odpovádají názvům vlastností objektu.
     *  Pole položek IteratorAggregate je také asociativní a touto metodou přidávaná položka je přidána s indexem zadaným jako parametr $key a kodnotou parametru $value.
     * @param type $key Index iterovatelné vlastnosti
     * @param type $value Hodnota iterovatelné vlastnosti
     */
    protected function addOrReplace($key, $value) {
        if(!array_key_exists($key, $this->items)) {
            $this->count++;
        }
        $this->items[$key] = $value;
    }
}

?>
