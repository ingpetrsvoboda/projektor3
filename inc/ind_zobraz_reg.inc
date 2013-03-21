<?php
//ind_zobraz_reg.inc.php




if(isset($_POST['beh'])) {
	$beh_id = $_POST['beh'];
}
elseif(isset($_COOKIE['beh_id'])){
	$beh_id = $_COOKIE['beh_id'];
}


//echo "<BR>" ; print_r($Beh);//pozn. dne 100406 tady neni nastaven 

if ( ($User->username == "sys_admin" ))  {
        echo "<BR>a v ind_zobraz_reg";
        echo "<BR>a Projekt->id:" . $Projekt->id;
        echo "<BR>a beh_id:" . $beh_id;
        echo "<BR>a Kancelar->id:" . $Kancelar->id;
        print_r($Kancelar);
        echo "<BR>a User:" . $User->id;
        print_r($User);
        echo "<hr>";
}



if ( ($User->username == "sys_admin" ))       //    and ($Kancelar->kod == "CE" or $Kancelar->kod == "TST"  ) )
{        
?>

<ul id="menu">
        <li><a href="index.php?akce=zobraz_reg_vahy">Vypočti váhy = v behu ve všech kancelářích (nemačkat bez rozmyslu, nastav si limity)</a></li>
</ul>        


<?php

}


//Nastaveni behu
setcookie("beh_id",$beh_id);
//Vyber osob z databaze
$query="SELECT beh_cislo,text,zacatek,konec
        FROM s_beh_projektu
        WHERE id_c_projekt = :1
        AND id_s_beh_projektu = :2";
$data=$dbh->prepare($query)->execute($Projekt->id,$beh_id)->fetch_assoc();


//echo "<br>data:". "<br>";
//print_r($data);
//echo "<br>";


echo "<li><h3>Zobrazeni registrací: ".$data['text'];
$datum = new Projektor_Data_Konverze_Datum($data['zacatek'],"MySQL");
echo " (od ".$datum->f_web;
if( $data['konec'] !=NULL) {
	$datum = new Projektor_Data_Konverze_Datum($data['konec'],"MySQL");
	echo " do ".$datum->f_web;
}
echo ")</h3></li>";
//if(strlen($data['text'])>2) {
//	echo " - ".$data['text'];
//}


//$kolize_podle_nazvu = Projektor_Table_UcKolizeData::Vyhodnot_kolizi("ZamDatum",111);


?>
<table>
	<tr>
		<td>
		<ul id="menu">
			<hr>
			<li><a href="index.php?akce=sml_uc">Nová osoba</a></li>
			<li><a href="index.php?akce=unset_beh">Zpět na výběr turnusu</a></li>
		</ul>
		</td>
		<td>
		<ul id="menu">
			<hr>
			<li><a href="index.php?akce=zobraz_reg_export">Exportuj seznam</a></li>
		</ul>
		</td>
	</tr>
</table>
<div ID="zaznamy"><?php



//precist tabulku povolenych odkazu pro daneho uzivatele=koordinatora

//Zobrazení zaregistrovaných osob
/*        $query = "SELECT id_ucastnik,jmeno_cele,identifikator
 FROM v_ucastnici
 WHERE id_s_beh_projektu = :1
 AND id_c_kancelar=:2";
 */

$query = "select `ucastnik`.`id_ucastnik` AS `id_ucastnik`,
				concat_ws(' ',`uc_flat_table`.`prijmeni`,`uc_flat_table`.`jmeno`,`uc_flat_table`.`titul`,`uc_flat_table`.`titul_za`) AS `jmeno_cele`,
				`ucastnik`.`identifikator` AS `identifikator`,
				`uc_flat_table`.`datum_reg` AS `datum_reg`,
				concat(`uc_flat_table`.`vzdelani1`,`uc_flat_table`.`vzdelani2`,`uc_flat_table`.`vzdelani3`,`uc_flat_table`.`vzdelani4`,`uc_flat_table`.`vzdelani5`) AS `vyplneno_vzdelani`,
				concat(`uc_plan_flat_table`.`zztp_text`,`uc_plan_flat_table`.`kom_text`) AS `vyplneny_kurzy`,
                                
				`uc_ukonc_flat_table`.`duvod_ukonceni` AS `duvod_ukonceni`,
				`uc_testpc_flat_table`.`testpc_datum2` AS `testpc_datum2`,
                                `uc_zam_flat_table`.`zam_nazev` AS `zam_nazev`,
                                `uc_zam_flat_table`.`zam_datum_vstupu` AS `zam_datum_vstupu`,
                                `uc_zam_flat_table`.`zam_ic` AS `zam_ic`,
                                
                                `uc_doporucenirk_flat_table`. `doporuceni_na_doplneni_1` AS `doporuceni_na_doplneni_1`,
                                `uc_doporucenirk_flat_table`. `doporuceni_na_doplneni_2` AS `doporuceni_na_doplneni_2`,
                                `uc_doporucenirk_flat_table`. `doporuceni_na_doplneni_3` AS `doporuceni_na_doplneni_3`,
                                
                                concat(`uc_doporucenirk_flat_table`.`nazev_RK_1`,`uc_doporucenirk_flat_table`.`nazev_RK_2`,`uc_doporucenirk_flat_table`.`nazev_RK_3`) AS `vyplneny_kurzyRK`,
                                
                                ucastnik_vyrazen_z_RK as ucastnik_vyrazen_z_RK
                                
                                
			from(
					(
						(
							(`ucastnik` join `uc_flat_table` on((`ucastnik`.`id_ucastnik` = `uc_flat_table`.`id_ucastnik`))) 
						left join `uc_plan_flat_table` on((`ucastnik`.`id_ucastnik` = `uc_plan_flat_table`.`id_ucastnik`))
						) 
					left join `uc_ukonc_flat_table` on((`ucastnik`.`id_ucastnik` = `uc_ukonc_flat_table`.`id_ucastnik`))
					)
				left join `uc_testpc_flat_table` on((`ucastnik`.`id_ucastnik` = `uc_testpc_flat_table`.`id_ucastnik`))
                                
                                left join `uc_zam_flat_table` on((`ucastnik`.`id_ucastnik` = `uc_zam_flat_table`.`id_ucastnik`))
                                left join `uc_doporucenirk_flat_table` on((`ucastnik`.`id_ucastnik` = `uc_doporucenirk_flat_table`.`id_ucastnik`))
                                
                                
			    )
        	WHERE `ucastnik`.`id_s_beh_projektu_FK` = :1
                   AND `ucastnik`.`id_c_kancelar_FK` = :2";

//        concat(`uc_flat_table`.`prijmeni`,' ',`uc_flat_table`.`jmeno`,if(isnull(`uc_flat_table`.`titul`),'',concat(' , ',`uc_flat_table`.`titul`)),if(isnull(`uc_flat_table`.`titul_za`),'',concat(', ',`uc_flat_table`.`titul_za`))) AS `nazev`



$data = $dbh->prepare($query)->execute($beh_id,$Kancelar->id);
echo '<table>';
while ($zaznam = $data->fetch_assoc()) {
        echo '<tr>';
	echo "<td class=identifikator>" . $zaznam['identifikator'] . "</td>";
	echo "<td class=jmeno>" . $zaznam['jmeno_cele']."</td>";
	
	
        //smlouva
        if ( ( ($Projekt->kod == "SPZP") and $User->tl_spzp_sml ) or
             ( ($Projekt->kod == "RNH") and $User->tl_rnh_sml )
           ) {
                echo "<td class='editace'><a title='editace' href=\"index.php?akce=sml_uc&id_ucastnik=".
                	$zaznam['id_ucastnik']."\">"."Smlouva</a></td>";
        }
	
        //registrační dotazník
        if ( ( ($Projekt->kod == "SPZP") and  $User->tl_spzp_dot) or
             ( ($Projekt->kod == "RNH") and  $User->tl_rnh_dot )
           ) {
        	if (isset($zaznam['vyplneno_vzdelani']) AND substr_count($zaznam['vyplneno_vzdelani'], "-") <> strlen($zaznam['vyplneno_vzdelani'])) 
        	{
                        //KOLIZE
                        if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_REG_DOT)  and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                             {echo "<td class='editace_kolize'><a title='editace - opravte chyby' "; }
                        else {echo "<td class='editace'><a title='editace' "; }
                }
        	else
        	{
                       //KOLIZE
                       if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_REG_DOT)  and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                            {echo "<td class='editace_kolize'><a title='editace - opravte chyby' ";}
                       else {echo "<td class='novy'><a title='nový' ";}
                }
	
        	echo " href=\"index.php?akce=zobraz_uc&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."Dotazník"."</a></td>";
        }
	
	
        //plán
        //include_once (INC_PATH."ind_plan_uc_kolize.php");
        //$kolize_ukonceni = fce_kolize_ukonceni_predukoncenimvsechkurzu ($zaznam['id_ucastnik']);
        
               
        if ( ( ($Projekt->kod == "SPZP") and $User->tl_spzp_plan) or
             ( ($Projekt->kod == "RNH") and  $User->tl_rnh_plan)
           ) {
                if (isset($zaznam['vyplneny_kurzy']) AND substr_count($zaznam['vyplneny_kurzy'], "-") <> strlen($zaznam['vyplneny_kurzy'])) 
                {                               
                        //KOLIZE
                        //var_dump();
                        if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_PLAN)
                            and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                             {echo "<td class='editace_kolize'><a title='editace - opravte chyby' "; }
                        else {echo "<td class='editace'><a title='editace' "; }
                        
                }
                else {                    
                       //KOLIZE
                       if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_PLAN)and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                            {echo "<td class='editace_kolize'><a title='editace - opravte chyby' ";}
                       else {echo "<td class='novy'><a title='nový' ";}
                }
                
                echo " href=\"index.php?akce=ind_plan_uc&id_ucastnik=".
                	$zaznam['id_ucastnik']."\">"."Plán kurzů"."</a>";
                   
                //if ($kolize_ukonceni->hodnota) {echo "!!!";
                //}     

                
                echo "</td>";
        }
	
          
       
         //doporuceni RK
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_dopRK ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_dopRK )
           ){ 
                if (isset($zaznam['vyplneny_kurzyRK'])  AND substr_count($zaznam['vyplneny_kurzyRK'], "-") <> strlen($zaznam['vyplneny_kurzyRK'])    ) 
        	{	echo "<td class='editace'><a title='editace' ";		}
        	else
        	{	echo "<td class='novy'><a title='nový' ";		}
	
        	echo " href=\"index.php?akce=doprk_uc&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."Doporučení RK"."</a></td>";
        }
        
        
         //doporuceni RK - doplneni1
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_dopRKdoplneni1 ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_dopRKdoplneni1 )
           ){ 
                if (isset($zaznam['doporuceni_na_doplneni_1']) )    
        	{	echo "<td class='editace'><a title='editace' ";		}
        	else
        	{	echo "<td class='novy'><a title='nový' ";		}
	
        	echo " href=\"index.php?akce=doprk_dopl1&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."DoplněníRK1"."</a></td>";
        }
         //doporuceni RK - doplneni2
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_dopRKdoplneni2 ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_dopRKdoplneni2 )
           ){ 
                if (isset($zaznam['doporuceni_na_doplneni_2']) )    
        	{	echo "<td class='editace'><a title='editace' ";		}
        	else
        	{	echo "<td class='novy'><a title='nový' ";		}
	
        	echo " href=\"index.php?akce=doprk_dopl2&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."DoplněníRK2"."</a></td>";
        }
         //doporuceni RK - doplneni3
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_dopRKdoplneni3 ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_dopRKdoplneni3 )
           ){ 
                if (isset($zaznam['doporuceni_na_doplneni_3']) )    
        	{	echo "<td class='editace'><a title='editace' ";		}
        	else
        	{	echo "<td class='novy'><a title='nový' ";		}
	
        	echo " href=\"index.php?akce=doprk_dopl3&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."DoplněníRK3"."</a></td>";
        }
        
        
         //doporuceni RK - vyrazeni z RK
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_dopRKvyrazeni ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_dopRKvyrazeni)
           ){ 
                if ( (isset($zaznam['ucastnik_vyrazen_z_RK']) )  and    $zaznam['ucastnik_vyrazen_z_RK'] != 'ne')
        	{	echo "<td class='editace'><a title='editace' ";		}
        	else
        	{	echo "<td class='novy'><a title='nový' ";		}
	
        	echo " href=\"index.php?akce=doprk_vyraz&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."Vyřazení z RK"."</a></td>";
        }
        
                
        
	//ukončení
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_ukon ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_ukon )
           ){
                if (isset($zaznam['duvod_ukonceni']) AND substr_count($zaznam['duvod_ukonceni'], "-") <> strlen($zaznam['duvod_ukonceni'])) 
        	{	//echo "<td class='editace'><a title='editace' ";
                   if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_UKONC) and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                             {echo "<td class='editace_kolize'><a title='editace - opravte chyby' "; }
                   else {echo "<td class='editace'><a title='editace' "; }
                }
        	else
        	{	//echo "<td class='novy'><a title='nový' ";
                   if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_UKONC) and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                            {echo "<td class='editace_kolize'><a title='editace - opravte chyby' ";}
                   else {echo "<td class='novy'><a title='nový' ";}     
                }
	
        	echo " href=\"index.php?akce=ukonceni_uc&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."Ukončení"."</a></td>";
        }
        
        
        //zamestnani
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_zam ) or
             (($Projekt->kod == "RNH") and  $User->tl_rnh_zam )
           ){ 
                if (isset($zaznam['zam_datum_vstupu']) AND isset($zaznam['zam_nazev']) AND  isset($zaznam['zam_ic']) ) 
        	{	//echo "<td class='editace'><a title='editace' ";
                   if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_ZAM) and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                             {echo "<td class='editace_kolize'><a title='editace - opravte chyby' "; }
                   else {echo "<td class='editace'><a title='editace' "; }
                }
        	else
        	{	//echo "<td class='novy'><a title='nový' ";
                   if (Projektor_Table_UcKolizeData::Najdi_kolize_pro_formular($zaznam['id_ucastnik'], FORMULAR_ZAM) and
                            Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych )
                            {echo "<td class='editace_kolize'><a title='editace - opravte chyby' ";}
                   else {echo "<td class='novy'><a title='nový' ";}
                        
                }
	
        	echo " href=\"index.php?akce=zam_uc&id_ucastnik=".
        		$zaznam['id_ucastnik']."\">"."Zaměstnání" . "</a></td>";
        }
        
	
	//PC test
        if ( (($Projekt->kod == "SPZP") and $User->tl_spzp_testpc) or
             (($Projekt->kod == "RNH") and $User->tl_rnh_testpc)   
           ) {
                if ($zaznam['testpc_datum2']) 
                {	echo "<td class='uzavreny'><span>Test PC uzavřen</span></td>";		}
        	else
        	{	echo "<td class='novy'><a title='nový' href=\"index.php?akce=testpc_uc&id_ucastnik=".
                                             $zaznam['id_ucastnik']."\">"."Test PC"."</a></td>";	}
					     
        }
        
        
	echo '</tr>';
}
echo '</table>';

if ($akce=="zobraz_reg_export") {
	//include "ind_exportuj_seznam.inc";
}



?></div>

<?php
if ($akce=="zobraz_reg_vahy") {
	include "ind_vypocti_vahy.inc";
}
?>


