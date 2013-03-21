<?php

function ProjektorAutoload($className) {
    $path = str_replace(SEPARATOR, "/", $className) . ".php";
    if (is_readable(CLASS_PATH.$path)) {
        require_once CLASS_PATH.$path;
        return TRUE;
    } else {
        if (is_readable(PEAR_PATH.$path)) {
            require_once PEAR_PATH.$path;
            return TRUE;
        } else {
            //patch - PHP Excel se sám nainstaluje do adresáře PEAR/PHPExcel/PHPExcel/jednotlivý_soubor.php, ale názvy tříd jsou PHPExcel_jednotlivý_soubor
            if (is_readable(PEAR_PATH."PHPExcel/".$path)) {
                require_once PEAR_PATH."PHPExcel/".$path;
                return TRUE;
            } else {
                // patch - fpdf - nedodržuje standard pojmenovávání tříd s podtržítky PEAR
                if (is_readable(PEAR_PATH.'fpdf/'.$className.'.php')) {
                    require_once PEAR_PATH.'fpdf/'.$className.'.php';
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }

}
//$aload = spl_autoload_functions();
	if (!defined("SEPARATOR")) define("SEPARATOR", "_");   //oddělovač v názvech tříd
        if (!defined("PATH_SEPARATOR")) define("PATH_SEPARATOR", ";"); //oddělovač jednotlivých cest pro set_iclude_path
	if (!defined("CLASS_PATH")) define("CLASS_PATH", "");  //relativní cesta ke kořenovému adresáři aplikace (Projektor)
	if (!defined("PEAR_PATH")) define("PEAR_PATH", "C://xampp/php/PEAR/");
        set_include_path(PEAR_PATH);

spl_autoload_register('ProjektorAutoload');
//$aload = spl_autoload_functions();
?>