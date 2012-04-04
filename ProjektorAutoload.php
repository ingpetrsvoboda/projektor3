<?php

function ProjektorAutoload($className) {
	
	// patch - zpetna kompatibilita se starym kodem Mapper
	if(strripos($className, MAPPER_SUFFIX)) {
            $className = str_ireplace(MAPPER_SUFFIX, "", $className);
            include(CLASS_PATH . str_replace(SEPARATOR, "/", $className) . "_mapper.php");
            return;
	}
        
        // patch - fpdf - nedodržuje standard pojmenovávání class s podtržítky PEAR
        if(@include(PEAR_PATH . 'fpdf/' . $className . '.php')) {
            return;
        } else {
            $path = str_replace(SEPARATOR, "/", $className) . ".php";
            //patch - PHP Excel se sám nainstaluje adresáře PEAR/PHPExcel/PHPExcel/jednotlivé_soubory.php
            if (@include(PEAR_PATH . "PHPExcel/" . $path)) {
                return;
            } else {
                if(@include(PEAR_PATH . $path)) {
                    return;
                } else {
                    include(CLASS_PATH . $path);
                }
            }
        
        }
  
}
//$aload = spl_autoload_functions();
	if (!defined("MAPPER_SUFFIX")) define("MAPPER_SUFFIX", "Mapper");   //patch  - zpetna kompatibilita se starym kodem Mapper
	if (!defined("SEPARATOR")) define("SEPARATOR", "_");   //oddělovač v názvech tříd
        if (!defined("PATH_SEPARATOR")) define("PATH_SEPARATOR", ";"); //oddělovač jednotlivých cest pro set_iclude_path
	if (!defined("CLASS_PATH")) define("CLASS_PATH", "classes/");
	if (!defined("PEAR_PATH")) define("PEAR_PATH", "C://xampp/php/PEAR/");
        set_include_path(PEAR_PATH);
        
spl_autoload_register('ProjektorAutoload');
//$aload = spl_autoload_functions();
?>