<?php
	define('FPDF_FONTPATH','classes/PDF/Fonts/');
	require_once("autoload.php");

$pdfpole = $pole;
 
  
	foreach($pdfpole  as $klic => $hodnota) 
	{
      	$pdfpole['$klic'] = trim($pdfpole['$klic']);  //??
  	}
  	
//*
    $pdfhlavicka = PDF_Kontext::dejHlavicku();
		$pdfhlavicka->text("Individuální plán účastníka 2.část");
		$pdfhlavicka->zarovnani("C");
		$pdfhlavicka->vyskaPisma(14);
		$pdfhlavicka->obrazek("./PDF/loga_BW_Rodina_neni_handicap.jpg", null, null,165,8.6);
    $pdfpaticka = PDF_Kontext::dejPaticku();
		$pdfpaticka->text("Rodina není handicap - Individuální plán účastníka - 2.část  Účastník: ".$Ucastnik->identifikator);
		$pdfpaticka->zarovnani("C");
		$pdfpaticka->vyskaPisma(6);
		$pdfpaticka->cislovani = true;
	  	
    $osobniUdaje = new PDF_SadaBunek();
		$osobniUdaje->Nadpis("Osobní údaje");
		$osobniUdaje->vyskaPismaNadpisu(12);  //neumi
		
		$osobniUdaje->PridejBunku("Identifikátor účastníka: ", $Ucastnik->identifikator,1);
		$celeJmeno =  @$pole_pro_zobrazeni["titul"]." ".@$pole_pro_zobrazeni["jmeno"]." ".@$pole_pro_zobrazeni["prijmeni"];
		if (@$pole_pro_zobrazeni["titul_za"]) 
		{
			$celeJmeno = $celeJmeno.", ".@$pole_pro_zobrazeni["titul_za"];
		}
		$osobniUdaje->PridejBunku("Účastník: ", $celeJmeno, 1);
		
		$adresapole="";
                if (@$pole_pro_zobrazeni["ulice"]) {
                    $adresapole .=   @$pole_pro_zobrazeni["ulice"];
                    if  (@$pole_pro_zobrazeni["mesto"])  {  $adresapole .=  ", ".   @$pole_pro_zobrazeni["mesto"];}
                    if  (@$pole_pro_zobrazeni["psc"])    {  $adresapole .= ", " . @$pole_pro_zobrazeni["psc"]; }
                }
                else {
                    if  (@$pole_pro_zobrazeni["mesto"])  {
                        $adresapole .= @$pole_pro_zobrazeni["mesto"];
                        if  (@$pole_pro_zobrazeni["psc"])    {  $adresapole .= ", " . @$pole_pro_zobrazeni["psc"]; }
                    }
                    else {
                         if  (@$pole_pro_zobrazeni["psc"])  {$adresapole .=  @$pole_pro_zobrazeni["psc"];}
                    } 
                }
		$osobniUdaje->PridejBunku("Bydliště: ", $adresapole,1);
			  //@$pole_pro_zobrazeni["ulice"].", ". @$pole_pro_zobrazeni["psc"]." ". @$pole_pro_zobrazeni["mesto"], 1);
		$osobniUdaje->PridejBunku("Vysílající úřad práce: ", @$pole_pro_zobrazeni["z_up"],0,80);
		$osobniUdaje->PridejBunku("Pracoviště vysílajícího úřadu práce: ", @$pole_pro_zobrazeni["prac_up"]);
		$osobniUdaje->NovyRadek();

		
	$kurzZZTP = new PDF_SadaBunek();
    	$kurzZZTP->Nadpis("Kurz základních znalostí trhu práce");
              //$kurzZZTP->Spoust("-", $pole_pro_zobrazeni['zztp_pdf_kod_kurzu']);
              if ( ($pole_pro_zobrazeni['zztp_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['zztp_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['zztp_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $kurzZZTP->SpustSadu(true);
              }  
        $kurzZZTP->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['zztp_pdf_termin_konani'] );
        $kurzZZTP->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['zztp_pdf_kod_kurzu'] );
        $kurzZZTP->PridejBunku("Skupina účastníků: ",$pole_pro_zobrazeni['zztp_pdf_skupina'],1 );        
        
    	$kurzZZTP->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['zztp_poc_abs_hodin'],1);
    	$kurzZZTP->PridejBunku("Důvod absence: ", @$pdfpole['zztp_duvod_absence'],1);
		$kurzZZTP->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['zztp_dokonceno'],1);
		if (@$pdfpole['zztp_dokonceno'] == "Ne")
        {
            $kurzZZTP->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['zztp_duvod_neukonceni'],1);
        }
	
        
	$komunikacniKurz = new PDF_SadaBunek();
        $komunikacniKurz->Nadpis("Komunikační kurz");
                //$komunikacniKurz->Spoust("-", $pole_pro_zobrazeni['kom_pdf_kod_kurzu']);
                if ( ($pole_pro_zobrazeni['kom_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['kom_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['kom_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $komunikacniKurz->SpustSadu(true);
                } 
        $komunikacniKurz->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['kom_pdf_termin_konani'] );
        $komunikacniKurz->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['kom_pdf_kod_kurzu'] );
        $komunikacniKurz->PridejBunku("Skupina účastníků: ",$pole_pro_zobrazeni['kom_pdf_skupina'],1 );
        
 		$komunikacniKurz->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['kom_poc_abs_hodin'],1);
        $komunikacniKurz->PridejBunku("Důvod absence: ", @$pdfpole['kom_duvod_absence'],1);
		$komunikacniKurz->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['kom_dokonceno'],1);
        if (@$pdfpole['kom_dokonceno'] == "Ne")
        {
            $komunikacniKurz->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['kom_duvod_neukonceni'],1);
        }
        $komunikacniKurz->PridejBunku("Datum vydání osvědčení o absolvování motivačního programu: ", @$pdfpole['mot_datum_certif'],1);        
        $komunikacniKurz->NovyRadek();
       	
        
        
        	
	$motivacniProgram = new PDF_SadaBunek();
		$motivacniProgram->Nadpis("Motivační program");
		if ($kurzZZTP->sadaSpustena == true OR $komunikacniKurz->sadaSpustena == true)
		{
			$motivacniProgram->SpustSadu(true);
		}
			
                        
                        
                        
                        
	$pcKurz = new PDF_SadaBunek();
        $pcKurz->Nadpis("PC kurz");
                //$pcKurz->Spoust("-", $pole_pro_zobrazeni['pc_pdf_kod_kurzu']);
                 if ( ($pole_pro_zobrazeni['pc_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['pc_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['pc_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $pcKurz->SpustSadu(true);
                  }  
        $pcKurz->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['pc_pdf_termin_konani'] );
        $pcKurz->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['pc_pdf_kod_kurzu'] );
        $pcKurz->PridejBunku("Skupina účastníků: ",$pole_pro_zobrazeni['pc_pdf_skupina'],1 );
        
        $pcKurz->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['pc_poc_abs_hodin'],1);
        $pcKurz->PridejBunku("Důvod absence: ", @$pdfpole['pc_duvod_absence'],1);
		$pcKurz->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['pc_dokonceno'],1);
       	if (@$pdfpole['pc_dokonceno'] == "Ne")
        {
            $pcKurz->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['pc_duvod_neukonceni'],1);
        }       
        $pcKurz->PridejBunku("Datum vydání osvědčení o rekvalifikaci (certifikátu): ", @$pdfpole['pc_datum_certif'],1);        
        $pcKurz->NovyRadek();
      
      
      
        
	$bilancDiag = new PDF_SadaBunek();
        $bilancDiag->Nadpis("Bilanční diagnostika");
                //$bilancDiag->Spoust("-", $pole_pro_zobrazeni['bidi_pdf_kod_kurzu']);
                if ( ($pole_pro_zobrazeni['bidi_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['bidi_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['bidi_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $bilancDiag->SpustSadu(true);
                }  
        $bilancDiag->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['bidi_pdf_termin_konani'] );
        $bilancDiag->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['bidi_pdf_kod_kurzu'],1 );
        
        $bilancDiag->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['bidi_poc_abs_hodin'],1);
        $bilancDiag->PridejBunku("Důvod absence: ", @$pdfpole['bidi_duvod_absence'],1);
        $bilancDiag->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['bidi_dokonceno'],1);
		if (@$pdfpole['bidi_dokonceno'] == "Ne")
        {
            $bilancDiag->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['bidi_duvod_neukonceni'],1);
        }
        $bilancDiag->PridejBunku("Datum vydání osvědčení: ", @$pdfpole['bidi_datum_certif'],1);        
        $bilancDiag->NovyRadek();
     
     
     
        $pracDiag = new PDF_SadaBunek();
        $pracDiag->Nadpis("Pracovní diagnostika");
                //$pracDiag->Spoust("-", $pole_pro_zobrazeni['prdi_pdf_kod_kurzu']);
                if ( ($pole_pro_zobrazeni['prdi_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['prdi_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['prdi_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $pracDiag->SpustSadu(true);
                }
        $pracDiag->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['prdi_pdf_termin_konani'] );
        $pracDiag->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['prdi_pdf_kod_kurzu'],1 );
        
        $pracDiag->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['prdi_poc_abs_hodin'],1);
        $pracDiag->PridejBunku("Důvod absence: ", @$pdfpole['prdi_duvod_absence'],1);
        $pracDiag->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['prdi_dokonceno'],1);
		if (@$pdfpole['prdi_dokonceno'] == "Ne")
        {
            $pracDiag->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['prdi_duvod_neukonceni'],1);
        }
        $pracDiag->PridejBunku("Datum vydání osvědčení: ", @$pdfpole['prdi_datum_certif'],1);        
        $pracDiag->NovyRadek();     
     
     
     
             
    $prax = new PDF_SadaBunek();
    $prax->Nadpis("Pracovní praxe");
	//	$prax->Spoust("-", $pdfpole['praxe_text']);
        if ( ($pole_pro_zobrazeni['praxe_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['praxe_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['praxe_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $prax->SpustSadu(true);
                }
        
        //if ( ($pole_pro_zobrazeni['praxe_text']!="nezařazen") and
        //               ($pole_pro_zobrazeni['praxe_text']!="odmítl účast") and
        //               (mb_substr ($pole_pro_zobrazeni['praxe_text'], 0,3)!= "---") 
        //             ) {
        //                $prax->SpustSadu(true);
        //        }
        
        $prax->PridejBunku("",$pole_pro_zobrazeni['praxe_pdf_nazev'],1 );
        $prax->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['praxe_pdf_termin_konani'] );
        $prax->PridejBunku("Kód: ",$pole_pro_zobrazeni['praxe_pdf_kod_kurzu'],1 );
 	
        $prax->PridejBunku("Datum ukončení praxe: ", @$pdfpole['praxe_datum_ukonceni'],1);
	$prax->NovyRadek();
     
                                                                            
        
        
    $prof1Kurz = new PDF_SadaBunek();
        $prof1Kurz->Nadpis("Profesní rekvalifikace");
                //$prof1Kurz->Spoust("-", $pole_pro_zobrazeni['prof1_pdf_kod_kurzu']);
                if ( ($pole_pro_zobrazeni['prof1_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['prof1_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['prof1_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $prof1Kurz->SpustSadu(true);
                }
        $prof1Kurz->PridejBunku("Název rekvalifikačního kurzu: ",$pole_pro_zobrazeni['prof1_pdf_nazev'], 1 );        
        $prof1Kurz->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['prof1_pdf_termin_konani'] );
        $prof1Kurz->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['prof1_pdf_kod_kurzu'],1 );
        
 	$prof1Kurz->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['prof1_poc_abs_hodin'],1);
        $prof1Kurz->PridejBunku("Důvod absence: ", @$pdfpole['prof1_duvod_absence'],1); 
	$prof1Kurz->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['prof1_dokonceno'],1);
		if (@$pdfpole['prof1_dokonceno'] == "Ne")
        {
            $prof1Kurz->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['prof1_duvod_neukonceni'],1);
        }
        $prof1Kurz->PridejBunku("Datum vydání osvědčení o rekvalifikaci (certifikátu): ", @$pdfpole['prof1_datum_certif'],1);        
        $prof1Kurz->NovyRadek();
	
        
        
	
    $prof2Kurz = new PDF_SadaBunek();
        $prof2Kurz->Nadpis("Profesní rekvalifikace - další");
                  //$prof2Kurz->Spoust("-", $pole_pro_zobrazeni['prof2_pdf_kod_kurzu']);
                  if ( ($pole_pro_zobrazeni['prof2_pdf_kod_kurzu']!="nezařazen") and
                       ($pole_pro_zobrazeni['prof2_pdf_kod_kurzu']!="odmítl účast") and
                       (mb_substr ($pole_pro_zobrazeni['prof2_pdf_kod_kurzu'], 0,3)!= "---") 
                     ) {
                        $prof2Kurz->SpustSadu(true);
                  }
        $prof2Kurz->PridejBunku("Název rekvalifikačního kurzu: ",$pole_pro_zobrazeni['prof2_pdf_nazev'], 1 );             
        $prof2Kurz->PridejBunku("Termín konání: ",$pole_pro_zobrazeni['prof2_pdf_termin_konani'] );
        $prof2Kurz->PridejBunku("Kód kurzu: ",$pole_pro_zobrazeni['prof2_pdf_kod_kurzu'],1 );
        
 	$prof2Kurz->PridejBunku("Počet absolvovaných hodin: ", @$pdfpole['prof2_poc_abs_hodin'],1);
        $prof2Kurz->PridejBunku("Důvod absence: ", @$pdfpole['prof2_duvod_absence'],1); 
	$prof2Kurz->PridejBunku("Dokončeno úspěšně: ", @$pdfpole['prof2_dokonceno'],1);
		if (@$pdfpole['prof2_dokonceno'] == "Ne")
        {
            $prof2Kurz->PridejBunku("Důvod neúspěšného ukončení: ", @$pdfpole['prof2_duvod_neukonceni'],1);
        }
        $prof2Kurz->PridejBunku("Datum vydání osvědčení o rekvalifikaci (certifikátu): ", @$pdfpole['prof2_datum_certif'],1);        
        $prof2Kurz->NovyRadek();	
	
	
	
        
    $podpisy = new PDF_SadaBunek();
       
       /* $kk = @$pole_pro_zobrazeni["z_up"];
        if ( @$pole_pro_zobrazeni["z_up"] == "Klatovy")
        {  if (@$pole_pro_zobrazeni["prac_up"] <> "Klatovy - pracoviště Klatovy")
        	{		$kk = "Sušice";    }
        }*/
            
	$kk = $Kancelar->plny_text;
	
    	$podpisy->PridejBunku("Kontaktní kancelář: ", $kk, 1); 
    	$podpisy->PridejBunku("Dne ", @$pdfpole["datum_vytvor_dok"],1);
        $podpisy->NovyRadek(0,5);
    	$podpisy->PridejBunku("                       ......................................................                                            ......................................................","",1);
     	$podpisy->PridejBunku("                                        účastník                                                                                     koordinátor","");
        $podpisy->NovyRadek();

	     	
//*/       
        
  //******************************************  
    $pdfdebug = PDF_Kontext::dejDebug();
    $pdfdebug->debug(0);
    
	ob_clean;
	$pdf = new PDF_VytvorPDF ();

	$pdf->AddFont('Times','','times.php');
	$pdf->AddFont('Times','B','timesbd.php');
	$pdf->AddFont("Times","BI","timesbi.php");
	$pdf->AddFont("Times","I","timesi.php");
  
  	$pdf->AddPage(); 
 	
    $pdf->TiskniSaduBunek($osobniUdaje, 5, 1);
	$pdf->TiskniSaduBunek($motivacniProgram, 3  ,0,1);     //sada, pocet mezer, tiskni vzdy, tiskni jen spustenou
	$pdf->TiskniSaduBunek($kurzZZTP, 3  ,0,1); 
	$pdf->TiskniSaduBunek($komunikacniKurz, 3  ,0,1);
	$pdf->TiskniSaduBunek($pcKurz, 3  ,0,1);
    $pdf->TiskniSaduBunek($bilancDiag, 3 ,0,1);
    $pdf->TiskniSaduBunek($pracDiag, 3  ,0,1);
    $pdf->TiskniSaduBunek($prax, 3,0,1);
    $pdf->TiskniSaduBunek($prof1Kurz, 3   ,0,1);
    $pdf->TiskniSaduBunek($prof2Kurz, 3    ,0,1);
    
    $pdf->Ln(5);
    $pdf->TiskniSaduBunek($podpisy, 0, 1);
	  
    //*/
        
//	$pdf->SetDisplayMode("real", "continuous");

	$filepathprefix= iconv('UTF-8', 'windows-1250', "./doku/RNH IP 2.cast ");
  	if (file_exists($filepathprefix. $Ucastnik->identifikator . ".pdf")) 
  	{
    	unlink($filepathprefix. $Ucastnik->identifikator . ".pdf");
  	}
  
  	$pdf->Output($filepathprefix. $Ucastnik->identifikator . ".pdf", F);
  

?>