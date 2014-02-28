<?php

ob_start();
require_once("Projektor/ProjektorAutoload.php");
require_once 'Twig/Autoloader.php';

Twig_Autoloader::register();
$loader   = new Twig_Loader_Filesystem('templates');
// in real life usage you should set up the cache directory!
$twig     = new Twig_Environment($loader);


if(isset($_COOKIE['lastname'])) {
	$lastname=@$_COOKIE['lastname'];
        if (isset($lastname)) $lastname=trim($lastname);
    }

session_start();
session_regenerate_id();
//v proměnné $_SESSION['originating_uri'] se předává uri odkud došlo k přesměrování sem (na login),
//když není správně nastavená, asi se sem uživatel dostal nějakým zlotřilým způsobem
$originating_uri = $_SESSION['originating_uri'];
if (!$originating_uri) {
    ob_clean();
    echo "Chybny pokus o pristup do login sekce. Kontaktujte administratora.";
    ob_end_flush();
    session_write_close();
    exit();
}
$zpozdeni = microtime(TRUE)-floatval($_SESSION['mic']);



//$originating_uri = @$_REQUEST['originating_uri'];

//if(isset($_GET['uri'])) {
//    $uri = $_GET['uri'];
//}
//else {
//    $uri = @$_REQUEST['originating_uri'];
//    if(!$uri) {                             //$_REQUEST['originating_uri' neexistuje a $uri pak také ne, pokud se sem přišlo ze zobrazené přuhlašovací stránky po stisku přihlásit
//	$uri = "index.php";
//    }
//}
//$loginWarning = @$_GET['login_warning'];
//print_r($_GET);
        $form = new HTML_QuickForm2("login");
//        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array
//                    (
//                        "name" => $lastname,    // po dobu životnosti cookie lastname vyplňuje do formuláře jméno posledního přihlášení
//                    )));
        $fieldsetCredentials = $form->addElement('fieldset')->setLabel('Přihlášení uživatele do systému Projektor');
        $name = $fieldsetCredentials->addElement('text', 'name')
                   ->setLabel('Jméno: ');
        $name->addRule('required', 'Jméno je nutno zadat', null,
                   HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $password = $fieldsetCredentials->addElement('password', 'password')
                           ->setLabel('Heslo: ');
        $password->addRule('required', 'Heslo je nutno zadat', null,
                   HTML_QuickForm2_Rule::ONBLUR_CLIENT_SERVER);
        $form->addElement('submit', 'submit', array('value' => 'Přihlásit'));

        /* Zpracovani */
        $data = array();
        if($form->validate()) {
            $data = $form->getValue();
            //lehká obrana proti robotům - vycházím z předpokladu, že robot bude rychlejší a dávam uživateli minutu času
            if ($zpozdeni>0.5 AND $zpozdeni<60) {
                $userid = Projektor_User_Identity::seIdentityByNamePassword($data["name"],$data["password"]);
                //echo "name:".$name." pass:".$password." userid:".$userid."<br>";
            }
            if($userid){
                setcookie("lastname",$data["name"],time()+3600);
                $cookie = new Framework_Cookie_CryptCookie($userid);
                $cookie->set();
                unset($_SESSION['originating_uri']);
                $_SESSION['logged']= 1;
                session_write_close();
                header("Location: ".$originating_uri);   //jde na uri odkud došlo k přesměrování na login
                exit;
            } else {
                $loginWarning = "Přihlášení se nezdařilo";
                //vyčištění viditelných proměnných
                $name->setValue("");
                $password->setValue("");

//                header("Location: ".$_SERVER['REQUEST_URI']);
//                exit;
            }
        }
    unset($_SESSION['logged']);
    $_SESSION['mic'] = microtime(TRUE);  //TRUE -> float
    session_write_close();
 //vydumpovani  databaze
 //exec("C:\\XAMPP\\mysql\\bin\\mysqldump --user=root --password=spravce projektor2kancelar>D:\\%COMPUTERNAME%_sql.sql");

//verze bez Twig
//$renderer = HTML_QuickForm2_Renderer::factory('default');
//$form->render($renderer);
//// Output javascript libraries, needed for client-side validation
//$html = $renderer->getJavascriptBuilder()->getLibraries(true, true);
//$html .= $renderer;

$renderer = HTML_QuickForm2_Renderer::factory('array');
$form->render($renderer);
$template = $twig->loadTemplate('login.twig');

$content = $template->render($data + array('login_warning' => $loginWarning) + array(
    'js_libraries' => $renderer->getJavascriptBuilder()->getLibraries(true, true),  // http://pear.php.net/manual/en/package.html.html-quickform2.javascript.php
    'form'         => $renderer->toArray()
));
echo $content;
?>

