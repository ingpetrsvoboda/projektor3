<?php
ob_start();

require_once "save_zpracuj_cookie.inc";

//Zpracování dat z formulare
//echo "<BR>*\Ucastnik*";  print_r ($Ucastnik); /*exit;*/
$Chyby = new App_Chyby;

if (array_key_exists('T1', $_POST)) {
    //jdu z tlacitka tisk ve smlouve , musim odstranit T1 z $pole, popr. _POST
    $tlacitko_tisk = array_pop($_POST);
}
else {
    $tlacitko_tisk = "";
}


$pole = $_POST;
if (!($pole[testpc_datum2])) {
   $pole[testpc_datum2] =  date("Y-m-d H:i:s"); 
}



//echo "<BR>POST*"; print_r($_POST) ; 
//echo "<BR>pole*"; print_r($pole) ; 
//exit;
//{
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
    $flat_table = new   Data_Flat_UcTestPCFlatTable($Ucastnik);
    
     /* PP opatreni pro zachovani=neprepsani udaju v tabulce, neni-li sloupecek ve formulari */$flat_table->precti();
    foreach($pole as $klic => $hodnota) {
            $flat_table->$klic = $hodnota;
        }
        
        
    /*************************************************************************************/
        
    //Nejsou-li chyby (v osobních udajích) - uložíme
    //if ( (!$Osobni_udaje->chyby->pocet) and (!(isset($_GET['uzjesml']))  ) )  {
      /*if ( (!$Osobni_udaje->chyby->pocet)  )  {*/
        
       /*
          //zruseno ukladani do tabulky uc_osobni_udaje
          //objekt Uc_osobni_udaje se v pgm vytvari,
          //probihaji metody na kontrolu chyb - generuji pripadne vyjimky a hlaseni chyb do formulare
          //pouze se nezapise do tabulky uc_osobni_udaje
     */
        
        
      if  ($User->povolen_zapis) {  
        if(!$Ucastnik->id) {
         Data_UcastnikMapper::insert($Ucastnik);    //DDBBHH  aby se nevytvarelo pokazde nove pripojeni
          /*  $Osobni_udaje->save_values();  */
        }
        else {
          /*  $Osobni_udaje->update_values();*/
        }
      } 
    
        
   //***********************Ulozeni flat tabulky*********************
        if (($flat_table->chyby->pocet==0)) {
            //if  ($data_users['povolen_zapis']) {
            //     $flat_table->uloz();
            //}
            if  ($User->povolen_zapis) {
                $flat_table->uloz();
            }
            
        }
        //echo "<BR>ULOZENO!"; exit;
       
    /*   }   */
    /*************************************************************************************/
    
    if ($flat_table->chyby->pocet) {
                                        //*******byly chyby - odstranujte je*******
        
        //Jsou chyby, zobrazíme hlášku
        //Vypsání hlavičky stránky
//        include "ind_hlavicka.inc";	//	klon
        //Zobrazení loga projektu
//        include"ind_logo_projektu.inc";	//	klon
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
    require_once "chyby_text.inc";
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
    
//        include "ind_plan_uc.inc";	//	klon       
    }
    
    
    /*
    else                // nebyly chyby - vytvoř pdf a vygeneruj obsah stránky
    //     
    */
        
        //header("Location: ./index.php");
        //header("Refresh: 5; url=./index.php");
        
/*********************************KONEC UKLADANI**************************/
      
?>