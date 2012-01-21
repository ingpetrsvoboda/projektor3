<?php
//ind_reg_dot.inc
//Načtení osoby
//echo "<BR> ind_sml_uc.inc";
//echo "<BR>\$_COOKIE: ";
//print_r ($_COOKIE);

$id_ucastnik = false;
if(isset($_GET['id_ucastnik'])) {
    $id_ucastnik = $_GET['id_ucastnik'];
    $pole=array();
}
else if (isset($Ucastnik->id)) {
    $id_ucastnik = $Ucastnik->id;
}


If($id_ucastnik){
     //echo "<BR>*ind_.inc - \$id_ucastnik je " . $id_ucastnik ;  //sss*

    If($Ucastnik = Data_UcastnikMapper::find_by_id($id_ucastnik)) {
        setcookie("id_ucastnik",$Ucastnik->id);


        $Flat_tab = new Data_Flat_UcFlatTable($Ucastnik);         // !! tab. uc_flat_table !ctu z ni jmeno pro zobrazeni!
        $Flat_tab->precti();
        $Flat_tab_data = $Flat_tab->get_values_assoc();
        Foreach($Flat_tab_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;
           // }
           $pole_pro_zobrazeni[$key]=$values;
        }




        $Flat_table = new  Data_Flat_UcTestPCFlatTable($Ucastnik);         // !! tab. uc_testpc_flat_table
        $Flat_table->precti();
        $Flat_table_data = $Flat_table->get_values_assoc();
        Foreach($Flat_table_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                        //polozky v $pole
           // }
           $pole[$key]=$values;
        }
   //     echo "<br>*ucastnik-cislo* " ;  echo($Ucastnik->cislo);
        //echo "<br>*z flat table testpc ucastnika*" .  "<br>";     //sss*

  //     print_r($Flat_table->get_values_assoc());
  //      echo "<BR>**<BR>";
        //print_r($pole);                               //sss*
        
        
        
   /*     
        $Flat_tableOK = new  Data_Flat_UcTestPCOKFlatTable($Ucastnik, $dbh);         // !! tab. uc_testpcOK_flat_table  se spravnymi odpovedmi
        $Flat_tableOK->precti();
        $Flat_table_dataOK = $Flat_tableOK->get_values_assoc();
        Foreach($Flat_table_dataOK as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                        //polozky v $pole
           // }
           $poleOK[$key]=$values;
        }
       echo "<br>*flat table testpcOK se spravnymi odpovedmi*" .  "<br>";  //sss
        print_r($poleOK);        
   */    
        
        
        
       $dbh = App_Kontext::getDbMySQL();
        $query="SELECT * FROM s_test_pc_otazky WHERE cislo_testu = 1 ORDER BY otazka_poradi";    // vybiram otazky 1.testu
        $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
          //echo "<br>" . $query;   //sss*
        $pole_otazek = array();
        while($otazka = $data->fetch_assoc()) {
	 //  echo "<br>"  ;   sss*
         // print_r($otazka);           //sss*
          
          $pole_otazek[] = $otazka;
        }
        //echo "<br><br>"; print_r($pole_otazek);    //sss
?>




        <ul id="menu">
            <li><a href="index.php?akce=zobraz_reg">Zpět na výběr účastníka</a></li>
        </ul>
        <table border="0">
        <tr>
            <td><h2><b>Účastník: </b><?php echo $pole_pro_zobrazeni['titul']." ".$pole_pro_zobrazeni['jmeno']." ".$pole_pro_zobrazeni['prijmeni'] ?></h2></td>
        </tr>
        <tr>
            <td><h3>Číslo účastníka: <?php echo $Ucastnik->identifikator; ?> </td>

        </tr>
        <hr>
        </table>
<?php
    }

}
else {
    //echo "<BR>.inc - \$id_ucastnik je false " . $id_ucastnik;
    setcookie("id_ucastnik");
}
?>
<ul id="menu">
        <hr>
            <li><a href="index.php?akce=unset_beh">Zpět na výběr turnusu</a></li>
        </ul>
</center>

<H3>TEST ZÁKLADNÍCH PC ZNALOSTÍ</H3>

<!-- onsubmit="ZobrazSmlouvu(<?php /*echo $Ucastnik->identifikator;*/?>);" -->

<!-- **** zde lze zobrazovat pole nactena z uc_flat_table , jsou v promenne $pole_pro_zobrazeni***
     **** tato pole se neposilaji POSTem *** -->
<!--
<p>
  Titul: <input ID="titul" disabled type="text" name="titul" size="3" maxlength="10" value="<?php /*echo @$pole_pro_zobrazeni['titul'];*/?>">
  Jméno: <input ID="jmeno" disabled type="text" name="jmeno" size="20" maxlength="50" value="<?php /*echo @$pole_pro_zobrazeni['jmeno'];*/?>">
  Příjmení: <input ID="prijmeni"  disabled type="text" name="prijmeni" size="20" maxlength="50" value="<?php /*echo @$pole_pro_zobrazeni['prijmeni'];*/?>">
</p>
-->
<!-- *************************************** -->

<?php  
    if ( isset($_GET['id_ucastnik']) ) {
    ?>
      <form method="POST" action="index.php?akce=testpc_uc&save=1&id_ucastnik= <?php
    echo $id_ucastnik.'">';
    } else {
    ?>
    <form method="POST" action="index.php?akce=testpc_uc&save=1">
    <?php
    }
    ?>
    
    
<?php
  $ii=1;
  foreach($pole_otazek as $data_otazky )   //jsou podle poradi
  {
    //echo "<br> "; print_r($data_otazky);                               //sss
    $O_cislo= str_pad($data_otazky[otazka_cislo],2,"0",STR_PAD_LEFT)

?>
<FIELDSET><LEGEND><b>Otázka č.<?php echo $ii++ ?></b></LEGEND>
  <?php echo $data_otazky['otazka_text']?><br>
 
  <input ID="testpc_<?php echo $O_cislo?>_1" type="radio" value="1" name="testpc_<?php echo $O_cislo?>" <?php if (@$pole['testpc_'.$O_cislo] == '1') {echo 'checked';} ?>>
         <?php echo  $data_otazky['odpoved_1'] ?><br>

  <input ID="testpc_$<?php echo $O_cislo?>_2" type="radio" value="2" name="testpc_<?php echo $O_cislo?>" <?php if (@$pole['testpc_'.$O_cislo] == '2') {echo 'checked';} ?>>
         <?php echo  $data_otazky['odpoved_2'] ?><br>
         
  <input ID="testpc_<?php echo $O_cislo?>_3" type="radio" value="3" name="testpc_<?php echo $O_cislo?>" <?php if (@$pole['testpc_'.$O_cislo] == '3') {echo 'checked';} ?>>
         <?php echo  $data_otazky['odpoved_3'] ?><br>
         
  <input ID="testpc_<?php echo $O_cislo?>_4" type="radio" value="4" name="testpc_<?php echo $O_cislo?>" <?php if (@$pole['testpc_'.$O_cislo] == '4') {echo 'checked';} ?>>
         <?php echo  $data_otazky['odpoved_4'] ?><br>

  <input ID="testpc_<?php echo $O_cislo?>_nevim" type="radio" 
         name="testpc_<?php echo $O_cislo?>" value="-1" <?php
         if ( !(@$pole['testpc_'.$O_cislo]) or  (@$pole['testpc_'.$O_cislo])=="-1" )  {echo 'checked';}?>> nevím<br>
 
</FIELDSET>

 
<?php    
  }
?>
   <input ID="testpc_datum1" type="hidden" value="<?php
                  if (!($pole[testpc_datum1])) {echo date("Y-m-d H:i:s");} else {echo $pole[testpc_datum1];}?>" name="testpc_datum1">
   <?php
        //if (!($pole[testpc_datum1]))  {echo "*nebylo ulozeno";} ?>                                                 
   
   <br>
   
   <input ID="testpc_datum2" type="hidden" value="<?php echo $pole[testpc_datum2];?>" name="testpc_datum2">
   <br>


<br>
<br>


<p><input type="submit" value="Uložit" name="B1">&nbsp;&nbsp;&nbsp;
<input type="reset" value="Zruš provedené změny" name="B2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>

<?php
    
/*    if ( isset($_GET['id_ucastnik']) ) {
          //    if ($id_ucastnik){
    echo ('<p><input type="submit" value="Tisk" name="T1">&nbsp;&nbsp;&nbsp;</p> ');
     }
*/     
?>


  </form>
  <ul id="menu">
     <li><a href="index.php?akce=zobraz_reg">Zpět na výběr účastníka</a></li>
  </ul>