<?php
abstract class Generator
{
    private static $prefixCesty;

    public static function getPrefixCesty()
    {
        return self::$prefixCesty;
    }

    protected static function generuj($trida = Stranka_Index::JMENO, $metoda = Stranka_Index::MAIN, $parametry = null)
    {
        App_Logger::resetLog();
            self::$prefixCesty = $trida;
//        $content = "Nazdar!";
            $cesta = new Router_Cesta($_GET[$trida . "_cesta"], $trida);     //provede parsing GET promenne "cesta" a vytvoří objekt Cesta

//                                echo "Vypis objektu ".get_class($cesta).":<br>";
//                                echo "<xmp>";
//                                print_r($cesta);
//                                echo "</xmp><br>";

            $obsah = $trida::priprav($cesta)->$metoda($parametry);  //podle objektu Cesta rekurzivně vygeneruje obsah (html a příslušné proměnné pro PHPTAL)

//                                echo "Vypis vlastnosti objektu ".get_class($obsah)."->html:<br>";
//				echo "<xmp>";
//				print_r($obsah->html);
// 				echo "</xmp>";
//                                echo "Vypis vlastnosti objektu ".get_class($obsah)."->promenne:<br>";
//				echo "<xmp>";
//				print_r($obsah->promenne);
// 				echo "</xmp><br>";

            $content = "";
            $tpl = new PHPTAL();
            $tpl->setSource($obsah->html);
            if($obsah->promenne) foreach($obsah->promenne as $klic => $hodnota) $tpl->$klic = $hodnota;

            try
            {
                $content .=  $tpl->execute();
            }
            catch (Exception $e)
            {
                    $content .= "<head>
                                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
                                    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/highlight.css\" />
                             </head>";
                    $content .= "<h1>Ekscepsna v templejte...</h1> <pre>{$e}</pre>";
                    $content .= Generator::debuguj($obsah);
                    return $content;
            }

            if(App_Kontext::getDebug()) $content .= Generator::debuguj($obsah);
            return $content;
    }

    protected static function debuguj($obsah)
    {
            $content .= "<h1>Debugovaci vypis</h1>\n";
            $content .= "<h2>Logger:</h2>";
            $content .= "<pre>";
            $content .= App_Logger::getLogText();
            $content .= "</pre>";
            $content .= "<h2>Vygenerovany template</h2>";
            if($obsah->html)
            {
                    $hlHTML = Text_Highlighter::factory("HTML");
                    $content .= $hlHTML->highlight($obsah->html);
            }

            $content .= "<h2>Nastavene promenne</h2>\n";
            if($obsah->promenne)
            {
                    $content .= "<pre>";
                    $content .= print_r($obsah->promenne, TRUE);
                    $content .= "</pre>";
            }
            return $content;
    }

    public static function getContent($trida = Stranka_Index::JMENO, $metoda = Stranka_Index::MAIN, $parametry = null)
    {
        $ProjektorContent = self::generuj($trida, $metoda, $parametry);
        return $ProjektorContent;
    }
}