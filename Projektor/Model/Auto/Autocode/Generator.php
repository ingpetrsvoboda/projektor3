<?php
/**
 * Description of Autocode
 *
 * @author pes2704
 */
class Projektor_Model_Auto_Autocode_Generator
{
    protected static $logger;

    const TEST = "Test třídy generator - generuje kód jen do testovacích souborů";
    const DEVELOPMENT = "Vývojová verze - generuje kód s našeptáváním v IDE";
    const PRODUCTION = "Produkční verze - generuje kratší kód bez našeptávání v IDE";

    /**
     * Název souboru s definicí class typu Item pro testovací běh generátoru autocode
     */
    const NAZEV_TESTOVACIHO_SOUBORU_ITEM = "AutocodeTestItem.php";

    /**
     * Název souboru s definicí class typu Collection pro testovací běh generátoru autocode
     */
    const NAZEV_TESTOVACIHO_SOUBORU_COLLECTION = "AutocodeTestCollection.php";

    /**
     * Metoda generuje a zapíše autocode do všech souborů s definicem tříd Item a Collection zapsaných v souborech s příponou .php 
     * a nacházejících se v složce zadané parametrem $phpDirectoryPath. Za soubory s definicemi Item a Collection jsou považovány 
     * všechny soubory ve složce zadané parametrem $phpDirectoryPath jejichž název končí řetězcem Item nebo Collection
     *
     * @param type $version Parametr určuje chování při generování autokódu.
     * <ul><li>
     * Pokud je parametr zadán s hodnotou konstatnty této třídy TEST, metoda pracuje v testovacím režimu 
     * a generuje autocode pouze do souborů s názvy zadanými v konstantách této třídy NAZEV_TESTOVACIHO_SOUBORU_ITEM a NAZEV_TESTOVACIHO_SOUBORU_COLLECTION.
     * </li><li>
     * Pokud je parametr zadán s hodnotou konstanty této třídy DEVELOPMENT, metoda generuje a zapíše plný
     * autocode včetně autocodu potřebného pro napovídání v IDE pro fázi vytváření kódu (development).
     * </li><li>
     * Pokud je parametr zadán s hodnotou konstatnty této třídy PRODUCTION, metoda generuje a zapíše pouze
     * autocode potřebný pro produkční nasazení a negeneruje autocode potřebný pro napovídání v IDE ve fázi vytváření kódu (development).
     * S parametrem nastaveným na PRODUCTION je vhodné metodu spustit před produkčním nasazením kódu, generuje se kratší kód.
     * </li></ul>
     * @param string $phpDirectoryPath Složka, ve které leží soubory php, do kterých chci generovat autocode. Pokud parametr $phpDirectoryPath 
     * není zadán, pak meoda pracuje se soubory v nadřazené složce (ve složce o jednu úroveň výše) než je složka se souborem, ve kterém 
     * je tato třída.
     * @param string $logDirectoryPath Složka, do které chci zapsat soubory s logy dokumentujícími proces generování autocode. 
     * Pokud parametr není zadán, třída zapisuje logovací soubory do složky Logs umístěné ve složce se soubory php, 
     * do kterých chci generovat autocode (pokus neexistuje, složku Logs vytvoří).
     * @return string|bool
     */
    public static function generuj($version, $phpDirectoryPath=NULL, $logDirectoryPath=NULL)
    {
        if (!$phpDirectoryPath){
            $phpDirectoryPath = substr(__DIR__, 0, strrpos(__DIR__, "\\")+1);  // adresář o úroveň výše
        }
        if (!$logDirectoryPath){
            $logDirectoryPath = $phpDirectoryPath."\\Logs\\";  // složku Logs vytvoří ve složce se soubory php, do kterých chci generovat autocode
            @mkdir($logDirectoryPath);
        }
        $logger = Projektor_Model_Auto_Autocode_Logger::getInstance($logDirectoryPath, "_AutocodeGenerator.log");

        switch ($version) {
            case self::TEST:
                $finalize = 0;
                break;
            case self::DEVELOPMENT:
                $finalize = 0;
                break;
            case self::PRODUCTION:
                $finalize = 1;
                break;
            default:
                $logger->loguj("Metoda ".__METHOD__." třídy ".__CLASS__." byla zavolána  s neznámým parametrem \$version: ".$version);
                return FALSE;
                break;
        }

        $autocodeConfig = Framework_Config::najdiSekciPodleJmena(Framework_Config::SEKCE_AUTOCODE);
        if (!$autocodeConfig->dbfieldprefix) throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Framework_Config::XML_INI." v sekci ".Framework_Config::SEKCE_AUTOCODE." není definován element dbfieldprefix: ".$autocodeConfig->dbfieldprefix);
        if (!$autocodeConfig->objectidname) throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Framework_Config::XML_INI." v sekci ".Framework_Config::SEKCE_AUTOCODE." není definován element objectidname: ".$autocodeConfig->objectidname);
        if (!$autocodeConfig->autocodeStart) throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Framework_Config::XML_INI." v sekci ".Framework_Config::SEKCE_AUTOCODE." není definován element autocodeStart: ".$autocodeConfig->autocodeStart);
        if (!$autocodeConfig->autocodeEnd) throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."V souboru ".Framework_Config::XML_INI." v sekci ".Framework_Config::SEKCE_AUTOCODE." není definován element autocodeEnd: ".$autocodeConfig->autocodeEnd);

        foreach (glob($phpDirectoryPath."*.php") as $phpFullFileName) {
            if (    ($version != self::TEST)
                    OR (($version == self::TEST) AND ($phpFullFileName==$phpDirectoryPath.self::NAZEV_TESTOVACIHO_SOUBORU_ITEM OR $phpFullFileName==$phpDirectoryPath.self::NAZEV_TESTOVACIHO_SOUBORU_COLLECTION))
               ) {
                $logger->loguj("Autocode pro {$phpFullFileName}");
                $koder = Projektor_Model_Auto_Autocode_Koder::getInstance($phpFullFileName, $finalize, $autocodeConfig, $logDirectoryPath);
                $logger->loguj($koder->generujAutocode(), TRUE);
            }
        }
        return $logger->getLog();
    }


}
?>
