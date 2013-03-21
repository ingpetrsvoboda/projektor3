<?php
//echo "<BR>\$_COOKIE: ";
//print_r ($_COOKIE);

//echo "<BR>\$_SERVER: ";
//print_r ($_SERVER);
//$jmeno_skriptu = substr(strrchr($_SERVER[SCRIPT_FILENAME], "/"),1);
//echo "<BR>*jmeno skriptu " . $jmeno_skriptu;

//-----------------------------------------------
//toto odstranit - stary zpusob
//include_once (INC_PATH."ind_plan_uc_kolize.php");  //definice class Varovani a funkci puvodnich



$id_ucastnik = false;
if(isset($_GET['id_ucastnik'])) {
    $id_ucastnik = $_GET['id_ucastnik'];
    $pole=array();
}
else if (isset($Ucastnik->id)) {
    $id_ucastnik = $Ucastnik->id;
}


If($id_ucastnik){

    If($Ucastnik = Ucastnik_mProjektor_Apper::find_by_id($id_ucastnik, $dbh)) {
        setcookie("id_ucastnik",$Ucastnik->id);

        $Flat_tab = new Flat_UcFlatTable($Ucastnik , $dbh);         // !! tab. uc_flat_table !ctu z ni jmeno pro zobrazeni!
        $Flat_tab->read_values();
        $Flat_tab_data = $Flat_tab->get_values_assoc();
        Foreach($Flat_tab_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;
           // }
           $pole_pro_zobrazeni[$key]=$values;
        }


        $Flat_table = new Flat_UcPlanFlatTable($Ucastnik, $dbh);         // !! tab. uc_plan_flat_table
        $Flat_table->read_values();
        $Flat_table_data = $Flat_table->get_values_assoc();
        Foreach($Flat_table_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                        //polozky v $pole
           // }
           $pole[$key]=$values;
        }
   //     echo "<br>*ucastnik-cislo* " ;  echo($Ucastnik->cislo);
   //     echo "<br>*flat table*" .  "<br>";

   //     print_r($Flat_table->get_values_assoc());
  //      echo "<BR>**<BR>";
  //      print_r($pole);
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
    //echo "<BR>.inc - \$id_ucastnik je false " . $id_ucastnik;
    setcookie("id_ucastnik");
}

if ( ($User->username == "sys_admin" ))  {
    echo "<BR>a *id_ucastnik je " . $id_ucastnik . "<BR>";
}



?>
<ul id="menu">
        <hr>
            <li><a href="index.php?akce=unset_beh">Zpět na výběr turnusu</a></li>
        </ul>
</center>

<H3>INDIVIDUÁLNÍ PLÁN KURZŮ</H3>

<!-- **** zde lze zobrazovat pole nactena z uc_flat_table , jsou v promenne $pole_pro_zobrazeni***
     **** tato pole se neposilaji POSTem *** -->
<!--<p>
  Titul: <input ID="titul" disabled type="text" name="titul" size="3" maxlength="10" value="<?php /*echo @$pole_pro_zobrazeni['titul'];*/?>">
  Jméno: <input ID="jmeno" disabled type="text" name="jmeno" size="20" maxlength="50" value="<?php /*echo @$pole_pro_zobrazeni['jmeno'];*/?>">
  Příjmení: <input ID="prijmeni"  disabled type="text" name="prijmeni" size="20" maxlength="50" value="<?php /*echo @$pole_pro_zobrazeni['prijmeni'];*/?>">
</p>-->
<!-- *************************************** -->

<?php
    if ( isset($_GET['id_ucastnik']) ) {
    ?>
      <form method="POST" action="index.php?akce=ind_plan_uc&save=1&id_ucastnik= <?php
    echo $id_ucastnik.'">';
    } else {
    ?>
      <form method="POST" action="index.php?akce=ind_plan_uc&save=1">
    <?php
    }

    $zavolane_kolize_v_plan=array();
    //echo "<br>zavolane_kolize_v_plan NA ZACATKU " ; var_dump($zavolane_kolize_v_plan);
    $Kolize_Lokace="zacatekformulare";
    //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
    //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********

    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//$Beh = Beh_mProjektor_Apper::find_by_id($_COOKIE['beh_id'], $dbh); //sss
/*
echo "<BR>" ; print_r($Ucastnik);
echo "<BR>" ; print_r($Ucastnik->id);
echo "<BR>" ; print_r($Ucastnik->projekt->kod);
echo "<BR>" ; print_r($Ucastnik->beh->beh_cislo);
 */

$dbh = Projektor_App_Container::getDbh(Projektor_App_Config::DATABAZE_PROJEKTOR);

//***priprava pro vsechny selecty, radky nereprezentujici kurz (---.., nezarazen, odmitl ucast)***
$query="SELECT * FROM s_kurz where
                kurz_zkratka='*'
                and valid
                order by razeni";
      // echo $query;
       $data= $dbh->prepare($query)->execute();
$nekurz_pole =array(); $nekurz_id_pole =array();
while($radek = $data->fetch_assoc()) {
    $nekurz_pole[]= $radek[kurz_nazev];
    $nekurz_id_pole[]= $radek[id_s_kurz];
}
$nekurz_pole_comb = array_combine($nekurz_id_pole, $nekurz_pole);
//var_dump($nekurz_pole_comb);
//***priprava pro vsechny selecty, radky nereprezentujici kurz (---.., nezarazen, odmitl ucast) - konec***
?>


<?php
        //$kolize_ukonceni_zztp_interval = fce_kolize_ukonceni_interval ("zztp", $id_ucastnik);  //vraci objekt typu kolize
        //$kolize_ukonceni_zztp_pred = fce_kolize_ukonceni_pred ("zztp", $id_ucastnik);          //vraci objekt typu kolize

$Kolize_Lokace="Motivační program";
?>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace; /*Motivační program*/ ?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********
    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);


//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='ZZTP'
                and valid
                order by razeni";
      // echo $query;
       $data= $dbh->prepare($query)->execute();
?>

 <p> Kurz základních znalostí trhu práce:
<select size="1" name="id_s_kurz_zztp_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_zztp_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {   ?>
<option <?php if (trim(@$pole['id_s_kurz_zztp_FK']) == trim($kurz['id_s_kurz'] ))
                          {echo 'selected';}?> value=<?php echo $kurz['id_s_kurz']; ?>><?php
         echo trim(text_retezec_kurz($kurz)); ?></option>
<?php
}
?>
</select>

<?php
  //if ($kolize_ukonceni_zztp_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_zztp_interval->text . " </span>";
  //if ($kolize_ukonceni_zztp_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_zztp_pred->text . " </span>";
?>
<br>

  Počet absolvovaných hodin: <input ID="zztp_poc_abs_hodin" type="text" name="zztp_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['zztp_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="zztp_duvod_absence" type="text" name="zztp_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['zztp_duvod_absence'];?>">
  </p>
  Dokončeno úspěšně:
  <input ID="zztp_dokoceno_uspech" type="radio" name="zztp_dokonceno" value="Ano" <?php if (@$pole['zztp_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="zztp_dokoceno_neuspech" type="radio" value="Ne" name="zztp_dokonceno" <?php if (@$pole['zztp_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>

  Při neúspěšném ukončení - důvod: <input ID="zztp_duvod_neukonceni" type="text" name="zztp_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['zztp_duvod_neukonceni'];?>">
  <br><br><br>




<?php
        //$kolize_ukonceni_kom_interval = fce_kolize_ukonceni_interval ("kom", $id_ucastnik);  //vraci objekt typu kolize
        //$kolize_ukonceni_kom_pred = fce_kolize_ukonceni_pred ("kom", $id_ucastnik);          //vraci objekt typu kolize
?>
<?php

     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
     //  Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********


//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='KOM'
                and valid
                order by razeni";
       //echo $query;
       $data= $dbh->prepare($query)->execute();
?>



  <p> Komunikační kurz:
<select size="1" name="id_s_kurz_kom_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_kom_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>


<?php
while($kurz = $data->fetch_assoc()) {   ?>
<option <?php if (trim(@$pole['id_s_kurz_kom_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>
<?php
}
?>
</select>
<?php
  //if ($kolize_ukonceni_kom_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_kom_interval->text . " </span>";
  //if ($kolize_ukonceni_kom_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_kom_pred->text . " </span>";
?>
<br>

  Počet absolvovaných hodin: <input ID="kom_poc_abs_hodin" type="text" name="kom_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['kom_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="kom_duvod_absence" type="text" name="kom_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['kom_duvod_absence'];?>">
  </p>
  Dokončeno úspěšně:
  <input ID="kom_dokoceno_uspech" type="radio" name="kom_dokonceno" value="Ano" <?php if (@$pole['kom_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="kom_dokoceno_neuspech" type="radio" value="Ne" name="kom_dokonceno" <?php if (@$pole['kom_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>

  Při neúspěšném ukončení - důvod: <input ID="kom_duvod_neukonceni" type="text" name="kom_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['kom_duvod_neukonceni'];?>">

  <p>Datum vydání osvědčení: <input ID="mot_datum_certif" type="text" name="mot_datum_certif" size="8" maxlength="10" value="<?php echo @$pole['mot_datum_certif'];?>"></p>


</FIELDSET>
 <br>


<?php
        //$kolize_ukonceni_pc_interval = fce_kolize_ukonceni_interval ("pc", $id_ucastnik);  //vraci objekt typu kolize
        //$kolize_ukonceni_pc_pred = fce_kolize_ukonceni_pred ("pc", $id_ucastnik);          //vraci objekt typu kolize
$Kolize_Lokace="PC kurz";
?>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace;?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci=druh pc, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci=druh pc, pomoci definici v tabulce s_typ_kolize - konec *********
    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='PC'
                and valid
                order by razeni";
       //echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>

  <p>PC kurz:
<select size="1" name="id_s_kurz_pc_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_pc_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {   ?>
<option <?php if (trim(@$pole['id_s_kurz_pc_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>
<?php
}
?>
</select>
<?php
  //if ($kolize_ukonceni_pc_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_pc_interval->text . " </span>";
  //if ($kolize_ukonceni_pc_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_pc_pred->text . " </span>";
?>
<br>

  Počet absolvovaných hodin: <input ID="pc_poc_abs_hodin" type="text" name="pc_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['pc_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="pc_duvod_absence" type="text" name="pc_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['pc_duvod_absence'];?>">
  </p>
  Dokončeno úspěšně:
  <input ID="pc_dokoceno_uspech" type="radio" name="pc_dokonceno" value="Ano" <?php if (@$pole['pc_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="pc_dokoceno_neuspech" type="radio" value="Ne" name="pc_dokonceno" <?php if (@$pole['pc_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>

  Při neúspěšném ukončení - důvod: <input ID="pc_duvod_neukonceni" type="text" name="pc_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['pc_duvod_neukonceni'];?>">
  <p>Datum vydání osvědčení: <input ID="pc_datum_certif" type="text" name="pc_datum_certif" size="8" maxlength="10" value="<?php echo @$pole['pc_datum_certif'];?>"></p>
</FIELDSET>
<br>



<?php
        //$kolize_ukonceni_bidi_interval = fce_kolize_ukonceni_interval ("bidi", $id_ucastnik);  //vraci objekt typu kolize
        //$kolize_ukonceni_bidi_pred = fce_kolize_ukonceni_pred ("bidi", $id_ucastnik);          //vraci objekt typu kolize
$Kolize_Lokace="Bilační diagnostika";
?>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace; /*Bilační diagnostika*/?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********
    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='BD'
                and valid
                order by razeni";
       //echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>

  <p> Bilanční diagnostika:
<select size="1" name="id_s_kurz_bidi_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_bidi_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {   ?>
<option <?php if (trim(@$pole['id_s_kurz_bidi_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>
<?php
}
?>
</select>
<?php
  //if ($kolize_ukonceni_bidi_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_bidi_interval->text . " </span>";
  //if ($kolize_ukonceni_bidi_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_bidi_pred->text . " </span>";
?>
<br>

  Počet absolvovaných hodin: <input ID="bidi_poc_abs_hodin" type="text" name="bidi_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['bidi_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="bidi_duvod_absence" type="text" name="bidi_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['bidi_duvod_absence'];?>">
  </p>
  Dokončeno úspěšně:
  <input ID="bidi_dokoceno_uspech" type="radio" name="bidi_dokonceno" value="Ano" <?php if (@$pole['bidi_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="bidi_dokoceno_neuspech" type="radio" value="Ne" name="bidi_dokonceno" <?php if (@$pole['bidi_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>

  Při neúspěšném ukončení - důvod: <input ID="bidi_duvod_neukonceni" type="text" name="bidi_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['bidi_duvod_neukonceni'];?>">
  <p>Datum vydání osvědčení: <input ID="bidi_datum_certif" type="text" name="bidi_datum_certif" size="8" maxlength="10" value="<?php echo @$pole['bidi_datum_certif'];?>"></p>
</FIELDSET>
<br>



<?php
        //$kolize_ukonceni_praxe_interval = fce_kolize_ukonceni_interval ("praxe", $id_ucastnik);  //vraci objekt typu kolize
        //$kolize_ukonceni_praxe_pred = fce_kolize_ukonceni_pred ("praxe", $id_ucastnik);          //vraci objekt typu kolize
$Kolize_Lokace="Pracovní praxe";
?>
<DIV>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace;?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********
    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='PP'
                and valid
                order by razeni";
       //echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>

  <p>Pracovní praxe:
<select size="1" name="id_s_kurz_praxe_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_praxe_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {   ?>
<option <?php if (trim(@$pole['id_s_kurz_praxe_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>
<?php
}
?>
</select>
<?php
  //if ($kolize_ukonceni_praxe_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_praxe_interval->text . " </span>";
  //if ($kolize_ukonceni_praxe_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_praxe_pred->text . " </span>";
?>
<br>
   <p>Datum ukončeni praxe: <input ID="praxe_datum_ukonceni" type="text" name="praxe_datum_ukonceni" size="8" maxlength="10"
                             value="<?php echo @$pole['praxe_datum_ukonceni'];?>"></p>
  </FIELDSET>
<br>


</DIV>



<?php
//!!puvodne - jen pro projekt RNH !!   nyni - pro RNH í SPZP
//nemusi se vytvaret neviditelne vstupni prvky  pro SPZP ,
//protoze uz umime zapsat do flat tabulky jen  promenne z formulare , bez ovlivneni tech sloupcu co ve formulari nejsou(drive se do nich automaticky zapsalo NULL)

//if ($Ucastnik->projekt->kod == "RNH")  {
?>

<?php
    //$kolize_ukonceni_prdi_interval = fce_kolize_ukonceni_interval ("prdi", $id_ucastnik);  //vraci objekt typu kolize
    //$kolize_ukonceni_prdi_pred = fce_kolize_ukonceni_pred ("prdi", $id_ucastnik);          //vraci objekt typu kolize
$Kolize_Lokace="Pracovni diagnostika";
?>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace; ?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********
     //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
     //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='PD'
                and valid
                order by razeni";
       //echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();

?>

  <p>Pracovní diagnostika:
<select size="1" name="id_s_kurz_prdi_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_prdi_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {   ?>
<option <?php if (trim(@$pole['id_s_kurz_prdi_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>
<?php
}
?>
</select>
<?php
  //if ($kolize_ukonceni_prdi_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_prdi_interval->text . " </span>";
  //if ($kolize_ukonceni_prdi_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_prdi_pred->text . " </span>";
?>
<br>
  Počet absolvovaných hodin: <input ID="prdi_poc_abs_hodin" type="text" name="prdi_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['prdi_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="prdi_duvod_absence" type="text" name="prdi_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['prdi_duvod_absence'];?>">
  </p>

  Dokončeno úspěšně:
  <input ID="prdi_dokoceno_uspech" type="radio" name="prdi_dokonceno" value="Ano" <?php if (@$pole['prdi_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="prdi_dokoceno_neuspech" type="radio" value="Ne" name="prdi_dokonceno" <?php if (@$pole['prdi_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>

  Při neúspěšném ukončení - důvod: <input ID="prdi_duvod_neukonceni" type="text" name="prdi_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['prdi_duvod_neukonceni'];?>">
  <p>Datum vydání osvědčení: <input ID="prdi_datum_certif" type="text" name="prdi_datum_certif" size="8" maxlength="10" value="<?php echo @$pole['prdi_datum_certif'];?>"></p>
</FIELDSET>
<br>

<?php
//}
?>




<?php
       // $kolize_ukonceni_prof1_interval = fce_kolize_ukonceni_interval ("prof1", $id_ucastnik);  //vraci objekt typu kolize
       // $kolize_ukonceni_prof1_pred = fce_kolize_ukonceni_pred ("prof1", $id_ucastnik);          //vraci objekt typu kolize
$Kolize_Lokace="Profesní kurz";
?>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace; ?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********
    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    // echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='RK'
                and valid
                order by razeni";
      // echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>

  <p>Profesní kurz:
<select size="1" name="id_s_kurz_prof1_FK">

<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_prof1_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {  ?>
<option <?php if (trim(@$pole['id_s_kurz_prof1_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>

<?php
}
?>
</select>
<?php
  //if ($kolize_ukonceni_prof1_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_prof1_interval->text . " </span>";
  //if ($kolize_ukonceni_prof1_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_prof1_pred->text . " </span>";
?>
<br>

  Počet absolvovaných hodin: <input ID="prof1_poc_abs_hodin" type="text" name="prof1_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['prof1_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="prof1_duvod_absence" type="text" name="prof1_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['prof1_duvod_absence'];?>">
  </p>
  Dokončeno úspěšně:
  <input ID="prof1_dokonceno_uspech" type="radio" name="prof1_dokonceno" value="Ano" <?php if (@$pole['prof1_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="prof1_dokonceno_neuspech" type="radio" value="Ne" name="prof1_dokonceno" <?php if (@$pole['prof1_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>
  Při neúspěšném ukončení - důvod: <input ID="prof1_duvod_ukonceni" type="text" name="prof1_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['prof1_duvod_neukonceni'];?>">
  <p>Datum vydání osvědčení: <input ID="prof1_datum_certif" type="text" name="prof1_datum_certif" size="8" maxlength="10" value="<?php echo @$pole['prof1_datum_certif'];?>"></p>

</FIELDSET>
<br>



<?php
    //    $kolize_ukonceni_prof2_interval = fce_kolize_ukonceni_interval ("prof2", $id_ucastnik);  //vraci objekt typu kolize
    //    $kolize_ukonceni_prof2_pred = fce_kolize_ukonceni_pred ("prof2", $id_ucastnik);          //vraci objekt typu kolize
$Kolize_Lokace="Profesní kurz - další";
?>
<FIELDSET style="width:;"><LEGEND><b><?php echo $Kolize_Lokace; ?></b></LEGEND>
<?php
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize *****************
       Projektor_Table_UcKolizeData::Vypis_kolize_pro_formular_a_lokaci($id_ucastnik,FORMULAR_PLAN,$Kolize_Lokace);
     //************** KOLIZE zjistovani kolizi pro tento formular a lokaci, pomoci definici v tabulce s_typ_kolize - konec *********
    //echo "<br><br>Projektor_Table_UcKolizeData::zjistovane_kolize  ve formulari" ;
    //var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);
    $zavolane_kolize_v_plan= array_merge($zavolane_kolize_v_plan,Projektor_Table_UcKolizeData::$zjistovane_kolize);
    //echo "<br>zavolane_kolize_v_plan ve formulari" ; var_dump($zavolane_kolize_v_plan);

//----------------------------------------------------------------
       $query="SELECT * FROM s_kurz where
                projekt_kod='" . $Ucastnik->projekt->kod . "' and
                beh_cislo='" . $Ucastnik->beh->beh_cislo . "' and
                kurz_druh='RK'
                and valid
                order by razeni";
      // echo $query;
       $data= $dbh->prepare($query)->execute();       //->fetch_assoc();
?>

  <p>Profesní kurz - další:
<select size="1" name="id_s_kurz_prof2_FK">
<?php
foreach ($nekurz_pole_comb as  $nekurz_id=>$nekurz) {  ?>
         <option <?php if (trim(@$pole['id_s_kurz_prof2_FK']) == trim($nekurz_id))
                          {echo 'selected';}?> value=<?php echo $nekurz_id; ?>><?php
         echo trim($nekurz); ?></option>
<?php }   ?>

<?php
while($kurz = $data->fetch_assoc()) {  ?>
<option <?php if (trim(@$pole['id_s_kurz_prof2_FK']) == trim($kurz['id_s_kurz'] ))
                        {echo 'selected';}?> value=<?php  echo $kurz['id_s_kurz']; ?>><?php
        echo trim(text_retezec_kurz($kurz)); ?></option>

<?php
}
?>

</select>
<?php
  //if ($kolize_ukonceni_prof2_interval->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_prof2_interval->text . " </span>";
  //if ($kolize_ukonceni_prof2_pred->hodnota) echo "<span class='kolize'> &nbsp;&nbsp;" . $kolize_ukonceni_prof2_pred->text . " </span>";
?>
<br>

  Počet absolvovaných hodin: <input ID="prof2_poc_abs_hodin" type="text" name="prof2_poc_abs_hodin" size="8" maxlength="10" value="<?php echo @$pole['prof2_poc_abs_hodin'];?>"><br>
  V případě, že neabsolvoval plný počet hodin, uveďte proč: <input ID="prof2_duvod_absence" type="text" name="prof2_duvod_absence" size="120" maxlength="120" value="<?php echo @$pole['prof2_duvod_absence'];?>">
  </p>
  Dokončeno úspěšně:
  <input ID="prof2_dokonceno_uspech" type="radio" name="prof2_dokonceno" value="Ano" <?php if (@$pole['prof2_dokonceno'] == 'Ano') {echo 'checked';} ?>>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokončeno neúspěšně:
  <input ID="prof2_dokonceno_neuspech" type="radio" value="Ne" name="prof2_dokonceno" <?php if (@$pole['prof2_dokonceno'] == 'Ne') {echo 'checked';} ?>><br>
  Při neúspěšném ukončení - důvod: <input ID="prof2_duvod_ukonceni" type="text" name="prof2_duvod_neukonceni" size="120" maxlength="120" value="<?php echo @$pole['prof2_duvod_neukonceni'];?>">
  <p>Datum vydání osvědčení: <input ID="prof2_datum_certif" type="text" name="prof2_datum_certif" size="8" maxlength="10" value="<?php echo @$pole['prof2_datum_certif'];?>"></p>

</FIELDSET>
<br>


<?php
  //************** KOLIZE *******************
  Projektor_Table_UcKolizeData::Vypis_kolize_formulare_dosud_nezavolane($id_ucastnik,FORMULAR_PLAN,$zavolane_kolize_v_plan) ;
  //************** KOLIZE *******************
?>



<p>Datum vytvoření:
<input ID="datum_vytvor_dok" type="text" name="datum_vytvor_dok" size="8" maxlength="10" value="<?php
                                        if (@$pole['datum_vytvor_dok']) {echo @$pole['datum_vytvor_dok'];}
                                        else {echo date("d.m.Y"); }
                                        ?>">
</p>


<p><input type="submit" value="Uložit" name="B1">&nbsp;&nbsp;&nbsp;
<input type="reset" value="Zruš provedené změny" name="B2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<!-- <input type="submit" value="Vytiskni Smlouvu" name="B1"> -->
<?php
    if ( isset($_GET['id_ucastnik']) ) {
//    if ($id_ucastnik){
    echo ('<p><input type="submit" value="Tisk" name="T1">&nbsp;&nbsp;&nbsp;</p> ');
    }
?>


  </form>
  <ul id="menu">
     <li><a href="index.php?akce=zobraz_reg">Zpět na výběr účastníka</a></li>
  </ul>