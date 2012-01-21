<?php
ob_start();

/*
if(isset($_POST['beh'])) {
	$beh_id = $_POST['beh'];
}
elseif(isset($_COOKIE['beh_id'])){
	$beh_id = $_COOKIE['beh_id'];
}
*/

require_once "save_zpracuj_cookie.inc"; //nastavi  mimo jine Kancelar Projekt Beh


if ( ($User->username == "sys_admin" ))  {
        echo "<BR>a v ind_save_doprk_uc";
        echo "<BR>a Projekt->id:" . $Projekt->id;
        echo "<BR>a Beh->id:" . $Beh->id;
        echo "<hr>";
}



//Zpracování dat z formulare
//echo "<BR>*\Ucastnik*";  print_r ($Ucastnik); /*exit;*/
$Chyby = new App_Chyby;

if (array_key_exists('T1', $_POST)) {
    //jdu z tlacitka tisk ve smlouve , musim odstranit T1 z $pole, popr. _POST
    $tlacitko_tisk = array_pop($_POST);
}
else
{
$tlacitko_tisk = "";
}

$pole = $_POST;

//echo "<BR>POST*"; print_r($_POST) ; 
//echo "<BR>pole*<BR>"; print_r($pole) ; 



/*******************TADY UKLADAME*******************************************/
    //************************Data pro tabulku uc_osobni_udaje
    //Pseudo čiselníky
/*   $Ciselnik = new Ciselnik("c_pohlavi", $dbh);
    if($Ciselnik->check_text(trim($pole['pohlavi']))){
        $pohlavi_id = $Ciselnik->id;
    }
    else {
        $pohlavi_id = 1;
    }
    $Ciselnik = new Ciselnik("c_titul_pred", $dbh);
    if($Ciselnik->check_column(trim($pole['titul']),'titul')){
        $titul_pred_id = $Ciselnik->id;
    }
    else {
        $titul_pred_id = "NULL";
    }
    
    $Osobni_udaje = new Uc_osobni_udaje($Ucastnik,1,$pole['jmeno'],$pole['prijmeni'],$pole['rodne_cislo'],$pole['datum_narozeni'],$pole['datum_reg'],"",$pohlavi_id,$titul_pred_id,null,null,null,$dbh);
    //Overeni udaju
    $Osobni_udaje->check_values();
*/  
   
    //echo "<BR>***Osobni_udaje->chyby->pocet: " . $Osobni_udaje->chyby->pocet    ;
    //exit;
    
    //Nacteni dat do falt tabulky (objektu)
    $flat_table = new Data_Flat_UcDoprkFlatTable($Ucastnik);
    
     /* PP opatreni pro zachovani=neprepsani udaju v tabulce, neni-li sloupecek ve formulari */
       $flat_table->precti();
       
     
    foreach($pole as $klic => $hodnota) {
            $flat_table->$klic = $hodnota;
         //   echo "<BR>" .  $flat_table->$klic;
        }
        
//exit;      
        
    /*************************************************************************************/
        
    //Nejsou-li chyby (v osobních udajích) - uložíme
    //if ( (!$Osobni_udaje->chyby->pocet) and (!(isset($_GET['uzjesml']))  ) )  {
/*    if ( (!$Osobni_udaje->chyby->pocet)  )  {    */
        
        
       /*
          //zruseno ukladani do tabulky uc_osobni_udaje
          //objekt Uc_osobni_udaje se v pgm vytvari,
          //probihaji metody na kontrolu chyb - generuji pripadne vyjimky a hlaseni chyb do formulare
          //pouze se nezapise do tabulky uc_osobni_udaje
     */
    //  echo "<BR>"   ." *id " . $Ucastnik->id;
      
      if  ($User->povolen_zapis) { 
        if(!$Ucastnik->id) {
         Data_UcastnikMapper::insert($Ucastnik);   
          /*  $Osobni_udaje->save_values();  */
        }
        else {
          /*  $Osobni_udaje->update_values();*/
        }
      }  
    //echo "<BR>"   ." *chyby pocet " . $flat_table->chyby->pocet;
  
       
       
        //***********************Ulozeni flat tabulky*********************
       
        if($flat_table->chyby->pocet==0) {
           // if  ($data_users['povolen_zapis']) {
           //   $flat_table->uloz();
           // }
            if  ($User->povolen_zapis) {
                  $flat_table->uloz();            
            }
        }
       
/*    }      */
    /*************************************************************************************/
    
    if( $flat_table->chyby->pocet) {
                                        //*******byly chyby - odstranujte je*******
        
        //Jsou chyby, zobrazíme hlášku
        //Vypsání hlavičky stránky
 //       include "ind_hlavicka.inc";	//	klon
        //Zobrazení loga projektu
//        include"ind_logo_projektu.inc";	//	klon
?>    <h1> Ve formuláři se vyskytly chyby !! </h1>
<br>    <h2> Prosíme, pokuste se je odstranit a uložte formulář znovu </h2>
<br>     
  
<hr>    Chyby v ostatních datech: 
<br>     
<table border="1">       
  <tr>        <td>Položka</td><td>Hodnota</td><td>Důvod</td>       
  </tr>
<?php
    require_once "chyby_text.inc";
    for($i=0;$i<$flat_table->chyby->pocet;$i++){
        echo "<tr>\n";
        echo "<td>".$flat_table->chyby->promnenna[$i]."</td><td>".$flat_table->chyby->hodnota[$i]."</td><td>".$chyby_text[$flat_table->chyby->chyba_cislo[$i]]."</td>\n";
        echo "</tr>\n";
    }
    ?>     
</table>

<?php
          
//      include "ind_ukonc_uc.inc";	//	klon    
    }
    
    else                // nebyly chyby - vytvoř pdf a vygeneruj obsah stránky
                  
    {
        /*   ***pro doporuceni RK neni   pdf
        
        $Flat_tab = new Flat_UcFlatTable($Ucastnik , $dbh);         // !! tab. uc_flat_table !ctu z ni jmeno pro zobrazeni!
        $Flat_tab->precti();
        $Flat_tab_data = $Flat_tab->get_values_assoc();
        Foreach($Flat_tab_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                      
           // }
           $pole_pro_zobrazeni[$key]=$values;   
        }  
        $Flat_tab = new Data_Flat_UcPlanFlatTable($Ucastnik , $dbh);         // !! tab. uc_plan_flat_table !ctu z ni údaje z plánu pro zobrazeni!
        $Flat_tab->precti();
        $Flat_tab_data = $Flat_tab->get_values_assoc();
        Foreach($Flat_tab_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                      
           // }
           $pole_pro_zobrazeni[$key]=$values;   
        }  
                
 
 
        if ($tlacitko_tisk <> "" ) {
            
          //require "./PDF/SPZP_pdfukonceni.php";
           if  ($Projekt->kod == "SPZP" ) {
              require "./PDF/SPZP_pdfukonceni.php"; }
            if  ($Projekt->kod == "RNH" ) {
              require "./PDF/RNH_pdfukonceni.php";  }
              
          echo '<script type ="text/javascript" src="./zdot.js"></script>'."\n";
          echo '<script type ="text/javascript">';
           //<!--
          echo " Zobraz('". $Ucastnik->identifikator . "','ukonc','".$filepathprefix."')" ;               
           //-->
          echo '</script>';
        }
      */       
//        include "ind_hlavicka.inc";	//	klon
//        include "ind_logo_projektu.inc";	//	klon     
//        include "ind_ukonc_uc.inc";	//	klon
        
// **RK vypocte vahy a zapise je-li vyrazen z rekvalifikaci (v doporucovaci t. uc_doporucenirk_flat_table)


$beh_id = $Beh->id;  // tuto promenou ocekava ind_vypocti_vahy.inc ( v ind_zobraz_reg (na tlacitku) je  take k dispozici)
// **RK vypocte vahy a zapise je-li vyrazen z rekvalifikaci (v doporucovaci t. uc_doporucenirk_flat_table)
include "ind_vypocti_vahy.inc";
// **RK           
       
        
       
    }
        
        //header("Location: ./index.php");
        //        header("Refresh: 5; url=./index.php");
    

/*********************************KONEC UKLADANI**************************/

          
?>