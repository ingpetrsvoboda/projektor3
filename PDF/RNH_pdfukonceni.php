<?php
  //define('FPDF_FONTPATH','/fpdf16/font/');
  //require('/fpdf16/fpdf.php');
  	define('FPDF_FONTPATH','classes/PDF/Fonts/');
	require_once("autoload.php");
  $pdfpole = $_POST;
 
  
  foreach($pdfpole  as $klic => $hodnota) {
      $pdfpole['$klic'] = trim($pdfpole['$klic']);  
  }
  
 
//*
    $pdfhlavicka = PDF_Kontext::dejHlavicku();
		$pdfhlavicka->text("Individuální plán účastníka 3.část");
		$pdfhlavicka->zarovnani("C");
		$pdfhlavicka->vyskaPisma(14);
		$pdfhlavicka->obrazek("./PDF/loga_BW_Rodina_neni_handicap.jpg", null, null,165,8.6);
    $pdfpaticka = PDF_Kontext::dejPaticku();
		$pdfpaticka->text("Rodina není handicap - Individuální plán účastníka - 3.část  Účastník: ".$Ucastnik->identifikator);
		$pdfpaticka->zarovnani("C");
		$pdfpaticka->vyskaPisma(6);
		$pdfpaticka->cislovani = true;
		
    $titulek = new PDF_Odstavec;
		$titulek->Nadpis("DOKLAD O UKONČENÍ ÚČASTI V PROJEKTU");
		$titulek->ZarovnaniNadpisu("C");
                $titulek->VyskaPismaNadpisu(12);
    $titulek1 = new PDF_Odstavec;
                $titulek1->Nadpis('„Rodina není handicap“');
                $titulek1->ZarovnaniNadpisu("C");
                $titulek1->VyskaPismaNadpisu(12);

//		$titulek->text('„Rodina není handicap ji“');
//		$titulek->Text('Lákamí vůněhulás úmyval rohlivý jednovod lek lák hane bývá přehliv smeti. Smělý Umyslemi dopicí sudba rojskočár ří bý autný tlínům z zavěď. Umí jít A hafan bý obal stako tak úmyvatkov Buben muto. ');
		//$titulek->VyskaPismaTextu(12);
		//$titulek->ZarovnaniTextu("C");
		
  	$osobniUdaje = new PDF_SadaBunek();
		$osobniUdaje->Nadpis("Údaje o účastníkovi");
		//$osobniUdaje->vyskaPismaNadpisu(16); neumi
		
		$celeJmeno =  @$pole_pro_zobrazeni["titul"]." ".@$pole_pro_zobrazeni["jmeno"]." ".@$pole_pro_zobrazeni["prijmeni"];
		if (@$pole_pro_zobrazeni["titul_za"]) 
		{
			$celeJmeno = $celeJmeno.", ".@$pole_pro_zobrazeni["titul_za"];
		}
		$osobniUdaje->PridejBunku("Účastník: ", $celeJmeno,0,80);
		$osobniUdaje->PridejBunku("Identifikátor účastníka: ", $Ucastnik->identifikator,1);
		
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
		$osobniUdaje->PridejBunku("Bydliště: ",$adresapole, 1);
		//$osobniUdaje->PridejBunku("Bydliště: ", @$pole_pro_zobrazeni["ulice"].", ". @$pole_pro_zobrazeni["psc"]." ". @$pole_pro_zobrazeni["mesto"], 1);
		
		$osobniUdaje->PridejBunku("Vysílající úřad práce: ", @$pole_pro_zobrazeni["z_up"],0,80);
		$osobniUdaje->PridejBunku("Pracoviště vysílajícího úřadu práce: ", @$pole_pro_zobrazeni["prac_up"]);
		$osobniUdaje->NovyRadek();
		
    $ukonceniUcasti = new PDF_SadaBunek();
		$ukonceniUcasti->Nadpis("Údaje o účasti v projektu");		
    	$ukonceniUcasti->PridejBunku("Datum zahájení účasti v projektu: ", @$pole_pro_zobrazeni["datum_reg"]);    
    	$ukonceniUcasti->PridejBunku("Datum ukončení účasti v projektu: ", @$pdfpole['datum_ukonceni'], 1);
    	 
              $duvod_ukonceni_pole =  explode ("|", $pdfpole['duvod_ukonceni']);
	$ukonceniUcasti->PridejBunku("Důvod ukončení účasti v projektu: ", $duvod_ukonceni_pole[0],1);  
        if ( ($duvod_ukonceni_pole[0] == "2b ") or ($duvod_ukonceni_pole[0]== "3a ")  or ($duvod_ukonceni_pole[0] == "3b ")
	      and $pdfpole['popis_ukonceni']
	    ) {
	    $ukonceniUcasti->PridejBunku("Podrobnější popis důvodu ukončení účasti v projektu: ", " " ,1);
	    $ukonceniUcasti1 = new PDF_Odstavec;
	    $ukonceniUcasti1->text( @$pdfpole['popis_ukonceni']);
	}
	
    $poznKUkonceni = new PDF_Odstavec;
		$poznKUkonceni->Text("Možné důvody:");
		$poznKUkonceni->VyskaPismaTextu(8);
	$poznKUkonceni1 = new PDF_Odstavec;
		$poznKUkonceni1->Text("1. uplynutím doby stanovené pro účast klienta v projektu – řádné absolvování projektu");
		$poznKUkonceni1->VyskaPismaTextu(8);
	$poznKUkonceni1a = new PDF_Odstavec;
		$poznKUkonceni1a->Text("a. tato doba je 6 měsíců");
		$poznKUkonceni1a->VyskaPismaTextu(8);
		$poznKUkonceni1a->OdsazeniZleva(3);
        $poznKUkonceni1a->Predsazeni(3);
	$poznKUkonceni1b = new PDF_Odstavec;
		$poznKUkonceni1b->Text("b. v případě účasti klienta v profesním rekvalifikačním kurzu (tedy nikoli v kurzech Obsluha osobního počítače nebo Obsluha osobního počítače dle osnov ECDL START) nebo Pracovní praxi končí jeho účast po uplynutí 14 dní od absolvování kurzu či Pracovní praxe, pokud je doba jeho účasti v projektu delší než 6 měsíců");
		$poznKUkonceni1b->VyskaPismaTextu(8);
        $poznKUkonceni1b->OdsazeniZleva(3);
        $poznKUkonceni1b->Predsazeni(3);
    $poznKUkonceni2 = new PDF_Odstavec;
		$poznKUkonceni2->Text("2. předčasným ukončením účasti ze strany klienta");
		$poznKUkonceni2->VyskaPismaTextu(8);
	$poznKUkonceni2a = new PDF_Odstavec;
		$poznKUkonceni2a->Text("a. dnem předcházejícím nástupu klienta do pracovního poměru (ve výjimečných případech může být dohodnuto jinak)");
		$poznKUkonceni2a->VyskaPismaTextu(8);
        $poznKUkonceni2a->OdsazeniZleva(3);
        $poznKUkonceni2a->Predsazeni(3);
    $poznKUkonceni2b = new PDF_Odstavec;
		$poznKUkonceni2b->Text("b. výpovědí dohody o účasti v projektu klientem z jiného důvodu než nástupu do zaměstnání (ukončení bude v den předcházející dni vzniku důvodu ukončení)");
		$poznKUkonceni2b->VyskaPismaTextu(8);
        $poznKUkonceni2b->OdsazeniZleva(3);
        $poznKUkonceni2b->Predsazeni(3);
    $poznKUkonceni3 = new PDF_Odstavec;
		$poznKUkonceni3->Text("3. předčasným ukončením účasti ze strany dodavatele");
		$poznKUkonceni3->VyskaPismaTextu(8);
	$poznKUkonceni3a = new PDF_Odstavec;
		$poznKUkonceni3a->Text("a. pokud klient porušuje podmínky účasti v projektu, neplní své povinnosti při účasti na aktivitách projektu (zejména na rekvalifikaci) nebo jiným závažným způsobem maří účel účasti v projektu");
		$poznKUkonceni3a->VyskaPismaTextu(8);
        $poznKUkonceni3a->OdsazeniZleva(3);
        $poznKUkonceni3a->Predsazeni(3);
    $poznKUkonceni3b = new PDF_Odstavec;
		$poznKUkonceni3b->Text("b. ve výjimečných případech na základě podnětu vysílajícího ÚP, např. při sankčním vyřazení z evidence ÚP (ukončení bude v pracovní den předcházející dni vzniku důvodu ukončení)");
		$poznKUkonceni3b->VyskaPismaTextu(8);
        $poznKUkonceni3b->OdsazeniZleva(3);
        $poznKUkonceni3b->Predsazeni(3);
        
    $osvedceni = new PDF_SadaBunek();
		$osvedceni->Nadpis("Osvědčení o absolvování projektu Rodina není handicap");
		$osvedceni->PridejBunku("Účastníkovi bylo vydáno osvědčení dne: ", @$pdfpole['datum_certif'],1);        
        $osvedceni->NovyRadek();
	$poznamkaOsvedceni = new PDF_Odstavec;
		$poznamkaOsvedceni->Text("Po ukončení účasti klienta v projektu řádným způsobem nebo z důvodu nástupu do zaměstnání po absolvování alespoň 3 aktivit projektu získá účastník Osvědčení o absolvování projektu Rodina není handicap.");
	    $poznamkaOsvedceni->VyskaPismaTextu(8);
/*
 * vyhodnocení účasti klienta v projektu, shrnutí absolvovaných aktivit a provedených kontaktů se zaměstnavateli a v případě, že klient nezíská při účasti v projektu zaměstnání, také doporučení pro ÚP ohledně další práce s klientem.
*/
    $vyhodnoceni=new PDF_SadaBunek();
    $vyhodnoceni->Nadpis("Vyhodnocení");
    $vyhodnoceniMot = new PDF_Odstavec;
    $vyhodnoceniMot->Text(@$pdfpole['mot_hodnoceni']);
    $vyhodnoceniPC = new PDF_Odstavec;
    $vyhodnoceniPC->Text(@$pdfpole['pc_hodnoceni']);
    $vyhodnoceniBidi = new PDF_Odstavec;
    $vyhodnoceniBidi->Text(@$pdfpole['bidi_hodnoceni']);
    $vyhodnoceniPrdi = new PDF_Odstavec;
    $vyhodnoceniPrdi->Text(@$pdfpole['prdi_hodnoceni']);
    /*$vyhodnoceniPraxe = new PDF_Odstavec;
    $vyhodnoceniPraxe->Text(@$pdfpole['praxe_hodnoceni']);*/
    $vyhodnoceniProf1 = new PDF_Odstavec;
    $vyhodnoceniProf1->Text(@$pdfpole['prof1_hodnoceni']);
    $vyhodnoceniProf2 = new PDF_Odstavec;
    $vyhodnoceniProf2->Text(@$pdfpole['prof2_hodnoceni']);
    $vyhodnoceniPoradenstvi = new PDF_Odstavec;
    $vyhodnoceniPoradenstvi->Text(@$pdfpole['porad_hodnoceni']);
    $vyhodnoceniDoporuceni = new PDF_Odstavec;
    $vyhodnoceniDoporuceni->Text(@$pdfpole['doporuceni']);
    $vyhodnoceniDalsi = new PDF_Odstavec;
    $vyhodnoceniDalsi->Text(@$pdfpole['vyhodnoceni']);

    
    $podpisy = new PDF_SadaBunek();
        /*$kk = @$pole_pro_zobrazeni["z_up"];
        if ( @$pole_pro_zobrazeni["z_up"] == "Klatovy")
        {
        	if (@$pole_pro_zobrazeni["prac_up"] <> "Klatovy - pracoviště Klatovy")
        	{
        		$kk = "Sušice";
        	}
        }*/
	$kk = $Kancelar->plny_text;
	
        $podpisy->PridejBunku("Kontaktní kancelář: ", $kk, 1); 
    	$podpisy->PridejBunku("Dne ", @$pdfpole["datum_vytvor_dok"],1);
        $podpisy->NovyRadek(0,5);
    	$podpisy->PridejBunku("                       ......................................................                                            ......................................................","",1);
     	$podpisy->PridejBunku("                                        účastník                                                                                     koordinátor","");
        $podpisy->NovyRadek();

    
    
    	    	     	
    $neniPodpis = new PDF_SadaBunek;
                $neniPodpis->PridejBunku("V případě, že nebylo možné získat podpis účastníka, uveďte zde důvod: ", " ",1);
    $neniPodpis1 = new PDF_Odstavec;
		$neniPodpis1->text(@$pdfpole['neni_podpis']);
    		
		
    $poznamka = new PDF_Odstavec;
	$poznamka->Text("V případě důvodu ukončení 2a) je přílohou tohoto dokladu kopie pracovní smlouvy, v případě 2b) kopie výpovědi podané účastníkem.");
	$poznamka->VyskaPismaTextu(7);
		
    $priloha = new  PDF_SadaBunek;                //PDF_Odstavec;
            $priloha->PridejBunku("Příloha: ", " ",1);
    $priloha1 =  new PDF_Odstavec;
	    $priloha1->text(@$pdfpole['priloha']);
    
        
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
 	
        
        $pdf->Ln(5);
	$pdf->TiskniOdstavec($titulek);
        $pdf->TiskniOdstavec($titulek1);
	
    $pdf->Ln(10);
    $pdf->TiskniSaduBunek($osobniUdaje, 8, 1);

    $pdf->Ln(5);	
    $pdf->TiskniSaduBunek($ukonceniUcasti,8, 1);
    $pdf->TiskniOdstavec($ukonceniUcasti1);
    $pdf->Ln(5);
    $pdf->TiskniOdstavec($poznKUkonceni); 
    $pdf->TiskniOdstavec($poznKUkonceni1);
    $pdf->TiskniOdstavec($poznKUkonceni1a);
    $pdf->TiskniOdstavec($poznKUkonceni1b);
    $pdf->TiskniOdstavec($poznKUkonceni2);
    $pdf->TiskniOdstavec($poznKUkonceni2a);
    $pdf->TiskniOdstavec($poznKUkonceni2b);
    $pdf->TiskniOdstavec($poznKUkonceni3);
    $pdf->TiskniOdstavec($poznKUkonceni3a);
    $pdf->TiskniOdstavec($poznKUkonceni3b);
    $pdf->Ln(10);
    $pdf->TiskniSaduBunek($osvedceni, 0, 1);
    $pdf->TiskniOdstavec($poznamkaOsvedceni);

    $pdf->AddPage();
    $pdf->TiskniSaduBunek($vyhodnoceni, 0, 1);
    $pdf->TiskniOdstavec($vyhodnoceniMot);
    $pdf->TiskniOdstavec($vyhodnoceniPC);
    $pdf->TiskniOdstavec($vyhodnoceniBidi);
    $pdf->TiskniOdstavec($vyhodnoceniPrdi);
    /*$pdf->TiskniOdstavec($vyhodnoceniPraxe);*/
    $pdf->TiskniOdstavec($vyhodnoceniProf1);
    $pdf->TiskniOdstavec($vyhodnoceniProf2);
    $pdf->TiskniOdstavec($vyhodnoceniPoradenstvi);
    $pdf->TiskniOdstavec($vyhodnoceniDoporuceni);
    $pdf->TiskniOdstavec($vyhodnoceniDalsi);
    $pdf->Ln(5);
    
    $pdf->TiskniSaduBunek($podpisy, 0, 1);
	
    $pdf->Ln(10);
    
    $pdf->TiskniSaduBunek($neniPodpis,0,1);
    $pdf->TiskniOdstavec($neniPodpis1,0,1);
    $pdf->Ln(10);
    
   
    if ( ($duvod_ukonceni_pole[0] == "2a ") or ($duvod_ukonceni_pole[0]== "2b ")) {
	$pdf->TiskniOdstavec($poznamka);
	$pdf->Ln(3);
	$pdf->TiskniSaduBunek($priloha,0,1);
        $pdf->TiskniOdstavec($priloha1,0,1);
    }
	
	//$pdf->TiskniOdstavec($neniPodpis);
	//$pdf->TiskniOdstavec($mocTecek);
	//$pdf->TiskniOdstavec($priloha);    //*/
        
//	$pdf->SetDisplayMode("real", "continuous");

	$filepathprefix= iconv('UTF-8', 'windows-1250', "./doku/RNH ukonceni ");
  	if (file_exists($filepathprefix. $Ucastnik->identifikator . ".pdf")) 
  	{
	    unlink($filepathprefix. $Ucastnik->identifikator . ".pdf");
  	}
  
  	$pdf->Output($filepathprefix. $Ucastnik->identifikator . ".pdf", F);
  
 

?>