<?php
/**
 * Vytvoří pdf objekt
 * Potomek třídy PDF_ExtFPDF, implementuje funkce Header a Footer volané zevnitř z třídy fpdf
 * a obsahuje funkce pro tisk objektů vytvořených třídami obsaženými v package PDF
 * @author Petr Svoboda
 *
 */
class PDF_VytvorPDF extends PDF_ExtFPDF
{
  	/**
  	 * @param character $orientation, default 'P'
  	 * @param string $unit, default 'mm'
  	 * @param string $format, default 'A4'
  	 * @return 
  	 */
	
    public function __construct($orientation='P', $unit='mm', $format='A4') 
    {
        parent::__construct();
        $this->FPDF($orientation, $unit, $format);     
    }

    function Debug($debug=false)
    {
        $pdfdebug = PDF_Kontext::dejDebug();
    	$pdfdebug->debug($debug);
    }

    function AutoSirka($txt)
    {
    	return $this->GetStringWidth($txt)+2*$this->cMargin;
    }
    
    function DebugCell ($obj, $w=0, $h=0, $txtUTF8='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $pdfdebug = PDF_Kontext::dejDebug();
        $txt1250 = iconv("UTF-8","windows-1250",$txtUTF8);
        print_r($pdfhlavicka->text);

        switch ($pdfdebug->debug) 
        {
            case 0:
                $this->Cell($w, $h, $txt1250, $border, $ln, $align, $fill, $link);
                //                    $this->MultiCell($w, $h, $txt1250, $border, $align, $fill);
                break;
            case 1:
                $this->SetDrawColor(255, 0, 63);	
                $this->Cell($w, $h, $txt1250, 1, $ln, $align, 1);
                $this->SetDrawColor(255,255,255);
                break;
            case 2:
                if (!$txt1250) $txt1250=iconv("UTF-8","windows-1250", "text je prázdný");
                $txt1250 =  get_class($obj) . ": " . $txt1250;
                $this->SetFillColor(191, 191, 0);
                $this->SetDrawColor(255, 0, 63);
                if (is_a  ($obj, PDF_Bunka)) 
                {
                $deb=iconv("UTF-8","windows-1250", $obj->debugPrazdna);
                $this->Cell(self::AutoSirka($deb), 6, $deb, 1, $ln, $align, 1);
                }
                $this->Cell(self::AutoSirka($txt1250), 6, $txt1250, 1, $ln, $align, 1);
                $this->SetFillColor(255,255,255);
                $this->SetDrawColor(255,255,255);
                break;
        }
    }
	
    function Header()
    {
        $pdfhlavicka = PDF_Kontext::dejHlavicku();
        if ($pdfhlavicka->obrazekSoubor)
        {
            if ($pdfhlavicka->zarovnani=="C") $xobr = ($this->w-$pdfhlavicka->obrazekW)/2;
            if ($pdfhlavicka->zarovnani=="R") $xobr = ($this->w-($pdfhlavicka->obrazekW+$pdfhlavicka->Odsazeni+$this->rMargin));
            if ($pdfhlavicka->zarovnani=="L") $xobr = ($pdfhlavicka->Odsazeni+$this->lMargin);
            $xobr = $xobr + $pdfhlavicka->obrazekX;		// x pozice obrázku je vztažena k levé straně hlavičky
            $this->Image($pdfhlavicka->obrazekSoubor, $xobr, $pdfhlavicka->obrazekY, $pdfhlavicka->obrazekW, $pdfhlavicka->obrazekH, $pdfhlavicka->obrazekTyp);
        }
    	if ($pdfhlavicka->text)
        {
            $this->SetFont('times','B',$pdfhlavicka->vyskaPisma);
            $w=self::AutoSirka($pdfhlavicka->text);
            if ($pdfhlavicka->zarovnani=="C") $this->SetX(($this->w-$w)/2);
            if ($pdfhlavicka->zarovnani=="R") $this->SetX(($this->w-($w+$pdfhlavicka->Odsazeni+$this->rMargin)));
            if ($pdfhlavicka->zarovnani=="L") $this->SetX($pdfhlavicka->Odsazeni+$this->lMargin);
                                 
            $this->SetDrawColor($pdfhlavicka->barvaRamecku);
            $this->SetFillColor($pdfhlavicka->barvaPozadi);
            $this->SetTextColor($pdfhlavicka->barvaPisma);
            $this->SetLineWidth(1);
            $this->Cell( $w,$pdfhlavicka->vyskaPisma/2,$pdfhlavicka->text,1,1,$pdfhlavicka->zarovnani,true);
            $this->Ln($pdfhlavicka->vyskaPisma);
        }
    }

    function Footer()
    {
        $pdfpaticka = PDF_Kontext::dejPaticku();
    	if ($pdfpaticka->text)
        {   
            $this->SetFont('Times','B',$pdfpaticka->vyskaPisma);
            $w=self::AutoSirka($pdfpaticka->text);
            $this->SetY(-2*$pdfpaticka->vyskaPisma);
            $this->SetDrawColor($pdfpaticka->barvaRamecku);
            $this->SetFillColor($pdfpaticka->barvaPozadi);
            $this->SetTextColor($pdfpaticka->barvaPisma);
            
            $this->SetLineWidth(1);
            
            if ($pdfpaticka->zarovnani=="C")
                $this->SetX(($this->w-$w)/2);
            if ($pdfpaticka->zarovnani=="R")
                $this->SetX(($this->w-($w+$pdfpaticka->Odsazeni+$this->rMargin)));
            if ($pdfpaticka->zarovnani=="L")
                $this->SetX($pdfpaticka->Odsazeni+$this->lMargin);

            $this->Cell($w,$pdfpaticka->vyskaPisma/2,$pdfpaticka->text,1,1,$pdfpaticka->zarovnani,true);
            if ($pdfpaticka->cislovani)
            {
               	$pdfpaticka->cisloStranky = $pdfpaticka->cisloStranky + 1;
            	$w = self::AutoSirka("- ".$pdfpaticka->cisloStranky." -");
            	if ($pdfpaticka->zarovnani=="C")
                	$this->SetX(($this->w-$w)/2);
            	if ($pdfpaticka->zarovnani=="R")
                	$this->SetX(($this->w-($w+$pdfpaticka->Odsazeni+$this->rMargin)));
            	if ($pdfpaticka->zarovnani=="L")
                	$this->SetX($pdfpaticka->Odsazeni+$this->lMargin);
            	$this->Cell($w,$pdfpaticka->vyskaPisma/2,"- ".$pdfpaticka->cisloStranky." -",1,1,$pdfpaticka->zarovnani,true);
            }
        }
    }


    /**
     * Funkce tiskne objekt třídy SadaBunek
     * @param SadaBunek $sadaBunek
     * @param integer $pocetMezer , počet mezer vkládaných mezi jednotlivé buňky v řádku
     * @param unknown_type $tiskniVzdy
     * @param unknown_type $tiskniJenNeprazdnou
     * @param unknown_type $rozdelujSadu
     * @return unknown_type
     */
    function TiskniSaduBunek($sadaBunek, $pocetMezer=1, $tiskniVzdy=false, $tiskniJenSpustenou=false, $rozdelujSadu=false)
    {
        $pdfdebug = PDF_Kontext::dejDebug();
				
		if($sadaBunek->sadaNeniPrazdna AND !$tiskniJenSpustenou OR $tiskniVzdy OR $sadaBunek->sadaSpustena OR $pdfdebug->debug > 0)
        {
        	// určení výšky sady buněk
        	$vyskaRadku = 0;
        	$vyskaSady = 0;
        	if ($sadaBunek->bunky)
			{
		    foreach ($sadaBunek->bunky as $bunka)
        		{
        			$vyskaRadku = max($vyskaRadku, $bunka->vyska); 
        			$bunkyVRadku[] = $bunka;
        			if($bunka->odradkovani)
        			{
        				$vyskaRadku = max($vyskaRadku, $sadaBunek->vyskaPismaBunek);
        				$radky[] = array(vyska => $vyskaRadku/2, bunky => $bunkyVRadku  );
        				$bunkyVRadku = null;
        				$vyskaSady = $vyskaSady + $vyskaRadku/2;
        	    		$vyskaRadku = 0;
        			}
        		}
			}
        	// automatické odstránkování, pokud se sada buněk nevejde na stránku
            $y = $this->y;          //aktuální pozice na stránce
			if($y+$vyskaSady > ($this->h - $this->bMargin) AND !$rozdelujSadu)
        	{
        		$this->AddPage();
        	}
        	// nastav barvy
        	$this->SetDrawColor(255,255,255);
        	$this->SetFillColor(255,255,255);
        	$this->SetTextColor($sadaBunek->barvaPisma);

        	if ($sadaBunek->nadpis) 
        	{
        		$this->SetFont('Times','B',$$sadaBunek->vyskaPismaNadpisu);
        		$this->Ln(1);
				$this->DebugCell($sadaBunek, 0,($sadaBunek->vyskaPismaNadpisu)/2,$sadaBunek->nadpis,0,1,'L'); 
				$this->Ln(1);  
        	}
        	elseif ($pdfdebug->debug > 1)
        	{
				$this->DebugCell($sadaBunek, 0, ($sadaBunek->vyskaPismaNadpisu)/2, "Text nadpisu sady buněk je prázdný" , 0, 1, "L");
        	}
        	
        	$this->SetFont('Times','',$sadaBunek->vyskaPismaBunek);
        	if ($radky)
			{
				foreach ($radky as $radek)
				{
					$bunky = $radek['bunky'];
					foreach ($bunky as $bunka)
					{
						$text = $bunka->textUTF8." ".$bunka->promennaUTF8;
						if ($bunka->sirka)
						{
							$sirka = $bunka->sirka;
						}
						else
						{
							if ($bunka->odradkovani)
							{
								$sirka = self::AutoSirka($text);        					
							}
							else
							{
								$sirka = self::AutoSirka($text.str_repeat(" ", $pocetMezer));        					
							}
						}
						$this->DebugCell($bunka, $sirka, $radek["vyska"], $text, $bunka->ohraniceni,
									$bunka->odradkovani, $bunka->zarovnani, $bunka->vypln);
					}
					if (!$bunka->odradkovani) $this->Ln(1);      			
				}
			}
			elseif ($pdfdebug->debug > 1)
			{
				$this->DebugCell($sadaBunek, 0, ($sadaBunek->vyskaPismaBunek)/2, "Sada buňek je prázdná" , 0, 1, "L");
			}
        }
    }
    function TiskniOdstavec($odstavec, $rozdelujOdstavec=false)
    {    	
        $pdfdebug = PDF_Kontext::dejDebug();
    	
        $this->SetFont('Times','',$odstavec->vyskaPismaTextu);
        $this->SetDrawColor("255,255,255");
        $this->SetFillColor("255,255,255");
        $this->SetTextColor($odstavec->barvaPisma);
        
        $paragrafy = explode(chr(13).chr(10), $odstavec->text);          //jen pro Windows
        
        foreach ($paragrafy as $paragraf)
        {
        	
            $slova = explode(" ", $paragraf);
            $pocetRadekParagrafu = 1;
            $textRadku = "";
            $sirkaOdstavce = $this->w - $this->lMargin - $this->rMargin - $odstavec->odsazeniZleva - $odstavec->odsazeniZprava;
            // spočítá počet řádků 
            foreach ($slova as $slovo)
            {
        	    $textRadku = $textRadku.$slovo." ";
                $delka = self::AutoSirka($textRadku);
                if ($delka > $sirkaOdstavce)
                {  
            	    $pocetRadekParagrafu++; 
            	    $textRadku = $slovo;  
                }
            }
        
            if ($odstavec->nadpis) 
            {
        	   $vyskaNadpisu = $odstavec->vyskaPismaNadpisu/2;
            }
            else
		    {
        	    $vyskaNadpisu = 0;
            }
            
            if ($paragraf) 
            {
                $vyskaParagrafu = $odstavec->vyskaPismaTextu/2*$pocetRadekParagrafu;
            }
            else
            {
                $vyskaParagrafu = 0;
            } 
            // automatické zalomení stránky, pokud se odstavce nemají rozdělovat a nevejde se na zbytek stránky
            if (!$rozdelujOdstavec)
            {
                if ($this->h-$this->y < ($vyskaNadpisu + $vyskaParagrafu)) $this->AddPage();    //CHYBA odstavec delší než stránka se tu zacyklí
            }
            if ($odstavec->nadpis) 
            {  
                $this->SetFont('Times','B',$odstavec->vyskaPismaNadpisu);
        	    $this->x = $this->lMargin + $odstavec->odsazeniZleva;
        	    $this->DebugCell($odstavec, 0,$vyskaNadpisu,$odstavec->nadpis,0,1,$odstavec->zarovnaniNadpisu); 
        	    $this->Ln(2);  
            }
            elseif ($pdfdebug->debug > 1)
            {
        	    $this->DebugCell($odstavec, 0,$vyskaNadpisu,"Text nadpisu je prázdný",0,1,$odstavec->zarovnaniNadpisu); 
            }

			$this->SetFont('Times','',$odstavec->vyskaPismaTextu );
			if ($paragraf)	//tiskne se jen neprázdný odstavec (pro prázdný se ani neodřádkuje)
			{
                            $this->x = $this->lMargin + $odstavec->odsazeniZleva;
                            $zacatek = $this->x;
                            $radek = "";
                            $space = "";      // před prvním slovem odstavce není mezera, mezi slovy pak ano
                            foreach ($slova as $slovo)
                            {
                                if (($zacatek+$this->GetStringWidth($radek.$space.$slovo)) > $sirkaOdstavce)
                                {
                                    // zalomení
                                    $this->DebugCell($odstavec, $this->GetStringWidth($radek), ($odstavec->vyskaPismaTextu)/2, $radek , 0, 0, $odstavec->zarovnaniTextu);
                                    $this->Ln();
                                    $radek = $slovo;
                                    $space = " ";
                                    $this->x = $this->lMargin + $odstavec->odsazeniZleva + $odstavec->predsazeni;
                                }
                                else
                                {
                                    $radek = $radek.$space.$slovo;
                                    $space = " ";
                                }
                            }
                            $this->DebugCell($odstavec, $this->GetStringWidth($radek), ($odstavec->vyskaPismaTextu)/2, $radek , 0, 0, $odstavec->zarovnaniTextu);
                            $this->Ln($odstavec->vyskaPismaTextu/2);        
                        }
                        elseif ($pdfdebug->debug > 1)
                        {
                            $this->DebugCell($odstavec, 0, ($odstavec->vyskaPismaTextu)/2, "Text odstavce je prázdný" , 0, 0, $odstavec->zarovnaniTextu);
                        }
		}
	}

   
  function TiskniTabulku($tabulka)
  {
      //Šířka sloupců
      $pocet_sloupcu[] = count($tabulka->zahlavi);
   	  foreach ($tabulka->data as $radek)
      {
      	$pocet_sloupcu[] = count($radek);
      }
      $pocet = max($pocet_sloupcu);      
      $sirkaSloupce = floor($tabulka->sirka/$pocet);	//všechny sloupce stejně široké
      
      //Nadpis
      if ($tabulka->nazev)
      {
        $this->SetFont('Times','BU',$tabulka->vyskaPismaNadpisu);
        $h = (($tabulka->sirka)/2) - $this->GetStringWidth($tabulka->nazev)/2;
        $this->SetX(($tabulka->odsazeni)+$h);
        $this->Cell(0,$tabulka->vyskaPismaNadpisu/2,"$tabulka->nazev",0,1,'L');
        $this->Ln(2);
      }
      
      //Záhlaví
      $this->SetX($tabulka->odsazeniZleva);
      $this->SetLineWidth(0.4);
      $this->SetFont('Times','B',($tabulka->vyskaPismaZahlavi));
      for($i=0; $i<($pocet); $i++)
      {
          if ($this->GetStringWidth($tabulka->zahlavi[$i])>$sirkaSloupce)
              $this->SetTextColor(255,0,0);
          $this->Cell($sirkaSloupce,$tabulka->vyskaPismaZahlavi/2,$tabulka->zahlavi[$i],1,0,'C');
          $this->SetTextColor(0,0,0);
      }
      $this->Ln();
      
      //Data
      $this->SetLineWidth(0.2);
      $this->SetFont('Times','',$tabulka->vyskaPismaDat);
      foreach($tabulka->data as $radek)
      {
          $this->SetX($tabulka->odsazeniZleva);
          foreach ($radek as $bunka)
          {
              if ($this->GetStringWidth($bunka)>$sirkaSloupce)
                  $this->SetTextColor(255,0,0);
              $this->Cell($sirkaSloupce,$tabulka->vyskaPismaDat/2,$bunka,1,0,'C');
              $this->SetTextColor(0,0,0);
          }
          $this->Ln();
      }
  }
   

}
