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
    //echo "<BR>*ind_sml_uc.inc - \$id_ucastnik je " . $id_ucastnik ;
    
    If($Ucastnik = Data_UcastnikMapper::find_by_id($id_ucastnik)) {
        setcookie("id_ucastnik",$Ucastnik->id);
        
      /*
        $osobni_udaje = new uc_osobni_udaje($Ucastnik);
        $osobni_udaje->get_values();
        $pole['datum_reg'] = $osobni_udaje->datum_vstupu->f_web;
        $C_titul_pred = new Ciselnik("c_titul_pred");
//  #############  Svoboda 24.10.2009 17:40:10 ####################
        $pole['titul'] = $C_titul_pred->get_value_from_column($osobni_udaje->id_c_titul_pred,$column="titul");
//        $pole['titul'] = $C_titul_pred->get_value_from_column($osobni_udaje->id_c_titul_pred,$column="text");
        $C_pohlavi = new Ciselnik("c_pohlavi");
        $pole['pohlavi'] = $C_pohlavi->get_value_from_column($osobni_udaje->id_c_pohlavi);
        $pole['jmeno'] = $osobni_udaje->jmeno;
        $pole['prijmeni'] = $osobni_udaje->prijmeni;
        $pole['datum_narozeni'] = $osobni_udaje->datum_narozeni->f_web;
        $pole['rodne_cislo'] = $osobni_udaje->rodne_cislo;*/
       
        $Flat_table = new Data_Flat_UcFlatTable($Ucastnik);         // !! tab. uc_flat_table
        $Flat_table->precti();
        $Flat_table_data = $Flat_table->get_values_assoc();
        Foreach($Flat_table_data as $key => $values) {
           // if(!array_key_exists($key,$pole)){
           //     $pole[$key]=$values;                        //polozky v $pole
           // }
           $pole[$key]=$values;   
        }
        //echo "<br>*ucastnik-cislo* " ;  echo($Ucastnik->cislo);
        //echo "<br>*flat table->get_values_assoc*" .  "<br>";
    
       // print_r($Flat_table->get_values_assoc());
        //echo "<BR>**<BR>";
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
            <td><h2><b>Účastník: </b><?php echo $pole['titul']." ".$pole['jmeno']." ".$pole['prijmeni'] ?></h2></td>
        </tr>
        <tr>
            <td><h3>Číslo účastníka: <?php echo $Ucastnik->identifikator; ?> </h3></td>
          
        </tr>
        </table>
<?php        
    }
    
}
else {
    //echo "<BR>ind_reg_dot.inc - \$id_ucastnik je false " . $id_ucastnik;
    setcookie("id_ucastnik");
}
?>
<ul id="menu">
        <hr>
            <li><a href="index.php?akce=unset_beh">Zpět na výběr turnusu</a></li>     
        </ul>
</center>
<H3>ÚČASTNICKÁ SMLOUVA</H3>

<!-- onsubmit="ZobrazSmlouvu(<?php /*echo $Ucastnik->identifikator;*/?>);" -->

<?php
    if ( isset($_GET['id_ucastnik']) ) {
    ?>
      <form method="POST" action="index.php?akce=sml_uc&save=1&id_ucastnik= <?php 
    echo $id_ucastnik.'">';        
    } else {
    ?>
    <form method="POST" action="index.php?akce=sml_uc&save=1">
    <?php       
    }
    ?>
<FIELDSET style="width:;"><LEGEND><b>Osobní údaje</b></LEGEND>
  
  Titul: <input ID="titul" type="text" name="titul" size="3" maxlength="10" value="<?php echo @$pole['titul'];?>">
  Jméno: <input ID="jmeno" type="text" name="jmeno" size="20" maxlength="50" value="<?php echo @$pole['jmeno'];?>">
  Příjmení: <input ID="prijmeni" type="text" name="prijmeni" size="20" maxlength="50" value="<?php echo @$pole['prijmeni'];?>">
  Titul za: <input ID="titul_za" type="text" name="titul_za" size="3" maxlength="10" value="<?php echo @$pole['titul_za'];?>">
  Pohlaví: <select ID="pohlavi" size="1" name="pohlavi">
          <option <?php if (@$pole['pohlavi'] == '--------'){echo 'selected';} ?>>----</option>
          <option <?php if (@$pole['pohlavi'] == 'muž'){echo 'selected';} ?>>muž</option>
          <option <?php if (@$pole['pohlavi'] == 'žena'){echo 'selected';} ?>>žena</option>
          </select></p>
  <p>Datum narození: <input ID="datum_narozeni" type="text" name="datum_narozeni" size="8" maxlength="10" value="<?php echo @$pole['datum_narozeni'];?>">
    Rodné číslo: <input ID="rodne_cislo" type="text" name="rodne_cislo" size="20" maxlength="50" value="<?php echo @$pole['rodne_cislo'];?>"></p>
  <p></p>  
</FIELDSET>  
  
<FIELDSET style="width:;"><LEGEND><b>Údaje účastníka</b></LEGEND>  
Datum vstupu do projektu: <input ID="datum_reg" type="text" name="datum_reg" size="8" maxlength="10" value="<?php echo @$pole['datum_reg'];?>">
  
<p>Vysílající úřad práce: <select ID="z_up" size="1" name="z_up">
          <option <?php if (@$pole['z_up'] == '------------'){echo 'selected';} ?>>------------</option>
          <option <?php if ( (@$pole['z_up'] == 'Plzeň město') or (@$pole['z_up'] == 'Úřad práce v Plzni') ) {echo 'selected';} ?>>Úřad práce v Plzni</option>
          <option <?php if ( (@$pole['z_up'] == 'Plzeň jih') or (@$pole['z_up'] == 'Úřad práce Plzeň-jih') ){echo 'selected';} ?>>Úřad práce Plzeň-jih</option>
          <option <?php if ( (@$pole['z_up'] == 'Plzeň sever') or (@$pole['z_up'] == 'Úřad práce Plzeň-sever') ){echo 'selected';} ?>>Úřad práce Plzeň-sever</option>
          <option <?php if ( (@$pole['z_up'] == 'Domažlice') or (@$pole['z_up'] == 'Úřad práce v Domažlicích') ){echo 'selected';} ?>>Úřad práce v Domažlicích</option>
          <option <?php if ( (@$pole['z_up'] == 'Tachov') or (@$pole['z_up'] == 'Úřad práce v Tachově') ){echo 'selected';} ?>>Úřad práce v Tachově</option>
          <option <?php if ( (@$pole['z_up'] == 'Rokycany') or (@$pole['z_up'] == 'Úřad práce v Rokycanech')){echo 'selected';} ?>>Úřad práce v Rokycanech</option>
          <option <?php if ( (@$pole['z_up'] == 'Klatovy') or (@$pole['z_up'] == 'Úřad práce v Klatovech') ){echo 'selected';} ?>>Úřad práce v Klatovech</option>
          </select>
          Pracoviště úřadu práce: <select ID="prac_up" size="1" name="prac_up">
          <option <?php if (@$pole['prac_up'] == '------------'){echo 'selected';} ?>>------------</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň město - pracoviště Plzeň'){echo 'selected';} ?>>Plzeň město - pracoviště Plzeň</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň jih - pracoviště Plzeň'){echo 'selected';} ?>>Plzeň jih - pracoviště Plzeň</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň jih - pracoviště Nepomuk'){echo 'selected';} ?>>Plzeň jih - pracoviště Nepomuk</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň jih - pracoviště Přeštice'){echo 'selected';} ?>>Plzeň jih - pracoviště Přeštice</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň sever - pracoviště Plzeň'){echo 'selected';} ?>>Plzeň sever - pracoviště Plzeň</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň sever - pracoviště Nýřany'){echo 'selected';} ?>>Plzeň sever - pracoviště Nýřany</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň sever - pracoviště Kralovice'){echo 'selected';} ?>>Plzeň sever - pracoviště Kralovice</option>
          <option <?php if (@$pole['prac_up'] == 'Plzeň sever - pracoviště Manětín'){echo 'selected';} ?>>Plzeň sever - pracoviště Manětín</option>
          <option <?php if (@$pole['prac_up'] == 'Domažlice - pracoviště Domažlice'){echo 'selected';} ?>>Domažlice - pracoviště Domažlice</option>
          <option <?php if (@$pole['prac_up'] == 'Tachov - pracoviště Tachov'){echo 'selected';} ?>>Tachov - pracoviště Tachov</option>
          <option <?php if (@$pole['prac_up'] == 'Tachov - pracoviště Stříbro'){echo 'selected';} ?>>Tachov - pracoviště Stříbro</option>
          <option <?php if (@$pole['prac_up'] == 'Rokycany - pracoviště Rokycany'){echo 'selected';} ?>>Rokycany - pracoviště Rokycany</option>
          <option <?php if (@$pole['prac_up'] == 'Klatovy - pracoviště Klatovy'){echo 'selected';} ?>>Klatovy - pracoviště Klatovy</option>
          <option <?php if (@$pole['prac_up'] == 'Klatovy - pracoviště Sušice'){echo 'selected';} ?>>Klatovy - pracoviště Sušice</option>
          <option <?php if (@$pole['prac_up'] == 'Klatovy - pracoviště Horažďovice'){echo 'selected';} ?>>Klatovy - pracoviště Horažďovice</option>
          </select>
</p>
  <p>Stav osoby: <select ID="stav" size="1" name="stav">
          <option <?php if (@$pole['stav'] == '------------'){echo 'selected';} ?>>------------</option>
          <option <?php if (@$pole['stav'] == 'uchazeč o zaměstnání'){echo 'selected';} ?>>uchazeč o zaměstnání</option>
          <option <?php if (@$pole['stav'] == 'zájemce o zaměstnání'){echo 'selected';} ?>>zájemce o zaměstnání</option>
          </select></br>
  Jedná-li se o zájemce o zaměstnání, zvolte typ:
          <select ID="zam_osvc_neaktivni" size="1" name="zam_osvc_neaktivni">
          <option <?php if (@$pole['zam_osvc_neaktivni'] == '------------'){echo 'selected';} ?>>------------</option>
          <option <?php if (@$pole['zam_osvc_neaktivni'] == 'zaměstnanec'){echo 'selected';} ?>>zaměstnanec</option>
          <option <?php if (@$pole['zam_osvc_neaktivni'] == 'OSVČ'){echo 'selected';} ?>>OSVČ</option>
          <option <?php if (@$pole['zam_osvc_neaktivni'] == 'neaktivní'){echo 'selected';} ?>>neaktivní</option>
          </select></p>
  <p>Datum zahájení individuálního poradenství: <input ID="datum_poradenstvi_zacatek" type="text" name="datum_poradenstvi_zacatek" size="8" maxlength="10" value="<?php echo @$pole['datum_poradenstvi_zacatek'];?>"></p>

  <p></p>          
</FIELDSET>



<FIELDSET style="width:;"><LEGEND><b>Bydliště a kontaktní údaje</b></LEGEND>
  <FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px">
  <LEGEND style="color:black;font-size: 11px"><b>Trvalé bydliště</b></LEGEND>
  <p>Město: <input ID="mesto" type="text" name="mesto" size="20" maxlength="50" value="<?php echo @$pole['mesto'];?>">
  Ulice: <input ID="ulice" type="text" name="ulice" size="20" maxlength="50" value="<?php echo @$pole['ulice'];?>">
  PSČ: <input ID="psc" type="text" name="psc" size="5" maxlength="5" value="<?php echo @$pole['psc'];?>">
  Pevný telefon: <input ID="pevny_telefon" type="text" name="pevny_telefon" size="15" maxlength="20" value="<?php echo @$pole['pevny_telefon'];?>">
  </p>
  </FIELDSET><br>
  <FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px">
  <LEGEND style="color:black;font-size: 11px"><b>Adresa dojíždění odlišná od místa bydliště</b></LEGEND>
  <p>Město: <input ID="mesto2" type="text" name="mesto2" size="20" maxlength="50" value="<?php echo @$pole['mesto2'];?>">
  Ulice: <input ID="ulice2" type="text" name="ulice2" size="20" maxlength="50" value="<?php echo @$pole['ulice2'];?>">
  PSČ: <input ID="psc2" type="text" name="psc2" size="5" maxlength="5" value="<?php echo @$pole['psc2'];?>">
  Pevný telefon: <input ID="pevny_telefon2" type="text" name="pevny_telefon2" size="15" maxlength="20" value="<?php echo @$pole['pevny_telefon2'];?>">
  </p>
  </FIELDSET>
  <p>Mobilní telefon: <input ID="mobilni_telefon" type="text" name="mobilni_telefon" size="12" maxlength="15" value="<?php echo @$pole['mobilni_telefon'];?>">
  Další telefony: <input ID="dalsi_telefon" type="text" name="dalsi_telefon" size="12" maxlength="15" value="<?php echo @$pole['dalsi_telefon'];?>">
  popis: <input ID="popis_telefon" type="text" name="popis_telefon" size="40" maxlength="100" value="<?php echo @$pole['popis_telefon'];?>"></p>
  <p>e-mail: <input ID="mail" type="text" name="mail" size="40" value="<?php echo @$pole['mail'];?>"></p>
</FIELDSET>

<DIV  style='display:none'>     <!-- zacatek div  v kterem je neviditelna caast formulare-->

<FIELDSET style="width:;"><LEGEND><b>Vzdělání a schopnosti</b></LEGEND>
  <FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px">
  <LEGEND style="color:black;font-size: 11px"><b>Absolvované školy</b></LEGEND>

  <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px">
  <LEGEND style="color:#808080;font-size: 12px"><b>I.</b></LEGEND>
  Název školy: <input ID="nazev_skoly1" type="text" name="nazev_skoly1" size="70" maxlength="100" value="<?php echo @$pole['nazev_skoly1'];?>"><br>
  Obor: <input ID="obor1" type="text" name="obor1" size="70" maxlength="100" value="<?php echo @$pole['obor1'];?>"><br>
  Rok ukončení studia: <input ID="rok_ukonceni_studia1" type="text" name="rok_ukonceni_studia1" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni_studia1'];?>">
  Stupeň vzdělání: <select ID="vzdelani1" size="1" name="vzdelani1">
    <option <?php if (@$pole['vzdelani1'] == '------------'){echo 'selected';} ?>>------------</option>
    <option <?php if (@$pole['vzdelani1'] == 'Bez vzdělání'){echo 'selected';} ?>>Bez vzdělání</option>
    <option <?php if (@$pole['vzdelani1'] == 'Neúplné základní'){echo 'selected';} ?>>Neúplné základní</option>
    <option <?php if (@$pole['vzdelani1'] == 'Základní + praktická škola'){echo 'selected';} ?>>Základní + praktická škola</option>
    <option <?php if (@$pole['vzdelani1'] == 'Nižší střední'){echo 'selected';} ?>>Nižší střední</option>
    <option <?php if (@$pole['vzdelani1'] == 'Nižší střední odborné'){echo 'selected';} ?>>Nižší střední odborné</option>
    <option <?php if (@$pole['vzdelani1'] == 'Střední odborné (vyučen)'){echo 'selected';} ?>>Střední odborné (vyučen)</option>
    <option <?php if (@$pole['vzdelani1'] == 'Střední nebo střední odborné bez maturity a bez vyučení'){echo 'selected';} ?>>Střední nebo střední odborné bez maturity a bez vyučení</option>
    <option <?php if (@$pole['vzdelani1'] == 'ÚSV'){echo 'selected';} ?>>ÚSV</option>
    <option <?php if (@$pole['vzdelani1'] == 'ÚSO (vyučení s maturitou)'){echo 'selected';} ?>>ÚSO (vyučení s maturitou)</option>
    <option <?php if (@$pole['vzdelani1'] == 'ÚSO s maturitou (bez vyučení)'){echo 'selected';} ?>>ÚSO s maturitou (bez vyučení)</option>
    <option <?php if (@$pole['vzdelani1'] == 'Vyšší odborné'){echo 'selected';} ?>>Vyšší odborné</option>
    <option <?php if (@$pole['vzdelani1'] == 'Bakalářské'){echo 'selected';} ?>>Bakalářské</option>
    <option <?php if (@$pole['vzdelani1'] == 'Vysokoškolské'){echo 'selected';} ?>>Vysokoškolské</option>
    <option <?php if (@$pole['vzdelani1'] == 'Doktorské (vědecká výchova)'){echo 'selected';} ?>>Doktorské (vědecká výchova)</option>
  </select><br>
    Závěrečná zkouška: <select ID="zaverecna_zkouska1" size="1" name="zaverecna_zkouska1">
    <option <?php if (@$pole['zaverecna_zkouska1'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Jiné'){echo 'selected';} ?>>Jiné</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Osvědčení'){echo 'selected';} ?>>Osvědčení</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Závěrečná zkouška + výuční list'){echo 'selected';} ?>>Závěrečná zkouška + výuční list</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Závěrečná zkouška'){echo 'selected';} ?>>Závěrečná zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Maturitní zkouška'){echo 'selected';} ?>>Maturitní zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Absolutorium'){echo 'selected';} ?>>Absolutorium</option>
    <option <?php if (@$pole['zaverecna_zkouska1'] == 'Státní zkouška'){echo 'selected';} ?>>Státní zkouška</option>
  </select><br>
  popis: <input ID="popis1" type="text" name="popis1" size="100" maxlength="150" value="<?php echo @$pole['popis1'];?>"><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno10" type="radio" name="dolozeno1" value="Ne" <?php if (@$pole['dolozeno1'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno11" type="radio" value="Ano" name="dolozeno1" <?php if (@$pole['dolozeno1'] == 'Ano') {echo 'checked';} ?>>Ano<br>
  </FIELDSET><br>

  <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px">
  <LEGEND style="color:#808080;font-size: 12px"><b>II.</b></LEGEND>
  Název školy: <input ID="nazev_skoly2" type="text" name="nazev_skoly2" size="70" maxlength="100" value="<?php echo @$pole['nazev_skoly2'];?>"><br>
  Obor: <input ID="obor2" type="text" name="obor2" size="70" maxlength="100" value="<?php echo @$pole['obor2'];?>"><br>
  Rok ukončení studia: <input ID="rok_ukonceni_studia2" type="text" name="rok_ukonceni_studia2" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni_studia2'];?>">
  Stupeň vzdělání: <select ID="vzdelani2" size="1" name="vzdelani2">
    <option <?php if (@$pole['vzdelani2'] == '------------'){echo 'selected';} ?>>------------</option>
    <option <?php if (@$pole['vzdelani2'] == 'Bez vzdělání'){echo 'selected';} ?>>Bez vzdělání</option>
    <option <?php if (@$pole['vzdelani2'] == 'Neúplné základní'){echo 'selected';} ?>>Neúplné základní</option>
    <option <?php if (@$pole['vzdelani2'] == 'Základní + praktická škola'){echo 'selected';} ?>>Základní + praktická škola</option>
    <option <?php if (@$pole['vzdelani2'] == 'Nižší střední'){echo 'selected';} ?>>Nižší střední</option>
    <option <?php if (@$pole['vzdelani2'] == 'Nižší střední odborné'){echo 'selected';} ?>>Nižší střední odborné</option>
    <option <?php if (@$pole['vzdelani2'] == 'Střední odborné (vyučen)'){echo 'selected';} ?>>Střední odborné (vyučen)</option>
    <option <?php if (@$pole['vzdelani2'] == 'Střední nebo střední odborné bez maturity a bez vyučení'){echo 'selected';} ?>>Střední nebo střední odborné bez maturity a bez vyučení</option>
    <option <?php if (@$pole['vzdelani2'] == 'ÚSV'){echo 'selected';} ?>>ÚSV</option>
    <option <?php if (@$pole['vzdelani2'] == 'ÚSO (vyučení s maturitou)'){echo 'selected';} ?>>ÚSO (vyučení s maturitou)</option>
    <option <?php if (@$pole['vzdelani2'] == 'ÚSO s maturitou (bez vyučení)'){echo 'selected';} ?>>ÚSO s maturitou (bez vyučení)</option>
    <option <?php if (@$pole['vzdelani2'] == 'Vyšší odborné'){echo 'selected';} ?>>Vyšší odborné</option>
    <option <?php if (@$pole['vzdelani2'] == 'Bakalářské'){echo 'selected';} ?>>Bakalářské</option>
    <option <?php if (@$pole['vzdelani2'] == 'Vysokoškolské'){echo 'selected';} ?>>Vysokoškolské</option>
    <option <?php if (@$pole['vzdelani2'] == 'Doktorské (vědecká výchova)'){echo 'selected';} ?>>Doktorské (vědecká výchova)</option>
  </select><br>
    Závěrečná zkouška: <select ID="zaverecna_zkouska2" size="1" name="zaverecna_zkouska2">
    <option <?php if (@$pole['zaverecna_zkouska2'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Jiné'){echo 'selected';} ?>>Jiné</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Osvědčení'){echo 'selected';} ?>>Osvědčení</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Závěrečná zkouška + výuční list'){echo 'selected';} ?>>Závěrečná zkouška + výuční list</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Závěrečná zkouška'){echo 'selected';} ?>>Závěrečná zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Maturitní zkouška'){echo 'selected';} ?>>Maturitní zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Absolutorium'){echo 'selected';} ?>>Absolutorium</option>
    <option <?php if (@$pole['zaverecna_zkouska2'] == 'Státní zkouška'){echo 'selected';} ?>>Státní zkouška</option>
  </select><br>
  Popis: <input ID="popis2" type="text" name="popis2" size="100" maxlength="150" value="<?php echo @$pole['popis2'];?>"><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno20" type="radio" checked name="dolozeno2" value="Ne" <?php if (@$pole['dolozeno2'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno21" type="radio" value="Ano" name="dolozeno2" <?php if (@$pole['dolozeno2'] == 'Ano') {echo 'checked';} ?>>Ano<br>
  </FIELDSET><br>

  <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px">
  <LEGEND style="color:#808080;font-size: 12px"><b>III.</b></LEGEND>
  Název školy: <input ID="nazev_skoly3" type="text" name="nazev_skoly3" size="70" maxlength="100" value="<?php echo @$pole['nazev_skoly3'];?>"><br>
  Obor: <input ID="obor3" type="text" name="obor3" size="70" maxlength="100" value="<?php echo @$pole['obor3'];?>"><br>
  Rok ukončení studia: <input ID="rok_ukonceni_studia3" type="text" name="rok_ukonceni_studia3" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni_studia3'];?>">
  Stupeň vzdělání: <select ID="vzdelani3" size="1" name="vzdelani3">
    <option <?php if (@$pole['vzdelani3'] == '------------'){echo 'selected';} ?>>------------</option>
    <option <?php if (@$pole['vzdelani3'] == 'Bez vzdělání'){echo 'selected';} ?>>Bez vzdělání</option>
    <option <?php if (@$pole['vzdelani3'] == 'Neúplné základní'){echo 'selected';} ?>>Neúplné základní</option>
    <option <?php if (@$pole['vzdelani3'] == 'Základní + praktická škola'){echo 'selected';} ?>>Základní + praktická škola</option>
    <option <?php if (@$pole['vzdelani3'] == 'Nižší střední'){echo 'selected';} ?>>Nižší střední</option>
    <option <?php if (@$pole['vzdelani3'] == 'Nižší střední odborné'){echo 'selected';} ?>>Nižší střední odborné</option>
    <option <?php if (@$pole['vzdelani3'] == 'Střední odborné (vyučen)'){echo 'selected';} ?>>Střední odborné (vyučen)</option>
    <option <?php if (@$pole['vzdelani3'] == 'Střední nebo střední odborné bez maturity a bez vyučení'){echo 'selected';} ?>>Střední nebo střední odborné bez maturity a bez vyučení</option>
    <option <?php if (@$pole['vzdelani3'] == 'ÚSV'){echo 'selected';} ?>>ÚSV</option>
    <option <?php if (@$pole['vzdelani3'] == 'ÚSO (vyučení s maturitou)'){echo 'selected';} ?>>ÚSO (vyučení s maturitou)</option>
    <option <?php if (@$pole['vzdelani3'] == 'ÚSO s maturitou (bez vyučení)'){echo 'selected';} ?>>ÚSO s maturitou (bez vyučení)</option>
    <option <?php if (@$pole['vzdelani3'] == 'Vyšší odborné'){echo 'selected';} ?>>Vyšší odborné</option>
    <option <?php if (@$pole['vzdelani3'] == 'Bakalářské'){echo 'selected';} ?>>Bakalářské</option>
    <option <?php if (@$pole['vzdelani3'] == 'Vysokoškolské'){echo 'selected';} ?>>Vysokoškolské</option>
    <option <?php if (@$pole['vzdelani3'] == 'Doktorské (vědecká výchova)'){echo 'selected';} ?>>Doktorské (vědecká výchova)</option>
  </select><br>
    Závěrečná zkouška: <select ID="zaverecna_zkouska3" size="1" name="zaverecna_zkouska3">
    <option <?php if (@$pole['zaverecna_zkouska3'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Jiné'){echo 'selected';} ?>>Jiné</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Osvědčení'){echo 'selected';} ?>>Osvědčení</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Závěrečná zkouška + výuční list'){echo 'selected';} ?>>Závěrečná zkouška + výuční list</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Závěrečná zkouška'){echo 'selected';} ?>>Závěrečná zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Maturitní zkouška'){echo 'selected';} ?>>Maturitní zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Absolutorium'){echo 'selected';} ?>>Absolutorium</option>
    <option <?php if (@$pole['zaverecna_zkouska3'] == 'Státní zkouška'){echo 'selected';} ?>>Státní zkouška</option>
  </select><br>
  Popis: <input ID="popis3" type="text" name="popis3" size="100" maxlength="150" value="<?php echo @$pole['popis3'];?>"><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno30" type="radio" checked name="dolozeno3" value="Ne" <?php if (@$pole['dolozeno3'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno31" type="radio" value="Ano" name="dolozeno3" <?php if (@$pole['dolozeno3'] == 'Ano') {echo 'checked';} ?>>Ano<br>
  </FIELDSET><br>

  <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px">
  <LEGEND style="color:#808080;font-size: 12px"><b>IV.</b></LEGEND>
  Název školy: <input ID="nazev_skoly4" type="text" name="nazev_skoly4" size="70" maxlength="100" value="<?php echo @$pole['nazev_skoly4'];?>"><br>
  Obor: <input ID="obor4" type="text" name="obor4" size="70" maxlength="100" value="<?php echo @$pole['obor4'];?>"><br>
  Rok ukončení studia: <input ID="rok_ukonceni_studia4" type="text" name="rok_ukonceni_studia4" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni_studia4'];?>">
  Stupeň vzdělání: <select ID="vzdelani4" size="1" name="vzdelani4">
    <option <?php if (@$pole['vzdelani4'] == '------------'){echo 'selected';} ?>>------------</option>
    <option <?php if (@$pole['vzdelani4'] == 'Bez vzdělání'){echo 'selected';} ?>>Bez vzdělání</option>
    <option <?php if (@$pole['vzdelani4'] == 'Neúplné základní'){echo 'selected';} ?>>Neúplné základní</option>
    <option <?php if (@$pole['vzdelani4'] == 'Základní + praktická škola'){echo 'selected';} ?>>Základní + praktická škola</option>
    <option <?php if (@$pole['vzdelani4'] == 'Nižší střední'){echo 'selected';} ?>>Nižší střední</option>
    <option <?php if (@$pole['vzdelani4'] == 'Nižší střední odborné'){echo 'selected';} ?>>Nižší střední odborné</option>
    <option <?php if (@$pole['vzdelani4'] == 'Střední odborné (vyučen)'){echo 'selected';} ?>>Střední odborné (vyučen)</option>
    <option <?php if (@$pole['vzdelani4'] == 'Střední nebo střední odborné bez maturity a bez vyučení'){echo 'selected';} ?>>Střední nebo střední odborné bez maturity a bez vyučení</option>
    <option <?php if (@$pole['vzdelani4'] == 'ÚSV'){echo 'selected';} ?>>ÚSV</option>
    <option <?php if (@$pole['vzdelani4'] == 'ÚSO (vyučení s maturitou)'){echo 'selected';} ?>>ÚSO (vyučení s maturitou)</option>
    <option <?php if (@$pole['vzdelani4'] == 'ÚSO s maturitou (bez vyučení)'){echo 'selected';} ?>>ÚSO s maturitou (bez vyučení)</option>
    <option <?php if (@$pole['vzdelani4'] == 'Vyšší odborné'){echo 'selected';} ?>>Vyšší odborné</option>
    <option <?php if (@$pole['vzdelani4'] == 'Bakalářské'){echo 'selected';} ?>>Bakalářské</option>
    <option <?php if (@$pole['vzdelani4'] == 'Vysokoškolské'){echo 'selected';} ?>>Vysokoškolské</option>
    <option <?php if (@$pole['vzdelani4'] == 'Doktorské (vědecká výchova)'){echo 'selected';} ?>>Doktorské (vědecká výchova)</option>
  </select><br>
    Závěrečná zkouška: <select ID="zaverecna_zkouska4" size="1" name="zaverecna_zkouska4">
    <option <?php if (@$pole['zaverecna_zkouska4'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Jiné'){echo 'selected';} ?>>Jiné</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Osvědčení'){echo 'selected';} ?>>Osvědčení</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Závěrečná zkouška + výuční list'){echo 'selected';} ?>>Závěrečná zkouška + výuční list</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Závěrečná zkouška'){echo 'selected';} ?>>Závěrečná zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Maturitní zkouška'){echo 'selected';} ?>>Maturitní zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Absolutorium'){echo 'selected';} ?>>Absolutorium</option>
    <option <?php if (@$pole['zaverecna_zkouska4'] == 'Státní zkouška'){echo 'selected';} ?>>Státní zkouška</option>
  </select><br>
  Popis: <input ID="popis4" type="text" name="popis4" size="100" maxlength="150" value="<?php echo @$pole['popis4'];?>"><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno40" type="radio" checked name="dolozeno4" value="Ne" <?php if (@$pole['dolozeno4'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno41" type="radio" value="Ano" name="dolozeno4" <?php if (@$pole['dolozeno4'] == 'Ano') {echo 'checked';} ?>>Ano<br>
  </FIELDSET><br>

  <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px">
  <LEGEND style="color:#808080;font-size: 12px"><b>V.</b></LEGEND>
  Název školy: <input ID="nazev_skoly5" type="text" name="nazev_skoly5" size="70" maxlength="100" value="<?php echo @$pole['nazev_skoly5'];?>"><br>
  Obor: <input ID="obor5" type="text" name="obor5" size="70" maxlength="100" value="<?php echo @$pole['obor5'];?>"><br>
  Rok ukončení studia: <input ID="rok_ukonceni_studia5" type="text" name="rok_ukonceni_studia5" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni_studia5'];?>">
  Stupeň vzdělání: <select ID="vzdelani5" size="1" name="vzdelani5">
    <option <?php if (@$pole['vzdelani5'] == '------------'){echo 'selected';} ?>>------------</option>
    <option <?php if (@$pole['vzdelani5'] == 'Bez vzdělání'){echo 'selected';} ?>>Bez vzdělání</option>
    <option <?php if (@$pole['vzdelani5'] == 'Neúplné základní'){echo 'selected';} ?>>Neúplné základní</option>
    <option <?php if (@$pole['vzdelani5'] == 'Základní + praktická škola'){echo 'selected';} ?>>Základní + praktická škola</option>
    <option <?php if (@$pole['vzdelani5'] == 'Nižší střední'){echo 'selected';} ?>>Nižší střední</option>
    <option <?php if (@$pole['vzdelani5'] == 'Nižší střední odborné'){echo 'selected';} ?>>Nižší střední odborné</option>
    <option <?php if (@$pole['vzdelani5'] == 'Střední odborné (vyučen)'){echo 'selected';} ?>>Střední odborné (vyučen)</option>
    <option <?php if (@$pole['vzdelani5'] == 'Střední nebo střední odborné bez maturity a bez vyučení'){echo 'selected';} ?>>Střední nebo střední odborné bez maturity a bez vyučení</option>
    <option <?php if (@$pole['vzdelani5'] == 'ÚSV'){echo 'selected';} ?>>ÚSV</option>
    <option <?php if (@$pole['vzdelani5'] == 'ÚSO (vyučení s maturitou)'){echo 'selected';} ?>>ÚSO (vyučení s maturitou)</option>
    <option <?php if (@$pole['vzdelani5'] == 'ÚSO s maturitou (bez vyučení)'){echo 'selected';} ?>>ÚSO s maturitou (bez vyučení)</option>
    <option <?php if (@$pole['vzdelani5'] == 'Vyšší odborné'){echo 'selected';} ?>>Vyšší odborné</option>
    <option <?php if (@$pole['vzdelani5'] == 'Bakalářské'){echo 'selected';} ?>>Bakalářské</option>
    <option <?php if (@$pole['vzdelani5'] == 'Vysokoškolské'){echo 'selected';} ?>>Vysokoškolské</option>
    <option <?php if (@$pole['vzdelani5'] == 'Doktorské (vědecká výchova)'){echo 'selected';} ?>>Doktorské (vědecká výchova)</option>
  </select><br>
    Závěrečná zkouška: <select ID="zaverecna_zkouska5" size="1" name="zaverecna_zkouska5">
    <option <?php if (@$pole['zaverecna_zkouska5'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Jiné'){echo 'selected';} ?>>Jiné</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Osvědčení'){echo 'selected';} ?>>Osvědčení</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Závěrečná zkouška + výuční list'){echo 'selected';} ?>>Závěrečná zkouška + výuční list</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Závěrečná zkouška'){echo 'selected';} ?>>Závěrečná zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Maturitní zkouška'){echo 'selected';} ?>>Maturitní zkouška</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Absolutorium'){echo 'selected';} ?>>Absolutorium</option>
    <option <?php if (@$pole['zaverecna_zkouska5'] == 'Státní zkouška'){echo 'selected';} ?>>Státní zkouška</option>
  </select><br>
  Popis: <input ID="popis5" type="text" name="popis5" size="100" maxlength="150" value="<?php echo @$pole['popis5'];?>"><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno50" type="radio" checked name="dolozeno5" value="Ne" <?php if (@$pole['dolozeno5'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno51" type="radio" value="Ano" name="dolozeno5" <?php if (@$pole['dolozeno5'] == 'Ano') {echo 'checked';} ?>>Ano<br>
  </FIELDSET><br>
</FIELDSET>

<FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px"><LEGEND style="color:black;font-size: 11px"><b>Další absolvovaná školení</b></LEGEND>
   <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>I.</b></LEGEND>
   Název: <input ID="nazev_skoleni1" type="text" name="nazev_skoleni1" size="70" maxlength="150" value="<?php echo @$pole['nazev_skoleni1'];?>"><br>
   Popis školení: <input ID="popis_skoleni1" type="text" name="popis_skoleni1" size="70" maxlength="150" value="<?php echo @$pole['popis_skoleni1'];?>"><br>
   Rok ukončení: <input ID="rok_ukonceni1" type="text" name="rok_ukonceni1" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni1'];?>">
   Doba trvání školení: <input ID="doba_skoleni1" type="text" name="doba_skoleni1" size="3" maxlength="3" value="<?php echo @$pole['doba_skoleni1'];?>">[dny]<br>
   Popis dokladu: <input ID="popis_dokladu1" type="text" name="popis_dokladu1" size="70" maxlength="150" value="<?php echo @$pole['popis_dokladu1'];?>"><br>
   Hrazeno: <select ID="hrazeno1" size="1" name="hrazeno1">
          <option <?php if (@$pole['hrazeno1'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
          <option <?php if (@$pole['hrazeno1'] == 'předchozím zaměstnavatelem'){echo 'selected';} ?>>předchozím zaměstnavatelem</option>
          <option <?php if (@$pole['hrazeno1'] == 'vlastní'){echo 'selected';} ?>>vlastní</option>
          <option <?php if (@$pole['hrazeno1'] == 'úřadem práce'){echo 'selected';} ?>>úřadem práce</option>
        </select><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno_skoleni10" type="radio" name="dolozeno_skoleni1" value="Ne" <?php if (@$pole['dolozeno_skoleni1'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno_skoleni11" type="radio" name="dolozeno_skoleni1" value="Ano" <?php if (@$pole['dolozeno_skoleni1'] == 'Ano') {echo 'checked';} ?>>Ano<br>
   </FIELDSET><br>
   <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>II.</b></LEGEND>
   Název: <input ID="nazev_skoleni2" type="text" name="nazev_skoleni2" size="70" maxlength="150" value="<?php echo @$pole['nazev_skoleni2'];?>"><br>
   Popis školení: <input ID="popis_skoleni2" type="text" name="popis_skoleni2" size="70" maxlength="150" value="<?php echo @$pole['popis_skoleni2'];?>"><br>
   Rok ukončení: <input ID="rok_ukonceni2" type="text" name="rok_ukonceni2" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni2'];?>">
   Doba trvání školení: <input ID="doba_skoleni2" type="text" name="doba_skoleni2" size="3" maxlength="3" value="<?php echo @$pole['doba_skoleni2'];?>">[dny]<br>
   Popis dokladu: <input ID="popis_dokladu2" type="text" name="popis_dokladu2" size="70" maxlength="150" value="<?php echo @$pole['popis_dokladu2'];?>"><br>
   Hrazeno: <select ID="hrazeno2" size="1" name="hrazeno2">
          <option <?php if (@$pole['hrazeno2'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
          <option <?php if (@$pole['hrazeno2'] == 'předchozím zaměstnavatelem'){echo 'selected';} ?>>předchozím zaměstnavatelem</option>
          <option <?php if (@$pole['hrazeno2'] == 'vlastní'){echo 'selected';} ?>>vlastní</option>
          <option <?php if (@$pole['hrazeno2'] == 'úřadem práce'){echo 'selected';} ?>>úřadem práce</option>
        </select><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno_skoleni20" type="radio" name="dolozeno_skoleni2" value="Ne" <?php if (@$pole['dolozeno_skoleni2'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno_skoleni21" type="radio" name="dolozeno_skoleni2" value="Ano" <?php if (@$pole['dolozeno_skoleni2'] == 'Ano') {echo 'checked';} ?>>Ano<br>
   </FIELDSET><br>
   <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>III.</b></LEGEND>
   Název: <input ID="nazev_skoleni3" type="text" name="nazev_skoleni3" size="70" maxlength="150" value="<?php echo @$pole['nazev_skoleni3'];?>"><br>
   Popis školení: <input ID="popis_skoleni3" type="text" name="popis_skoleni3" size="70" maxlength="150" value="<?php echo @$pole['popis_skoleni3'];?>"><br>
   Rok ukončení: <input ID="rok_ukonceni3" type="text" name="rok_ukonceni3" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni3'];?>">
   Doba trvání školení: <input ID="doba_skoleni3" type="text" name="doba_skoleni3" size="3" maxlength="3" value="<?php echo @$pole['doba_skoleni3'];?>">[dny]<br>
   Popis dokladu: <input ID="popis_dokladu3" type="text" name="popis_dokladu3" size="70" maxlength="150" value="<?php echo @$pole['popis_dokladu3'];?>"><br>
   Hrazeno: <select ID="hrazeno3" size="1" name="hrazeno3">
          <option <?php if (@$pole['hrazeno3'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
          <option <?php if (@$pole['hrazeno3'] == 'předchozím zaměstnavatelem'){echo 'selected';} ?>>předchozím zaměstnavatelem</option>
          <option <?php if (@$pole['hrazeno3'] == 'vlastní'){echo 'selected';} ?>>vlastní</option>
          <option <?php if (@$pole['hrazeno3'] == 'úřadem práce'){echo 'selected';} ?>>úřadem práce</option>
        </select><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno_skoleni30" type="radio" name="dolozeno_skoleni3" value="Ne" <?php if (@$pole['dolozeno_skoleni3'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno_skoleni31" type="radio" name="dolozeno_skoleni3" value="Ano" <?php if (@$pole['dolozeno_skoleni3'] == 'Ano') {echo 'checked';} ?>>Ano<br>
   </FIELDSET><br>
   <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>IV.</b></LEGEND>
   Název: <input ID="nazev_skoleni4" type="text" name="nazev_skoleni4" size="70" maxlength="150" value="<?php echo @$pole['nazev_skoleni4'];?>"><br>
   Popis školení: <input ID="popis_skoleni4" type="text" name="popis_skoleni4" size="70" maxlength="150" value="<?php echo @$pole['popis_skoleni4'];?>"><br>
   Rok ukončení: <input ID="rok_ukonceni4" type="text" name="rok_ukonceni4" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni4'];?>">
   Doba trvání školení: <input ID="doba_skoleni4" type="text" name="doba_skoleni4" size="3" maxlength="3" value="<?php echo @$pole['doba_skoleni4'];?>">[dny]<br>
   Popis dokladu: <input ID="popis_dokladu4" type="text" name="popis_dokladu4" size="70" maxlength="150" value="<?php echo @$pole['popis_dokladu4'];?>"><br>
   Hrazeno: <select ID="hrazeno4" size="1" name="hrazeno4">
          <option <?php if (@$pole['hrazeno4'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
          <option <?php if (@$pole['hrazeno4'] == 'předchozím zaměstnavatelem'){echo 'selected';} ?>>předchozím zaměstnavatelem</option>
          <option <?php if (@$pole['hrazeno4'] == 'vlastní'){echo 'selected';} ?>>vlastní</option>
          <option <?php if (@$pole['hrazeno4'] == 'úřadem práce'){echo 'selected';} ?>>úřadem práce</option>
        </select><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno_skoleni40" type="radio" name="dolozeno_skoleni3" value="Ne" <?php if (@$pole['dolozeno_skoleni4'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno_skoleni41" type="radio" name="dolozeno_skoleni3" value="Ano" <?php if (@$pole['dolozeno_skoleni4'] == 'Ano') {echo 'checked';} ?>>Ano<br>
 </FIELDSET><br>
   <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>V.</b></LEGEND>
   Název: <input ID="nazev_skoleni5" type="text" name="nazev_skoleni5" size="70" maxlength="150" value="<?php echo @$pole['nazev_skoleni5'];?>"><br>
   Popis školení: <input ID="popis_skoleni5" type="text" name="popis_skoleni5" size="70" maxlength="150" value="<?php echo @$pole['popis_skoleni5'];?>"><br>
   Rok ukončení: <input ID="rok_ukonceni5" type="text" name="rok_ukonceni5" size="4" maxlength="4" value="<?php echo @$pole['rok_ukonceni5'];?>">
   Doba trvání školení: <input ID="doba_skoleni5" type="text" name="doba_skoleni5" size="3" maxlength="3" value="<?php echo @$pole['doba_skoleni5'];?>">[dny]<br>
   Popis dokladu: <input ID="popis_dokladu5" type="text" name="popis_dokladu5" size="70" maxlength="150" value="<?php echo @$pole['popis_dokladu5'];?>"><br>
   Hrazeno: <select ID="hrazeno5" size="1" name="hrazeno5">
          <option <?php if (@$pole['hrazeno5'] == '------------------------'){echo 'selected';} ?>>------------------------</option>
          <option <?php if (@$pole['hrazeno5'] == 'předchozím zaměstnavatelem'){echo 'selected';} ?>>předchozím zaměstnavatelem</option>
          <option <?php if (@$pole['hrazeno5'] == 'vlastní'){echo 'selected';} ?>>vlastní</option>
          <option <?php if (@$pole['hrazeno5'] == 'úřadem práce'){echo 'selected';} ?>>úřadem práce</option>
        </select><br>
  Doloženo dokladem:<br>
  <input ID="dolozeno_skoleni50" type="radio" name="dolozeno_skoleni5" value="Ne" <?php if (@$pole['dolozeno_skoleni5'] != 'Ano') {echo 'checked';} ?>>Ne<br>
  <input ID="dolozeno_skoleni51" type="radio" name="dolozeno_skoleni5" value="Ano" <?php if (@$pole['dolozeno_skoleni5'] == 'Ano') {echo 'checked';} ?>>Ano<br>
   </FIELDSET>
</FIELDSET>
<FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px"><LEGEND style="color:black;font-size: 11px"><b>Specializace v praxi</b></LEGEND>
  <p><textarea rows="4" name="specializace_v_praxi" cols="60"><?php echo @$pole['specializace_v_praxi'];?></textarea></p>
</FIELDSET>
<FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px"><LEGEND style="color:black;font-size: 11px"><b>Jazykové znalosti</b></LEGEND>
  <p><table border="0" width="442" cellspacing="0" cellpadding="0">
    <tr>
      <td width="123">Jazyk</td>
      <td width="116">Úroveň</td>
      <td width="203">Schopnosti</td>
    </tr>
    <tr>
      <td width="123">Anglický jazyk</td>
      <td width="116"><select ID="aj_uroven" size="1" name="aj_uroven">
          <option <?php if (@$pole['aj_uroven'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['aj_uroven'] == 'začátečníci'){echo 'selected';} ?>>začátečníci</option>
          <option <?php if (@$pole['aj_uroven'] == 'mírně pokročilí'){echo 'selected';} ?>>mírně pokročilí</option>
          <option <?php if (@$pole['aj_uroven'] == 'pokročilí'){echo 'selected';} ?>>pokročilí</option>
        </select></td>
      <td width="203"><select ID="aj_schopnosti" size="1" name="aj_schopnosti">
          <option <?php if (@$pole['aj_schopnosti'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['aj_schopnosti'] == 'porozumění odbornému textu'){echo 'selected';} ?>>porozumění odbornému textu</option>
          <option <?php if (@$pole['aj_schopnosti'] == 'základní konverzace'){echo 'selected';} ?>>základní konverzace</option>
          <option <?php if (@$pole['aj_schopnosti'] == 'výborná konverzace'){echo 'selected';} ?>>výborná konverzace</option>
        </select></td>
    </tr>
    <tr>
      <td width="123">Německý jazyk</td>
      <td width="116"><select ID="nj_uroven" size="1" name="nj_uroven">
          <option <?php if (@$pole['nj_uroven'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['nj_uroven'] == 'začátečníci'){echo 'selected';} ?>>začátečníci</option>
          <option <?php if (@$pole['nj_uroven'] == 'mírně pokročilí'){echo 'selected';} ?>>mírně pokročilí</option>
          <option <?php if (@$pole['nj_uroven'] == 'pokročilí'){echo 'selected';} ?>>pokročilí</option>
        </select></td>
      <td width="203"><select ID="nj_schopnosti" size="1" name="nj_schopnosti">
          <option <?php if (@$pole['nj_schopnosti'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['nj_schopnosti'] == 'porozumění odbornému textu'){echo 'selected';} ?>>porozumění odbornému textu</option>
          <option <?php if (@$pole['nj_schopnosti'] == 'základní konverzace'){echo 'selected';} ?>>základní konverzace</option>
          <option <?php if (@$pole['nj_schopnosti'] == 'výborná konverzace'){echo 'selected';} ?>>výborná konverzace</option>
        </select></td>
    </tr>
    <tr>
      <td width="123">Ruský jazyk</td>
      <td width="116"><select ID="rj_uroven" size="1" name="rj_uroven">
          <option <?php if (@$pole['rj_uroven'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['rj_uroven'] == 'začátečníci'){echo 'selected';} ?>>začátečníci</option>
          <option <?php if (@$pole['rj_uroven'] == 'mírně pokročilí'){echo 'selected';} ?>>mírně pokročilí</option>
          <option <?php if (@$pole['rj_uroven'] == 'pokročilí'){echo 'selected';} ?>>pokročilí</option>
        </select></td>
      <td width="203"><select ID="rj_schopnosti" size="1" name="rj_schopnosti">
          <option <?php if (@$pole['rj_schopnosti'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['rj_schopnosti'] == 'porozumění odbornému textu'){echo 'selected';} ?>>porozumění odbornému textu</option>
          <option <?php if (@$pole['rj_schopnosti'] == 'základní konverzace'){echo 'selected';} ?>>základní konverzace</option>
          <option <?php if (@$pole['rj_schopnosti'] == 'výborná konverzace'){echo 'selected';} ?>>výborná konverzace</option>
        </select></td>
    </tr>
    <tr>
      <td width="123">Další:<input ID="dalsi_jazyk1_jmeno" type="text" name="dalsi_jazyk1_jmeno" size="10" maxlength="15"  value="<?php echo @$pole['dalsi_jazyk1_jmeno'];?>"></td>
      <td width="116"><select ID="dalsi_jazyk1_jmeno_uroven" size="1" name="dalsi_jazyk1_jmeno_uroven">
          <option <?php if (@$pole['dalsi_jazyk1_jmeno_uroven'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['dalsi_jazyk1_jmeno_uroven'] == 'začátečníci'){echo 'selected';} ?>>začátečníci</option>
          <option <?php if (@$pole['dalsi_jazyk1_jmeno_uroven'] == 'mírně pokročilí'){echo 'selected';} ?>>mírně pokročilí</option>
          <option <?php if (@$pole['dalsi_jazyk1_jmeno_uroven'] == 'pokročilí'){echo 'selected';} ?>>pokročilí</option>
        </select></td>
      <td width="203"><select ID="dalsi_jazyk1_schopnosti" size="1" name="dalsi_jazyk1_schopnosti">
          <option <?php if (@$pole['dalsi_jazyk1_schopnosti'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['dalsi_jazyk1_schopnosti'] == 'porozumění odbornému textu'){echo 'selected';} ?>>porozumění odbornému textu</option>
          <option <?php if (@$pole['dalsi_jazyk1_schopnosti'] == 'základní konverzace'){echo 'selected';} ?>>základní konverzace</option>
          <option <?php if (@$pole['dalsi_jazyk1_schopnosti'] == 'výborná konverzace'){echo 'selected';} ?>>výborná konverzace</option>
        </select></td>
    </tr>
    <tr>
      <td width="123">Další:<input ID="dalsi_jazyk2_jmeno" type="text" name="dalsi_jazyk2_jmeno" size="10" maxlength="15" value="<?php echo @$pole['dalsi_jazyk2_jmeno'];?>"></td>
      <td width="116"><select ID="dalsi_jazyk2_jmeno_uroven" size="1" name="dalsi_jazyk2_jmeno_uroven">
          <option <?php if (@$pole['dalsi_jazyk2_jmeno_uroven'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['dalsi_jazyk2_jmeno_uroven'] == 'začátečníci'){echo 'selected';} ?>>začátečníci</option>
          <option <?php if (@$pole['dalsi_jazyk2_jmeno_uroven'] == 'mírně pokročilí'){echo 'selected';} ?>>mírně pokročilí</option>
          <option <?php if (@$pole['dalsi_jazyk2_jmeno_uroven'] == 'pokročilí'){echo 'selected';} ?>>pokročilí</option>
        </select></td>
      <td width="203"><select ID="dalsi_jazyk2_schopnosti" size="1" name="dalsi_jazyk2_schopnosti">
          <option <?php if (@$pole['dalsi_jazyk2_schopnosti'] == '--------------'){echo 'selected';} ?>>--------------</option>
          <option <?php if (@$pole['dalsi_jazyk2_schopnosti'] == 'porozumění odbornému textu'){echo 'selected';} ?>>porozumění odbornému textu</option>
          <option <?php if (@$pole['dalsi_jazyk2_schopnosti'] == 'základní konverzace'){echo 'selected';} ?>>základní konverzace</option>
          <option <?php if (@$pole['dalsi_jazyk2_schopnosti'] == 'výborná konverzace'){echo 'selected';} ?>>výborná konverzace</option>
        </select></td>
    </tr>
  </table>
</FIELDSET>
<FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px"><LEGEND style="color:black;font-size: 11px"><b>PC dovednosti</b></LEGEND>
  MS Office - úroveň <select ID="pc_office_uroven" size="1" name="pc_office_uroven">
    <option <?php if (@$pole['pc_office_uroven'] == '------------'){echo 'selected';} ?>>------------</option>
    <option <?php if (@$pole['pc_office_uroven'] == 'uživatelská'){echo 'selected';} ?>>uživatelská</option>
    <option <?php if (@$pole['pc_office_uroven'] == 'expert'){echo 'selected';} ?>>expert</option>
  </select><br>
  <input ID="PC_ERP0" type="radio" name="PC_ERP" value="Ne" <?php if (@$pole['PC_ERP'] != 'Ano') {echo 'checked';} ?>> Ne
  <input ID="PC_ERP1" type="radio" name="PC_ERP" value="Ano" <?php if (@$pole['PC_ERP'] == 'Ano') {echo 'checked';} ?>> Ano&nbsp;&nbsp;&nbsp;ERP systémy (<i>SAP, BAAN, účetnictví</i>) - Název: <input ID="PC_ERP_nazev" type="text" name="PC_ERP_nazev" size="40" maxlength="80" value="<?php echo @$pole['PC_ERP_nazev'];?>"><br>
  <input ID="PC_CAD0" type="radio" name="PC_CAD" value="Ne" <?php if (@$pole['PC_CAD'] != 'Ano') {echo 'checked';} ?>> Ne
  <input ID="PC_CAD1" type="radio" name="PC_CAD" value="Ano" <?php if (@$pole['PC_CAD'] == 'Ano') {echo 'checked';} ?>> Ano&nbsp;&nbsp;&nbsp;CAD systémy - Název: <input ID="PC_CAD_nazev" type="text" name="PC_CAD_nazev" size="40" maxlength="80" value="<?php echo @$pole['PC_CAD_nazev'];?>"><br>
  <input ID="PC_GRA0" type="radio" name="PC_GRA" value="Ne" <?php if (@$pole['PC_GRA'] != 'Ano') {echo 'checked';} ?>> Ne
  <input ID="PC_GRA1" type="radio" name="PC_GRA" value="Ano" <?php if (@$pole['PC_GRA'] == 'Ano') {echo 'checked';} ?>> Ano&nbsp;&nbsp;&nbsp;Grafické programy - Název: <input ID="PC_GRA_nazev" type="text" name="PC_GRA_nazev" size="40" maxlength="80" value="<?php echo @$pole['PC_GRA_nazev'];?>"><br>
  <input ID="PC_IT0" type="radio" name="PC_IT" value="Ne" <?php if (@$pole['PC_IT'] != 'Ano') {echo 'checked';} ?>> Ne
  <input ID="PC_IT1" type="radio" name="PC_IT" value="Ano" <?php if (@$pole['PC_IT'] == 'Ano') {echo 'checked';} ?>> Ano&nbsp;&nbsp;&nbsp;IT expert - Popis expertních PC dovedností:
  <textarea ID="PC_popis" rows="4" name="PC_popis" cols="60"><?php echo @$pole['PC_popis'];?></textarea></p>
</FIELDSET>
<FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px"><LEGEND style="color:black;font-size: 11px"><b>Řidičské oprávnění</b></legend>
<br>
<table style="border:solid black 0px;">
<tr><td>Skupina: <input ID="ridic_sk1" type="text" name="ridic_sk1" size="10" maxlength="10" value="<?php echo @$pole['ridic_sk1'];?>"></td>
    <td>Skupina: <input ID="ridic_sk2" type="text" name="ridic_sk2" size="10" maxlength="10" value="<?php echo @$pole['ridic_sk2'];?>"></td>
    <td>Skupina: <input ID="ridic_sk3" type="text" name="ridic_sk3" size="10" maxlength="10" value="<?php echo @$pole['ridic_sk3'];?>"></td>
    <td>Skupina: <input ID="ridic_sk4" type="text" name="ridic_sk4" size="10" maxlength="10" value="<?php echo @$pole['ridic_sk4'];?>"></td>
</tr>
<tr><td>Rok vystavení: <input ID="ridic_rok1" type="text" name="ridic_rok1" size="5" maxlength="10" value="<?php echo @$pole['ridic_rok1'];?>"></td>
    <td>Rok vystavení: <input ID="ridic_rok2" type="text" name="ridic_rok2" size="5" maxlength="10" value="<?php echo @$pole['ridic_rok2'];?>"></td>
    <td>Rok vystavení: <input ID="ridic_rok3" type="text" name="ridic_rok3" size="5" maxlength="10" value="<?php echo @$pole['ridic_rok3'];?>"></td>
    <td>Rok vystavení: <input ID="ridic_rok4" type="text" name="ridic_rok4" size="5" maxlength="10" value="<?php echo @$pole['ridic_rok4'];?>"></td>
</tr>
</table>
</FIELDSET>
</FIELDSET>
<FIELDSET style="width:;"><LEGEND><b>Informace o předchozím zaměstnání</b></LEGEND>
<!--  <p><table border="0" width="678" cellspacing="1">
    <tr>
      <td width="15" align="center"></td>
      <td width="71" align="center">
        <p align="center">od</p>
      </td>
      <td width="60" align="center">do</td>
      <td width="208">zaměstnavatel</td>
      <td width="157">pozice</td>
      <td width="158">popis pozice</td>
    </tr>
    <tr>
      <td width="15" align="center">1.</td>
      <td width="71" align="center"><input ID="zamestnani_od1" type="text" name="zamestnani_od1" size="9" maxlength="10"></td>
      <td width="60" align="center"><input ID="zamestnani_do1" type="text" name="zamestnani_do1" size="9" maxlength="10"></td>
      <td width="208"><input ID="zamestnani_zamestnavatel1" type="text" name="zamestnani_zamestnavatel1" size="35" maxlength="60"></td>
      <td width="157"><input ID="zamestnani_pozice1" type="text" name="zamestnani_pozice1" size="25" maxlength="60"></td>
      <td width="158"><input ID="zamestnani_popis1" type="text" name="zamestnani_popis1" size="25" maxlength="100"></td>
    </tr>
    <tr>
      <td width="15" align="center">2.</td>
      <td width="71" align="center"><input ID="zamestnani_od2" type="text" name="zamestnani_od2" size="9" maxlength="10"></td>
      <td width="60" align="center"><input ID="zamestnani_do2" type="text" name="zamestnani_do2" size="9" maxlength="10"></td>
      <td width="208"><input ID="zamestnani_zamestnavatel2" type="text" name="zamestnani_zamestnavatel2" size="35" maxlength="60"></td>
      <td width="157"><input ID="zamestnani_pozice2" type="text" name="zamestnani_pozice2" size="25" maxlength="60"></td>
      <td width="158"><input ID="zamestnani_popis2" type="text" name="zamestnani_popis2" size="25" maxlength="100"></td>
    </tr>
    <tr>
      <td width="15" align="center">3.</td>
      <td width="71" align="center"><input ID="zamestnani_od3" type="text" name="zamestnani_od3" size="9" maxlength="10"></td>
      <td width="60" align="center"><input ID="zamestnani_do3" type="text" name="zamestnani_do3" size="9" maxlength="10"></td>
      <td width="208"><input ID="zamestnani_zamestnavatel3" type="text" name="zamestnani_zamestnavatel3" size="35" maxlength="60"></td>
      <td width="157"><input ID="zamestnani_pozice3" type="text" name="zamestnani_pozice3" size="25" maxlength="60"></td>
      <td width="158"><input ID="zamestnani_popis3" type="text" name="zamestnani_popis3" size="25" maxlength="100"></td>
    </tr>
    <tr>
      <td width="15" align="center">4.</td>
      <td width="71" align="center"><input ID="zamestnani_od4" type="text" name="zamestnani_od4" size="9" maxlength="10"></td>
      <td width="60" align="center"><input ID="zamestnani_do4" type="text" name="zamestnani_do4" size="9" maxlength="10"></td>
      <td width="208"><input ID="zamestnani_zamestnavatel4" type="text" name="zamestnani_zamestnavatel4" size="35" maxlength="60"></td>
      <td width="157"><input ID="zamestnani_pozice4" type="text" name="zamestnani_pozice4" size="25" maxlength="60"></td>
      <td width="158"><input ID="zamestnani_popis4" type="text" name="zamestnani_popis4" size="25" maxlength="100"></td>
    </tr>
    <tr>
      <td width="15" align="center">5.</td>
      <td width="71" align="center"><input ID="zamestnani_od5" type="text" name="zamestnani_od5" size="9" maxlength="10"></td>
      <td width="60" align="center"><input ID="zamestnani_do5" type="text" name="zamestnani_do5" size="9" maxlength="10"></td>
      <td width="208"><input ID="zamestnani_zamestnavatel5" type="text" name="zamestnani_zamestnavatel5" size="35" maxlength="60"></td>
      <td width="157"><input ID="zamestnani_pozice5" type="text" name="zamestnani_pozice5" size="25" maxlength="60"></td>
      <td width="158"><input ID="zamestnani_popis5" type="text" name="zamestnani_popis5" size="25" maxlength="100"></td>
    </tr>
  </table>-->
    <FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>I.</b></LEGEND>
  <p><table style="width: 765px; height: 70px;" border="0" cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="71">
      <p align="center">Od</p>
      </td>
      <td align="center" width="60">Do</td>
      <td width="208">Zaměstnavatel (<i>ne zkratka</i>)</td>
      <td colspan="2" rowspan="1" width="157">Pozice dle uchazeče<br>
      </td>
    </tr>
    <tr>
      <td align="center" width="71">
      <input id="zamestnani_od1" name="zamestnani_od1" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_od1'];?>"></td>
      <td align="center" width="60">
      <input id="zamestnani_do1" name="zamestnani_do1" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_do1'];?>"></td>
      <td width="208">
      <input id="zamestnani_zamestnavatel1" name="zamestnani_zamestnavatel1" size="35" maxlength="60" type="text" value="<?php echo @$pole['zamestnani_zamestnavatel1'];?>"></td>
      <td colspan="2" rowspan="1" width="157">
      <input id="zamestnani_pozice1" name="zamestnani_pozice1" size="55" maxlength="60" value="<?php echo @$pole['zamestnani_pozice1'];?>"></td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Popis pozice<br>
      <textarea rows="4" cols="46" id="zamestnani_popis1" name="zamestnani_popis1"><?php echo @$pole['zamestnani_popis1'];?></textarea></td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">
      <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('kzam.html', 'KZAM_cislo1');">&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;</td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">č&iacute;slo dle KZAM<br>
      <input id="KZAM_cislo1" maxlength="20" size="20" readonly name="KZAM_cislo1" value="<?php echo @$pole['KZAM_cislo1'];?>"></td>
    </tr>
  </tbody>
 </table>
</FIELDSET><br>

<FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>II.</b></LEGEND>
  <p><table style="width: 765px; height: 70px;" border="0" cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="71">
      <p align="center">Od</p>
      </td>
      <td align="center" width="60">Do</td>
      <td width="208">Zaměstnavatel (<i>ne zkratka</i>)</td>
      <td colspan="2" rowspan="1" width="157">Pozice dle uchazeče<br>
      </td>
    </tr>
    <tr>
      <td align="center" width="71">
      <input id="zamestnani_od2" name="zamestnani_od2" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_od2'];?>"></td>
      <td align="center" width="60">
      <input id="zamestnani_do2" name="zamestnani_do2" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_do2'];?>"></td>
      <td width="208">
      <input id="zamestnani_zamestnavatel2" name="zamestnani_zamestnavatel2" size="35" maxlength="60" type="text" value="<?php echo @$pole['zamestnani_zamestnavatel2'];?>"></td>
      <td colspan="2" rowspan="1" width="157">
      <input id="zamestnani_pozice2" name="zamestnani_pozice2" size="55" maxlength="60" value="<?php echo @$pole['zamestnani_pozice2'];?>"></td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Popis pozice<br>
      <textarea rows="4" cols="46" id="zamestnani_popis2" name="zamestnani_popis2"><?php echo @$pole['zamestnani_popis2'];?></textarea></td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">
      <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'KZAM_cislo2');">&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;</td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">č&iacute;slo dle KZAM<br>
      <input id="KZAM_cislo2" maxlength="20" size="20" readonly name="KZAM_cislo2" value="<?php echo @$pole['KZAM_cislo2'];?>"></td>
    </tr>
  </tbody>
 </table>
</FIELDSET><br>

<FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>III.</b></LEGEND>
  <p><table style="width: 765px; height: 70px;" border="0" cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="71">
      <p align="center">Od</p>
      </td>
      <td align="center" width="60">Do</td>
      <td width="208">Zaměstnavatel (<i>ne zkratka</i>)</td>
      <td colspan="2" rowspan="1" width="157">Pozice dle uchazeče<br>
      </td>
    </tr>
    <tr>
      <td align="center" width="71">
      <input id="zamestnani_od3" name="zamestnani_od3" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_od3'];?>"></td>
      <td align="center" width="60">
      <input id="zamestnani_do3" name="zamestnani_do3" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_do3'];?>"></td>
      <td width="208">
      <input id="zamestnani_zamestnavatel3" name="zamestnani_zamestnavatel3" size="35" maxlength="60" type="text" value="<?php echo @$pole['zamestnani_zamestnavatel3'];?>"></td>
      <td colspan="2" rowspan="1" width="157">
      <input id="zamestnani_pozice3" name="zamestnani_pozice3" size="55" maxlength="60" value="<?php echo @$pole['zamestnani_pozice3'];?>"></td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Popis pozice<br>
      <textarea rows="4" cols="46" id="zamestnani_popis3" name="zamestnani_popis3"><?php echo @$pole['zamestnani_popis3'];?></textarea></td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">
      <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'KZAM_cislo3');">&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;</td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">č&iacute;slo dle KZAM<br>
      <input id="KZAM_cislo3" maxlength="20" size="20" readonly name="KZAM_cislo3" value="<?php echo @$pole['KZAM_cislo3'];?>"></td>
    </tr>
  </tbody>
 </table>
</FIELDSET><br>

<FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>IV.</b></LEGEND>
  <p><table style="width: 765px; height: 70px;" border="0" cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="71">
      <p align="center">Od</p>
      </td>
      <td align="center" width="60">Do</td>
      <td width="208">Zaměstnavatel (<i>ne zkratka</i>)</td>
      <td colspan="2" rowspan="1" width="157">Pozice dle uchazeče<br>
      </td>
    </tr>
    <tr>
      <td align="center" width="71">
      <input id="zamestnani_od4" name="zamestnani_od4" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_od4'];?>"></td>
      <td align="center" width="60">
      <input id="zamestnani_do4" name="zamestnani_do4" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_do4'];?>"></td>
      <td width="208">
      <input id="zamestnani_zamestnavatel4" name="zamestnani_zamestnavatel4" size="35" maxlength="60" type="text" value="<?php echo @$pole['zamestnani_zamestnavatel4'];?>"></td>
      <td colspan="2" rowspan="1" width="157">
      <input id="zamestnani_pozice4" name="zamestnani_pozice4" size="55" maxlength="60" value="<?php echo @$pole['zamestnani_pozice4'];?>"></td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Popis pozice<br>
      <textarea rows="4" cols="46" id="zamestnani_popis4" name="zamestnani_popis4"><?php echo @$pole['zamestnani_popis4'];?></textarea></td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">
      <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'KZAM_cislo4');">&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;</td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">č&iacute;slo dle KZAM<br>
      <input id="KZAM_cislo4" maxlength="20" size="20" readonly name="KZAM_cislo4" value="<?php echo @$pole['KZAM_cislo4'];?>"></td>
    </tr>
  </tbody>
 </table>
</FIELDSET><br>

<FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px"><LEGEND style="color:#808080;font-size: 12px"><b>V.</b></LEGEND>
  <p><table style="width: 765px; height: 70px;" border="0" cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="71">
      <p align="center">Od</p>
      </td>
      <td align="center" width="60">Do</td>
      <td width="208">Zaměstnavatel (<i>ne zkratka</i>)</td>
      <td colspan="2" rowspan="1" width="157">Pozice dle uchazeče<br>
      </td>
    </tr>
    <tr>
      <td align="center" width="71">
      <input id="zamestnani_od5" name="zamestnani_od5" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_od5'];?>"></td>
      <td align="center" width="60">
      <input id="zamestnani_do5" name="zamestnani_do5" size="9" maxlength="10" type="text" value="<?php echo @$pole['zamestnani_do5'];?>"></td>
      <td width="208">
      <input id="zamestnani_zamestnavatel5" name="zamestnani_zamestnavatel5" size="35" maxlength="60" type="text" value="<?php echo @$pole['zamestnani_zamestnavatel5'];?>"></td>
      <td colspan="2" rowspan="1" width="157">
      <input id="zamestnani_pozice5" name="zamestnani_pozice5" size="55" maxlength="60" value="<?php echo @$pole['zamestnani_pozice5'];?>"></td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Popis pozice<br>
      <textarea rows="4" cols="46" id="zamestnani_popis5" name="zamestnani_popis5"><?php echo @$pole['zamestnani_popis5'];?></textarea></td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">
      <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'KZAM_cislo5');">&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;</td>
      <td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">č&iacute;slo dle KZAM<br>
      <input id="KZAM_cislo5" maxlength="20" size="20" readonly name="KZAM_cislo5" value="<?php echo @$pole['KZAM_cislo5'];?>"></td>
    </tr>
  </tbody>
 </table>
</FIELDSET>

<p>Datum ukončení posledního pracovního poměru: <input ID="zamestnani_konec_posledniho"  type="text" name="zamestnani_konec_posledniho" size="9" maxlength="10" value="<?php echo @$pole['zamestnani_konec_posledniho'];?>"><br>
<p>Poslední pracovní poměr:
  <input style="display:none" ID="zamestnani_zpukonceni0" type="radio" name="zamestnani_zpukonceni" value="none" <?php if ((@$pole['zamestnani_zpukonceni'] != 'Ještě v pracovním poměru') and (@$pole['zamestnani_zpukonceni'] != 'Ukončil s odstupným') and (@$pole['zamestnani_zpukonceni'] != 'Ukončil a je evidován na ÚP') and (@$pole['zamestnani_zpukonceni'] != 'Mateřská dovolená') and (@$pole['zamestnani_zpukonceni'] != 'Absolvent') and (@$pole['zamestnani_zpukonceni'] != 'Absolvent evidován na ÚP') or (@$pole['zamestnani_zpukonceni'] == 'none')) {echo 'checked';} ?>><br>
  <input ID="zamestnani_zpukonceni1" type="radio" name="zamestnani_zpukonceni" value="Ještě v pracovním poměru" <?php if (@$pole['zamestnani_zpukonceni'] == 'Ještě v pracovním poměru') {echo 'checked';} ?>> Ještě v pracovním poměru<br>
  <input ID="zamestnani_zpukonceni2" type="radio" name="zamestnani_zpukonceni" value="Ukončil s odstupným" <?php if (@$pole['zamestnani_zpukonceni'] == 'Ukončil s odstupným') {echo 'checked';} ?>> Ukončil s odstupným<br>
  <input ID="zamestnani_zpukonceni3" type="radio" name="zamestnani_zpukonceni" value="Ukončil a je evidován na ÚP" <?php if (@$pole['zamestnani_zpukonceni'] == 'Ukončil a je evidován na ÚP') {echo 'checked';} ?>> Ukončil a je evidován na ÚP<br>
  <input ID="zamestnani_zpukonceni4" type="radio" name="zamestnani_zpukonceni" value="Mateřská dovolená" <?php if (@$pole['zamestnani_zpukonceni'] == 'Mateřská dovolená') {echo 'checked';} ?>> Mateřská dovolená<br>
  <input ID="zamestnani_zpukonceni5" type="radio" name="zamestnani_zpukonceni" value="Absolvent" <?php if (@$pole['zamestnani_zpukonceni'] == 'Absolvent') {echo 'checked';} ?>> Absolvent<br>
  <input ID="zamestnani_zpukonceni6" type="radio" name="zamestnani_zpukonceni" value="Absolvent evidován na ÚP" <?php if (@$pole['zamestnani_zpukonceni'] == 'Absolvent evidován na ÚP') {echo 'checked';} ?>> Absolvent evidován na ÚP</p>
</FIELDSET>
<FIELDSET style="width:;"><LEGEND><b>Představa o uplatnění</b></LEGEND>
  <p><FIELDSET style="border-style: solid;border-color: silver; border-width: 1px;margin: 4px">
  Jaké povolání byste chtěl/a vykonávat? <br>Popis:&nbsp;&nbsp;<input ID="pozadavky_povolani" type="text" name="pozadavky_povolani" size="80" maxlength="100" value="<?php echo @$pole['pozadavky_povolani'];?>"><br><br>
  1. <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'pozadavky_KZAM1');">&nbsp;&nbsp;&gt;&gt;&gt;&nbsp;&nbsp;<input id="pozadavky_KZAM1" maxlength="20" size="20" readonly name="pozadavky_KZAM1" value="<?php echo @$pole['pozadavky_KZAM1'];?>"><br>
  2. <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'pozadavky_KZAM2');">&nbsp;&nbsp;&gt;&gt;&gt;&nbsp;&nbsp;<input id="pozadavky_KZAM2" maxlength="20" size="20" readonly name="pozadavky_KZAM2" value="<?php echo @$pole['pozadavky_KZAM2'];?>"><br>
  3. <input id="kzam" name="kzam" type="button" value="Výběr KZAM" onClick="kzam_okno('./kzam.html', 'pozadavky_KZAM3');">&nbsp;&nbsp;&gt;&gt;&gt;&nbsp;&nbsp;<input id="pozadavky_KZAM3" maxlength="20" size="20" readonly name="pozadavky_KZAM3" value="<?php echo @$pole['pozadavky_KZAM3'];?>"><br>
  <br>
  </FIELDSET>
 <FIELDSET style="border-style: solid;border-color: black; border-width: 1px;margin: 4px"><LEGEND style="color:black;font-size: 11px"><b>Požadavky uchazeče</b></LEGEND>
   <table style="width: 765px;" border="0" cellspacing="1">
  <tbody>
    <tr>
      <td style="width: 50%;">
Uchazeč hled&aacute;
      </td>
      <td style="width: 50%;">
Uchazeč odm&iacute;t&aacute;
      </td>
    </tr>
    <tr>
      <td>
  <input ID="pozadavky_hleda10" type="radio" name="pozadavky_hleda1" value="----" <?php if (@$pole['pozadavky_hleda1'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda11" type="radio" name="pozadavky_hleda1" value="hlavní pracovní poměr" <?php if (@$pole['pozadavky_hleda1'] == 'hlavní pracovní poměr') {echo 'checked';} ?>>hlavní pracovní poměr<br>
  <input ID="pozadavky_hleda20" type="radio" checked name="pozadavky_hleda2" value="----" <?php if (@$pole['pozadavky_hleda2'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda21" type="radio" name="pozadavky_hleda2" value="vedlejší pracovní poměr" <?php if (@$pole['pozadavky_hleda2'] == 'vedlejší pracovní poměr') {echo 'checked';} ?>>vedlejší pracovní poměr<br>
  <input ID="pozadavky_hleda30" type="radio" checked name="pozadavky_hleda3" value="----" <?php if (@$pole['pozadavky_hleda3'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda31" type="radio" name="pozadavky_hleda3" value="brigáda" <?php if (@$pole['pozadavky_hleda3'] == 'brigáda') {echo 'checked';} ?>>brigáda<br>
  <input ID="pozadavky_hleda40" type="radio" checked name="pozadavky_hleda4" value="----" <?php if (@$pole['pozadavky_hleda4'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda41" type="radio" name="pozadavky_hleda4" value="zástup za pracovníka na mateřské dovolené" <?php if (@$pole['pozadavky_hleda4'] == 'zástup za pracovníka na mateřské dovolené') {echo 'checked';} ?>>zástup za pracovníka na mateřské dovolené<br>
  <input ID="pozadavky_hleda50" type="radio" checked name="pozadavky_hleda5" value="----" <?php if (@$pole['pozadavky_hleda5'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda51" type="radio" name="pozadavky_hleda5" value="absolventská místa" <?php if (@$pole['pozadavky_hleda5'] == 'absolventská místa') {echo 'checked';} ?>>absolventská místa<br>
  <input ID="pozadavky_hleda60" type="radio" checked name="pozadavky_hleda6" value="----" <?php if (@$pole['pozadavky_hleda6'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda61" type="radio" name="pozadavky_hleda6" value="práce na živnostenský list" <?php if (@$pole['pozadavky_hleda6'] == 'práce na živnostenský list') {echo 'checked';} ?>>práce na živnostenský list<br>
  <input ID="pozadavky_hleda70" type="radio" checked name="pozadavky_hleda7" value="----" <?php if (@$pole['pozadavky_hleda7'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda71" type="radio" name="pozadavky_hleda7" value="jednosměnný provoz" <?php if (@$pole['pozadavky_hleda7'] == 'jednosměnný provoz') {echo 'checked';} ?>>jednosměnný provoz<br>
  <input ID="pozadavky_hleda80" type="radio" checked name="pozadavky_hleda8" value="----" <?php if (@$pole['pozadavky_hleda8'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda81" type="radio" name="pozadavky_hleda8" value="dvousměnný provoz" <?php if (@$pole['pozadavky_hleda8'] == 'dvousměnný provoz') {echo 'checked';} ?>>dvousměnný provoz<br>
  <input ID="pozadavky_hleda90" type="radio" checked name="pozadavky_hleda9" value="----" <?php if (@$pole['pozadavky_hleda9'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda91" type="radio" name="pozadavky_hleda9" value="třísměnný provoz" <?php if (@$pole['pozadavky_hleda9'] == 'třísměnný provoz') {echo 'checked';} ?>>třísměnný provoz<br>
  <input ID="pozadavky_hleda100" type="radio" checked name="pozadavky_hleda10" value="----" <?php if (@$pole['pozadavky_hleda10'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda101" type="radio" name="pozadavky_hleda10" value="pružná pracovní doba" <?php if (@$pole['pozadavky_hleda10'] == 'pružná pracovní doba') {echo 'checked';} ?>>pružná pracovní doba<br>
  <input ID="pozadavky_hleda110" type="radio" checked name="pozadavky_hleda11" value="----" <?php if (@$pole['pozadavky_hleda11'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda111" type="radio" name="pozadavky_hleda11" value="dlouhý/krátký týden" <?php if (@$pole['pozadavky_hleda11'] == 'dlouhý/krátký týden') {echo 'checked';} ?>>dlouhý/krátký týden<br>
  <input ID="pozadavky_hleda120" type="radio" checked name="pozadavky_hleda12" value="----" <?php if (@$pole['pozadavky_hleda12'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda121" type="radio" name="pozadavky_hleda12" value="zkrácená pracovní doba 6" <?php if (@$pole['pozadavky_hleda12'] == 'zkrácená pracovní doba 6') {echo 'checked';} ?>>zkrácená pracovní doba na 6 hodin denně<br>
  <input ID="pozadavky_hleda130" type="radio" checked name="pozadavky_hleda13" value="----" <?php if (@$pole['pozadavky_hleda13'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_hleda131" type="radio" name="pozadavky_hleda13" value="zkrácená pracovní doba 4" <?php if (@$pole['pozadavky_hleda13'] == 'zkrácená pracovní doba 4') {echo 'checked';} ?>>zkrácená pracovní doba na 4 hodiny denně<br>

      <td>
  <input ID="pozadavky_odmita10" type="radio" name="pozadavky_odmita1" value="----" <?php if (@$pole['pozadavky_odmita1'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita11" type="radio" name="pozadavky_odmita1" value="hlavní pracovní poměr" <?php if (@$pole['pozadavky_odmita1'] == 'hlavní pracovní poměr') {echo 'checked';} ?>>hlavní pracovní poměr<br>
  <input ID="pozadavky_odmita20" type="radio" checked name="pozadavky_odmita2" value="----" <?php if (@$pole['pozadavky_odmita2'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita21" type="radio" name="pozadavky_odmita2" value="vedlejší pracovní poměr" <?php if (@$pole['pozadavky_odmita2'] == 'vedlejší pracovní poměr') {echo 'checked';} ?>>vedlejší pracovní poměr<br>
  <input ID="pozadavky_odmita30" type="radio" checked name="pozadavky_odmita3" value="----" <?php if (@$pole['pozadavky_odmita3'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita31" type="radio" name="pozadavky_odmita3" value="brigáda" <?php if (@$pole['pozadavky_odmita3'] == 'brigáda') {echo 'checked';} ?>>brigáda<br>
  <input ID="pozadavky_odmita40" type="radio" checked name="pozadavky_odmita4" value="----" <?php if (@$pole['pozadavky_odmita4'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita41" type="radio" name="pozadavky_odmita4" value="zástup za pracovníka na mateřské dovolené" <?php if (@$pole['pozadavky_odmita4'] == 'zástup za pracovníka na mateřské dovolené') {echo 'checked';} ?>>zástup za pracovníka na mateřské dovolené<br>
  <input ID="pozadavky_odmita50" type="radio" checked name="pozadavky_odmita5" value="----" <?php if (@$pole['pozadavky_odmita5'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita51" type="radio" name="pozadavky_odmita5" value="absolventská místa" <?php if (@$pole['pozadavky_odmita5'] == 'absolventská místa') {echo 'checked';} ?>>absolventská místa<br>
  <input ID="pozadavky_odmita60" type="radio" checked name="pozadavky_odmita6" value="----" <?php if (@$pole['pozadavky_odmita6'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita61" type="radio" name="pozadavky_odmita6" value="práce na živnostenský list" <?php if (@$pole['pozadavky_odmita6'] == 'práce na živnostenský list') {echo 'checked';} ?>>práce na živnostenský list<br>
  <input ID="pozadavky_odmita70" type="radio" checked name="pozadavky_odmita7" value="----" <?php if (@$pole['pozadavky_odmita7'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita71" type="radio" name="pozadavky_odmita7" value="jednosměnný provoz" <?php if (@$pole['pozadavky_odmita7'] == 'jednosměnný provoz') {echo 'checked';} ?>>jednosměnný provoz<br>
  <input ID="pozadavky_odmita80" type="radio" checked name="pozadavky_odmita8" value="----" <?php if (@$pole['pozadavky_odmita8'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita81" type="radio" name="pozadavky_odmita8" value="dvousměnný provoz" <?php if (@$pole['pozadavky_odmita8'] == 'dvousměnný provoz') {echo 'checked';} ?>>dvousměnný provoz<br>
  <input ID="pozadavky_odmita90" type="radio" checked name="pozadavky_odmita9" value="----" <?php if (@$pole['pozadavky_odmita9'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita91" type="radio" name="pozadavky_odmita9" value="třísměnný provoz" <?php if (@$pole['pozadavky_odmita9'] == 'třísměnný provoz') {echo 'checked';} ?>>třísměnný provoz<br>
  <input ID="pozadavky_odmita100" type="radio" checked name="pozadavky_odmita10" value="----" <?php if (@$pole['pozadavky_odmita10'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita101" type="radio" name="pozadavky_odmita10" value="pružná pracovní doba" <?php if (@$pole['pozadavky_odmita10'] == 'pružná pracovní doba') {echo 'checked';} ?>>pružná pracovní doba<br>
  <input ID="pozadavky_odmita110" type="radio" checked name="pozadavky_odmita11" value="----" <?php if (@$pole['pozadavky_odmita11'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita111" type="radio" name="pozadavky_odmita11" value="dlouhý/krátký týden" <?php if (@$pole['pozadavky_odmita11'] == 'dlouhý/krátký týden') {echo 'checked';} ?>>dlouhý/krátký týden<br>
  <input ID="pozadavky_odmita120" type="radio" checked name="pozadavky_odmita12" value="----" <?php if (@$pole['pozadavky_odmita12'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita121" type="radio" name="pozadavky_odmita12" value="zkrácená pracovní doba 6" <?php if (@$pole['pozadavky_odmita12'] == 'zkrácená pracovní doba 6') {echo 'checked';} ?>>zkrácená pracovní doba na 6 hodin denně<br>
  <input ID="pozadavky_odmita130" type="radio" checked name="pozadavky_odmita13" value="----" <?php if (@$pole['pozadavky_odmita13'] != 'Ano') {echo 'checked';} ?>> ne
  <input ID="pozadavky_odmita131" type="radio" name="pozadavky_odmita13" value="zkrácená pracovní doba 4" <?php if (@$pole['pozadavky_odmita13'] == 'zkrácená pracovní doba 4') {echo 'checked';} ?>>zkrácená pracovní doba na 4 hodiny denně<br>
      </td>
    </tr>
  </tbody>
</table>
 </FIELDSET>
  Kdy chcete nastoupit do nového zaměstnání? <input ID="pozadavky_nastup" type="text" name="pozadavky_nastup" size="9" maxlength="10" value="<?php echo @$pole['pozadavky_nastup'];?>"><br>
  Platové požadavky: <input ID="pozadavky_plat" type="text" name="pozadavky_plat" size="6" maxlength="7" value="<?php echo @$pole['pozadavky_plat'];?>"> [Kč/měsíc]<br>
  Specifické požadavky účastníka:<br>
  <textarea ID="pozadavky_prace" rows="4" name="pozadavky_prace" cols="60"><?php echo @$pole['pozadavky_prace'];?></textarea>
</FIELDSET>
<FIELDSET style="width:;"><LEGEND><b>Doplňující údaje o účastníkovi projektu</b></LEGEND>
<p>Péče o závislé osoby: <input ID="pece_o_zav_osoby" type="text" name="pece_o_zav_osoby" size="40" maxlength="80" value="<?php echo @$pole['pece_o_zav_osoby'];?>"><br>
Zdravotní stav: <input ID="zdrav_stav" type="text" name="zdrav_stav" size="40" maxlength="80" value="<?php echo @$pole['zdrav_stav'];?>"><br>
Změněná pracovní schopnost:<br>
  <input ID="ZPS0" type="radio" checked name="ZPS" value="Ne" <?php if (@$pole['ZPS'] != 'Ano') {echo 'checked';} ?>> Ne<br>
  <input ID="ZPS1" type="radio" value="Ano" name="ZPS" <?php if (@$pole['ZPS'] == 'Ano') {echo 'checked';} ?>> Ano<br>
                                                                                                                    
Zdravotní znevýhodnění:
          <select ID="zdravotni_znevyhodneni" size="1" name="zdravotni_znevyhodneni">
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == '------------'){echo 'selected';} ?>>------------</option>
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == 'částečná invalidita'){echo 'selected';} ?>>částečná invalidita</option>
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == 'úplná invalidita'){echo 'selected';} ?>>úplná invalidita</option>
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == 'invalidita prvního stupně'){echo 'selected';} ?>>invalidita prvního stupně</option>
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == 'invalidita druhého stupně'){echo 'selected';} ?>>invalidita druhého stupně</option>
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == 'invalidita třetího stupně'){echo 'selected';} ?>>invalidita třetího stupně</option>
          <option <?php if (@$pole['zdravotni_znevyhodneni'] == 'zdravotní znevýhodnění dle rozhodnutí ÚP'){echo 'selected';} ?>>zdravotní znevýhodnění dle rozhodnutí ÚP</option>
          </select>                                                                                                                    
                                                                                                                    
                                                                                                                    
<br>
<p>Jak dlouho jste v evideci úřadu práce jako nezaměstnaný/á (číslo v měsících):
    <input ID="doba_evidence" type="text" name="doba_evidence" size="3" maxlength="3" value="<?php echo @$pole['doba_evidence'];?>"><br>
<p>Pokolikáté jste v evideci úřadu práce jako nezaměstnaný/á (číslo):
    <input ID="kolikrat_ev" type="text" name="kolikrat_ev" size="2" maxlength="2" value="<?php echo @$pole['kolikrat_ev'];?>"><br>

</FIELDSET>

<FIELDSET><LEGEND><b>Prostředky přímé podpory</b></LEGEND>
<p>Účastník požaduje vyplácet prostředky přímé podpory v hotovosti v kontaktní kanceláři. 
<input ID="prostredky_p_p1" type="radio" name="prostredky_p_p" value="ano" <?php if (@$pole['prostredky_p_p'] == 'ano') {echo 'checked';} ?>>ANO
<input ID="prostredky_p_p2" type="radio" name="prostredky_p_p" value="ne" <?php if (@$pole['prostredky_p_p'] != 'ano') {echo 'checked';} ?>> NE </p>
<p style="padding-left:45px;">
<table style="text-align: left; height: 32px; width: 262px;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td
 style="width: 77px; background-color: rgb(204, 0, 0); font-weight: bold;">předč&iacute;sl&iacute;*</td>
      <td style="width: 6px;">-</td>
      <td
 style="background-color: rgb(204, 0, 0); font-weight: bold; width: 78px;">č&iacute;slo</td>
      <td style="width: 0px;">/</td>
      <td
 style="background-color: rgb(204, 0, 0); font-weight: bold; width: 86px;">k&oacute;d
banky</td>
    </tr>
  </tbody>
</table>
* - Pokud číslo účtu neobsahuje předčíslí, pak jej nevyplňujte!
</p>
<p>Číslo účtu: <input id="predcisli" maxlength="6" size="6" name="predcisli" value="<?php echo @$pole['predcisli'];?>"> -&nbsp;<input id="cislo" maxlength="10" size="10" name="cislo" value="<?php echo @$pole['cislo'];?>"> /&nbsp;<!--<input id="kod" maxlength="4" size="4" name="kod" value="<?php echo @$pole['banka'];?>">-->
<select size="1" name="banka">
<option <?php if (@$pole['banka'] == '-------------'){echo 'selected';} ?>>-------------</option> 
<option <?php if (@$pole['banka'] == '0100 | Komerční banka, a.s.'){echo 'selected';} ?>>0100 | Komerční banka, a.s.</option> 
<option <?php if (@$pole['banka'] == '0300 | Československá obchodní banka, a.s.'){echo 'selected';} ?>>0300 | Československá obchodní banka, a.s.</option> 
<option <?php if (@$pole['banka'] == '0600 | GE Money Bank, a.s.'){echo 'selected';} ?>>0600 | GE Money Bank, a.s.</option> 
<option <?php if (@$pole['banka'] == '0710 | Česká národní banka'){echo 'selected';} ?>>0710 | Česká národní banka</option> 
<option <?php if (@$pole['banka'] == '0800 | Česká spořitelna, a.s.'){echo 'selected';} ?>>0800 | Česká spořitelna, a.s.</option> 
<option <?php if (@$pole['banka'] == '2010 | Fio, družstevní záložna'){echo 'selected';} ?>>2010 | Fio, družstevní záložna</option> 
<option <?php if (@$pole['banka'] == '2020 | Bank of Tokyo-Mitsubishi UFJ (Holland) N.V. Prague Branch, organizační složka'){echo 'selected';} ?>>2020 | Bank of Tokyo-Mitsubishi UFJ (Holland) N.V. Prague Branch, organizační složka</option> 
<option <?php if (@$pole['banka'] == '2030 | AKCENTA, spořitelní a úvěrní družstvo'){echo 'selected';} ?>>2030 | AKCENTA, spořitelní a úvěrní družstvo</option> 
<option <?php if (@$pole['banka'] == '2040 | UNIBON – spořitelní a úvěrní družstvo'){echo 'selected';} ?>>2040 | UNIBON – spořitelní a úvěrní družstvo</option> 
<option <?php if (@$pole['banka'] == '2050 | WPB Capital, spořitelní družstvo'){echo 'selected';} ?>>2050 | WPB Capital, spořitelní družstvo</option> 
<option <?php if (@$pole['banka'] == '2060 | Prague Credit Union, spořitelní družstvo'){echo 'selected';} ?>>2060 | Prague Credit Union, spořitelní družstvo</option> 
<option <?php if (@$pole['banka'] == '2070 | Moravský Peněžní Ústav – spořitelní družstvo'){echo 'selected';} ?>>2070 | Moravský Peněžní Ústav – spořitelní družstvo</option> 
<option <?php if (@$pole['banka'] == '2100 | Hypoteční banka, a.s.'){echo 'selected';} ?>>2100 | Hypoteční banka, a.s.</option> 
<option <?php if (@$pole['banka'] == '2200 | Peněžní dům, spořitelní družstvo'){echo 'selected';} ?>>2200 | Peněžní dům, spořitelní družstvo</option> 
<option <?php if (@$pole['banka'] == '2210 | Banka mezinárodní spolupráce, a.s.'){echo 'selected';} ?>>2210 | Banka mezinárodní spolupráce, a.s.</option> 
<option <?php if (@$pole['banka'] == '2600 | Citibank Europe plc, organizační složka'){echo 'selected';} ?>>2600 | Citibank Europe plc, organizační složka</option> 
<option <?php if (@$pole['banka'] == '2700 | UniCredit Bank Czech Republic, a.s.'){echo 'selected';} ?>>2700 | UniCredit Bank Czech Republic, a.s.</option> 
<option <?php if (@$pole['banka'] == '3500 | ING Bank N.V.'){echo 'selected';} ?>>3500 | ING Bank N.V.</option> 
<option <?php if (@$pole['banka'] == '4000 | LBBW Bank CZ a.s.'){echo 'selected';} ?>>4000 | LBBW Bank CZ a.s.</option> 
<option <?php if (@$pole['banka'] == '4300 | Českomoravská záruční a rozvojová banka, a.s.'){echo 'selected';} ?>>4300 | Českomoravská záruční a rozvojová banka, a.s.</option> 
<option <?php if (@$pole['banka'] == '5000 | CALYON S.A., organizační složka'){echo 'selected';} ?>>5000 | CALYON S.A., organizační složka</option> 
<option <?php if (@$pole['banka'] == '5400 | ABN AMRO Bank N.V.'){echo 'selected';} ?>>5400 | ABN AMRO Bank N.V.</option> 
<option <?php if (@$pole['banka'] == '5500 | Raiffeisenbank a.s.'){echo 'selected';} ?>>5500 | Raiffeisenbank a.s.</option> 
<option <?php if (@$pole['banka'] == '5800 | J & T Banka, a.s.'){echo 'selected';} ?>>5800 | J & T Banka, a.s.</option> 
<option <?php if (@$pole['banka'] == '6000 | PPF banka a.s.'){echo 'selected';} ?>>6000 | PPF banka a.s.</option> 
<option <?php if (@$pole['banka'] == '6100 | Banco Popolare Česká republika, a.s.'){echo 'selected';} ?>>6100 | Banco Popolare Česká republika, a.s.</option> 
<option <?php if (@$pole['banka'] == '6200 | COMMERZBANK Aktiengesellschaft, pobočka Praha'){echo 'selected';} ?>>6200 | COMMERZBANK Aktiengesellschaft, pobočka Praha</option> 
<option <?php if (@$pole['banka'] == '6210 | BRE Bank S.A., organizační složka podniku'){echo 'selected';} ?>>6210 | BRE Bank S.A., organizační složka podniku</option> 
<option <?php if (@$pole['banka'] == '6300 | Fortis Bank SA/NV, pobočka ČR'){echo 'selected';} ?>>6300 | Fortis Bank SA/NV, pobočka ČR</option> 
<option <?php if (@$pole['banka'] == '6700 | Všeobecná úvěrová banka a.s., pobočka Praha'){echo 'selected';} ?>>6700 | Všeobecná úvěrová banka a.s., pobočka Praha</option> 
<option <?php if (@$pole['banka'] == '6800 | Volksbank CZ, a.s.'){echo 'selected';} ?>>6800 | Volksbank CZ, a.s.</option> 
<option <?php if (@$pole['banka'] == '7910 | Deutsche Bank A.G. Filiale Prag'){echo 'selected';} ?>>7910 | Deutsche Bank A.G. Filiale Prag</option> 
<option <?php if (@$pole['banka'] == '7940 | Waldviertler Sparkasse von 1842 AG'){echo 'selected';} ?>>7940 | Waldviertler Sparkasse von 1842 AG</option> 
<option <?php if (@$pole['banka'] == '7950 | Raiffeisen stavební spořitelna a.s.'){echo 'selected';} ?>>7950 | Raiffeisen stavební spořitelna a.s.</option> 
<option <?php if (@$pole['banka'] == '7960 | Českomoravská stavební spořitelna, a.s.'){echo 'selected';} ?>>7960 | Českomoravská stavební spořitelna, a.s.</option> 
<option <?php if (@$pole['banka'] == '7970 | Wüstenrot-stavební spořitelna a.s.'){echo 'selected';} ?>>7970 | Wüstenrot-stavební spořitelna a.s.</option> 
<option <?php if (@$pole['banka'] == '7980 | Wüstenrot hypoteční banka a.s.'){echo 'selected';} ?>>7980 | Wüstenrot hypoteční banka a.s.</option> 
<option <?php if (@$pole['banka'] == '7990 | Modrá pyramida stavební spořitelna, a.s.'){echo 'selected';} ?>>7990 | Modrá pyramida stavební spořitelna, a.s.</option> 
<option <?php if (@$pole['banka'] == '8030 | Raiffeisenbank im Stiftland eG pobočka Cheb, odštěpný závod'){echo 'selected';} ?>>8030 | Raiffeisenbank im Stiftland eG pobočka Cheb, odštěpný závod</option> 
<option <?php if (@$pole['banka'] == '8040 | Oberbank AG pobočka Česká republika'){echo 'selected';} ?>>8040 | Oberbank AG pobočka Česká republika</option> 
<option <?php if (@$pole['banka'] == '8060 | Stavební spořitelna České spořitelny, a.s.'){echo 'selected';} ?>>8060 | Stavební spořitelna České spořitelny, a.s.</option> 
<option <?php if (@$pole['banka'] == '8090 | Česká exportní banka, a.s.'){echo 'selected';} ?>>8090 | Česká exportní banka, a.s.</option> 
<option <?php if (@$pole['banka'] == '8150 | HSBC Bank plc - pobočka Praha'){echo 'selected';} ?>>8150 | HSBC Bank plc - pobočka Praha</option> 


</select>

</p>

</FIELDSET>

</DIV>  <!-- konec div  v kterem je neviditelna caast formulare-->

<p>Datum vytvoření:
<input ID="datum_vytvor_dok" type="text" name="datum_vytvor_dok" size="8" maxlength="10" value="<?php
                                        if (@$pole['datum_vytvor_dok']) {echo @$pole['datum_vytvor_dok'];}
                                        else {echo date("d.m.Y"); }
                                        ?>">
</p>

<p><input type="submit" value="Uložit" name="B1">&nbsp;&nbsp;&nbsp;
<input type="reset" value="Zruš provedené změny" name="B2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<!-- <input type="submit" value="Vytiskni Smlouvu" name="B1">
-->
<?php
//    if ( isset($_GET['id_ucastnik']) ) {
   if ($id_ucastnik){
    echo ('<p><input type="submit" value="Tisk" name="T1">&nbsp;&nbsp;&nbsp;</p> ');
    }    
?>


  </form>
  <ul id="menu">
     <li><a href="index.php?akce=zobraz_reg">Zpět na výběr účastníka</a></li>
  </ul>  

