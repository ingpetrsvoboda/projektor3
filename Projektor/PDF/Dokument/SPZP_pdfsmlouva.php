<?php
  //define('FPDF_FONTPATH','/fpdf16/font/');
  //require('/fpdf16/fpdf.php');
        define('FPDF_FONTPATH','classes/PDF/Fonts/');
	require_once("autoload.php");  
  $pdfpole = $_POST;
 
  
  foreach($pdfpole  as $klic => $hodnota) {
      $pdfpole['$klic'] = trim($pdfpole['$klic']);  //??
  }
  
//echo "<br>" . $pdfpole["datum_vytvor_smlouvy"]    ;

    $pdfhlavicka = PDFContext::dejHlavicku();
		//$pdfhlavicka->text("Individuální plán účastníka - 1. část");
		$pdfhlavicka->zarovnani("C");
		$pdfhlavicka->vyskaPisma(14);
		$pdfhlavicka->obrazek("./PDF/loga_SPZP_vedlesebe_bw.jpg", null, null,167,14);
		
    $pdfpaticka = PDFContext::dejPaticku();
		$pdfpaticka->text("Dohoda o účasti v projektu „S pomocí za prací v Plzeňském kraji“  Účastník: ".$Ucastnik->identifikator);
		$pdfpaticka->zarovnani("C");
		$pdfpaticka->vyskaPisma(6);
		$pdfpaticka->cislovani = true;
  
    $titulka1 = new Projektor_Pdf_Odstavec;
            $titulka1->Nadpis("DOHODA O ÚČASTI V PROJEKTU ");
            $titulka1->ZarovnaniNadpisu("C");
            $titulka1->VyskaPismaNadpisu(14);
    $titulka2 = new Projektor_Pdf_Odstavec;        
            $titulka2->Nadpis('„S pomocí za prací v Plzeňském kraji“');
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
                
	    $stranaUcastnik->PridejBunku("identifikační číslo účastníka: ", $Ucastnik->identifikator,1);
	    $stranaUcastnik->PridejBunku("(dále jen „Účastník“)", "",1);
		
		
    $spolecneUzaviraji1 = new Projektor_Pdf_Odstavec;
	$spolecneUzaviraji1->text("Dodavatel a Účastník společně (dále jen „Strany dohody“) a každý z nich (dále jen „Strana dohody“)");
			     //chr(13) . chr(10). chr(13) . chr(10). chr(13) . chr(10).chr(13) . chr(10).
    $spolecneUzaviraji2 = new Projektor_Pdf_Odstavec;		     
	$spolecneUzaviraji2->text("uzavírají níže uvedeného dne, měsíce a roku tuto" );
    
    $dohoda1 = new Projektor_Pdf_Odstavec;
	    $dohoda1->Nadpis("DOHODU O ÚČASTI V PROJEKTU");
	    $dohoda1->ZarovnaniNadpisu("C");
            $dohoda1->VyskaPismaNadpisu(12);
    $dohoda2 = new Projektor_Pdf_Odstavec;
	    $dohoda2->Nadpis("„S pomocí za prací v Plzeňském kraji“");
	    $dohoda2->ZarovnaniNadpisu("C");
            $dohoda2->VyskaPismaNadpisu(12);
		
		//$osobniUdaje->PridejBunku("Vysílající úřad práce: ", @$pdfpole["z_up"],0,80);
                //$osobniUdaje->PridejBunku("Stav: ",@$pdfpole["stav"],1);
		//$osobniUdaje->PridejBunku(" Pracoviště úřadu práce: ", @$pdfpole["prac_up"],1);
    
$odstavec1 = new Projektor_Pdf_Odstavec;
    $odstavec1 -> Nadpis("1. PREAMBULE");

$odstavec1_1 = new Projektor_Pdf_Odstavec;
    $odstavec1_1->text("1.1. Projekt „S pomocí za prací v Plzeňském kraji“, reg. č. CZ.1.04/2.1.00/13.00011 (dále jen „S pomocí za prací“) je projekt Úřadu práce v Plzni, jenž je financován z prostředků ESF prostřednictvím Operačního programu Lidské zdroje a zaměstnanost a státního rozpočtu ČR. Projekt byl připraven v souladu s Operačním programem Lidské zdroje a zaměstnanost (OP LZZ) a podán do výzvy pro předkládání projektů vyhlášené MPSV v rámci prioritní osy 4.2a OP LZZ – Aktivní politiky trhu práce. Projekt byl schválen poskytovatelem a jeho realizace byla zahájena dne 1. 2. 2009.");
    $odstavec1_1->predsazeni(6);
    $odstavec1_1->odsazeniZleva(6);

$odstavec1_2 = new Projektor_Pdf_Odstavec;        
    $odstavec1_2->text(" 1.2. Projekt představuje soubor aktivit a nástrojů určených pro dosažení cílů projektu. Hlavním cílem „S pomocí za prací“ je snižování nezaměstnanosti a předcházení dlouhodobé nezaměstnanosti v Plzeňském kraji. Záměrem projektu je pracovat s cílovými skupinami osob tak, aby se zvýšila jejich zaměstnatelnost a šance pro uplatnění na trhu práce a aby jejich návrat na trh práce byl co nejrychlejší a s co nejlepšími předpoklady pro setrvání na pracovní pozici.");
    $odstavec1_2->predsazeni(6);
    $odstavec1_2->odsazeniZleva(6);
    
$odstavec2 = new Projektor_Pdf_Odstavec;
    $odstavec2 -> Nadpis("2. Předmět dohody");

$odstavec2_1 = new Projektor_Pdf_Odstavec;
    $odstavec2_1->text("2.1. Předmětem dohody je stanovení podmínek účasti Účastníka na aktivitách projektu S pomocí za prací v Plzeňském kraji a úprava práv a povinností Stran dohody při realizaci těchto aktivit.");
    $odstavec2_1->predsazeni(6);
    $odstavec2_1->odsazeniZleva(6);
 
$odstavec3 = new Projektor_Pdf_Odstavec;
    $odstavec3 -> Nadpis("3. Povinnosti a práva účastníka projektu „S pomocí za prací v Plzeňském kraji“");  

$odstavec3_1 = new Projektor_Pdf_Odstavec;
    $odstavec3_1->text("3.1. Účastník potvrzuje, že se účastnil výběrové schůzky, kde byl seznámen s prezentací projektu S pomocí za prací v Plzeňském kraji, na půdě Úřadu práce podepsal Souhlas se zařazením do projektu ESF a obdržel Základní poučení účastníka v projektu ESF.");
    $odstavec3_1->predsazeni(6);
    $odstavec3_1->odsazeniZleva(6);

$odstavec3_2 = new Projektor_Pdf_Odstavec;
    $odstavec3_2->text("3.2. Účastník se zavazuje k tomu, že v dohodnutém termínu schůzky, nejvýše do 5 pracovních dnů od podpisu této Dohody se osobně dostaví do Kontaktní kanceláře, kde poskytne své osobní údaje a údaje o svém vzdělání a předchozích zaměstnáních a kde mu bude vypracována první část Individuálního plánu. Účastník se zavazuje, že poskytne Dodavateli v maximální míře kopie dokladů osvědčujících uváděné skutečnosti, zejména doklady o ukončeném vzdělání, kurzech a předchozích zaměstnáních.");
    $odstavec3_2->predsazeni(6);
    $odstavec3_2->odsazeniZleva(6);

$odstavec3_3 = new Projektor_Pdf_Odstavec;
    $odstavec3_3->text("3.3. Individuální plán se skládá z několika částí.");
    $odstavec3_3->predsazeni(6);
    $odstavec3_3->odsazeniZleva(6);  

$odstavec3_3_a = new Projektor_Pdf_Odstavec;
    $odstavec3_3_a->text("a)   První část individuálního plánu obsahuje charakteristiku účastníka, která zahrnuje jeho nacionále, údaje o dosaženém vzdělání a získaných dovednostech, o předchozích pracovních zkušenostech, o zdravotním stavu a charakterových předpokladech, motivaci k práci, potřebách na doplnění vzdělání, představách o jeho dalším pracovním zařazení atd.");
    $odstavec3_3_a->predsazeni(6);
    $odstavec3_3_a->odsazeniZleva(14);
$odstavec3_3_b = new Projektor_Pdf_Odstavec;
    $odstavec3_3_b->text("b)   Další část individuálního plánu bude dle vyhodnocení první části sestavený plán účasti v projektu, tedy doporučení aktivit, jichž by se klient měl účastnit, zaměstnavatelů, na které by se měl zaměřit při hledání práce atd.");
    $odstavec3_3_b->predsazeni(6);
    $odstavec3_3_b->odsazeniZleva(14);
$odstavec3_3_c = new Projektor_Pdf_Odstavec;
    $odstavec3_3_c->text("c)   Individuální plán se bude na schůzkách účastníka s koordinátorem v Kontaktní kanceláři průběžně aktualizovat, doplňovat anebo měnit. Změněný individuální plán bude znovu vytištěn buď jako celek anebo formou dodatku a podepsán účastníkem.");
    $odstavec3_3_c->predsazeni(6);
    $odstavec3_3_c->odsazeniZleva(14);

$odstavec3_4 = new Projektor_Pdf_Odstavec;
    $odstavec3_4->text("3.4. Účastník se zavazuje bezodkladně informovat Dodavatele o všech skutečnostech, souvisejících s jeho účastí na projektu, zejména o důvodech absence na aktivitách projektu a o překážkách bránících mu v účasti na rekvalifikačním kurzu.");
    $odstavec3_4->predsazeni(6);
    $odstavec3_4->odsazeniZleva(6);

$odstavec3_5 = new Projektor_Pdf_Odstavec;
    $odstavec3_5->text("3.5. Účastník se zavazuje k tomu, že veškeré absence na aktivitách, jichž se dle Individuálního plánu má účastnit, do 3 dnů řádně omluví, a to dokladem prokazujícím nemoc, návštěvu lékaře, ošetřování člena rodiny, případně jiným dokladem prokazujícím důvod absence.");
    $odstavec3_5->predsazeni(6);
    $odstavec3_5->odsazeniZleva(6);

$odstavec3_6 = new Projektor_Pdf_Odstavec;
    $odstavec3_6->text("3.6. Účastník bere na vědomí, že jestliže se nebude bez vážných důvodů účastnit aktivit projektu, zejména rekvalifikačních kurzů, bude porušovat své studijní a výcvikové povinnosti, porušovat předpisy a řády rekvalifikačního zařízení nebo nedokončí rekvalifikační kurz ve stanoveném termínu, Dodavatel tuto skutečnost oznámí příslušnému Úřadu práce. Pokud bude tímto chováním porušena povinnost Účastníka vůči Úřadu práce vyplývající např. z Dohody o rekvalifikaci nebo z uzavřeného IAP, bude ze strany příslušného Úřadu práce zahájeno řízení o sankčním vyřazením z evidence, případně také další sankční kroky.");
    $odstavec3_6->predsazeni(6);
    $odstavec3_6->odsazeniZleva(6);

$odstavec3_7 = new Projektor_Pdf_Odstavec;
    $odstavec3_7->text("3.7. Účastník se zavazuje potvrzovat Dodavateli podpisy v Prezenčních listinách nebo v Návštěvní knize svou účast (případně informace o nenastoupení) ve všech aktivitách projektu.");
    $odstavec3_7->predsazeni(6);
    $odstavec3_7->odsazeniZleva(6);

$odstavec3_8 = new Projektor_Pdf_Odstavec;
    $odstavec3_8->text("3.8. Účastník se rovněž zavazuje:");
    $odstavec3_8->predsazeni(6);
    $odstavec3_8->odsazeniZleva(6);

$odstavec3_8_a = new Projektor_Pdf_Odstavec;
    $odstavec3_8_a->text("a)   docházet do příslušné Kontaktní kanceláře na dohodnuté schůzky a spolupracovat s koordinátory projektu v této kanceláři a dalšími pracovníky projektu");
    $odstavec3_8_a->predsazeni(6);
    $odstavec3_8_a->odsazeniZleva(14);
$odstavec3_8_b= new Projektor_Pdf_Odstavec;
    $odstavec3_8_b->text("b)   účastnit se doporučených aktivit uvedených v jednotlivých částech a dodatcích Individuálního plánu");
    $odstavec3_8_b->predsazeni(6);
    $odstavec3_8_b->odsazeniZleva(14);
$odstavec3_8_c= new Projektor_Pdf_Odstavec;
    $odstavec3_8_c->text("c)   účastnit se kurzů projektu S pomocí za prací v Plzeňském kraji");
    $odstavec3_8_c->predsazeni(6);
    $odstavec3_8_c->odsazeniZleva(14);

$odstavec3_9 = new Projektor_Pdf_Odstavec;
    $odstavec3_9->text("3.9. Účastník souhlasí se svým uvedením v seznamu účastníků zařazených do rekvalifikace");
    $odstavec3_9->predsazeni(6);
    $odstavec3_9->odsazeniZleva(6);

$odstavec3_10 = new Projektor_Pdf_Odstavec;
    $odstavec3_10->text("3.10. Účastník, který získal zaměstnání anebo se sebezaměstnal v průběhu své účasti v projektu anebo do 2 měsíců od ukončení účasti:");
    $odstavec3_10->predsazeni(6);
    $odstavec3_10->odsazeniZleva(6);

$odstavec3_10_a = new Projektor_Pdf_Odstavec;
    $odstavec3_10_a->text("a)   zavazuje se informovat do 3 pracovních dnů Dodavatele o této skutečnosti");
    $odstavec3_10_a->predsazeni(6);
    $odstavec3_10_a->odsazeniZleva(14);
$odstavec3_10_b = new Projektor_Pdf_Odstavec;
    $odstavec3_10_b->text("b)   souhlasí se svým uvedením v seznamu osob, které získaly ve stanovené době zaměstnání anebo se sebezaměstnaly.");
    $odstavec3_10_b->predsazeni(6);
    $odstavec3_10_b->odsazeniZleva(14);
$odstavec3_10_c = new Projektor_Pdf_Odstavec;
    $odstavec3_10_c->text("c)   účastník, který získal zaměstnání, se zavazuje dodat Dodavateli kopii své uzavřené pracovní smlouvy.");
    $odstavec3_10_c->predsazeni(6);
    $odstavec3_10_c->odsazeniZleva(14);
$odstavec3_10_d = new Projektor_Pdf_Odstavec;
    $odstavec3_10_d->text("d)   účastník, který se sebezaměstnal, doloží Dodavateli písemné potvrzení Úřadu práce o ukončení evidence účastníka na vlastní žádost a prohlášení účastníka o podnikání kroků k zahájení podnikání, výpis nebo kopii výpisu z Živnostenského rejstříku potvrzující jeho oprávnění k podnikání.");
    $odstavec3_10_d->predsazeni(6);
    $odstavec3_10_d->odsazeniZleva(14);

$odstavec3_11 = new Projektor_Pdf_Odstavec;
    $odstavec3_11->text("3.11. Pokud se účastník nepodrobí závěrečnému ověření získaných znalostí v některém z kurzů, zavazuje se, že podepíše Potvrzení o tom, že se nepodrobí závěrečnému ověření získaných znalostí.");
    $odstavec3_11->predsazeni(6);
    $odstavec3_11->odsazeniZleva(6);

$odstavec4 = new Projektor_Pdf_Odstavec;
    $odstavec4 -> Nadpis("4. Ukončení účasti účastníka v projektu");  

$odstavec4_1 = new Projektor_Pdf_Odstavec;
    $odstavec4_1->text("4.1. K ukončení účasti účastníka v projektu S pomocí za prací v Plzeňském kraji dojde v následujících případech:");
    $odstavec4_1->predsazeni(6);
    $odstavec4_1->odsazeniZleva(6);

$odstavec4_1_a = new Projektor_Pdf_Odstavec;
    $odstavec4_1_a->text("a)   uplynutím doby stanovené pro účast účastníka v projektu v případě řádného absolvování projektu účastníkem:");
    $odstavec4_1_a->predsazeni(6);
    $odstavec4_1_a->odsazeniZleva(14);
    
$odstavec4_1_a_1 = new Projektor_Pdf_Odstavec;
    $odstavec4_1_a_1->text("-  tato doba je 3 měsíce");
    $odstavec4_1_a_1->predsazeni(3);
    $odstavec4_1_a_1->odsazeniZleva(24);
$odstavec4_1_a_2 = new Projektor_Pdf_Odstavec;
    $odstavec4_1_a_2->text("-  v případě účasti účastníka v profesním rekvalifikačním kurzu (nejedná se o kurzy Obsluha osobního počítače anebo Obsluha osobního počítače dle osnov ECDL START) anebo na praxi, končí jeho účast po uplynutí 14 dní od absolvování kurzu anebo praxe, pokud je doba účasti v kurzu anebo na praxi delší než 3 měsíce");
    $odstavec4_1_a_2->predsazeni(3);
    $odstavec4_1_a_2->odsazeniZleva(24);
    
$odstavec4_1_b = new Projektor_Pdf_Odstavec;
    $odstavec4_1_b->text("b)   předčasným ukončením účasti ze strany účastníka:");
    $odstavec4_1_b->predsazeni(6);
    $odstavec4_1_b->odsazeniZleva(14);
    
$odstavec4_1_b_1 = new Projektor_Pdf_Odstavec;
    $odstavec4_1_b_1->text("-  dnem předcházejícím nástupu účastníka do pracovního poměru anebo dnem předcházejícím sebezaměstnání účastníka (zahájení podnikání); ve výjimečných případech může být písemně dohodnuto jinak");
    $odstavec4_1_b_1->predsazeni(3);
    $odstavec4_1_b_1->odsazeniZleva(24);
$odstavec4_1_b_2 = new Projektor_Pdf_Odstavec;
    $odstavec4_1_b_2->text("-  výpovědí této Dohody o účasti v projektu účastníkem z jiného důvodu než nástupu do zaměstnání (ukončení dnem, kdy byla výpověď doručena zástupci Dodavatele)");
    $odstavec4_1_b_2->predsazeni(3);
    $odstavec4_1_b_2->odsazeniZleva(24);

$odstavec4_1_c = new Projektor_Pdf_Odstavec;
    $odstavec4_1_c->text("c)   předčasným ukončením účasti ze strany Dodavatele:");
    $odstavec4_1_c->predsazeni(6);
    $odstavec4_1_c->odsazeniZleva(14);
    
    
$odstavec4_1_c_1 = new Projektor_Pdf_Odstavec;
    $odstavec4_1_c_1->text("-  jestliže účastník porušuje podmínky účasti v projektu (viz výše), neplní své povinnosti při účasti na aktivitách projektu (zejména na rekvalifikaci) anebo jiným závažným způsobem maří účel účasti v projektu, případně z podnětu vysílajícího Úřadu práce.");
    $odstavec4_1_c_1->predsazeni(3);
    $odstavec4_1_c_1->odsazeniZleva(24);
 
$odstavec4_2 = new Projektor_Pdf_Odstavec;
    $odstavec4_2->text("4.2. V případě, že tato Dohoda bude ze strany Dodavatele vypovězena, platí, že vypovězením této Dohody zanikají veškeré závazky Dodavatele vůči účastníkovi plynoucí z této Dohody s výjimkou závazku uhradit platby přímé podpory za dobu účasti účastníka v projektu. K ukončení účasti dojde dnem, kdy byla výpověď účastníkovi doručena nebo třicátý den od odeslání.");
    $odstavec4_2->predsazeni(6);
    $odstavec4_2->odsazeniZleva(6);

$odstavec4_3 = new Projektor_Pdf_Odstavec;
    $odstavec4_3->text("4.3. Účastník se zavazuje, že se dostaví do Kontaktní kanceláře a podepíše doklad o ukončení účasti účastníka v projektu S pomocí za prací v Plzeňském kraji, pokud nebude dohodnuto jinak. Uvedený doklad předá Dodavatel příslušnému Úřadu práce. Přílohou tohoto dokladu bude například kopie pracovní smlouvy, kopie výpovědi atd.");
    $odstavec4_3->predsazeni(6);
    $odstavec4_3->odsazeniZleva(6);    
    
$odstavec4_4 = new Projektor_Pdf_Odstavec;
    $odstavec4_4->text("4.4. Po ukončení účasti účastníka v projektu S pomocí za prací v Plzeňském kraji řádným způsobem anebo z důvodu nástupu do zaměstnání po absolvování alespoň 3 aktivit projektu získá účastník Osvědčení o absolvování projektu S pomocí za prací. Kopii tohoto Osvědčení předá Dodavatel společně s ukončením účasti účastníka Úřadu práce.");
    $odstavec4_4->predsazeni(6);
    $odstavec4_4->odsazeniZleva(6);
    
$odstavec4_5 = new Projektor_Pdf_Odstavec;
    $odstavec4_5->text("4.5. Po absolvování kurzů Kurz základních znalostí trhu práce a Kurz komunikace Dodavatel zajistí, že účastník obdrží osvědčení o absolvování motivačního programu.");
    $odstavec4_5->predsazeni(6);
    $odstavec4_5->odsazeniZleva(6);
    
$odstavec4_6 = new Projektor_Pdf_Odstavec;
    $odstavec4_6->text("4.6. Po ukončení rekvalifikačního kurzu (zahrnujícího motivační program, rekvalifikační kurzy a kurzy obsluhy PC) Dodavatel zajistí, že rekvalifikační zařízení zhotoví a předá účastníkovi, který kurz úspěšně absolvoval, Osvědčení o rekvalifikaci (případně jiné doklady, například průkazy atd.).");
    $odstavec4_6->predsazeni(6);
    $odstavec4_6->odsazeniZleva(6);
    
$odstavec4_7 = new Projektor_Pdf_Odstavec;
    $odstavec4_7->text("4.7. Dodavatel založí každému účastníkovi jeho Osobní složku, do které bude zakládat, počínaje touto Dohodou o účasti v projektu a Souhlasem s poskytnutím a zpracováním osobních údajů, všechny dokumenty vztahující se k jeho účasti v projektu. Osobní složky budou uloženy po dobu trvání plnění projektu v příslušné kontaktní kanceláři v zabezpečené kartotéce. Osobní složka každého účastníka projektu bude pro konkrétního účastníka přístupná v době dohodnuté konzultační schůzky v Kontaktní kanceláři.");
    $odstavec4_7->predsazeni(6);
    $odstavec4_7->odsazeniZleva(6);  
 
$odstavec5 = new Projektor_Pdf_Odstavec;
    $odstavec5 -> Nadpis("5. Doprovodná opatření – druhy přímé podpory pro účastníky");
  
 
$odstavec5_1 = new Projektor_Pdf_Odstavec;
    $odstavec5_1->text("5.1. Účastníkovi mohou být při účasti na aktivitách projektu poskytovány příspěvky na náhradu některých nákladů souvisejících s účastí v projektu (tzv. přímá podpora), a to za podmínek stanovených projektem. Jedná se o tyto příspěvky:");
    $odstavec5_1->predsazeni(6);
    $odstavec5_1->odsazeniZleva(6); 

$odstavec5_1_a = new Projektor_Pdf_Odstavec;
    $odstavec5_1_a->text("a)   příspěvek na jízdné");
    $odstavec5_1_a->predsazeni(6);
    $odstavec5_1_a->odsazeniZleva(14);
$odstavec5_1_b = new Projektor_Pdf_Odstavec;
    $odstavec5_1_b->text("b)   příspěvek na stravné");
    $odstavec5_1_b->predsazeni(6);
    $odstavec5_1_b->odsazeniZleva(14);
$odstavec5_1_c = new Projektor_Pdf_Odstavec;
    $odstavec5_1_c->text("c)   příspěvek na zajištění péče o děti nebo jiné závislé osoby");
    $odstavec5_1_c->predsazeni(6);
    $odstavec5_1_c->odsazeniZleva(14);
$odstavec5_1_d = new Projektor_Pdf_Odstavec;
    $odstavec5_1_d->text("d)   příspěvek na potvrzení zdravotní způsobilosti");
    $odstavec5_1_d->predsazeni(6);
    $odstavec5_1_d->odsazeniZleva(14);
$odstavec5_1_e = new Projektor_Pdf_Odstavec;
    $odstavec5_1_e->text("e)   příspěvek na zabezpečení jiných výdajů souvisejících s projektem");
    $odstavec5_1_e->predsazeni(6);
    $odstavec5_1_e->odsazeniZleva(14);

$odstavec5_2 = new Projektor_Pdf_Odstavec;
    $odstavec5_2->text("5.2. Bližší specifikace uvedených druhů přímé podpory je uvedena v dokumentu Základní informace účastníka projektu S pomocí za prací v Plzeňském kraji.");
    $odstavec5_2->predsazeni(6);
    $odstavec5_2->odsazeniZleva(6);     

$odstavec5_3 = new Projektor_Pdf_Odstavec;
    $odstavec5_3->text("5.3. Účastník bere na vědomí, že příspěvky přímé podpory jsou nenárokové a o jejich poskytnutí rozhoduje Úřad práce.");
    $odstavec5_3->predsazeni(6);
    $odstavec5_3->odsazeniZleva(6);    

$odstavec6 = new Projektor_Pdf_Odstavec;
    $odstavec6 -> Nadpis("6. Povinnosti dodavatele");    

$odstavec6_1 = new Projektor_Pdf_Odstavec;
    $odstavec6_1->text("6.1. Dodavatel se zavazuje poskytnout Účastníkovi zdarma aktivity projektu. Dodavatel je povinen vyvinout úsilí k tomu, aby zabezpečil účastníkovi absolvování aktivit doporučených v Individuálním plánu.");
    $odstavec6_1->predsazeni(6);
    $odstavec6_1->odsazeniZleva(6);    

$odstavec6_2 = new Projektor_Pdf_Odstavec;
    $odstavec6_2->text(" 6.2. Dodavatel musí informovat účastníky projektu o jejich povinnosti spočívající v tom, dostavit se před zahájením účasti v rekvalifikačním kurzu na vysílající Úřad práce k podpisu Dohody o rekvalifikaci. Dále musí dodavatel účastníkům tuto návštěvu Úřadu práce v době dohodnuté s příslušným vysílajícím Úřadem práce umožnit, nejpozději však 3 pracovní dny před zahájením rekvalifikace (nebude-li dohodnuto s vysílajícím Úřadem práce jinak).");
    $odstavec6_2->predsazeni(6);
    $odstavec6_2->odsazeniZleva(6);    

$odstavec6_3 = new Projektor_Pdf_Odstavec;
    $odstavec6_3->text("6.3. Dodavatel musí informovat účastníka o všech podmínkách účasti v kurzu (například potvrzení od lékaře, nutné očkování) a umožnit mu jejich obstarání. Pro účely účasti v kurzu, kde je vyžadováno lékařské potvrzení, může účastník na vyžádání obdržet od příslušného Úřadu práce potvrzení o tom, že je jím do kurzu vyslán. Dodavatel musí účastníkovi umožnit dojít si na vysílající Úřad práce pro získání příslušného formuláře. Dodavatel je přitom povinen vyvinout maximální součinnost s příslušným Úřadem práce, tedy vysílající Úřad práce předem informovat o plánované účasti účastníka v kurzu a o tom, že se účastník na Úřad práce dostaví pro potvrzení o vyslání Úřadem práce na kurz.");
    $odstavec6_3->predsazeni(6);
    $odstavec6_3->odsazeniZleva(6);     


$odstavec7 = new Projektor_Pdf_Odstavec;
    $odstavec7 -> Nadpis("7. Ochrana osobních údajů účastníků");   

$odstavec7_1 = new Projektor_Pdf_Odstavec;
    $odstavec7_1->text("7.1. Účastník souhlasí s poskytnutím a zpracováváním svých osobních údajů pro účely projektu, v souladu se zákonem č. 101/2000 Sb., zákon o ochraně osobních údajů. Tyto údaje může Dodavatel poskytnout třetí straně, tedy vysílajícímu Úřadu práce a Úřadu práce v Plzni a dále potenciálnímu zaměstnavateli za účelem zprostředkování zaměstnání účastníkovi projektu. Tuto skutečnost účastník potvrzuje podpisem této Dohody.");
    $odstavec7_1->predsazeni(6);
    $odstavec7_1->odsazeniZleva(6);     


$odstavec8 = new Projektor_Pdf_Odstavec;
    $odstavec8 -> Nadpis("8. Závěrečná ustanovení");   

$odstavec8_1 = new Projektor_Pdf_Odstavec;
    $odstavec8_1->text("8.1. Tuto Dohodu lze měnit či doplňovat pouze po dohodě smluvních stran formou písemných a číslovaných dodatků.");
    $odstavec8_1->predsazeni(6);
    $odstavec8_1->odsazeniZleva(6);    

$odstavec8_2 = new Projektor_Pdf_Odstavec;
    $odstavec8_2->text("8.2. Účastník svým podpisem rovněž potvrzuje, že se osobně seznámil, se zněním Základní informace účastníka projektu S pomocí za prací v Plzeňském kraji, souhlasí s nimi a zavazuje se vzhledem k nim.");
    $odstavec8_2->predsazeni(6);
    $odstavec8_2->odsazeniZleva(6);    

$odstavec8_3 = new Projektor_Pdf_Odstavec;
    $odstavec8_3->text("8.3. Tato Dohoda je sepsána ve třech vyhotoveních s platností originálu, přičemž Účastník obdrží jedno a Dodavatel dvě vyhotovení.");
    $odstavec8_3->predsazeni(6);
    $odstavec8_3->odsazeniZleva(6);

$odstavec8_4 = new Projektor_Pdf_Odstavec;
    $odstavec8_4->text("8.4. Tato Dohoda nabývá platnosti a účinnosti dnem jejího podpisu oběma smluvními stranami; tímto dnem jsou její účastníci svými projevy vázáni.");
    $odstavec8_4->predsazeni(6);
    $odstavec8_4->odsazeniZleva(6);    

$odstavec8_5 = new Projektor_Pdf_Odstavec;
    $odstavec8_5->text("8.5. Dodavatel i Účastník shodně prohlašují, že si tuto Dohodu před jejím podpisem přečetli, že byla uzavřena podle jejich pravé a svobodné vůle, určitě, vážně a srozumitelně, nikoliv v tísni za nápadně nevýhodných podmínek. Smluvní strany potvrzují autentičnost této Dohody svým podpisem.");
    $odstavec8_5->predsazeni(6);
    $odstavec8_5->odsazeniZleva(6);    
    
   
   
    


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
	$podpisy->PridejBunku("                       Účastník:                                                                                   Dodavatel:","",1);
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
        $pdf->TiskniOdstavec($odstavec3_3_a);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_3_b);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_3_c);

        $pdf->Ln(2);  
        $pdf->TiskniOdstavec($odstavec3_4);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_5);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_6);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_7);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_8);
        
	$pdf->Ln(2);           
        $pdf->TiskniOdstavec($odstavec3_8_a);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_8_b);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_8_c);

        $pdf->Ln(2); 
	$pdf->TiskniOdstavec($odstavec3_9);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_10 );
	
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_10_a);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_10_b);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_10_c);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_10_d);

        $pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec3_11);

        $pdf->Ln(7);  
        $pdf->TiskniOdstavec($odstavec4);
        $pdf->TiskniOdstavec($odstavec4_1);
	
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_a);
        
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_a_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_a_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_b);
	
        $pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_b_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_b_2);
	
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_c);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_1_c_1);

        $pdf->Ln(2); 
	$pdf->TiskniOdstavec($odstavec4_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_3);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_4);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_5);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_6);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec4_7);

        $pdf->Ln(7); 
	$pdf->TiskniOdstavec($odstavec5);
        $pdf->TiskniOdstavec($odstavec5_1);
	
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_1_a);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_1_b);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_1_c);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_1_d);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_1_e);

	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec5_3);

        $pdf->Ln(7); 
	$pdf->TiskniOdstavec($odstavec6);
        $pdf->TiskniOdstavec($odstavec6_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec6_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec6_3);

        $pdf->Ln(7); 
	$pdf->TiskniOdstavec($odstavec7);
        $pdf->TiskniOdstavec($odstavec7_1);

        $pdf->Ln(7); 
	$pdf->TiskniOdstavec($odstavec8);
        $pdf->TiskniOdstavec($odstavec8_1);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec8_2);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec8_3);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec8_4);
	$pdf->Ln(2); 
        $pdf->TiskniOdstavec($odstavec8_5);
        
	
        
       
        
        $pdf->Ln(20);
        $pdf->TiskniSaduBunek($podpisy, 0, 1);



  //$pdf->Output("doc.pdf",D);
  
$filepathprefix= iconv('UTF-8', 'windows-1250', "./doku/SPZP SML UC smlouva ");
if (file_exists($filepathprefix. $Ucastnik->identifikator . ".pdf"))  	{
    	unlink($filepathprefix. $Ucastnik->identifikator . ".pdf");
}

$pdf->Output($filepathprefix. $Ucastnik->identifikator . ".pdf", F);  
  
  
  
//  if (file_exists("./doku/smlouva". $Ucastnik->identifikator . ".pdf")) {
//    unlink("./doku/smlouva". $Ucastnik->identifikator . ".pdf");
//  }
  
//  $pdf->Output("doku/smlouva". $Ucastnik->identifikator . ".pdf", F);
  
  
 

?>