<?php
  //define('FPDF_FONTPATH','/fpdf16/font/');
  //require('/fpdf16/fpdf.php');
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
	require_once("autoload.php");  
  $pdfpole = $_POST;
 
  
  foreach($pdfpole  as $klic => $hodnota) {
      $pdfpole['$klic'] = trim($pdfpole['$klic']);  //??
  }
  


    $pdfhlavicka = PDFContext::dejHlavicku();
		//$pdfhlavicka->text("Individuální plán účastníka - 1. část");
		$pdfhlavicka->zarovnani("C");
		$pdfhlavicka->vyskaPisma(14);
		$pdfhlavicka->obrazek("./PDF/logo_agp_bw.png", null, null,90,12);
		
    $pdfpaticka = PDFContext::dejPaticku();
		$pdfpaticka->text("Dohoda o zprostředkování zaměstnání v projektu Personal Service  Zájemce: ".$Zajemce->identifikator);
		$pdfpaticka->zarovnani("C");
		$pdfpaticka->vyskaPisma(6);
		$pdfpaticka->cislovani = true;
  
    $titulka1 = new Projektor_Pdf_Odstavec;
            $titulka1->Nadpis("DOHODA O ZPROSTŘEDKOVÁNÍ ZAMĚSTNÁNÍ ");
            $titulka1->ZarovnaniNadpisu("C");
            $titulka1->VyskaPismaNadpisu(14);
    $titulka2 = new Projektor_Pdf_Odstavec;        
            $titulka2->Nadpis('„Personal Service“');
            $titulka2->ZarovnaniNadpisu("C");
            $titulka2->VyskaPismaNadpisu(14);
  
    $strany = new Projektor_Pdf_Odstavec;
            $strany->Nadpis("S t r a n y   d o h o d y ");
            $strany->ZarovnaniNadpisu("L");
            $strany->VyskaPismaNadpisu(11);
  
    $stranaDodavatel = new Projektor_Pdf_Odstavec;
            $stranaDodavatel->text("Grafia, společnost s ručením omezeným" . chr(13) . chr(10).
                                "zapsaná v obchodním rejstříku vedeném Krajským soudem v Plzni, odd. C, vl. 3067" . chr(13) . chr(10).
                                "sídlo: Plzeň, Budilova 4, PSČ 301 21" . chr(13) . chr(10).
                                "zastupující: Mgr. Jana Brabcová, jednatelka společnosti" . chr(13) . chr(10).
                                "IČ: 47714620" . chr(13) . chr(10).
                                "DIČ: CZ 47714620" . chr(13) . chr(10).
                                "bankovní spojení: ČSOB" . chr(13) . chr(10).
                                "č. účtu:  275795033/0300" . chr(13) . chr(10).
                                "zapsán v obchodním rejstříku vedeném Krajským soudem v Plzni, v oddílu C vložka 3067" . chr(13) . chr(10).
                                "(dále jen „Dodavatel“)" . chr(13) . chr(10));
    $a = new Projektor_Pdf_Odstavec;
	    $a->text("a        ");
                                
  
    $stranaUcastnik = new Projektor_Pdf_SadaBunek();
	          $celeJmeno =  @$pdfpole["titul"]." ".@$pdfpole["jmeno"]." ".@$pdfpole["prijmeni"];
	          if (@$pdfpole["titul_za"]) 
		  {
			$celeJmeno = $celeJmeno.", ".@$pdfpole["titul_za"];
		  }
	    $stranaUcastnik->PridejBunku("jméno, příjmení, titul: ", $celeJmeno,1);
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
            $stranaUcastnik->PridejBunku("bydliště: ", $adresapole,1);
		
	    $stranaUcastnik->PridejBunku("nar.: ", @$pdfpole["datum_narozeni"],1);
		
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
            if  ($adresapole)   {
                $stranaUcastnik->PridejBunku( "adresa dojíždění odlišná od místa bydliště: ", $adresapole,1);
            }
                
	    $stranaUcastnik->PridejBunku("identifikační číslo zájemce: ", $Zajemce->identifikator,1);
	    $stranaUcastnik->PridejBunku("(dále jen „Zájemce“)", "",1);
		
		
    $spolecneUzaviraji1 = new Projektor_Pdf_Odstavec;
	$spolecneUzaviraji1->text("Dodavatel a Zájemce společně (dále jen „Strany dohody“) a každý z nich (dále jen „Strana dohody“)");
			     //chr(13) . chr(10). chr(13) . chr(10). chr(13) . chr(10).chr(13) . chr(10).
    $spolecneUzaviraji2 = new Projektor_Pdf_Odstavec;		     
	$spolecneUzaviraji2->text("uzavírají níže uvedeného dne, měsíce a roku tuto" );
    
    $dohoda1 = new Projektor_Pdf_Odstavec;
	    $dohoda1->Nadpis("DOHODA O ZPROSTŘEDKOVÁNÍ ZAMĚSTNÁNÍ ");
	    $dohoda1->ZarovnaniNadpisu("C");
            $dohoda1->VyskaPismaNadpisu(12);
    $dohoda2 = new Projektor_Pdf_Odstavec;
	    $dohoda2->Nadpis("„Personal Service“");
	    $dohoda2->ZarovnaniNadpisu("C");
            $dohoda2->VyskaPismaNadpisu(12);
		
		//$osobniUdaje->PridejBunku("Vysílající úřad práce: ", @$pdfpole["z_up"],0,80);
                //$osobniUdaje->PridejBunku("Stav: ",@$pdfpole["stav"],1);
		//$osobniUdaje->PridejBunku(" Pracoviště úřadu práce: ", @$pdfpole["prac_up"],1);
    
$odstavec1 = new Projektor_Pdf_Odstavec;
    $odstavec1 -> Nadpis("1. PREAMBULE");

$odstavec1_1 = new Projektor_Pdf_Odstavec;
    $odstavec1_1->text("1.1 Projekt „Personal Service“ je projekt společnosti Grafia, s.r.o., Plzeň.");
    $odstavec1_1->predsazeni(6);
    $odstavec1_1->odsazeniZleva(6);

$odstavec1_2 = new Projektor_Pdf_Odstavec;        
    $odstavec1_2->text("1.2 Hlavním cílem aktivit projektu „Personal Service“  pro Zájemce je zprostředkování zaměstnání. Zprostředkováním zaměstnání pro účly této dohody se rozumí vyhledání zaměstnání pro fyzickou osobu, která se o práci uchází (Zájemce), a vyhledání zaměstnanců pro zaměstnavatele, který hledá nové pracovní síly.");
    $odstavec1_2->predsazeni(6);
    $odstavec1_2->odsazeniZleva(6);
    
$odstavec2 = new Projektor_Pdf_Odstavec;
    $odstavec2 -> Nadpis("2. Předmět dohody");

$odstavec2_1 = new Projektor_Pdf_Odstavec;
    $odstavec2_1->text("2.1. Předmětem dohody je stanovení podmínek účasti Zájemce na aktivitách projektu Personal Service a úprava práv a povinností Stran dohody při realizaci těchto aktivit.");
    $odstavec2_1->predsazeni(6);
    $odstavec2_1->odsazeniZleva(6);
 
$odstavec3 = new Projektor_Pdf_Odstavec;
    $odstavec3 -> Nadpis("3. Povinnosti a práva Zájemce o služby projektu „Personal Service“");  

$odstavec3_1 = new Projektor_Pdf_Odstavec;
    $odstavec3_1->text("3.1. Zájemce potvrzuje, že se účastnil vstupní schůzky, kde sdělil údaje pro tuto dohodu a registrační dotazník. Současně Zájemce souhlasí se zpracováním osobních údajů pro účely zprostředkování zaměstnání a poskytování dalších služeb projektu Personal Service.");
    $odstavec3_1->predsazeni(6);
    $odstavec3_1->odsazeniZleva(6);

$odstavec3_2 = new Projektor_Pdf_Odstavec;
    $odstavec3_2->text("3.2. Zájemce se zavazuje k tomu, že se bude v dohodnutých termínech účastnit schůzek a dalších aktivit projektu Personal Service. Zájemce se zavazuje, že poskytne Dodavateli v maximální míře kopie dokladů osvědčujících uváděné skutečnosti, zejména doklady o ukončeném vzdělání, kurzech a předchozích zaměstnáních. Porušení těchto závazků může být důvodem okamžité výpovědi této dohody ze strany Dodavatele. ");
    $odstavec3_2->predsazeni(6);
    $odstavec3_2->odsazeniZleva(6);

$odstavec3_3 = new Projektor_Pdf_Odstavec;
    $odstavec3_3->text("3.3. Zájemce se zavazuje bezodkladně informovat Dodavatele o všech skutečnostech, souvisejících s jeho účastí na projektu, zejména o důvodech absence na aktivitách projektu a o překážkách bránících mu v účasti na pohovorech a výběrových řízeních u potenciálních zaměstnavatelů.");
    $odstavec3_3->predsazeni(6);
    $odstavec3_3->odsazeniZleva(6);

$odstavec3_4 = new Projektor_Pdf_Odstavec;
    $odstavec3_4->text("3.4. Zájemce souhlasí se zařazením do databáze zájemců o zaměstnání Personal service, kterou vlastní Dodavatel, a s poskytováním osobních, vzdělanostních, kvalifikačních a dalších údajů potenciálním zaměstnavatelům za účelem zprostředkování zaměstnání u těchto zaměstnavatelů.");
    $odstavec3_4->predsazeni(6);
    $odstavec3_4->odsazeniZleva(6);

$odstavec3_5 = new Projektor_Pdf_Odstavec;
    $odstavec3_5->text("3.5. Zájemce, který získal zaměstnání anebo se sebezaměstnal v průběhu své účasti v projektu anebo kdykoli v případě, že získal zaměstnání na základě doporučení Dodavatele:");
    $odstavec3_5->predsazeni(6);
    $odstavec3_5->odsazeniZleva(6);

$odstavec3_5_a = new Projektor_Pdf_Odstavec;
    $odstavec3_5_a->text("a)   zavazuje se informovat do 3 pracovních dnů Dodavatele o této skutečnosti");
    $odstavec3_5_a->predsazeni(6);
    $odstavec3_5_a->odsazeniZleva(14);
$odstavec3_5_b = new Projektor_Pdf_Odstavec;
    $odstavec3_5_b->text("b)   souhlasí se svým uvedením v seznamu osob, které získaly pomocí služeb Personal Service zaměstnání anebo se sebezaměstnaly, a to bez uvedení osobních údajů, tedy anonymně.");
    $odstavec3_5_b->predsazeni(6);
    $odstavec3_5_b->odsazeniZleva(14);
$odstavec3_5_c = new Projektor_Pdf_Odstavec;
    $odstavec3_5_c->text("c)   Zájemce, který získal zaměstnání na základě doporučení Dodavatele, se zavazuje dodat Dodavateli kopii těch částí své uzavřené pracovní smlouvy, dohody či obdobné smlouvy, z nichž bude zřejmý zaměstnavatel, datum zahájení pracovního poměru, pracovní pozice, případně náplň práce. Zájemce může poskytnout i údaj o své skutečné nástupní mzdě nebo platu, pokud se nezavázal tento údaj nesdělovat a pokud to sám uzná za přijatelné.");
    $odstavec3_5_c->predsazeni(6);
    $odstavec3_5_c->odsazeniZleva(14);



$odstavec4 = new Projektor_Pdf_Odstavec;
    $odstavec4 -> Nadpis("4. Ukončení dohody");  
    
$odstavec4_1 = new Projektor_Pdf_Odstavec;

    $odstavec4_1->text("4.1. Tuto dohodu lze ukončit dohodou stran nebo jednostranou výpovědí jedné smluvní strany. K ukončení účasti výpovědí dojde dnem, kdy byla výpověď doručena druhé smluvní straně.");
    $odstavec4_1->predsazeni(6);
    $odstavec4_1->odsazeniZleva(6);
    
$odstavec4_2 = new Projektor_Pdf_Odstavec;
    $odstavec4_2->text("4.2. Ukončením Dohody zanikají veškeré závazky z této Dohody s výjimkou závazků dle bodu 3.5 . ");
    $odstavec4_2->predsazeni(6);
    $odstavec4_2->odsazeniZleva(6);
  

$odstavec5 = new Projektor_Pdf_Odstavec;
    $odstavec5 -> Nadpis("5. Povinnosti dodavatele");    

$odstavec5_1 = new Projektor_Pdf_Odstavec;
    $odstavec5_1->text("5.1. Dodavatel se zavazuje poskytnout Zájemci zdarma aktivity projektu bezprostředně související se zprostředkováním zaměstnání. Na případné další služby a dodávky se tato dohoda nevztahuje.");
    $odstavec5_1->predsazeni(6);
    $odstavec5_1->odsazeniZleva(6);        

$odstavec5_2 = new Projektor_Pdf_Odstavec;
    $odstavec5_2->text("5.2. Dodavatel se bude snažit v součinnosti s potenciálním zaměstnavatelem co nejlépe informovat Zájemce o všech podmínkách účasti na pohovorech a výběrových řízeních (například o termínech, místech, nutných dokladech či jejich kopiích, potvrzení od lékaře, nutného očkování).");
    $odstavec5_2->predsazeni(6);
    $odstavec5_2->odsazeniZleva(6);     
    


$odstavec6 = new Projektor_Pdf_Odstavec;
    $odstavec6 -> Nadpis("6. Závěrečná ustanovení");   

$odstavec6_1 = new Projektor_Pdf_Odstavec;
    $odstavec6_1->text("6.1. Tuto Dohodu lze měnit či doplňovat pouze po dohodě smluvních stran formou písemných a číslovaných dodatků.");
    $odstavec6_1->predsazeni(6);
    $odstavec6_1->odsazeniZleva(6);       

$odstavec6_2 = new Projektor_Pdf_Odstavec;
    $odstavec6_2->text("6.2. Tato Dohoda je sepsána ve dvou vyhotoveních s platností originálu, přičemž Zájemce i Dodavatel obdrží po jednom vyhotovení.");
    $odstavec6_2->predsazeni(6);
    $odstavec6_2->odsazeniZleva(6);

$odstavec6_3 = new Projektor_Pdf_Odstavec;
    $odstavec6_3->text("6.3. Tato Dohoda nabývá platnosti a účinnosti dnem jejího podpisu oběma smluvními stranami; tímto dnem jsou její účastníci svými projevy vázáni.");
    $odstavec6_3->predsazeni(6);
    $odstavec6_3->odsazeniZleva(6);    

$odstavec6_4 = new Projektor_Pdf_Odstavec;
    $odstavec6_4->text("6.4. Dodavatel i Zájemce shodně prohlašují, že si tuto Dohodu před jejím podpisem přečetli, že byla uzavřena podle jejich pravé a svobodné vůle, určitě, vážně a srozumitelně, nikoliv v tísni za nápadně nevýhodných podmínek. Smluvní strany potvrzují autentičnost této Dohody svým podpisem.");
    $odstavec6_4->predsazeni(6);
    $odstavec6_4->odsazeniZleva(6);    
    
   
   
    


$podpisy = new Projektor_Pdf_SadaBunek();
      /*  $kk = @$pdfpole["z_up"];  
        if ( @$pdfpole["z_up"] == "Klatovy")
        {
        	if (@$pdfpole["prac_up"] <> "Klatovy - pracoviště Klatovy")
        	{
        		$kk = "Sušice";
        	}
        }   */
	$kk = $Kancelar->plny_text;
       	
    	$podpisy->PridejBunku("Kontaktní kancelář: ", $kk, 1); 
    	$podpisy->PridejBunku("Dne ", @$pdfpole["datum_vytvor_smlouvy"],1);
	$podpisy->NovyRadek(0,1);
	$podpisy->PridejBunku("                       Zájemce:                                                                                   Dodavatel:","",1);
        $podpisy->NovyRadek(0,5);
        //  $podpisy->NovyRadek(0,3);
    	$podpisy->PridejBunku("                       ......................................................                                            ......................................................","",1);
     	$podpisy->PridejBunku("                           " . str_pad(str_pad($celeJmeno, 30, " ", STR_PAD_BOTH) , 92) . $User->name,"",1);
//	$podpisy->PridejBunku("                           " . $celeJmeno . "                                                                         " . $User->name,"",1);

        //$podpisy->PridejBunku("                                     podpis účastníka                                                                podpis, jméno a příjmení","",1);
	$podpisy->PridejBunku("                                                                                                                             okresní koordinátor projektu","");
        $podpisy->NovyRadek();



//**********************************************

    $pdfdebug = PDFContext::dejDebug();
    $pdfdebug->debug(0);

    ob_clean;
	$pdf = new Projektor_Pdf_VytvorPDF ();

	$pdf->AddFont('Times','','times.php');
	$pdf->AddFont('Times','B','timesbd.php');
	$pdf->AddFont("Times","BI","timesbi.php");
	$pdf->AddFont("Times","I","timesi.php");

        $pdf->AddPage();   //uvodni stranka
        $pdf->Ln(100);
        $pdf->TiskniOdstavec($titulka1);
        $pdf->TiskniOdstavec($titulka2);
        
        

  	$pdf->AddPage();   //dalsi stranky 

	$pdf->Ln(5);
        
        $pdf->TiskniOdstavec($strany);
	$pdf->Ln(10); 
        $pdf->TiskniOdstavec($stranaDodavatel);
	
	$pdf->Ln(10); 
	$pdf->TiskniOdstavec($a);
       
        $pdf->Ln(10); 
        $pdf->TiskniSaduBunek($stranaUcastnik);
	
	$pdf->Ln(10); 
	$pdf->TiskniOdstavec($spolecneUzaviraji1);
	$pdf->Ln(5); 
	$pdf->TiskniOdstavec($spolecneUzaviraji2);
	
	$pdf->Ln(15); 
	$pdf->TiskniOdstavec($dohoda1);
	$pdf->TiskniOdstavec($dohoda2);
	
        
	$pdf->AddPage();   //cislovane odstavce
	$pdf->Ln(7); 
        $pdf->TiskniOdstavec($odstavec1);
        $pdf->TiskniOdstavec($odstavec1_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec1_2);

        $pdf->Ln(7); 
        $pdf->TiskniOdstavec($odstavec2);
        $pdf->TiskniOdstavec($odstavec2_1);
                
	$pdf->Ln(7); 	 
        $pdf->TiskniOdstavec($odstavec3);
        $pdf->TiskniOdstavec($odstavec3_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_3);

        $pdf->Ln(2);  
        $pdf->TiskniOdstavec($odstavec3_4);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_5);
	$pdf->Ln(2); 
        
        $pdf->TiskniOdstavec($odstavec3_5_a);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_5_b);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_5_c);
	$pdf->Ln(2);           


        $pdf->Ln(7);  
        $pdf->TiskniOdstavec($odstavec4);
        $pdf->TiskniOdstavec($odstavec4_1);

        $pdf->Ln(2); 
	$pdf->TiskniOdstavec($odstavec4_2);

        $pdf->Ln(7); 
	$pdf->TiskniOdstavec($odstavec5);
        $pdf->TiskniOdstavec($odstavec5_1);

	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_2);

        $pdf->Ln(7); 
	$pdf->TiskniOdstavec($odstavec6);
        $pdf->TiskniOdstavec($odstavec6_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec6_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec6_3);     
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec6_4);  	
        
       
        
        $pdf->Ln(20);
        $pdf->TiskniSaduBunek($podpisy, 0, 1);



  //$pdf->Output("doc.pdf",D);
  
$filepathprefix= iconv('UTF-8', 'windows-1250', "./doku/AGP_SML_ZA_");    // "C:/_Export Projektor/PDF/AGP_SML_ZA_"
if (file_exists($filepathprefix. $Zajemce->identifikator . ".pdf"))  	{
    	unlink($filepathprefix. $Zajemce->identifikator . ".pdf");
}

$pdf->Output($filepathprefix. $Zajemce->identifikator . ".pdf", F);  
  
  
  
//  if (file_exists("./doku/smlouva". $Ucastnik->identifikator . ".pdf")) {
//    unlink("./doku/smlouva". $Ucastnik->identifikator . ".pdf");
//  }
  
//  $pdf->Output("doku/smlouva". $Ucastnik->identifikator . ".pdf", F);
  
  
 

?>