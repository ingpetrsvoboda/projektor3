<?php
/**
 * Zajišťuje autoloading tříd v package Atw.
 *
 * Vyžaduje dodržení této konvence pojmenovávání tříd v package Atw: Package_[Subfolder_]File
 * a odpovídající umístění třídy ve struktuře složek. Např. třída umístěná v package Atw, podsložce Controller a v souboru User.php
 * musí být pojmenována Atw_Controller_User.
 */
class Projektor_Autoloader
{
    /**
     * Registruje metodu třídy autoload jako SPL autoloader.
     */
    static public function register()
    {
//$aload = spl_autoload_functions();
	if (!defined("SEPARATOR")) define("SEPARATOR", "_");   //oddělovač v názvech tříd
        if (!defined("PATH_SEPARATOR")) define("PATH_SEPARATOR", ";"); //oddělovač jednotlivých cest pro set_iclude_path
	if (!defined("CLASS_PATH")) define("CLASS_PATH", "");  //relativní cesta ke kořenovému adresáři aplikace (Projektor)
	if (!defined("PEAR_PATH")) define("PEAR_PATH", "C://xampp/php/PEAR/");
        set_include_path(PEAR_PATH);

        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));  //registuje Projektor_Autoloader::autoload()
    }


static public function autoload($className) {
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

}
?>