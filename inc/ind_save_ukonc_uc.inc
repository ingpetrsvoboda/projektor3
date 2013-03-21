<?php
ob_start();

require_once "save_zpracuj_cookie.inc";

//Zpracování dat z formulare
//echo "<BR>*\Ucastnik*";  print_r ($Ucastnik); /*exit;*/
$Chyby = new Projektor_App_Chyby;

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
//echo "<BR>pole*"; print_r($pole) ; 



/*******************TADY UKLADAME*******************************************/
     
     $idcka_skolizi_z_formulare =  array();
    
    //Nacteni dat do falt tabulky (objektu)
    $flat_table = new Projektor_Data_Flat_UcUkoncFlatTable($Ucastnik);
    
    $flat_table->precti();  //PP opatreni pro zachovani=neprepsani udaju v tabulce, neni-li sloupecek ve formulari 
    foreach($pole as $klic => $hodnota) {
        
               //tady !nepriradit! ty policka, co do uc_ukonc_flat_table nepatri
            
            if (strpos($klic, '§')!== false) {  //tj. § tam nekde je, je mozne hledat pozici
                if ( substr($klic, 0, strlen("uc_kolize_table") )  == "uc_kolize_table")   //vyhledavam policka  pro uc_kolize_table
                {  
                  //!! echo "<br>" .$klic  . "   pozice p: "  . mb_strpos($klic, 'p') .  " " . substr($klic,  mb_strpos($klic, '§')+1 , mb_strlen($klic) );
                  // !! pozor ! § zaujima dve pozice
                  
                  //sesbirat, ktere s_typ_kolize jsou nyni aktualni - tj. byly vypsane, ve jmenu polozek je id_s_typ_kolize
                  $a1 = explode ("§", $klic );
                     //echo "<br>prvni explode" ; var_dump(  $a1 );
                  $a2 = explode ("_", $a1[1] );
                     //echo "<br>druhe explode" ; var_dump(  $a2  );
                  
                  if (in_array($a2[0], $idcka_skolizi_z_formulare)) {}
                  else {
                    $idcka_skolizi_z_formulare[]= $a2[0];
                  }  
                  
                }
                else {       
                  $flat_table->$klic = $hodnota;
                }
            }
            else {   //pro policka bez tabulkoveho§ prefixu 
                $flat_table->$klic = $hodnota;
            }
        }
 // echo "<hr><br> idcka s_typ_kolize " ;  var_dump($idcka_skolizi_z_formulare);  echo "<hr>";
 //exit;        
       
        
    /*************************************************************************************/
        
 
      if  ($User->povolen_zapis) { 
        if(!$Ucastnik->id) {
         Projektor_Data_UcastnikMProjektor_Apper::insert($Ucastnik);    //DDBBHH  aby se nevytvarelo pokazde nove pripojeni
        }
        else {
        }
      }
      
    // echo "<br> Ucastnik-id"  . $Ucastnik->id;
       
       
     //***********************Ulozeni flat tabulky*********************
       
     if($flat_table->chyby->pocet==0) {
           // if  ($data_users['povolen_zapis']) {
           //   $flat_table->uloz();
           // }
            if  ($User->povolen_zapis) {
                $flat_table->uloz();
                
              //*** KOLIZE - zapis  do tabulky uc_kolize_table
              Projektor_Table_UcKolizeData::Zapis_vsechny_kolize_v_zaveru_formulare($pole, $idcka_skolizi_z_formulare,  $Ucastnik->id, FORMULAR_UKONC); 
              //*** KOLIZE - zapis  do tabulky uc_kolize_table - konec
                
            }
     }
       

    /*************************************************************************************/
    
    if( $flat_table->chyby->pocet) { //*******byly chyby - odstranujte je*******
                                        
        //Jsou chyby, zobrazíme hlášku
        //Vypsání hlavičky stránky //       include "ind_hlavicka.inc";	//	klon
        //Zobrazení loga projektu //        include"ind_logo_projektu.inc";	//	klon
?>
<h1> Ve formuláři se vyskytly chyby !! </h1>
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
        $Flat_tab = new Projektor_Data_Flat_UcFlatTable($Ucastnik);         // !! tab. uc_flat_table !ctu z ni jmeno pro zobrazeni!
        $Flat_tab->precti();
        $Flat_tab_data = $Flat_tab->get_values_assoc();
        Foreach($Flat_tab_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                      
           // }
           $pole_pro_zobrazeni[$key]=$values;   
        }  
        $Flat_tab = new Flat_UcPlanFlatTable($Ucastnik);         // !! tab. uc_plan_flat_table !ctu z ni údaje z plánu pro zobrazeni!
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

        
//        include "ind_hlavicka.inc";	//	klon
//        include "ind_logo_projektu.inc";	//	klon     
//        include "ind_ukonc_uc.inc";	//	klon
     

$beh_id = $Beh->id;  // tuto promenou ocekava ind_vypocti_vahy.inc ( v ind_zobraz_reg (na tlacitku) je  take k dispozici)
// **RK vypocte vahy a zapise je-li vyrazen z rekvalifikaci (v doporucovaci t. uc_doporucenirk_flat_table)
include "ind_vypocti_vahy.inc";
// **RK           
       
       
        
       
    }
        
        //header("Location: ./index.php");
        //        header("Refresh: 5; url=./index.php");
    

/*********************************KONEC UKLADANI**************************/

          
?>