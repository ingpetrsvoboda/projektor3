<?php
ob_start();


define('INC_PATH','./inc/');

define ("FORMULAR_PLAN", "ind_plan_uc");
define ("FORMULAR_ZAM", "ind_zam_uc");
define ("FORMULAR_UKONC", "ind_ukonc_uc");
define ("FORMULAR_REG_DOT", "ind_reg_dot");

define ("FORMULAR_ZOBRAZ", "ind_zobraz_reg");

require_once("autoload.php");

require_once(INC_PATH."ind_pomocne_funkce.php");

try {
    $cookie = new Auth_Cookie();
    $cookie->validate();
    //session_id($cookie->userid);
    //session_start();
    global $userid;
    $userid =$cookie->get_userid();
    session_id($userid);
    session_start();
}
//catch (AuthException $e) { //klon
catch (Auth_Exception $e) {
	header("Location: ./login.php?originating_uri=".$_SERVER['REQUEST_URI']);
    exit;
}

$dbh = App_Kontext::getDbMySQL();
//zjisteni jmena uzivatele
$query = "SELECT * FROM sys_users                      
            WHERE id_sys_users = :1";
$data_users = $dbh->prepare($query)->execute($userid)->fetch_assoc();  //Data_UserMapper::find_by_id($userid)->username
//print_r($da['username']);   
    
$Kancelar = Data_KancelarMapper::find_by_id($_COOKIE['kancelarId']);
$Projekt = Data_ProjektMapper::find_by_id($_COOKIE['projektId']);
$User = Data_UserMapper::find_by_id($userid);

//print_r($Kancelar);   



//Vypsání hlavičky stránky
include INC_PATH."ind_hlavicka.inc";
//print_r($_POST);    

    //Kontrola oprávnění
// SVOBODA - přístupy do databáze vyhodit do class a asi zjenodušit
    $ready = true;
    $query = "SELECT * FROM sys_acc_usr_kancelar
            WHERE id_sys_users = :1
            AND id_c_kancelar = :2";
    $data = $dbh->prepare($query)->execute($userid,$Kancelar->id)->fetch_assoc();   // SVOBODA vraci jen prvni kancelar, do ktere ma user přístup
    if(!$data) {                    // a vyhodnocuje se jen jestli je alespoň jedna kancelář, do které má user přístup ?? count?
        echo "<H1> V této kanceláři nemáte přístupná žádná data, zkuste se odhlásit a vybrat jinou </H1>\n";
        $ready = false;
    }
    $query = "SELECT * FROM sys_acc_usr_projekt
            WHERE id_sys_users = :1
            AND id_c_projekt = :2";
    $data = $dbh->prepare($query)->execute($userid,$Projekt->id)->fetch_assoc();  // SVOBODA vraci je prvni projekt, do ktere ma user přístup
    if(!$data) {                // a vyhodnocuje se jen jestli je alespoň jeden projekt, do kterého má user přístup ?? count?
        echo "<H1> V tomto projektu nemáte přístupná žádná data, zkuste se odhlásit a vybrat jiný </H1>\n";
        $ready = false;
    }
    //Nastavení kontextu
    setcookie("Context","ucastnik");
    
    if($ready) {
        //Nastavení kontextu
        setcookie("Context","ucastnik");
        //Nacteni akce
        if(isset($_GET['akce'])) {
            $akce = $_GET['akce'];
        }
        else {
            $akce = false;
            if(isset($_POST['akce'])) {
                $akce = $_POST['akce'];
            }
            else {
                $akce = false;
            }
        }
        
        //Zobrazení loga projektu
        include INC_PATH."ind_logo_projektu.inc";	//	klon
        
        //Volba akce
        switch($akce){
		
           /*
	    case "testpc_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) {
			include INC_PATH.'ind_save_testpc_uc.inc';}
		else {
			include INC_PATH."ind_testpc_uc.inc";
			break;
		}
	 */     
		
            case "zobraz_reg":
                include INC_PATH."ind_zobraz_reg.inc";
                break;
           case "zobraz_reg_export":
                include INC_PATH."ind_zobraz_reg.inc";  //v ind_zobraz_reg.inc na konci proběhne export do excelu  
                break;
          
           case "zobraz_reg_vahy":
                include INC_PATH."ind_zobraz_reg.inc";  //v ind_zobraz_reg.inc na konci proběhne vypocet a zapis do db  
                break;  
        
        
            case "zobraz_stat":
                include INC_PATH."ind_zobraz_stat.inc";
                break;
            case "reg_dot":
                setcookie("id_ucastnik");
                if ($_GET['save']) include INC_PATH.'ind_save_form.inc'; 
                include INC_PATH."ind_reg_dot.inc";
                break;
            case "sml_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_form.inc'; 
                include INC_PATH."ind_sml_uc.inc";
                break;
            case "ind_plan_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_plan_uc.inc'; 
                include INC_PATH."ind_plan_uc.inc";
                //include INC_PATH."ind_kolize_kterenejsouveskriptuvolane_uc.inc"; //tady nelze, protoze nejde ulozit sloupecky revidovano
                break;
        
            case "doprk_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_doprk_uc.inc'; 
                include INC_PATH."ind_doprk_uc.inc";
                break;
           case "doprk_dopl1":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_doprk_uc.inc'; 
                include INC_PATH."ind_doprk_uc_dopl1.inc";
                break;
           case "doprk_dopl2":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_doprk_uc.inc'; 
                include INC_PATH."ind_doprk_uc_dopl2.inc";
                break;
           case "doprk_dopl3":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_doprk_uc.inc'; 
                include INC_PATH."ind_doprk_uc_dopl3.inc";
                break;
          case "doprk_vyraz":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_doprk_uc.inc'; 
                include INC_PATH."ind_doprk_uc_vyraz.inc";
                break;
        
        
            case "ukonceni_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_ukonc_uc.inc'; 
                include INC_PATH."ind_ukonc_uc.inc";
                break;
	  case "zam_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) include INC_PATH.'ind_save_zam_uc.inc'; 
                include INC_PATH."ind_zam_uc.inc";
                break;
          
	    case "testpc_uc":
                setcookie("id_ucastnik");   
                if ($_GET['save']) {
			include INC_PATH.'ind_save_testpc_uc.inc';
			include INC_PATH."ind_zobraz_reg.inc";
		}
		else {
			include INC_PATH."ind_testpc_uc.inc";
		}
                break;
						    
            case "uloz_vyplnil_stat":
                include INC_PATH."set_stat_form_fill.inc";
                include INC_PATH."ind_zobraz_stat.inc";
                break;
            case "zobraz_uc":
                setcookie("id_ucastnik",$_GET['id_ucastnik']);
                if ($_GET['save']) include INC_PATH.'ind_save_form'; 
                include INC_PATH."ind_reg_dot.inc";
                break;
            case "unset_beh":
                setcookie("beh_id");
                include INC_PATH."ind_vyber_beh.inc";
                break;
            default:
                if(isset($_COOKIE['beh_id']) && $_COOKIE['beh_id']) {
                    include INC_PATH."ind_zobraz_reg.inc";
                }
                else {
                    include INC_PATH."ind_vyber_beh.inc";
                }
                break;
        }
    }
    else {
?>
        <form name="Logout" ID="Logout" action="./logout.php" method="get">
            <input type="Submit" value="Odhlásit">
        </form>
<?php
    }
    
        
include INC_PATH."ind_paticka.inc";	//	klon
?>

