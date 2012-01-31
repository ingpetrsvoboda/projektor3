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

if ( ($User->username == "sys_admin" ))  {
    echo "<BR>a *id_ucastnik je " . $id_ucastnik . "*<BR>" ;
}    

If($id_ucastnik){
    //echo "<BR>*ind_.inc - \$id_ucastnik je " . $id_ucastnik . "<BR>" ;
    
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
        /*
        $Flat_tab = new Data_Flat_UcPlanFlatTable($Ucastnik , $dbh);         // !! tab. uc_plan_flat_table !ctu z ni údaje z plánu pro zobrazeni!
        $Flat_tab->precti();
        $Flat_tab_data = $Flat_tab->get_values_assoc();
        Foreach($Flat_tab_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                      
           // }
           $pole_pro_zobrazeni[$key]=$values;   
        }
      */
               
        $Flat_table = new Data_Flat_UcDoprkFlatTable($Ucastnik, $dbh);         // !! tab. uc_doprk_flat_table
        $Flat_table->precti();
        $Flat_table_data = $Flat_table->get_values_assoc();  
        Foreach($Flat_table_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                        //polozky v $pole
           // }
           
           $pole[$key]=$values;   
        }
    //    echo "<br>*ucastnik-cislo* " ;  echo($Ucastnik->cislo); 
    //    print_r($Flat_table->get_values_assoc());
  
       //print_r($pole);
        //Zobrazení navigace
?>
        
        
<?php

        //Zobrazeni stavové informace
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
    //echo "<BR>ind_.inc - \$id_ucastnik je false " . $id_ucastnik;
    setcookie("id_ucastnik");
}
?>
<ul id="menu">
        <hr>
            <li><a href="index.php?akce=unset_beh">Zpět na výběr turnusu</a></li>     
        </ul>
</center>
            
            
            
<H3>DOPORUČENÍ REKVALIFIKAČNÍCH KURZŮ</H3>

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
    <form method="POST" action="index.php?akce=doprk_dopl3&save=1&id_ucastnik= <?php 
    echo $id_ucastnik.'">';        
    } else {
    ?>
    <form method="POST" action="index.php?akce=doprk_dopl3&save=1">
    <?php       
    }
    
    $dbh = App_Kontext::getDbMySQLProjektor();
    ?>
    

<p>
  Datum zahájení účasti v projektu: <?php echo $pole_pro_zobrazeni['datum_reg'] ?>  </p>


<!----------------------------------------------------------------->

<DIV style="  color:white; font:80%; font-style: italic;">
<FIELDSET style="width:;"><LEGEND style="color:white;"><b>I. Rekvalifikační kurz</b></LEGEND>
  <?php
       $query="SELECT c_projekt.kod , s_typ_kurzu_rk.*
               FROM
               s_typ_kurzu_rk    join    c_projekt on (s_typ_kurzu_rk.id_c_projekt_FK = c_projekt.id_c_projekt) 
               WHERE
                (c_projekt.kod='" . $Ucastnik->projekt->kod . "')";
      // echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>  

<p>
  Název typu rekvalifikačního kurzu:         
    <input ID="nazev_RK_1" type="text" name="nazev_RK_1" size="80" maxlength="255"  readonly value="<?php echo @$pole['nazev_RK_1'];?>">            
</p>

<p>
Účastník požaduje:
  <input ID="ucastnik_pozaduje_1" type="text" name="ucastnik_pozaduje_1" size="3" maxlength="255"  readonly value="<?php echo @$pole['ucastnik_pozaduje_1'];?>">     
<br>
    
Účastník požaduje - popis:<br>
 <textarea ID="popis_ucastnik_pozaduje_1" name="popis_ucastnik_pozaduje_1" cols="100" rows=3 readonly><?php echo @$pole['popis_ucastnik_pozaduje_1'];?></textarea>
</p>
 
<p>
Poradce doporučuje:
  <input ID="poradce_doporucuje_1" type="text" name="poradce_doporucuje_1" size="3" maxlength="255"  readonly value="<?php echo @$pole['poradce_doporucuje_1'];?>">     
<br>

Poradce doporučuje - popis:<br>
 <textarea ID="popis_poradce_pozaduje_1" name="popis_poradce_doporucuje_1" cols="100" rows=3 reaadonly><?php echo @$pole['popis_poradce_doporucuje_1'];?></textarea>
</p> 

</FIELDSET><br>
            
<!---------------------------------------------------------------->    
<!----------------------------------------------------------------->    
<FIELDSET style="width:;"><LEGEND style="color:white;"><b>II. Rekvalifikační kurz</b></LEGEND>
  <?php
       $query="SELECT c_projekt.kod , s_typ_kurzu_rk.*
               FROM
               s_typ_kurzu_rk    join    c_projekt on (s_typ_kurzu_rk.id_c_projekt_FK = c_projekt.id_c_projekt) 
               WHERE
                (c_projekt.kod='" . $Ucastnik->projekt->kod . "')";
       //echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>  

<p>
  Název typu rekvalifikačního kurzu: 
  <input ID="nazev_RK_2" type="text" name="nazev_RK_2" size="80" maxlength="255"  readonly value="<?php echo @$pole['nazev_RK_2'];?>">                        
</p>

<p>
Účastník požaduje:
  <input ID="ucastnik_pozaduje_2" type="text" name="ucastnik_pozaduje_2" size="3" maxlength="255"  readonly value="<?php echo @$pole['ucastnik_pozaduje_2'];?>">      
<br>
Účastník požaduje - popis:<br>
 <textarea ID="popis_ucastnik_pozaduje_2" name="popis_ucastnik_pozaduje_2" cols="100" rows=3 readonly><?php echo @$pole['popis_ucastnik_pozaduje_2'];?></textarea>
</p>
 
<p>
Poradce doporučuje:
  <input ID="poradce_doporucuje_2" type="text" name="poradce_doporucuje_2" size="3" maxlength="255"  readonly value="<?php echo @$pole['poradce_doporucuje_2'];?>">     
<br>
Poradce doporučuje - popis:<br>
  <textarea ID="popis_poradce_doporucuje_2" name="popis_poradce_doporucuje_2" cols="100" rows=3 readonly><?php echo @$pole['popis_poradce_doporucuje_2'];?></textarea>
</p> 


</FIELDSET><br>
            
<!---------------------------------------------------------------->
<!----------------------------------------------------------------->    
<FIELDSET ><LEGEND style="color:white;"><b>III. Rekvalifikační kurz</b></LEGEND>
  <?php
       $query="SELECT c_projekt.kod , s_typ_kurzu_rk.*
               FROM
               s_typ_kurzu_rk    join    c_projekt on (s_typ_kurzu_rk.id_c_projekt_FK = c_projekt.id_c_projekt) 
               WHERE
                (c_projekt.kod='" . $Ucastnik->projekt->kod . "')";
       //echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>  

<p>
  Název typu rekvalifikačního kurzu:            
   <input ID="nazev_RK_3" type="text" name="nazev_RK_3" size="80" maxlength="255"  readonly value="<?php echo @$pole['nazev_RK_3'];?>">                                 
</p>

<p>
Účastník požaduje:
  <input ID="ucastnik_pozaduje_3" type="text" name="ucastnik_pozaduje_3" size="3" maxlength="255"  readonly value="<?php echo @$pole['ucastnik_pozaduje_3'];?>">      
<br>
Účastník požaduje - popis:<br>
 <textarea ID="popis_ucastnik_pozaduje_3" name="popis_ucastnik_pozaduje_3" cols="100" rows=3 readonly><?php echo @$pole['popis_ucastnik_pozaduje_3'];?></textarea>
</p>
 
<p>
Poradce doporučuje:
  <input ID="poradce_doporucuje_3" type="text" name="poradce_doporucuje_3" size="3" maxlength="255"  readonly value="<?php echo @$pole['poradce_doporucuje_3'];?>">     
<br>    
Poradce doporučuje - popis:<br>
 <textarea ID="popis_poradce_doporucuje_3" name="popis_poradce_doporucuje_3" cols="100" rows=3 readonly><?php echo @$pole['popis_poradce_doporucuje_3'];?></textarea>
</p> 
 


</FIELDSET><br>
</DIV>            
<!---------------------------------------------------------------->


<?php 
//-- cist doporucene kurzy pro ucastnika 
$dbh = App_Kontext::getDbMySQLProjektor();

   $query=" SELECT * FROM uc_doporucenirk_flat_table
              JOIN s_typ_kurzu_rk ON (uc_doporucenirk_flat_table.id_doporucen_kurz_1 = s_typ_kurzu_rk.id_s_typ_kurzu_rk)
            WHERE ( id_ucastnik =" .   $id_ucastnik    .  ")";
     //echo $query . "<br>";      
   $data = $dbh->prepare($query)->execute();
   while($zaznam = $data->fetch_assoc()) {    //echo "<br>";  print_r ($zaznam); 
      $prvnivporadi = $zaznam['nazev_kurzu']; 
   }
   
   
   $query=" SELECT * FROM uc_doporucenirk_flat_table
              JOIN s_typ_kurzu_rk ON (uc_doporucenirk_flat_table.id_doporucen_kurz_2 = s_typ_kurzu_rk.id_s_typ_kurzu_rk)
            WHERE ( id_ucastnik =" .   $id_ucastnik    .  ")";
     //echo $query . "<br>";      
   $data = $dbh->prepare($query)->execute();
   while($zaznam = $data->fetch_assoc()) {    //echo "<br>";  print_r ($zaznam);
      $druhyvporadi = $zaznam['nazev_kurzu'];
   }
   
   
   $query=" SELECT * FROM uc_doporucenirk_flat_table
              JOIN s_typ_kurzu_rk ON (uc_doporucenirk_flat_table.id_doporucen_kurz_3 = s_typ_kurzu_rk.id_s_typ_kurzu_rk)
            WHERE ( id_ucastnik =" .   $id_ucastnik    . ")";
    //echo $query . "<br>";      
   $data = $dbh->prepare($query)->execute();
   while($zaznam = $data->fetch_assoc()) {    //echo "<br>"; print_r ($zaznam);
      $tretivporadi = $zaznam['nazev_kurzu'];
   }
  
?>

<FIELDSET style="font:140%;" ><LEGEND><b> Účastníkovi byl doporučen kurz</b></LEGEND>
<?php
  echo  $prvnivporadi  ;
?>
<span style="font:70%;">
<br><br>Další kurzy, do kterých může být účastník zařazen v případě, že odmítne nastoupit do doporučeného kurzu nebo nesplní kvalifikační předpoklady pro kurz:<br>
</span>
<?php    echo $druhyvporadi. "<br>" . $tretivporadi ; ?>
</FIELDSET><br>
<!---------------------------------------------------------------->


<?php
//cist zamestnan?
$query=" SELECT * FROM uc_ukonc_flat_table
         WHERE ( id_ucastnik =" .   $id_ucastnik    . ")";
        //echo $query . "<br>";      
   $data = $dbh->prepare($query)->execute();
   
   while($zaznam = $data->fetch_assoc()) {  // echo "<br>"; print_r ($zaznam);
     
      //echo  "<br>*" .  $zaznam['datum_ukonceni']  .  "*"  . substr($zaznam['duvod_ukonceni'],0,2). "<br>";
     
     if  (($zaznam['datum_ukonceni']) and (substr($zaznam['duvod_ukonceni'],0,2)=="2a")) {
     ?>
       <FIELDSET ><LEGEND><b> Účastník byl zaměstnán! </b></LEGEND>    
           Datum ukončení v projektu:   <?php  echo $zaznam['datum_ukonceni'];  ?>
       </FIELDSET > <br>
     
     <?php   
     }  
   }//while
   
?>


<!---------------------------------------------------------------->

<DIV style="  color:white; font:80%; font-style: italic;">    
<p>
Doporučení na doplnění - 1:<br>
 <textarea ID="doporuceni_na_doplneni_1" name="doporuceni_na_doplneni_1" cols="100" rows=3 readonly><?php echo @$pole['doporuceni_na_doplneni_1'];?></textarea>
</p>

 <p>
Doporučení na doplnění - 2:<br>
 <textarea ID="doporuceni_na_doplneni_2" name="doporuceni_na_doplneni_2" cols="100" rows=3 readonly><?php echo @$pole['doporuceni_na_doplneni_2'];?></textarea>
</p>
 <p>
</DIV>

Doporučení na doplnění - 3:<br>
 <textarea ID="doporuceni_na_doplneni_3" name="doporuceni_na_doplneni_3" cols="100" rows=3><?php echo @$pole['doporuceni_na_doplneni_3'];?></textarea>
</p>
 
<DIV  style='display:none'> 
 <p>
Doporučení na doplnění - 4:<br>
 <textarea ID="doporuceni_na_doplneni_4" name="doporuceni_na_doplneni_4" cols="100" rows=3><?php echo @$pole['doporuceni_na_doplneni_4'];?></textarea>
</p>
 <p>
Doporučení na doplnění - 5:<br>
 <textarea ID="doporuceni_na_doplneni_5" name="doporuceni_na_doplneni_5" cols="100" rows=3><?php echo @$pole['doporuceni_na_doplneni_5'];?></textarea>
</p>
 <p>
Doporučení na doplnění - 6:<br>
 <textarea ID="doporuceni_na_doplneni_6" name="doporuceni_na_doplneni_6" cols="100" rows=3><?php echo @$pole['doporuceni_na_doplneni_6'];?></textarea>
</p>
</DIV>
 
 
<FIELDSET ><LEGEND><b> Vyřazení </b></LEGEND>   
Účastník vyřazen z rekvalifikací:
<select size="1" name="ucastnik_vyrazen_z_RK">
   <option <?php if (!(@$pole['ucastnik_vyrazen_z_RK']) or (@$pole['ucastnik_vyrazen_z_RK'])=='ne' ) {echo 'selected';} ?>>ne</option>
   <option <?php if (@$pole['ucastnik_vyrazen_z_RK'] == trim( 'ano')){echo 'selected';} ?>>ano</option>
</select>  

<p> Důvod vyřazení účastníka:
  <input ID="duvod_vyrazeni_ucastnika" type="text" name="duvod_vyrazeni_ucastnika" size="120" maxlength="120" value="<?php echo @$pole['duvod_vyrazeni_ucastnika'];?>">
</p>
</FIELDSET > <br>  
 
 

<!---------------------------------------------------------------->
<!-- protoze uklada do nezmineneho  sloupce  null, nevznikly-li tyto sloupce  z formulare ale doplnenim, ztratily by se <DIV  style='display:none'>-->
<!-- if (!( @$pole['id_s_typ_kurzu_rk_FK_3'])) {echo "0";} else {echo @$pole['id_s_typ_kurzu_rk_FK_3'];} -->

<DIV  style='display:none'>
<input ID="id_s_typ_kurzu_rk_FK_1" type="text" name="id_s_typ_kurzu_rk_FK_1" size="120" maxlength="500"
                            value="<?php echo @$pole['id_s_typ_kurzu_rk_FK_1'];?>">
<input ID="id_s_typ_kurzu_rk_FK_2" type="text" name="id_s_typ_kurzu_rk_FK_2" size="120" maxlength="500"
                            value="<?php echo @$pole['id_s_typ_kurzu_rk_FK_2'];?>">
<input ID="id_s_typ_kurzu_rk_FK_3" type="text" name="id_s_typ_kurzu_rk_FK_3" size="120" maxlength="500"
                            value="<?php echo @$pole['id_s_typ_kurzu_rk_FK_3'];?>">
<?php
  /* echo "* ";
   if (is_null( $pole['id_s_typ_kurzu_rk_FK_3'])) {echo "je to null.";}
   print_r  ($pole['id_s_typ_kurzu_rk_FK_3']); */
?>
                                    
<input ID="id_doporucen_kurz_1" type="text" name="id_doporucen_kurz_1" size="120" maxlength="500"
                            value="<?php echo @$pole['id_doporucen_kurz_1'];?>">
<input ID="id_doporucen_kurz_2" type="text" name="id_doporucen_kurz_2" size="120" maxlength="500"
                            value="<?php echo @$pole['id_doporucen_kurz_2'];?>">
<input ID="id_doporucen_kurz_3" type="text" name="id_doporucen_kurz_3" size="120" maxlength="500"
                            value="<?php echo @$pole['id_doporucen_kurz_3'];?>">

<input ID="vaha_1" type="text" name="vaha_1" size="120" maxlength="500"
                            value="<?php echo @$pole['vaha_1'];?>">
<input ID="vaha_2" type="text" name="vaha_2" size="120" maxlength="500"
                            value="<?php echo @$pole['vaha_2'];?>">
<input ID="vaha_3" type="text" name="vaha_3" size="120" maxlength="500"
                            value="<?php echo @$pole['vaha_3'];?>">

<input ID="korekce_1" type="text" name="korekce_1" size="120" maxlength="500"
                            value="<?php echo @$pole['korekce_1'];?>">
<input ID="korekce_2" type="text" name="korekce_2" size="120" maxlength="500"
                            value="<?php echo @$pole['korekce_2'];?>">
<input ID="korekce_3" type="text" name="korekce_3" size="120" maxlength="500"
                            value="<?php echo @$pole['korekce_3'];?>"> 


</DIV>
                                    
                                    



<!---------------------------------------------------------------->   



<!--
<p>Datum vytvoření:
<input ID="datum_vytvor_dok" type="text" name="datum_vytvor_dok" size="8" maxlength="10" value="<?php /*
                                        if (@$pole['datum_vytvor_dok']) {echo @$pole['datum_vytvor_dok'];}
                                        else {echo date("d.m.Y"); }
               */ ?>">
</p> -->

<p><input type="submit" value="Uložit" name="B1">&nbsp;&nbsp;&nbsp;
<input type="reset" value="Zruš provedené změny" name="B2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>


<?php
  
?>


  </form>
  <ul id="menu">
     <li><a href="index.php?akce=zobraz_reg">Zpět na výběr účastníka</a></li>
  </ul>  