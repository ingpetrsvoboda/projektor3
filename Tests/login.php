<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
const PEAR_PATH = "C:/xampp/php/pear/";

require_once PEAR_PATH.'HTML/QuickForm2.php';
require_once PEAR_PATH.'HTML/QuickForm2/Renderer.php';
require_once '../Twig/Autoloader.php';

Twig_Autoloader::register();

$form = new HTML_QuickForm2('login');

?>
