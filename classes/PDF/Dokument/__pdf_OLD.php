<?php
  define('FPDF_FONTPATH','/fpdf16/font/');
  require('/fpdf16/fpdf.php');
  $pole = $_POST;
 
  
  function prevod ($vstup, $vyska = 0)
  {
    global $celkova_vyska_textu;
    $celkova_vyska_textu = $celkova_vyska_textu + $vyska;
    return iconv('UTF-8', 'windows-1250', $vstup);  
  }
  $vyska_stranky = 258;
  
  $pdf = new FPDF();
  $pdf->SetLeftMargin(20);
  $pdf->AddPage();
  $pdf->AddFont('TimesN','','novy_times.php');
  $pdf->AddFont('TimesB','','novy_timesbd.php');
  $pdf->AddFont("TimesBI","","900c0fcd375d16113599217c28f3c6ff_timesbi.php");
  $pdf->AddFont("TimesI","","76956561af2d16478b5cae80d5258929_timesi.php");
  
  //$pdf->SetLineWidth(0.4);
  //$pdf->Line(40,20,120,20);
  $vyska = 16;
  $pdf->SetFont('TimesB','',24);
  $pdf->Cell(0,$vyska,prevod ("Nadpis",$vyska),0,1,"C");
 
  $vyska = 10;
  $pdf->SetFont('TimesB','',12);
  $pdf->Cell(0,$vyska,prevod("1. Osobní údaje",$vyska),0,1);
  
  $vyska = 8;
  $pdf->SetFont('TimesN','',12);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(40,$vyska,prevod("Datum registrace:"));
  $pdf->Cell(75,$vyska,prevod($pole["datum_reg"]));
  $pdf->Cell(55,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));   
  $pdf->Cell(25,$vyska,prevod("Titul: ".$pole["titul"]));
  $pdf->Cell(35,$vyska,prevod("Jméno: ".$pole["jmeno"]));
  $pdf->Cell(40,$vyska,prevod("Příjmení: ".$pole["prijmeni"]));
  $pdf->Cell(30,$vyska,prevod("Titul za: ".$pole["titul_za"]));
  $pdf->Cell(40,$vyska,prevod("Pohlaví: ".$pole["pohlavi"],$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(40,$vyska,prevod("Datum narození: "));
  $pdf->Cell(35,$vyska,prevod($pole["datum_narozeni"]));
  $pdf->Cell(95,$vyska,prevod("Rodné číslo: ".$pole["rodne_cislo"],$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(65,$vyska,prevod("Vysílající úřad práce: ".$pole[z_up]));
  $pdf->Cell(100,$vyska,prevod("Pracoviště úřadu práce: ".$pole["prac_up"]));
  $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(100,$vyska,prevod("Stav osoby: ".$pole[stav]));
  $pdf->Cell(70,$vyska,prevod("",$vyska),0,1);
  $pdf->Cell(0,$vyska,"",0,1);

  if ($pole["mesto"] != "")
  {
    $pdf->SetFont('TimesB','',12);
    $pdf->Cell(0,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(0,$vyska,prevod("2. Bydliště a kontaktní údaje",$vyska),0,1);
  
    $pdf->SetFont('TimesN','',12);
      $pdf->Cell(5,$vyska,prevod(""));
    $pdf->Cell(160,$vyska,prevod("2.1 Trvalé bydliště",$vyska),0,1);
      $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(30,$vyska,prevod("Město: ".$pole["mesto"]));
    $pdf->Cell(40,$vyska,prevod("Ulice: ".$pole["ulice"]));
    $pdf->Cell(30,$vyska,prevod("PSČ: ".$pole["psc"]));
    $pdf->Cell(60,$vyska,prevod("Pevný telefon: ".$pole["pevny_telefon"]));
      $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
  
      $pdf->Cell(170,$vyska/2,prevod("",$vyska/2),0,1);
      $pdf->Cell(10,$vyska,prevod(""));  
    $pdf->Cell(160,$vyska,prevod("2.2 Kontaktní adresa (přechodné bydliště)",$vyska),0,1);
      $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(30,$vyska,prevod("Město: ".$pole["mesto2"]));
    $pdf->Cell(40,$vyska,prevod("Ulice: ".$pole["ulice2"]));
    $pdf->Cell(30,$vyska,prevod("PSČ: ".$pole["psc2"]));
    $pdf->Cell(60,$vyska,prevod("Pevný telefon: ".$pole["pevny_telefon2"]));
      $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
  
      $pdf->Cell(170,$vyska/2,prevod("",$vyska/2),0,1);
      $pdf->Cell(10,$vyska,prevod(""));  
    $pdf->Cell(50,$vyska,prevod("Mobilní telefon: ".$pole["mobilni_telefon"]));
    $pdf->Cell(50,$vyska,prevod("Další telefony: ".$pole["dalsi_telefon"]));
    $pdf->Cell(60,$vyska,prevod("popis:".$pole["popis_telefon"]));
      $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
  
  $pdf->Cell(10,$vyska,prevod(""));  
  $pdf->Cell(160,$vyska,prevod("e-mail: ".$pole["mail"]));
  $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
  $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
  $pdf->Cell(0,$vyska,"",0,1);
  }  

  $odeslano = false;
  $cislo_skoly = Array ("I.","II.","III.","IV","V.");
  
  for ($i = 1; $i < 6; $i++)
  {
    if ($pole["nazev_skoly$i"] != "")
    {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*8))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
      if (!$odeslano)
      {
        if ($celkova_vyska_textu > ($vyska_stranky-$vyska*10))
        { $pdf->AddPage(); $celkova_vyska_textu = 0; }
        $odeslano = true;
        $pdf->SetFont('TimesB','',12);
        $pdf->Cell(170,$vyska,prevod("3. Vzdělání a schopnosti",$vyska),0,1);
        $pdf->SetFont('TimesN','',12);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(160,$vyska,prevod("3.1 Absolvované školy"));
        $patka = true;
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)])); 
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      }
      else
      {
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)])); 
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      }
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Název školy: ".$pole["nazev_skoly$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Obor: ".$pole["obor$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(50,$vyska,prevod("Rok ukončení studia: ".$pole["rok_ukonceni_studia$i"],$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(80,$vyska,prevod("Stupeň vzdělání: ".$pole["vzdelani$i"],$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Závěrečná zkouška: ".$pole["zaverecna_zkouska$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("popis: ".$pole["popis$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Doloženo dokladem: ".$pole["dolozeno$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    }
  }
  if ($patka)
  {
    $patka = false;
    $pdf->Cell(0,$vyska,"",0,1);
  }
  $odeslano2 = false;
  
  for ($i = 1; $i < 6; $i++)
  {
    if ($pole["nazev_skoleni$i"] != "")
    {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*8))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
      if (!$odeslano)
      {
        if ($celkova_vyska_textu > ($vyska_stranky-$vyska*10))
        { $pdf->AddPage(); $celkova_vyska_textu = 0; }
        $pdf->SetFont('TimesB','',12);
        $pdf->Cell(170,$vyska,prevod("3. Vzdělání a schopnosti",$vyska),0,1);
        $pdf->SetFont('TimesN','',12);
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(160,$vyska,prevod("3.2 Další absolvovaná školení"));
        $patka = true;
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)])); 
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $odeslano = true;
      }
      elseif (!$odeslano2)
      {
        $odeslano2 = true;
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(160,$vyska,prevod("3.2 Další absolvovaná školení"));
        $patka = true;
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)])); 
          $pdf->Cell(5,$vyska,prevod(""));
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      }
      else
      {
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)])); 
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      }
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Název: ".$pole["nazev_skoleni$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Popis školení: ".$pole["popis_skoleni$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(70,$vyska,prevod("Rok ukončení: ".$pole["rok_ukonceni$i"]));
      $pdf->Cell(80,$vyska,prevod("Doba trvání školení: [dny] ".$pole["doba_skoleni$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Popis dokladu: ".$pole["popis_dokladu$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Hrazeno: ".$pole["hrazeno$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod(""));
      $pdf->Cell(150,$vyska,prevod("Doloženo dokladem: ".$pole["dolozeno_skoleni$i"]));
        $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    }  
  }
  if ($patka)
  {
    $patka = false;
    $pdf->Cell(0,$vyska,"",0,1);
  }
  $odeslano2 = false;  
  if ($pole["specializace_v_praxi"] != "")
  {
      $delka = strlen($pole["specializace_v_praxi"]);
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/70)+3)))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
      
      if (!$odeslano)
      {
        $pdf->SetFont('TimesB','',12);
        $pdf->Cell(170,$vyska,prevod("3. Vzdělání a schopnosti",$vyska),0,1);
        $pdf->SetFont('TimesN','',12);
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(160,$vyska,prevod("3.3 Specializace v praxi"));
        $patka = true;
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
        $odeslano = true;
      }
      else
      {
          $pdf->Cell(5,$vyska,prevod(""));
        $pdf->Cell(160,$vyska,prevod("3.3 Specializace v praxi"));
        $patka = true;
          $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      }

      $retezec = explode(" ", $pole["specializace_v_praxi"]);
      $radek = 1;
      
      foreach ($retezec as $slovo)
      { 
        $veta = "$veta"."$slovo ";
        if ((strlen($veta) > 70) || ($slovo == End($retezec)))
        {
            $pdf->Cell(10,$vyska,prevod(""));
          $pdf->Cell(160,$vyska,prevod($veta,$vyska),0,1);
            $veta = "";
        }
      }
  }
  if ($patka)
  {
    $patka = false;
    $pdf->Cell(0,$vyska,"",0,1);
  }

  
  if ($pole["aj_uroven"] != "--------------" ||
  $pole["nj_uroven"] != "--------------" ||
  $pole["rj_uroven"] != "--------------" ||
  $pole["dalsi_jazyk1_jmeno"] != "" ||
  $pole["dalsi_jazyk2_jmeno"] != "")
  {
    if (!$odeslano)
    {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*7))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
        $pdf->SetFont('TimesB','',12);
        $pdf->Cell(170,$vyska,prevod("3. Vzdělání a schopnosti",$vyska),0,1);
        $pdf->SetFont('TimesN','',12);
      $odeslano = true;
    }
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*6))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
    
      $pdf->Cell(5,$vyska,prevod(""));
    $pdf->Cell(160,$vyska,prevod("3.4 Jazykové znalosti"));
    $patka = true;
      $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(50,$vyska,prevod("Jazyk"));      
    $pdf->Cell(40,$vyska,prevod("Úroveň"));      
    $pdf->Cell(55,$vyska,prevod("Schopnosti"));
      $pdf->Cell(20,$vyska,prevod("",$vyska),0,1);
          
    if ($pole["aj_uroven"] != "--------------")
      {     
          $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(50,$vyska,prevod("Anglický jazyk"));      
        $pdf->Cell(40,$vyska,prevod($pole["aj_uroven"]));      
        $pdf->Cell(55,$vyska,prevod($pole["aj_schopnosti"]));
          $pdf->Cell(20,$vyska,prevod("",$vyska),0,1);
      }
    if ($pole["nj_uroven"] != "--------------")
      {     
          $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(50,$vyska,prevod("Německý jazyk"));      
        $pdf->Cell(40,$vyska,prevod($pole["nj_uroven"]));      
        $pdf->Cell(55,$vyska,prevod($pole["nj_schopnosti"]));
          $pdf->Cell(20,$vyska,prevod("",$vyska),0,1);
      } 
    if ($pole["rj_uroven"] != "--------------")
      {     
          $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(50,$vyska,prevod("Ruský jazyk"));      
        $pdf->Cell(40,$vyska,prevod($pole["rj_uroven"]));      
        $pdf->Cell(55,$vyska,prevod($pole["rj_schopnosti"]));
          $pdf->Cell(20,$vyska,prevod("",$vyska),0,1);
      } 
    if ($pole["dalsi_jazyk1_jmeno"] != "")
      {     
          $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(50,$vyska,prevod("Další:".$pole["dalsi_jazyk1_jmeno"]));      
        $pdf->Cell(40,$vyska,prevod($pole["dalsi_jazyk1_jmeno_uroven"]));      
        $pdf->Cell(55,$vyska,prevod($pole["dalsi_jazyk1_schopnosti"]));
          $pdf->Cell(20,$vyska,prevod("",$vyska),0,1);
      } 
    if ($pole["dalsi_jazyk2_jmeno"] != "")
      {     
          $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(50,$vyska,prevod("Další:".$pole["dalsi_jazyk1_jmeno"]));      
        $pdf->Cell(40,$vyska,prevod($pole["dalsi_jazyk2_jmeno_uroven"]));      
        $pdf->Cell(55,$vyska,prevod($pole["dalsi_jazyk2_schopnosti"]));
          $pdf->Cell(20,$vyska,prevod("",$vyska),0,1);
      }     
    $patka = false;
    $pdf->Cell(0,$vyska,"",0,1);
  }
  
  if ($pole["pc_office_uroven"] != "------------" ||
  $pole["PC_ERP"] != "Ne" ||
  $pole["PC_ERP_nazev"] != "" ||
  $pole["PC_CAD"] != "Ne" ||
  $pole["PC_CAD_nazev"] != "" ||
  $pole["PC_GRA"] != "Ne" ||
  $pole["PC_GRA_nazev"] != "" ||
  $pole["PC_IT"] != "Ne" ||
  $pole["PC_popis"] != "") 
  {
  if (!$odeslano)
  {
    
    $delka = strlen($pole["PC_popis"]);
    if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/70)+6)))
    { $pdf->AddPage(); $celkova_vyska_textu = 0; }
        $pdf->SetFont('TimesB','',12);
        $pdf->Cell(170,$vyska,prevod("3. Vzdělání a schopnosti",$vyska),0,1);
        $pdf->SetFont('TimesN','',12);
    $odeslano = true;
  }
      $delka = strlen($pole["PC_popis"]);
    if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/70)+5)))
    { $pdf->AddPage(); $celkova_vyska_textu = 0; }
    
    $pdf->Cell(5,$vyska,prevod(""));
  $pdf->Cell(160,$vyska,prevod("3.5 PC dovednosti"));
  $patka = true;
    $pdf->Cell(10,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(50,$vyska,prevod("MS Office - úroveň"));      
  $pdf->Cell(110,$vyska,prevod($pole["pc_office_uroven"]));
    $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(10,$vyska,prevod($pole["PC_ERP"]));
  $pdf->Cell(90,$vyska,prevod("ERP systémy (SAP, BAAN, účetnictví) - Název:"));      
  $pdf->Cell(60,$vyska,prevod($pole["PC_ERP_nazev"]));
    $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(10,$vyska,prevod($pole["PC_CAD"]));
  $pdf->Cell(90,$vyska,prevod("CAD systémy - Název:"));      
  $pdf->Cell(60,$vyska,prevod($pole["PC_CAD_nazev"]));
    $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(10,$vyska,prevod($pole["PC_GRA"]));
  $pdf->Cell(90,$vyska,prevod("Grafické programy - Název:"));      
  $pdf->Cell(60,$vyska,prevod($pole["PC_GRA_nazev"]));
    $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
  $pdf->Cell(10,$vyska,prevod($pole["PC_IT"]));
  $pdf->Cell(90,$vyska,prevod("IT expert - Popis expertních PC dovedností:"));      
  $pdf->Cell(60,$vyska,prevod(""));
    $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    if ($pole["PC_popis"] != "")
    {
      $retezec = explode(" ", $pole["PC_popis"]);
      $radek = 1;
      
      foreach ($retezec as $slovo)
      { 
        $veta = "$veta"."$slovo ";
        if ((strlen($veta) > 70) || ($slovo == End($retezec)))
        {
            $pdf->Cell(10,$vyska,prevod(""));
          $pdf->Cell(160,$vyska,prevod($veta));
            $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
          $veta = "";
          $radek++;
        }
      }
    }
  $patka = false;
  $pdf->Cell(0,$vyska,"",0,1);
  }
  
  if ($pole["ridic_sk1"] || $pole["ridic_sk2"] || $pole["ridic_sk3"] || $pole["ridic_sk4"])
  {
    if (!$odeslano)
    {
      $delka = strlen($pole["PC_popis"]);
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/70)+4)))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
      $pdf->SetFont('TimesB','',12);
      $pdf->Cell(170,$vyska,prevod("3. Vzdělání a schopnosti",$vyska),0,1);
      $pdf->SetFont('TimesN','',12);
      $odeslano = true;
    }
    if ($celkova_vyska_textu > ($vyska_stranky-$vyska*3))
    { $pdf->AddPage(); $celkova_vyska_textu = 0; }
    
      $pdf->Cell(5,$vyska,prevod(""));
    $pdf->Cell(160,$vyska,prevod("3.6 Řidičské oprávnění"));
      $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
    $patka = true;
    
      $pdf->Cell(10,$vyska,prevod(""));
    $k = 4;
    for ($i = 1; $i < 5; $i++)
    {
      if ($pole["ridic_sk$i"]!="")
      {
        $pdf->Cell(39,$vyska,prevod("Skupina: ".$pole["ridic_sk$i"]));
        $k--;
      }
    }
      $pdf->Cell((4+$k*39),$vyska,prevod(""));
      $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);
      $pdf->Cell(10,$vyska,prevod(""));
    $k = 4;
    for ($i = 1; $i < 5; $i++)
    {
      if ($pole["ridic_sk$i"]!="")
      {
        $pdf->Cell(39,$vyska,prevod("Rok vystavení: ".$pole["ridic_rok$i"]));
        $k--;
      }
    }
    $pdf->Cell((4+$k*39),$vyska,prevod(""));
    $pdf->Cell(5,$vyska,prevod("",$vyska),0,1);

  }
  if ($odeslano)
  { $pdf->Cell(170,$vyska,prevod("",$vyska),0,1); }
  $pdf->Cell(0,$vyska,prevod("",$vyska),0,1);
  
  $odeslano = false;
  $ukonceni = false;
  for ($i = 1; $i < 6; $i++)
  {
    if ($pole["zamestnani_zamestnavatel$i"] != "")
    {
      $delka = strlen($pole["zamestnani_popis$i"]);
      if (!$odeslano)
      {
        if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/65)+8)))
        { $pdf->AddPage(); $celkova_vyska_textu = 0; }
        $pdf->SetFont('TimesB','',12);
        $pdf->Cell(170,$vyska,prevod("4. Informace o předchozím zaměstnání",$vyska),0,1);
        $pdf->SetFont('TimesN','',12);
        $pdf->Cell(15,$vyska,prevod(""));
        $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)]),0,1); 
        $odeslano = true;
      }
      else
      {
        if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/65)+7)))
        { $pdf->AddPage(); $celkova_vyska_textu = 0; }
        $pdf->Cell(15,$vyska,prevod(""));
        $pdf->Cell(150,$vyska,prevod($cislo_skoly[($i-1)],$vyska),0,1); 
      }
        $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(25,$vyska,prevod("Od"));
        $pdf->Cell(25,$vyska,prevod("Do"));
        $pdf->Cell(50,$vyska,prevod("Zaměstnavatel"));
        $pdf->Cell(60,$vyska,prevod("Pozice dle uchazeče",$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(25,$vyska,prevod($pole["zamestnani_od$i"]));
        $pdf->Cell(25,$vyska,prevod($pole["zamestnani_do$i"]));
        $pdf->Cell(50,$vyska,prevod($pole["zamestnani_zamestnavatel$i"]));
        $pdf->Cell(60,$vyska,prevod($pole["zamestnani_pozice$i"],$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
        $pdf->Cell(40,$vyska,prevod("číslo dle KZAM"));
        $pdf->Cell(40,$vyska,prevod($pole["KZAM_cislo$i"],$vyska),0,1);
        
        if ($pole["zamestnani_popis$i"] != "")
        {
          $pdf->Cell(10,$vyska,prevod(""));
          $pdf->Cell(25,$vyska,prevod("Popis pozice:"),0,0);
          $retezec = explode(" ", $pole["zamestnani_popis$i"]);
          foreach ($retezec as $slovo)
          { 
            $veta = "$veta"."$slovo ";
            if ((strlen($veta) > 65) || ($slovo == End($retezec)))
            {
              $pdf->Cell(155,$vyska,prevod($veta,$vyska),0,1);
              $pdf->Cell(35,$vyska,prevod(""));
              $veta = "";
              $radek++;
            } 
          }
        }
        $pdf->Cell(5,$vyska,prevod(""),0,1);
    $ukonceni = true;
    } 
  }
  if ($ukonceni)
  {
    $pdf->Cell(0,1,prevod(""),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(40,$vyska,prevod("Datum ukončení posledního pracovního poměru: ".$pole["zamestnani_konec_posledniho"],$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(50,$vyska,prevod("Poslední pracovní poměr:"));
    $pdf->Cell(40,$vyska,prevod($pole["zamestnani_zpukonceni"],$vyska),0,1);
    $pdf->Cell(40,$vyska,prevod("",$vyska),0,1);
    $pdf->Cell(40,$vyska,prevod("",$vyska),0,1);
  }
  
  $k = 4;
  $odeslano = false;
  $hleda = Array();
  $odmita = Array();
  
  for ($i=1;$i<4;$i++)
  { if (IsSet($pole["pozadavky_KZAM$i"])) $k++; }
  for ($i=0;$i<14;$i++)
  {
    if ($pole["pozadavky_hleda$i"] != "----")
      $hleda[] = $pole["pozadavky_hleda$i"];
    if ($pole["pozadavky_odmita$i"] != "----")
      $odmita[] = $pole["pozadavky_odmita$i"];
  }
  $l = (count($hleda) >= count($odmita) ? count($hleda) : count($odmita));
  $k = $k + $l -1;
  if ($pole["pozadavky_povolani"] != "")
  {
    if (!$odeslano)
    {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*9))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
      $pdf->SetFont('TimesB','',12);
      $pdf->Cell(60,$vyska,prevod("5. Představa o uplatnění",$vyska),0,1);
      $pdf->SetFont('TimesN','',12);
      $odeslano = true;
    }
    if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(7)))
    { $pdf->AddPage(); $celkova_vyska_textu = 0; }
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(60,$vyska,prevod("Jaké povolání byste chtěl/a vykonávat? ",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(15,$vyska,prevod("Popis:"));
    $pdf->Cell(40,$vyska,prevod($pole["pozadavky_povolani"],$vyska),0,1);
    $pdf->Cell(10,$vyska/2,prevod("",$vyska/2),0,1);
    for ($i=1;$i<4;$i++)
    {
      $pdf->Cell(15,$vyska,prevod(""));
      $pdf->Cell(35,$vyska,prevod("Požadavky KZAM:"));
      $pdf->Cell(60,$vyska,prevod($pole["pozadavky_KZAM$i"],$vyska),0,1);
    }
    $pdf->Cell(10,$vyska,prevod("",$vyska),0,1);
  }
  if (count($hleda)>1 || count($odmita)>1)
  {
    if (!$odeslano)
    {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*($k+2)))
      { $pdf->AddPage(); $celkova_vyska_textu = 0;}
      $pdf->SetFont('TimesB','',12);
      $pdf->Cell(60,$vyska,prevod("5. Představa o uplatnění",$vyska),0,1);
      $pdf->SetFont('TimesN','',12);
      $odeslano = true;
    }
    if ($celkova_vyska_textu > ($vyska_stranky-$vyska*($k)))
    { $pdf->AddPage(); $celkova_vyska_textu = 0;}
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(15,$vyska,prevod("5.1 Požadavky uchazeče",$vyska),0,1);
    $pdf->Cell(80,$vyska,prevod("Uchazeč hledá"),0,0,C);
    $pdf->Cell(80,$vyska,prevod("Uchazeč odmítá",$vyska),0,1,C);
    for ($i=1;$i<$l;$i++)
    {
      $pdf->Cell(80,$vyska,prevod($hleda[$i]),0,0,C);
      $pdf->Cell(80,$vyska,prevod($odmita[$i],$vyska),0,1,C);
    }
    $pdf->Cell(10,$vyska,prevod("",$vyska),0,1);
  }

  if ($pole["pozadavky_nastup"] != "")
  {  
    $delka = strlen($pole["pozadavky_prace"]);
    if (!$odeslano)
    {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/70)+7)))
      { $pdf->AddPage(); $celkova_vyska_textu = 0; }
      $pdf->SetFont('TimesB','',12);
      $pdf->Cell(60,$vyska,prevod("5. Představa o uplatnění",$vyska),0,1);
      $pdf->SetFont('TimesN','',12);
      $odeslano = true;
    }
    if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(ceil($delka/70)+5)))
    { $pdf->AddPage(); $celkova_vyska_textu = 0; }
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(80,$vyska,prevod("Kdy chcete nastoupit do nového zaměstnání?"));
    $pdf->Cell(70,$vyska,prevod($pole["pozadavky_nastup"],$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(40,$vyska,prevod("Platové požadavky: "));
    $pdf->Cell(70,$vyska,prevod($pole["pozadavky_plat"]." [Kč/měsíc]",$vyska),0,1);
    $pdf->Cell(10,$vyska,prevod(""));
    $pdf->Cell(40,$vyska,prevod("Specifické požadavky účastníka: ",$vyska),0,1);
    if ($pole["pozadavky_prace"] != "")
    {
      $pdf->Cell(20,$vyska,prevod(""));
      $retezec = explode(" ", $pole["pozadavky_prace"]);
      foreach ($retezec as $slovo)
      { 
        $veta = "$veta"."$slovo ";
        if ((strlen($veta) > 65) || ($slovo == End($retezec)))
        {
          $pdf->Cell(160,$vyska,prevod($veta,$vyska),0,1);
          $pdf->Cell(20,$vyska,prevod(""));
          $veta = "";
        } 
      }
    }
    $pdf->Cell(10,$vyska,prevod("",$vyska),0,1);
  }
  
  if ($pole["pece_o_zav_osoby"] != "" || $pole["zdrav_stav"] != "")
  {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(7)))
      { $pdf->AddPage(); $celkova_vyska_textu = 0;}
      $pdf->SetFont('TimesB','',12);
      $pdf->Cell(60,$vyska,prevod("6. Doplňující údaje o účastníkovi projektu",$vyska),0,1);
      $pdf->SetFont('TimesN','',12);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(40,$vyska,prevod("Péče o závislé osoby:"));
      $pdf->Cell(70,$vyska,prevod($pole["pece_o_zav_osoby"],$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(40,$vyska,prevod("Zdravotní stav:"));
      $pdf->Cell(70,$vyska,prevod($pole["zdrav_stav"],$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(60,$vyska,prevod("Změněná pracovní schopnost:"));
      $pdf->Cell(70,$vyska,prevod($pole["ZPS"],$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(120,$vyska,prevod("Jak dlouho jste v evideci úřadu práce jako nezaměstnaný/á (číslo v měsících): ".$pole["doba_evidence"],$vyska),0,1);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(120,$vyska,prevod("Pokolikáté jste v evideci úřadu práce jako nezaměstnaný/á (číslo): ".$pole["kolikrat_ev"],$vyska),0,1);
      $pdf->Cell(10,$vyska,prevod("",$vyska),0,1);
  }

  if ($pole["banka"] != "-------------" || $pole["cislo"] != "")
  {
      if ($celkova_vyska_textu > ($vyska_stranky-$vyska*(4)))
      { $pdf->AddPage(); $celkova_vyska_textu = 0;}
      $pdf->SetFont('TimesB','',12);
      $pdf->Cell(60,$vyska,prevod("7. Prostředky přímé podpory",$vyska),0,1);
      $pdf->SetFont('TimesN','',12);
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(120,$vyska,prevod("Účastník požaduje vyplácet prostředky přímé podpory v hotovosti v kontaktní kanceláři:  ".$pole["prostredky_p_p"],$vyska),0,1);
       /* $pdf->Cell(40,$vyska,prevod(""));
      $pdf->Cell(40,$vyska,prevod("předčíslí*   -"));
      $pdf->Cell(40,$vyska,prevod("číslo  /"));
      $pdf->Cell(70,$vyska,prevod("kód banky",$vyska),0,1);
        $pdf->Cell(40,$vyska,prevod(""));
      $pdf->Cell(60,$vyska,prevod("* - Pokud číslo účtu neobsahuje předčíslí, pak jej nevyplňujte!",$vyska),0,1);*/
        $pdf->Cell(10,$vyska,prevod(""));
      $pdf->Cell(120,$vyska,prevod("Číslo účtu: ".$pole["predcisli"]." - ".$pole["cislo"]." / ".$pole["banka"],$vyska),0,1);
  }

/*
  $klic = array_keys ($pole);
  foreach ($pole as $prom)
  {
    $ll = current($klic);
    echo "$ll = ";
    echo " $prom<br>\n";
    next ($klic);
  }
*/

  $pdf->Output("doc.pdf",D);

exit;
?>