<?php
  //define('FPDF_FONTPATH','/fpdf16/font/');
  //require('/fpdf16/fpdf.php');
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
	require_once("autoload.php");
  $pdfpole = $_POST;
 
  
  foreach($pdfpole  as $klic => $hodnota) {
      $pdfpole['$klic'] = trim($pdfpole['$klic']);
  }
  
  //echo "<br>Pdfpole v pdfdotaznik po trim<br>";   /*SEL*/
  //print_r($pdfpole);

//*  
    $pdfhlavicka = PDF_Kontext::dejHlavicku();
		$pdfhlavicka->text("Individuální plán účastníka - 1. část");
		$pdfhlavicka->zarovnani("C");
		$pdfhlavicka->vyskaPisma(14);
		$pdfhlavicka->obrazek("./PDF/loga_SPZP_vedlesebe_bw.jpg", null, null,167,14);
	$pdfpaticka = PDF_Kontext::dejPaticku();
		$pdfpaticka->text("S pomocí za prací - Individuální plán účastníka - 1. část  Účastník: ".$Ucastnik->identifikator);
		$pdfpaticka->zarovnani("C");
		$pdfpaticka->vyskaPisma(6);
		$pdfpaticka->cislovani = true;
		
    $titulek = new PDF_Odstavec;
		$titulek->Nadpis("Individuální plán účastníka - 1. část");
		$titulek->ZarovnaniNadpisu("C");
                $titulek->VyskaPismaNadpisu(12);
   
//		$titulek->Text('Lákamí vůněhulás úmyval rohlivý jednovod lek lák hane bývá přehliv smeti. Smělý Umyslemi dopicí sudba rojskočár ří bý autný tlínům z zavěď. Umí jít A hafan bý obal stako tak úmyvatkov Buben muto. ');

 
    $titulek1 = new PDF_Odstavec;
                $titulek1->Nadpis('„S pomocí za prací v Plzeňském kraji“');
                $titulek1->ZarovnaniNadpisu("C");
                $titulek1->VyskaPismaNadpisu(12);
               
 
    $osobniUdaje = new PDF_SadaBunek();
		$osobniUdaje->Nadpis("Osobní údaje");
                $osobniUdaje->VyskaPismaNadpisu(12);
                $osobniUdaje->PridejBunku("Datum vstupu do projektu: ", @$pdfpole["datum_reg"],0,80);
		$osobniUdaje->PridejBunku("Identifikátor účastníka: ", $Ucastnik->identifikator,1);
	          $celeJmeno =  @$pdfpole["titul"]." ".@$pdfpole["jmeno"]." ".@$pdfpole["prijmeni"];
	          if (@$pdfpole["titul_za"]) 
		  {
			$celeJmeno = $celeJmeno.", ".@$pdfpole["titul_za"];
		  }
		$osobniUdaje->PridejBunku("Jméno, příjmení, titul: ", $celeJmeno,1);
		$osobniUdaje->PridejBunku("Datum narození: ", @$pdfpole["datum_narozeni"],0,80);
                $osobniUdaje->PridejBunku("Rodné číslo: ", @$pdfpole["rodne_cislo"],1);
		$osobniUdaje->PridejBunku("Vysílající úřad práce: ", @$pdfpole["z_up"],0,80);
                $osobniUdaje->PridejBunku("Stav: ",@$pdfpole["stav"],1);
                if  (substr_count(@$pdfpole["zam_osvc_neaktivni"], "-") <> strlen(@$pdfpole["zam_osvc_neaktivni"]))
                {
                    $osobniUdaje->PridejBunku("Stav zájemce o zaměstnání: ",@$pdfpole["zam_osvc_neaktivni"],1);
                }
                //$osobniUdaje->PridejBunku("Stav zájemce: ",@$pdfpole["zam_osvc_neaktivni"],1,70,'-');
		$osobniUdaje->PridejBunku("Pracoviště vysílajícího úřadu práce: ", @$pdfpole["prac_up"],1);
 
    $bydliste = new PDF_SadaBunek();
                $bydliste->Nadpis("Bydiště a kontaktní údaje");
                $bydliste->VyskaPismaNadpisu(12);
                $adresapole="";
                if (@$pdfpole["ulice"]) {
                    $adresapole .=   @$pdfpole["ulice"];
                    if  (@$pdfpole["mesto"])  {  $adresapole .=  ", ".   @$pdfpole["mesto"];}
                    if  (@$pdfpole["psc"])    {  $adresapole .= ", " . @$pdfpole["psc"]; }
                }
                else {
                    if  (@$pdfpole["mesto"])  {
                        $adresapole .= @$pdfpole["mesto"];
                        if  (@$pdfpole["psc"])    {  $adresapole .= ", " . @$pdfpole["psc"]; }
                    }
                    else {
                         if  (@$pdfpole["psc"])  {$adresapole .=  @$pdfpole["psc"];}
                    } 
                }
                $bydliste->PridejBunku( "Adresa: ", $adresapole,1);
                //$bydliste->PridejBunku( "Adresa: ", @$pdfpole["mesto"] .", " . @$pdfpole["ulice"] . " " .@$pdfpole["psc"],1);
                $bydliste->PridejBunku ("Pevný telefon: ", @$pdfpole["pevny_telefon"],1);
                
           //if ( @$pdfpole["mesto2"] or  @$pdfpole["ulice2"] or @$pdfpole["psc2"] ) 
    $bydlistePrechodne = new PDF_SadaBunek();
                $adresapole="";
                if (@$pdfpole["ulice2"]) {
                    $adresapole .=   @$pdfpole["ulice2"];
                    if  (@$pdfpole["mesto2"])  {  $adresapole .= ", " . @$pdfpole["mesto2"];}
                    if  (@$pdfpole["psc2"])    {  $adresapole .= ", " . @$pdfpole["psc2"]; }
                }
                else {
                    if  (@$pdfpole["mesto2"])  {
                        $adresapole .= @$pdfpole["mesto2"];
                        if  (@$pdfpole["psc2"])   {  $adresapole .= ", " . @$pdfpole["psc2"]; }
                    }
                    else {
                         if  (@$pdfpole["psc2"])  {  $adresapole .= @$pdfpole["psc2"];}
                    } 
                }
                $bydlistePrechodne->PridejBunku( "Adresa dojíždění odlišná od místa bydliště: ", $adresapole,1); 
                //$bydlistePrechodne->PridejBunku("Přechodné bydliště: ", @$pdfpole["mesto2"] .", " . @$pdfpole["ulice2"] . " " .@$pdfpole["psc2"],1 );
                if ( @$pdfpole["pevny_telefon2"]) {
                  $bydlistePrechodne->PridejBunku("Pevný telefon: ", @$pdfpole["pevny_telefon2"],1);
                }
                //if ( @$pdfpole["mesto2"] or  @$pdfpole["ulice2"] or @$pdfpole["psc2"] or @$pdfpole["pevny_telefon2"])
                //     { $bydlistePrechodne->SpustSadu(true);}
    
    
    $kontakt = new PDF_SadaBunek();
                $kontakt->PridejBunku("Mobilní telefon: ", @$pdfpole["mobilni_telefon"],0,80);
                $kontakt->PridejBunku("e-mail: ", @$pdfpole["mail"],1);
                
    $kontakt_dalsi = new PDF_SadaBunek();                
                $kontakt_dalsi->PridejBunku("Další telefon: ", @$pdfpole["dalsi_telefon"]);
                $kontakt_dalsi->PridejBunku("Popis: ", @$pdfpole["popis_telefon"],1);
                //$kontakt_dalsi->NovyRadek();
    
               
    //$vzdelaniSkoly = new PDF_SadaBunek();
    //            $vzdelaniSkoly->Nadpis("Absolvované školy",1);
    $vzdelaniSkoly = new PDF_Odstavec;
		$vzdelaniSkoly->Nadpis("Absolvované školy");
		$vzdelaniSkoly->ZarovnaniNadpisu("L");
                $vzdelaniSkoly->VyskaPismaNadpisu(12);
                
    $vzdelaniSkolaI = new PDF_SadaBunek();
                $vzdelaniSkolaI->Nadpis("I.");
                $vzdelaniSkolaI->PridejBunku("Název školy: ",@$pdfpole["nazev_skoly1"],1);
                $vzdelaniSkolaI->PridejBunku("Obor: ",@$pdfpole["obor1"],1);
                $vzdelaniSkolaI->PridejBunku("Stupeň vzdělání: ",@$pdfpole["vzdelani1"],1,0,"-");
                $vzdelaniSkolaI->PridejBunku("Závěrečná zkouška: ",@$pdfpole["zaverecna_zkouska1"],0,90,"-");
                $vzdelaniSkolaI->PridejBunku("Rok ukončení studia: ",@$pdfpole["rok_ukonceni_studia1"],1);
                $vzdelaniSkolaI->PridejBunku("Popis: ",@$pdfpole["popis1"],1);
                $vzdelaniSkolaI->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno1"],1,0,"Ne");
                
    $vzdelaniSkolaII = new PDF_SadaBunek();
                $vzdelaniSkolaII->Nadpis("II.");
                $vzdelaniSkolaII->PridejBunku("Název školy: ",@$pdfpole["nazev_skoly2"],1);
                $vzdelaniSkolaII->PridejBunku("Obor: ",@$pdfpole["obor2"],1);
                $vzdelaniSkolaII->PridejBunku("Stupeň vzdělání: ",@$pdfpole["vzdelani2"],1,0,"-");
                $vzdelaniSkolaII->PridejBunku("Závěrečná zkouška: ",@$pdfpole["zaverecna_zkouska2"],0,90,"-");
                $vzdelaniSkolaII->PridejBunku("Rok ukončení studia: ",@$pdfpole["rok_ukonceni_studia2"],1);
                $vzdelaniSkolaII->PridejBunku("Popis: ",@$pdfpole["popis2"],1);
                $vzdelaniSkolaII->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno2"],1,0,"Ne");
    
                   
    $vzdelaniSkolaIII = new PDF_SadaBunek();
                $vzdelaniSkolaIII->Nadpis("III.");
                $vzdelaniSkolaIII->PridejBunku("Název školy: ",@$pdfpole["nazev_skoly3"],1);
                $vzdelaniSkolaIII->PridejBunku("Obor: ",@$pdfpole["obor3"],1);
                $vzdelaniSkolaIII->PridejBunku("Stupeň vzdělání: ",@$pdfpole["vzdelani3"],1,0,"-");
                $vzdelaniSkolaIII->PridejBunku("Závěrečná zkouška: ",@$pdfpole["zaverecna_zkouska3"],0,90,"-");
                $vzdelaniSkolaIII->PridejBunku("Rok ukončení studia: ",@$pdfpole["rok_ukonceni_studia3"],1);
                $vzdelaniSkolaIII->PridejBunku("Popis: ",@$pdfpole["popis3"],1);
                $vzdelaniSkolaIII->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno3"],1,0,"Ne");
                
    $vzdelaniSkolaIV = new PDF_SadaBunek();
                $vzdelaniSkolaIV->Nadpis("IV.");
                $vzdelaniSkolaIV->PridejBunku("Název školy: ",@$pdfpole["nazev_skoly4"],1);
                $vzdelaniSkolaIV->PridejBunku("Obor: ",@$pdfpole["obor4"],1);
                $vzdelaniSkolaIV->PridejBunku("Stupeň vzdělání: ",@$pdfpole["vzdelani4"],1,0,"-");
                $vzdelaniSkolaIV->PridejBunku("Závěrečná zkouška: ",@$pdfpole["zaverecna_zkouska4"],0,90,"-");
                $vzdelaniSkolaIV->PridejBunku("Rok ukončení studia: ",@$pdfpole["rok_ukonceni_studia4"],1);
                $vzdelaniSkolaIV->PridejBunku("Popis: ",@$pdfpole["popis4"],1);
                $vzdelaniSkolaIV->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno4"],1,0,"Ne");
                $vzdelaniSkolaIV->NovyRadek();
                
    $vzdelaniSkolaV = new PDF_SadaBunek();
                $vzdelaniSkolaV->Nadpis("V.");
                $vzdelaniSkolaV->PridejBunku("Název školy: ",@$pdfpole["nazev_skoly5"],1);
                $vzdelaniSkolaV->PridejBunku("Obor: ",@$pdfpole["obor5"],1);
                $vzdelaniSkolaV->PridejBunku("Stupeň vzdělání: ",@$pdfpole["vzdelani4"],1,0,"-");
                $vzdelaniSkolaV->PridejBunku("Závěrečná zkouška: ",@$pdfpole["zaverecna_zkouska5"],0,90,"-");
                $vzdelaniSkolaV->PridejBunku("Rok ukončení studia: ",@$pdfpole["rok_ukonceni_studia5"],1);
                $vzdelaniSkolaV->PridejBunku("Popis: ",@$pdfpole["popis5"],1);
                $vzdelaniSkolaV->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno5"],1,0,"Ne");
                $vzdelaniSkolaV->NovyRadek();             

    $vzdelaniSkoleni = new PDF_Odstavec;
		$vzdelaniSkoleni->Nadpis("Absolvovaná školení");
		$vzdelaniSkoleni->ZarovnaniNadpisu("L");
                $vzdelaniSkoleni->VyskaPismaNadpisu(12);

    $vzdelaniSkoleniI = new PDF_SadaBunek();
                $vzdelaniSkoleniI->Nadpis("I.");
                $vzdelaniSkoleniI->PridejBunku("Název školení: ",@$pdfpole["nazev_skoleni1"],1);
                $vzdelaniSkoleniI->PridejBunku("Popis školení: ",@$pdfpole["popis_skoleni1"],1);
                $vzdelaniSkoleniI->PridejBunku("Doba trvání školení (dny): ",@$pdfpole["doba_skoleni1"],0,90);
                $vzdelaniSkoleniI->PridejBunku("Rok ukončení: ",@$pdfpole["rok_ukonceni1"],1);
                $vzdelaniSkoleniI->PridejBunku("Popis dokladu: ",@$pdfpole["popis_dokladu1"],1);
                $vzdelaniSkoleniI->PridejBunku("Hrazeno: ",@$pdfpole["hrazeno1"],1,0,"-");
                $vzdelaniSkoleniI->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno_skoleni1"],1,0,"Ne");
                
    $vzdelaniSkoleniII = new PDF_SadaBunek();
                $vzdelaniSkoleniII->Nadpis("II.");
                $vzdelaniSkoleniII->PridejBunku("Název školení: ",@$pdfpole["nazev_skoleni2"],1);
                $vzdelaniSkoleniII->PridejBunku("Popis školení: ",@$pdfpole["popis_skoleni2"],1);
                $vzdelaniSkoleniII->PridejBunku("Doba trvání školení (dny): ",@$pdfpole["doba_skoleni2"],0,90);
                $vzdelaniSkoleniII->PridejBunku("Rok ukončení: ",@$pdfpole["rok_ukonceni2"],1);
                $vzdelaniSkoleniII->PridejBunku("Popis dokladu: ",@$pdfpole["popis_dokladu2"],1);
                $vzdelaniSkoleniII->PridejBunku("Hrazeno: ",@$pdfpole["hrazeno2"],1,0,"-");
                $vzdelaniSkoleniII->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno_skoleni2"],1,0,"Ne");
    
    $vzdelaniSkoleniIII = new PDF_SadaBunek();
                $vzdelaniSkoleniIII->Nadpis("III.");
                $vzdelaniSkoleniIII->PridejBunku("Název školení: ",@$pdfpole["nazev_skoleni3"],1);
                $vzdelaniSkoleniIII->PridejBunku("Popis školení: ",@$pdfpole["popis_skoleni3"],1);
                $vzdelaniSkoleniIII->PridejBunku("Doba trvání školení (dny): ",@$pdfpole["doba_skoleni3"],0,90);
                $vzdelaniSkoleniIII->PridejBunku("Rok ukončení: ",@$pdfpole["rok_ukonceni3"],1);
                $vzdelaniSkoleniIII->PridejBunku("Popis dokladu: ",@$pdfpole["popis_dokladu3"],1);
                $vzdelaniSkoleniIII->PridejBunku("Hrazeno: ",@$pdfpole["hrazeno3"],1,0,"-");
                $vzdelaniSkoleniIII->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno_skoleni3"],1,0,"Ne");
                
    $vzdelaniSkoleniIV = new PDF_SadaBunek();
                $vzdelaniSkoleniIV->Nadpis("IV.");
                $vzdelaniSkoleniIV->PridejBunku("Název školení: ",@$pdfpole["nazev_skoleni4"],1);
                $vzdelaniSkoleniIV->PridejBunku("Popis školení: ",@$pdfpole["popis_skoleni4"],1);
                $vzdelaniSkoleniIV->PridejBunku("Doba trvání školení (dny): ",@$pdfpole["doba_skoleni4"],0,90);
                $vzdelaniSkoleniIV->PridejBunku("Rok ukončení: ",@$pdfpole["rok_ukonceni4"],1);
                $vzdelaniSkoleniIV->PridejBunku("Popis dokladu: ",@$pdfpole["popis_dokladu4"],1);
                $vzdelaniSkoleniIV->PridejBunku("Hrazeno: ",@$pdfpole["hrazeno4"],1,0,"-");
                $vzdelaniSkoleniIV->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno_skoleni4"],1,0,"Ne");
                
    $vzdelaniSkoleniV = new PDF_SadaBunek();
                $vzdelaniSkoleniV->Nadpis("V.");
                $vzdelaniSkoleniV->PridejBunku("Název školení: ",@$pdfpole["nazev_skoleni5"],1);
                $vzdelaniSkoleniV->PridejBunku("Popis školení: ",@$pdfpole["popis_skoleni5"],1);
                $vzdelaniSkoleniV->PridejBunku("Doba trvání školení (dny): ",@$pdfpole["doba_skoleni5"],0,90);
                $vzdelaniSkoleniV->PridejBunku("Rok ukončení: ",@$pdfpole["rok_ukonceni5"],1);
                $vzdelaniSkoleniV->PridejBunku("Popis dokladu: ",@$pdfpole["popis_dokladu5"],1);
                $vzdelaniSkoleniV->PridejBunku("Hrazeno: ",@$pdfpole["hrazeno5"],1,0,"-");
                $vzdelaniSkoleniV->PridejBunku("Doloženo dokladem: ",@$pdfpole["dolozeno_skoleni5"],1,0,"Ne");

    $specializaceVPraxi = new PDF_Odstavec();
                $specializaceVPraxi->Nadpis("Specializace v praxi");
                $specializaceVPraxi->VyskaPismaNadpisu(12);
                // ?  $specializaceVPraxi->ZarovnaniNadpisu("L");
                $specializaceVPraxi->Text(@$pdfpole["specializace_v_praxi"]);
                //$specializaceVPraxi->VyskaPismaTextu(10);
                
   
    $jazykoveZnalosti = new PDF_Odstavec();
                $jazykoveZnalosti ->Nadpis("Jazykové znalosti");
                $jazykoveZnalosti ->VyskaPismaNadpisu(12);    
    $jazykoveZnalostiAj = new PDF_SadaBunek();
                	$jazykoveZnalostiAj->PridejBunku("Anglický jazyk","",0,40);
                	$jazykoveZnalostiAj->PridejBunku("Úroveň: ",@$pdfpole["aj_uroven"],0,70,'-');
                	$jazykoveZnalostiAj->PridejBunku("Schopnosti: ",@$pdfpole["aj_schopnosti"],1,0,'-');
    $jazykoveZnalostiNj = new PDF_SadaBunek();   		
                	$jazykoveZnalostiNj->PridejBunku("Německý jazyk","",0,40);
                	$jazykoveZnalostiNj->PridejBunku("Úroveň: ",@$pdfpole["nj_uroven"],0,70,'-');
                	$jazykoveZnalostiNj->PridejBunku("Schopnosti: ",@$pdfpole["nj_schopnosti"],1,0,'-');
    $jazykoveZnalostiRj = new PDF_SadaBunek();                           
                	$jazykoveZnalostiRj->PridejBunku("Ruský jazyk","",0,40);
                	$jazykoveZnalostiRj->PridejBunku("Úroveň: ",@$pdfpole["rj_uroven"],0,70,'-');
                	$jazykoveZnalostiRj->PridejBunku("Schopnosti: ",@$pdfpole["rj_schopnosti"],1,0,'-');
    $jazykoveZnalostiD1 = new PDF_SadaBunek();                  	
                        $jazykoveZnalostiD1->PridejBunku("",@$pdfpole["dalsi_jazyk1_jmeno"],0,40);
                	$jazykoveZnalostiD1->PridejBunku("Úroveň: ",@$pdfpole["dalsi_jazyk1_jmeno_uroven"],0,70,'-');
                	$jazykoveZnalostiD1->PridejBunku("Schopnosti: ",@$pdfpole["dalsi_jazyk1_schopnosti"],1,0,'-');
    $jazykoveZnalostiD2 = new PDF_SadaBunek();      		
                	$jazykoveZnalostiD2->PridejBunku("",@$pdfpole["dalsi_jazyk2_jmeno"],0,40);
                	$jazykoveZnalostiD2->PridejBunku("Úroveň: ",@$pdfpole["dalsi_jazyk2_jmeno_uroven"],0,70,'-');
                	$jazykoveZnalostiD2->PridejBunku("Schopnosti: ",@$pdfpole["dalsi_jazyk2_schopnosti"],1,0,'-');
   		
                         

    $PC_dovednosti = new PDF_Odstavec();
                $PC_dovednosti->Nadpis("PC dovednosti");
                $PC_dovednosti->VyskaPismaNadpisu(12);
    $PC_dovednostiOffice  = new PDF_SadaBunek();
                $PC_dovednostiOffice->PridejBunku("MS-Office-úroveň: ",@$pdfpole["pc_office_uroven"],1,0,"-");
    $PC_dovednostiERP  = new PDF_SadaBunek();
                $PC_dovednostiERP->PridejBunku("ERP systémy(SAP, BAAN, účetnictví): ",@$pdfpole["PC_ERP"],1,0,"Ne");
                $PC_dovednostiERP->PridejBunku("Název: ",@$pdfpole["PC_ERP_nazev"],1);
    $PC_dovednostiCAD  = new PDF_SadaBunek();
                $PC_dovednostiCAD->PridejBunku("CAD systémy : ",@$pdfpole["PC_CAD"],1,0,"Ne");
                $PC_dovednostiCAD->PridejBunku("Název: ",@$pdfpole["PC_CAD_nazev"],1);
    $PC_dovednostiGrafika  = new PDF_SadaBunek();
                $PC_dovednostiGrafika->PridejBunku("Grafické programy: ",@$pdfpole["PC_GRA"],1,0,"Ne");
                $PC_dovednostiGrafika->PridejBunku("Název: ",@$pdfpole["PC_GRA_nazev"],1);
    $PC_dovednostiIT  = new PDF_SadaBunek();
                $PC_dovednostiIT->PridejBunku("IT expert: ",@$pdfpole["PC_IT"],1,0,"Ne");
                $PC_dovednostiIT->PridejBunku("Popis: ",@$pdfpole["PC_popis"],1);

    $ridic = new PDF_Odstavec();
                $ridic->Nadpis("Řidičské oprávnění");
                $ridic->VyskaPismaNadpisu(12);
    $ridic1  = new PDF_SadaBunek();
                //$ridic1->Nadpis("Řidičské oprávnění");
                //$ridic1->VyskaPismaNadpisu(12);
                $ridic1->PridejBunku("Skupina: ", @$pdfpole["ridic_sk1"],0,40);
                $ridic1->PridejBunku("Rok: ", @$pdfpole["ridic_rok1"],1);
    $ridic2  = new PDF_SadaBunek();
                $ridic2->PridejBunku("Skupina: ", @$pdfpole["ridic_sk2"],0,40);
                $ridic2->PridejBunku("Rok: ", @$pdfpole["ridic_rok2"],1);
    $ridic3  = new PDF_SadaBunek();
                $ridic3->PridejBunku("Skupina: ", @$pdfpole["ridic_sk3"],0,40);
                $ridic3->PridejBunku("Rok: ", @$pdfpole["ridic_rok3"],1);
    $ridic4  = new PDF_SadaBunek();
                $ridic4->PridejBunku("Skupina: ", @$pdfpole["ridic_sk4"],0,40);
                $ridic4->PridejBunku("Rok: ", @$pdfpole["ridic_rok4"],1);


    $predchoziZamestnani = new PDF_Odstavec;
		$predchoziZamestnani->Nadpis("Předchozí zaměstnání");
		$predchoziZamestnani->ZarovnaniNadpisu("L");
                $predchoziZamestnani->VyskaPismaNadpisu(12);
    $predchoziZamestnaniI = new PDF_SadaBunek();
                $predchoziZamestnaniI->Nadpis("I.");
                $predchoziZamestnaniI->PridejBunku("Od: ",@$pdfpole["zamestnani_od1"]);
                $predchoziZamestnaniI->PridejBunku("Do: ",@$pdfpole["zamestnani_do1"],1);
                $predchoziZamestnaniI->PridejBunku("Zaměstnavatel: ",@$pdfpole["zamestnani_zamestnavatel1"],1);
                $predchoziZamestnaniI->PridejBunku("Pozice: ",@$pdfpole["zamestnani_pozice1"]);
                $predchoziZamestnaniI->PridejBunku("Číslo dle KZAM: ",@$pdfpole["KZAM_cislo1"],1);
                $predchoziZamestnaniI->PridejBunku("Popis pozice: ",@$pdfpole["zamestnani_popis1"],1);
                
    $predchoziZamestnaniII = new PDF_SadaBunek();
                $predchoziZamestnaniII->Nadpis("II.");
                $predchoziZamestnaniII->PridejBunku("Od: ",@$pdfpole["zamestnani_od2"]);
                $predchoziZamestnaniII->PridejBunku("Do: ",@$pdfpole["zamestnani_do2"],1);
                $predchoziZamestnaniII->PridejBunku("Zaměstnavatel: ",@$pdfpole["zamestnani_zamestnavatel2"],1);
                $predchoziZamestnaniII->PridejBunku("Pozice: ",@$pdfpole["zamestnani_pozice2"]);
                $predchoziZamestnaniII->PridejBunku("Číslo dle KZAM: ",@$pdfpole["KZAM_cislo2"],1);
                $predchoziZamestnaniII->PridejBunku("Popis pozice: ",@$pdfpole["zamestnani_popis2"],1);
                
    $predchoziZamestnaniIII = new PDF_SadaBunek();
                $predchoziZamestnaniIII->Nadpis("III.");
                $predchoziZamestnaniIII->PridejBunku("Od: ",@$pdfpole["zamestnani_od3"]);
                $predchoziZamestnaniIII->PridejBunku("Do: ",@$pdfpole["zamestnani_do3"],1);
                $predchoziZamestnaniIII->PridejBunku("Zaměstnavatel: ",@$pdfpole["zamestnani_zamestnavatel3"],1);
                $predchoziZamestnaniIII->PridejBunku("Pozice: ",@$pdfpole["zamestnani_pozice3"]);
                $predchoziZamestnaniIII->PridejBunku("Číslo dle KZAM: ",@$pdfpole["KZAM_cislo3"],1);
                $predchoziZamestnaniIII->PridejBunku("Popis pozice: ",@$pdfpole["zamestnani_popis3"],1);
                
    $predchoziZamestnaniIV = new PDF_SadaBunek();
                $predchoziZamestnaniIV->Nadpis("IV.");
                $predchoziZamestnaniIV->PridejBunku("Od: ",@$pdfpole["zamestnani_od4"]);
                $predchoziZamestnaniIV->PridejBunku("Do: ",@$pdfpole["zamestnani_do4"],1);
                $predchoziZamestnaniIV->PridejBunku("Zaměstnavatel: ",@$pdfpole["zamestnani_zamestnavatel4"],1);
                $predchoziZamestnaniIV->PridejBunku("Pozice: ",@$pdfpole["zamestnani_pozice4"]);
                $predchoziZamestnaniIV->PridejBunku("Číslo dle KZAM: ",@$pdfpole["KZAM_cislo4"],1);
                $predchoziZamestnaniIV->PridejBunku("Popis pozice: ",@$pdfpole["zamestnani_popis4"],1);
                
    $predchoziZamestnaniV = new PDF_SadaBunek();
                $predchoziZamestnaniV->Nadpis("V.");
                $predchoziZamestnaniV->PridejBunku("Od: ",@$pdfpole["zamestnani_od5"]);
                $predchoziZamestnaniV->PridejBunku("Do: ",@$pdfpole["zamestnani_do5"],1);
                $predchoziZamestnaniV->PridejBunku("Zaměstnavatel: ",@$pdfpole["zamestnani_zamestnavatel5"],1);
                $predchoziZamestnaniV->PridejBunku("Pozice: ",@$pdfpole["zamestnani_pozice5"]);
                $predchoziZamestnaniV->PridejBunku("Číslo dle KZAM: ",@$pdfpole["KZAM_cislo5"],1);
                $predchoziZamestnaniV->PridejBunku("Popis pozice: ",@$pdfpole["zamestnani_popis5"],1);
                

    $posledniPomer = new PDF_SadaBunek();
                $posledniPomer->PridejBunku("Datum ukončení posledního prac.poměru: ",@$pdfpole["zamestnani_konec_posledniho"],0,100);
                $posledniPomer->PridejBunku("Poslední prac.poměr: ",@$pdfpole["zamestnani_zpukonceni"],1);


    $predstavaUplatneni = new PDF_Odstavec();
                $predstavaUplatneni->Nadpis("Představa o uplatnění");
                $predstavaUplatneni->VyskaPismaNadpisu(12);
    $predstavaUplatneniPopis = new PDF_SadaBunek();
                $predstavaUplatneniPopis->PridejBunku("Popis povolání, které bych chtěl/a vykonávat: ","",1);
                $predstavaUplatneniPopis->PridejBunku("",@$pdfpole["pozadavky_povolani"],1);
                $predstavaUplatneniPopis->PridejBunku("1.KZAM: ",@$pdfpole["pozadavky_KZAM1"]);
                $predstavaUplatneniPopis->PridejBunku("2.KZAM: ",@$pdfpole["pozadavky_KZAM2"]);
                $predstavaUplatneniPopis->PridejBunku("3.KZAM: ",@$pdfpole["pozadavky_KZAM3"]);
                $predstavaUplatneniPopis->NovyRadek();

    $predstavaUplatneniHledaOdmita = new PDF_SadaBunek();
                $predstavaUplatneniHledaOdmita->PridejBunku("","Uchazeč hledá: ",1);
                for ($it = 1; $it <= 13; $it++) {
                  if (substr_count(@$pdfpole["pozadavky_hleda". $it], "-") <> strlen(@$pdfpole["pozadavky_hleda". $it]) )
                   {$predstavaUplatneniHledaOdmita->PridejBunku("- ",@$pdfpole["pozadavky_hleda". $it],1);}
                }
                $predstavaUplatneniHledaOdmita->PridejBunku("","Uchazeč odmítá: ",1);
                for ($it = 1; $it <= 13; $it++) {
                  if (substr_count(@$pdfpole["pozadavky_odmita". $it], "-") <> strlen(@$pdfpole["pozadavky_odmita". $it]) )
                   {$predstavaUplatneniHledaOdmita->PridejBunku("- ",@$pdfpole["pozadavky_odmita". $it],1);}
                }
                //$predstavaUplatneniHledaOdmita->NovyRadek();
                
    $predstavaUplatneniPozadavky = new PDF_SadaBunek();
                $predstavaUplatneniPozadavky->PridejBunku("","Požadovaný datum nástupu do zaměstnání: ");
                $predstavaUplatneniPozadavky->PridejBunku("",@$pdfpole["pozadavky_nastup"],1);
                $predstavaUplatneniPozadavky->PridejBunku("","Požadovaný plat: ");
                $predstavaUplatneniPozadavky->PridejBunku("",@$pdfpole["pozadavky_plat"] . " Kč/měsíc",1);
                $predstavaUplatneniPozadavky->PridejBunku("","Další požadavky: ");
                $predstavaUplatneniPozadavky->PridejBunku("",@$pdfpole["pozadavky_prace"],1);

    $doplnujiciUdaje = new PDF_Odstavec;
		$doplnujiciUdaje->Nadpis("Doplňující údaje o účastníkovi projektu");
		$doplnujiciUdaje->ZarovnaniNadpisu("L");
                $doplnujiciUdaje->VyskaPismaNadpisu(12);
    $doplnujiciUdaje1 = new PDF_SadaBunek();
                $doplnujiciUdaje1->PridejBunku("Péče o závislé osaby: " , @$pdfpole["pece_o_zav_osoby"],1 );
                $doplnujiciUdaje1->PridejBunku("Zdravotní stav: " , @$pdfpole["zdrav_stav"],1 );
                $doplnujiciUdaje1->PridejBunku("Změněná pracovní schopnost: " , @$pdfpole["ZPS"],1 );
                if (substr_count(@$pdfpole["zdravotni_znevyhodneni"], "-") <> strlen(@$pdfpole["zdravotni_znevyhodneni"]) )
                {
                    $doplnujiciUdaje1->PridejBunku("Zdravotni znevyhodneni: " , @$pdfpole["zdravotni_znevyhodneni"],1 );
                }              
                
                $doplnujiciUdaje1->PridejBunku("V evidenci úřadu práce jako nezaměstnaný (počet měsíců): " , @$pdfpole["doba_evidence"],1 );
                $doplnujiciUdaje1->PridejBunku("Pokolikáté v evidenci úřadu práce jako nezaměstnaný: " , @$pdfpole["kolikrat_ev"],1);

/*
 * to nemá být vytištěno v IP, je to jen dotazníku
 *
    $vyplataPrimePodpory = new PDF_SadaBunek();
                $vyplataPrimePodpory->PridejBunku("Účastník požaduje vyplácet prostředky přímé podpory v hotovosti v kontaktní kanceláři: ",
                                                   @$pdfpole["prostredky_p_p"],1 );  //!!prostredky_p_p jsouvyplneny ano/ne !s malym pismenem
                 //tady to asi jeste neni dobre
                if ((substr_count(@$pdfpole["banka"], "-") == strlen(@$pdfpole["banka"]) ) or !!!! nedelalo mi to spravne!
                    !(@$pdfpole["ucet"]) )  {}
                else
                {
                  if  (@$pdfpole["predcisli"]) {$predc = @$pdfpole["predcisli"] . "-" ;}
                  else {$predc="";}
                  $bank=explode("|",@$pdfpole["banka"]);
                  $vyplataPrimePodpory->PridejBunku("Číslo účtu: ", $predc . @$pdfpole["cislo"] . "/" . trim($bank[0]),1 );
                }
*/

    $podpisy = new PDF_SadaBunek();
        /*$kk = @$pdfpole["z_up"];
        if ( @$pdfpole["z_up"] == "Klatovy")
        {
        	if (@$pdfpole["prac_up"] <> "Klatovy - pracoviště Klatovy")
        	{
        		$kk = "Sušice";
        	}
        }*/
	$kk = $Kancelar->plny_text;
	
    	$podpisy->PridejBunku("Kontaktní kancelář: ", $kk, 1); 
    //	$podpisy->PridejBunku("Dne ", @$pdfpole["datum_reg"],1);
    	$podpisy->PridejBunku("Dne ", @$pdfpole["datum_vytvor_dok"],1);
        $podpisy->NovyRadek(0,5);
    	$podpisy->PridejBunku("                       ......................................................                                            ......................................................","",1);
     	$podpisy->PridejBunku("                                        účastník                                                                                     koordinátor","");
        $podpisy->NovyRadek();


   //*******************************************************************
    $pdfdebug = PDF_Kontext::dejDebug();
    $pdfdebug->debug(0);

    ob_clean;
	$pdf = new PDF_VytvorPDF ();

	$pdf->AddFont('Times','','times.php');
	$pdf->AddFont('Times','B','timesbd.php');
	$pdf->AddFont("Times","BI","timesbi.php");
	$pdf->AddFont("Times","I","timesi.php");

  	$pdf->AddPage();

	$pdf->Ln(5);
	$pdf->TiskniOdstavec($titulek);
        $pdf->TiskniOdstavec($titulek1);
	$pdf->Ln(5);

        $pdf->TiskniSaduBunek($osobniUdaje,5,1);
         
        $pdf->Ln(5); 
        $pdf->TiskniSaduBunek($bydliste,5,1);
        $pdf->TiskniSaduBunek($bydlistePrechodne,5);

        $pdf->TiskniSaduBunek($kontakt,5,1);
        $pdf->TiskniSaduBunek($kontakt_dalsi,5);

        $pdf->Ln(5);
        $pdf->TiskniOdstavec($vzdelaniSkoly);
        $pdf->TiskniSaduBunek($vzdelaniSkolaI,5);
        $pdf->TiskniSaduBunek($vzdelaniSkolaII,5);
        $pdf->TiskniSaduBunek($vzdelaniSkolaIII,5);
        $pdf->TiskniSaduBunek($vzdelaniSkolaIV,5);
        $pdf->TiskniSaduBunek($vzdelaniSkolaV,5);

        $pdf->Ln(5);
        $pdf->TiskniOdstavec($vzdelaniSkoleni);
        $pdf->TiskniSaduBunek($vzdelaniSkoleniI,5);
        $pdf->TiskniSaduBunek($vzdelaniSkoleniII,5);
        $pdf->TiskniSaduBunek($vzdelaniSkoleniIII,5);
        $pdf->TiskniSaduBunek($vzdelaniSkoleniIV,5);
        $pdf->TiskniSaduBunek($vzdelaniSkoleniV,5);
        
        $pdf->Ln(5);
        $pdf->TiskniOdstavec($specializaceVPraxi);
         
        $pdf->Ln(5);
        $pdf->TiskniOdstavec($jazykoveZnalosti);
        $pdf->TiskniSaduBunek($jazykoveZnalostiAj,5);
        $pdf->TiskniSaduBunek($jazykoveZnalostiNj,5);
        $pdf->TiskniSaduBunek($jazykoveZnalostiRj,5);
        $pdf->TiskniSaduBunek($jazykoveZnalostiD1,5);
        $pdf->TiskniSaduBunek($jazykoveZnalostiD2,5);

        $pdf->Ln(5); 
        $pdf->TiskniOdstavec($PC_dovednosti);
        $pdf->TiskniSaduBunek($PC_dovednostiOffice,5);
        $pdf->TiskniSaduBunek($PC_dovednostiERP,5);
        $pdf->TiskniSaduBunek($PC_dovednostiCAD,5);
        $pdf->TiskniSaduBunek($PC_dovednostiGrafika,5);
        $pdf->TiskniSaduBunek($PC_dovednostiIT,5);

        $pdf->Ln(5);
        $pdf->TiskniOdstavec($ridic);
        $pdf->TiskniSaduBunek($ridic1,5);
        $pdf->TiskniSaduBunek($ridic2,5);
        $pdf->TiskniSaduBunek($ridic3,5);
        $pdf->TiskniSaduBunek($ridic4,5);

        $pdf->Ln(5);
        $pdf->TiskniOdstavec($predchoziZamestnani,5);
        $pdf->TiskniSaduBunek($predchoziZamestnaniI,5);
        $pdf->TiskniSaduBunek($predchoziZamestnaniII,5);
        $pdf->TiskniSaduBunek($predchoziZamestnaniIII,5);
        $pdf->TiskniSaduBunek($predchoziZamestnaniIV,5);
        $pdf->TiskniSaduBunek($predchoziZamestnaniV,5);

        $pdf->Ln(5);
        $pdf->TiskniSaduBunek($posledniPomer,5);
        
        $pdf->Ln(5);
        $pdf->TiskniOdstavec($predstavaUplatneni,5);
        $pdf->TiskniSaduBunek($predstavaUplatneniPopis,5);
        $pdf->TiskniSaduBunek($predstavaUplatneniHledaOdmita,5);
        $pdf->Ln(5);
        $pdf->TiskniSaduBunek($predstavaUplatneniPozadavky,5);

        $pdf->Ln(5);
        $pdf->TiskniOdstavec($doplnujiciUdaje,5);
        $pdf->TiskniSaduBunek($doplnujiciUdaje1,5);


        /*  netisknout  netiskli jsme neni vyse pripravene */
        /* $pdf->TiskniSaduBunek($vyplataPrimePodpory,5);*/


        $pdf->Ln(10);
        $pdf->TiskniSaduBunek($podpisy, 0, 1);
	
	$pdf->Ln(10);


$filepathprefix= iconv('UTF-8', 'windows-1250', "./doku/SPZP IP 1.cast ");
if (file_exists($filepathprefix. $Ucastnik->identifikator . ".pdf"))  	{
    	unlink($filepathprefix. $Ucastnik->identifikator . ".pdf");
}

$pdf->Output($filepathprefix. $Ucastnik->identifikator . ".pdf", F);


//exit;
?>