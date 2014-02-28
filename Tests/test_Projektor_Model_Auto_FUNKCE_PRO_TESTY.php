<?php
//define ("CLASS_PATH", "../");
//// zajištění autoload pro Projektor
//require_once '../Projektor/Autoloader.php';
//Projektor_Autoloader::register();


//==== balíček funkcí pro testy ====================================================
function interval() {
    static $lasttime;
    if ($lasttime)
    {
        $t = microtime_float()-$lasttime;
    } else {
        $t = 0;
    }
    $lasttime = microtime_float();
    return 'Interval: '.$t.' sec';
}
    
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function runTestFunction($closure) {
    $html = getFunctionCode($closure);
    echo "Kód: <br><pre class='kod'>".$html."</pre>";
    $closure();
    echo "<p >".interval()."</p>";
}

/**
 * 
 * @param mixed $functionName Jméno (identifikátor) funkce (string) nebo proměnná obsahující Closure
 * @param bool $highlightedHTML Pokud je TRUE, funkce vrací kód ve formátu HTML a se zvýrazněním pomocí PEAR třídy Text_Highlighter. Default TRUE.
 * @return string
 * @author http://stackoverflow.com/questions/7026690/reconstruct-get-code-of-php-function & Petr Svoboda
 */
function getFunctionCode($functionName, $highlightedHTML=TRUE, $complete=FALSE) {
    $func = new ReflectionFunction($functionName);
    $filename = $func->getFileName();
    $start_line = $func->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
    $end_line = $func->getEndLine();
    $length = $end_line - $start_line;

    $source = file($filename);  // vrací pole, každá položka obsahuje jeden řádek souboru
    $code = implode("", array_slice($source, $start_line, $length));
//    $body = htmlspecialchars($body);
    if ($highlightedHTML) {
//        $options = array(
//            'numbers' => HL_NUMBERS_LI,
//            'tabsize' => 8,
//        );
//        $renderer = new Text_Highlighter_Renderer_HTML($options);
        $renderer = new Text_Highlighter_Renderer_HTML();    
        $phpHighlighter = Text_Highlighter::factory('php');  /** 'php' funguje jen na kód začínající <?php a končící ?>   **/
        $phpHighlighter->setRenderer($renderer);

        $code = $phpHighlighter->highlight('<?php'.$code.'?>');  
        if ($complete) {
            // včetně klíčových slov (function) a jména funkce
            $code = str_replace('&lt;?php', '', $code);
            $code = str_replace('?&gt;', '', $code);
        } else {
            $startCode = strpos($code, '{')+1;
            $lengthCode = strrpos($code, '}')-$startCode;
            $code = substr($code, $startCode, $lengthCode);   
            $code = '<div class="hl-main"><pre><span>'.$code.'</span></pre></div>';
        }

    }
    return $code;
}

function vypisCollection(Projektor_Model_Collection $collection) {
    echo "<p>Kolekce ".get_class($collection).":</p>";
    echo("<table border=1>");
    if ($collection)
    {
        $prvniItem = TRUE;
        foreach ($collection as $item) {
            if ($prvniItem) {
                $prvniItem = FALSE;
                echo ("<tr>");
                vypisHlavickuProItem($item);
                $refCollections = dejReferencovaneKolekce($item);
                echo("</tr>");
            }
            echo("<tr>");
            foreach ($item as $polozka)
                if (is_object($polozka))
                    echo("<td>{$polozka->controllerName}</td>");
                else
                    echo("<td>{$polozka}</td>");
            echo("</tr>\n");
        }
    }
    if ($prvniItem) {
        echo("<tr>");
        vypisHlavickuProPrazdnouCollection($collection);
        echo("</tr>\n");        
    }
    echo("</table>\n");
    vypisCollections($refCollections);
}

function vypisCollections($collections=array()) {
    if ($collections AND is_array($collections)) {
        foreach ($collections as $collection) {
            vypisCollection($collection);
        }    
    }
}

function vypisItem(Projektor_Model_Item $item) {
    echo("<table border=1>");
        $prvniVlastnost = TRUE;
        echo ("<tr>");
        foreach ($item as $vlastnost) {
            if ($prvniVlastnost) {
                $prvniVlastnost = FALSE;
                vypisHlavickuProItem($item);
                $refCollections = dejReferencovaneKolekce($item);
                echo("</tr>\n");
                echo ("<tr>");
            }        
            if (is_object($vlastnost)) {
                $nazev = $vlastnost::NAZEV_ZOBRAZOVANE_VLASTNOSTI;
                echo("<td>{$vlastnost->$nazev}</td>");
            } else {
                echo("<td>{$vlastnost}</td>");
            }
        }
        echo("</tr>\n");
        if ($prvniVlastnost) {
            echo ("<tr>");
            vypisHlavickuProPrazdnyItem($item);
            echo("</tr>\n");            
        }
        echo("</table>\n");
        if ($refCollections)
        {
            foreach ($refCollections as $refCollection)
            {
                echo "<div style='color:blue'>";
                echo "<p>Kolekce pro cizí klíč:</p>";
                vypisCollection($refCollection);
                echo "</div>";
            }
        }

    }

function vypisHlavickuProItem(Projektor_Model_Item $item) {
    foreach ($item as $name=>$v) {
        $nazevSloupceDb = $item->dejStrukturuSloupce($name)->controllerName;
        if ($nazevSloupceDb)
        {
            echo ("<th>{$nazevSloupceDb}</th>");
        }
    }
}
    
function vypisHlavickuProPrazdnyItem(Projektor_Model_Item $item)
{
    $itemClassName = get_class($item);
    $sloupce = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce;
    foreach ($sloupce as $sloupec)
    {
        echo ("<th style='color: maroon'>{$sloupec->controllerName}</th>");
    }
}
    
function vypisHlavickuProPrazdnouCollection(Projektor_Model_Collection $collection)
{
    $itemClassName = $collection::NAZEV_TRIDY_ITEM;
    $sloupce = Projektor_Model_Auto_Cache_Struktury::getStrukturuTabulky($itemClassName::DATABAZE, $itemClassName::TABULKA)->sloupce;
    foreach ($sloupce as $sloupec)
    {
        echo ("<th style='color: maroon'>{$sloupec->controllerName}</th>");
    }
}

function dejReferencovaneKolekce(Projektor_Model_Item $item) {
    $refCollections = array();
    foreach ($item as $name=>$v) {
        $refCollection = $item->dejReferencovanouKolekci($name);
        if ($refCollection) $refCollections[] = $refCollection;
    }
    if (count($refCollections)) return $refCollections;
    return FALSE;   
}
?>