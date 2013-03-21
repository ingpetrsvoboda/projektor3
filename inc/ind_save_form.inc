<?php
//ind_save_form.php
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
//exit;
//{
/*******************TADY UKLADAME*******************************************/
    //************************Data pro tabulku uc_osobni_udaje
    //Pseudo čiselníky
    $Ciselnik = new Ciselnik("c_pohlavi", $dbh);
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
    
    $Osobni_udaje = new Projektor_Data_Flat_UcOsobniUdaje($Ucastnik,1,$pole['jmeno'],$pole['prijmeni'],$pole['rodne_cislo'],$pole['datum_narozeni'],$pole['datum_reg'],"",$pohlavi_id,$titul_pred_id,null,null,null,$dbh);
    //Overeni udaju
    $Osobni_udaje->check_values();
   
   
    //echo "<BR>***Osobni_udaje->chyby->pocet: " . $Osobni_udaje->chyby->pocet    ;
    //exit;
    
    
    $idcka_skolizi_z_formulare =  array();
    
    //Nacteni dat do falt tabulky (objektu)
    $flat_table = new Projektor_Data_Flat_UcFlatTable($Ucastnik);
    
    $flat_table->precti();   // PP opatreni pro zachovani=neprepsani udaju v tabulce, neni-li sloupecek ve formulari 
    foreach($pole as $klic => $hodnota) {
        
         //tady !nepriradit! ty policka, co do uc_flat_table nepatri
           
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
        
        
    /*************************************************************************************/
        
    //Nejsou-li chyby (v osobních udajích) - uložíme
    //if ( (!$Osobni_udaje->chyby->pocet) and (!(isset($_GET['uzjesml']))  ) )  {
    if ( (!$Osobni_udaje->chyby->pocet)  )  {
        
       // if ($ukladat)  {
        
       /*
          //zruseno ukladani do tabulky uc_osobni_udaje
          //objekt Uc_osobni_udaje se v pgm vytvari,
          //probihaji metody na kontrolu chyb - generuji pripadne vyjimky a hlaseni chyb do formulare
          //pouze se nezapise do tabulky uc_osobni_udaje
     */
       
        if  ($User->povolen_zapis) {
          if(!$Ucastnik->id) {
           Projektor_Data_UcastnikMProjektor_Apper::insert($Ucastnik);    //DDBBHH  aby se nevytvarelo pokazde nove pripojeni
          /*  $Osobni_udaje->save_values();  */
          }
          else {
            /*  $Osobni_udaje->update_values();*/
          }
        }
        
        //***********************Ulozeni flat tabulky*********************
        if($flat_table->chyby->pocet==0) {
            //if  ($data_users['povolen_zapis']) {
            //    $flat_table->uloz();
            //}
            if  ($User->povolen_zapis) {
                $flat_table->uloz();
                
                //*** KOLIZE - zapis  do tabulky uc_kolize_table
                Projektor_Table_UcKolizeData::Zapis_vsechny_kolize_v_zaveru_formulare($pole, $idcka_skolizi_z_formulare,  $Ucastnik->id, FORMULAR_REG_DOT); 
                //*** KOLIZE - zapis  do tabulky uc_kolize_table - konec
                
            }
        }
        //echo "<BR>ULOZENO!"; exit;
        
       // }
       // else
       // {    //neukladam
       // }
    }
    /*************************************************************************************/
    
    if($Osobni_udaje->chyby->pocet || $flat_table->chyby->pocet) {
                                        //*******byly chyby - odstranujte je*******
        
        //Jsou chyby, zobrazíme hlášku
        //Vypsání hlavičky stránky
//        include "ind_hlavicka.inc";   //  klon
        //Zobrazení loga projektu
//        include"ind_logo_projektu.inc";  //  klon
?>    <h1> Ve formuláři se vyskytly chyby !! </h1>
<br>    <h2> Prosíme, pokuste se je odstranit a uložte formulář znovu </h2>
<br>     
<!-- <h2> Následující seznam se Vám pokusí napovědět </h2><br> -->      
<hr>    Chyby v osobních údajích: 
<br>     
<table border="1">       
  <tr>        <td>Položka</td><td>Hodnota</td><td>Důvod</td>       
  </tr>
<?php
    require_once INC_PATH."chyby_text.inc";
    for($i=0;$i<$Osobni_udaje->chyby->pocet;$i++){
        echo "<tr>\n";
        echo "<td>".$Osobni_udaje->chyby->promnenna[$i]."&nbsp;</td><td>".
                  /* $Osobni_udaje->chyby->hodnota[$i].*/ "&nbsp;</td><td>".
                   $chyby_text[$Osobni_udaje->chyby->chyba_cislo[$i]]."&nbsp;</td>\n";
        echo "</tr>\n";
    }
    ?>     
</table>     
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
    //echo "<BR> Pred include************<BR> ";     /*SEL*/
    //print_r($_POST);
    
    //v GET[akce] si nesu informaci z ktereho formulare byl tento skript zavolan
//    if ($_GET['akce'] == 'reg_dot') {		//	klon
//        include "ind_reg_dot.inc";}
//    if ($_GET['akce'] == 'sml_uc') {
//        include "ind_sml_uc.inc";}        
    }
    
    else                // nebyly chyby - vytvoř pdf a vygeneruj obsah stránky
                        // 
                  
    {
        //require_once "./PDF/PDF/pdf.php";
        
        if ($_GET['akce'] == 'reg_dot') {
          
          //echo "<br>save dotanik <br>";
          if ($tlacitko_tisk <> "" ) {
            
            if  ($Projekt->kod == "SPZP" ) {
              require "./PDF/SPZP_pdfdotaznik.php"; }
            if  ($Projekt->kod == "RNH" ) {
              require "./PDF/RNH_pdfdotaznik.php";  }
              
          echo '<script type ="text/javascript" src="./zdot.js"></script>'."\n";
          echo '<script type ="text/javascript">';
           //<!--
            //echo " Zobraz('". $Ucastnik->identifikator . "','reg_dot')" ;
           echo " Zobraz('". $Ucastnik->identifikator . "','reg_dot','".$filepathprefix."')" ;   
           //-->
          echo '</script>';
          }
        //Vypsání hlavičky stránky
//          include "ind_hlavicka.inc";	//	klon
        //Zobrazení loga projektu
//          include"ind_logo_projektu.inc";	//	klon      
//          include "ind_reg_dot.inc";	//	klon
          
        }
        
        if ($_GET['akce'] == 'sml_uc') {
           
          //echo "<br>save smlouva <br>";
          if ($tlacitko_tisk <> "" ) {
            
            if  ($Projekt->kod== "SPZP" ) {
              require "./PDF/SPZP_pdfsmlouva.php"; }
            if  ($Projekt->kod== "RNH" ) {
              require "./PDF/RNH_pdfsmlouva.php";  }
              
              echo '<script type ="text/javascript" src="./zdot.js"></script>'."\n";
              echo '<script type ="text/javascript">';
                //<!--
                  //   echo " Zobraz('". $Ucastnik->identifikator . "','sml_uc')" ;
              echo " Zobraz('". $Ucastnik->identifikator . "','sml_uc','".$filepathprefix."')" ;   //filepathprefix vznikne v pdfsmlouva
                //-->
              echo '</script>';
              
            
            
 /*           echo '<script type ="text/javascript" src="./zdot.js"></script>'."\n";
              echo '<script type ="text/javascript">';
                //<!--
                  //   echo " Zobraz('". $Ucastnik->identifikator . "','sml_uc')" ;
              echo " Zobraz('". $Ucastnik->identifikator . "','sml_uc','".$filepathprefix."')" ;   
                //-->
              echo '</script>';*/
              
          }
          
          //Vypsání hlavičky stránky
//          include "ind_hlavicka.inc";	//	klon
          //Zobrazení loga projektu
//          include"ind_logo_projektu.inc";	//	klon
//          include "ind_sml_uc.inc";	//	klon   
//exit;
           
        }     
      }
        
//header("Location: ./index.php");
//header("Refresh: 5; url=./index.php");
    
    
//print_r($Osobni_udaje);       
        
//print_r($_POST);
//print_r($_COOKIE);
/*********************************KONEC UKLADANI**************************/
//}
          
?>