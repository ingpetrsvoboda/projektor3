<?php
/**
 * Vytvoří pdf objekt
 * Potomek třídy PDF_ExtFPDF, implementuje funkce Header a Footer volané zevnitř z třídy fpdf
 * a obsahuje funkce pro tisk objektů vytvořených třídami obsaženými v package PDF
 * @author Petr Svoboda
 *
 */
class Projektor2_PDF_VytvorPDF extends Projektor2_PDF_ExtendedFPDF
{
    public function Debug($debug=false)
    {
        $pdfdebug = Projektor2_PDFContext::getDebug();
    	$pdfdebug->debug($debug);
    }

    private function AutoSirka($txt)
    {
        $max = 0;
        if (strlen($txt)) {
            $paragrafy = $txt ? preg_split('/\R/', $txt) : '';
            foreach ($paragrafy as $paragraf) {
                $new = $this->GetStringWidth(iconv("UTF-8","windows-1250",$paragraf))+2*$this->cMargin;
                if ($new>$max) $max = $new;
            }
        }
    	return $max;
    }

    private function DebugCell ($w=0, $h=0, $txtUTF8='', $border=0, $ln=0, $align='', $fill=false, $link='', $lineSpacing=1.5, $hangingIndent=0) {
        $pdfdebug = Projektor2_PDFContext::getDebug();
        $txt1250 = iconv("UTF-8","windows-1250",$txtUTF8);

        switch ($pdfdebug->debug) {
            case 0:
                $this->Cell($w, $h, $txt1250, $border, $ln, $align, $fill, $link, $lineSpacing, $hangingIndent);
                break;
            case 1:
                $this->SetDrawColor(255, 0, 63);
                $this->Cell($w, $h, $txt1250, 1, $ln, $align, 1, $link, $lineSpacing, $hangingIndent);
                $this->SetDrawColor(255,255,255);
                break;
            default:
                $this->Cell($w, $h, $txt1250, $border, $ln, $align, $fill, $link, $lineSpacing, $hangingIndent);                
                break;
        }
    }

    public function Header()     //must be public as in FPDF
    {
        $pdfhlavicka = Projektor2_PDFContext::getHlavicka();
        $this->Ln($pdfhlavicka->odsazeniNahore);
        if ($pdfhlavicka->obrazekSoubor)
        {
            if ($pdfhlavicka->zarovnani=="C")
                $xobr = ($this->w-$pdfhlavicka->obrazekW)/2;
            if ($pdfhlavicka->zarovnani=="R")
                $xobr = ($this->w-($pdfhlavicka->obrazekW+$pdfhlavicka->Odsazeni+$this->rMargin));
            if ($pdfhlavicka->zarovnani=="L")
                $xobr = ($pdfhlavicka->Odsazeni+$this->lMargin);
            $xobr = $xobr + $pdfhlavicka->obrazekX;		// x pozice obrázku je vztažena k levé straně hlavičky
            $this->Image($pdfhlavicka->obrazekSoubor, $xobr, $pdfhlavicka->obrazekY,
        	$pdfhlavicka->obrazekW, $pdfhlavicka->obrazekH, $pdfhlavicka->obrazekTyp);
        }
    	if ($pdfhlavicka->text)
        {
            $this->SetFont('times','B',$pdfhlavicka->vyskaPisma);
            $sirkaTextu=$this->AutoSirka($pdfhlavicka->text);
            if ($pdfhlavicka->zarovnani=="C")
                $this->SetX(($this->w-$sirkaTextu)/2);
            if ($pdfhlavicka->zarovnani=="R")
                $this->SetX(($this->w-($sirkaTextu+$pdfhlavicka->Odsazeni+$this->rMargin)));
            if ($pdfhlavicka->zarovnani=="L")
                $this->SetX($pdfhlavicka->Odsazeni+$this->lMargin);

            $this->SetDrawColor($pdfhlavicka->barvaRamecku);
            $this->SetFillColor($pdfhlavicka->barvaPozadi);
            $this->SetTextColor($pdfhlavicka->barvaPisma);

            $this->SetLineWidth(1);
            $this->Cell( $sirkaTextu,$pdfhlavicka->vyskaPisma/$this->k,$pdfhlavicka->text,1,1,$pdfhlavicka->zarovnani,true, '', $pdfhlavicka->radkovani);
        }
        $this->Ln($pdfhlavicka->odsazeniDole);
    }

    public function Footer()  //must be public as in FPDF
    {
        $pdfpaticka = Projektor2_PDFContext::getPaticka();
        $vyskaTextuPaticky = $this->LineCount($pdfpaticka->text)*$pdfpaticka->vyskaPisma*$pdfpaticka->radkovani/$this->k;
        $vyskaCislovaniPaticky = $pdfpaticka->cislovani ? $pdfpaticka->vyskaPisma*$pdfpaticka->radkovani/$this->k : 0;
        $this->SetFont('Times','',$pdfpaticka->vyskaPisma);
        $this->SetPageBreakTrigger($this->h-$this->bMargin-$pdfpaticka->odsazeniNahore-$vyskaTextuPaticky-$vyskaCislovaniPaticky-$pdfpaticka->odsazeniDole);
        $this->SetY(-1*($vyskaTextuPaticky+$vyskaCislovaniPaticky+$pdfpaticka->odsazeniDole));  //záporná hodnota = výška nad bottom margin strínky
        $this->SetDrawColor($pdfpaticka->barvaRamecku);
        $this->SetFillColor($pdfpaticka->barvaPozadi);
        $this->SetTextColor($pdfpaticka->barvaPisma);
        $this->SetLineWidth(1);
    	if ($pdfpaticka->text) {
            $sirkaTextu=$this->AutoSirka($pdfpaticka->text);
            if ($pdfpaticka->zarovnani=="C")
                $this->SetX(($this->w-$sirkaTextu)/2);
            if ($pdfpaticka->zarovnani=="R")
                $this->SetX(($this->w-($sirkaTextu+$pdfpaticka->Odsazeni+$this->rMargin)));
            if ($pdfpaticka->zarovnani=="L")
                $this->SetX($pdfpaticka->Odsazeni+$this->lMargin);

            $this->Cell($sirkaTextu, $vyskaTextuPaticky, $pdfpaticka->text, 1, 1, $pdfpaticka->zarovnani, true, '', $pdfpaticka->radkovani);
        }
        if ($pdfpaticka->cislovani) {
            $pdfpaticka->cisloStranky = $pdfpaticka->cisloStranky + 1;
            $sirkaTextu = $this->AutoSirka("- ".$pdfpaticka->cisloStranky." -");
            if ($pdfpaticka->zarovnani=="C")
                    $this->SetX(($this->w-$sirkaTextu)/2);
            if ($pdfpaticka->zarovnani=="R")
                    $this->SetX(($this->w-($sirkaTextu+$pdfpaticka->Odsazeni+$this->rMargin)));
            if ($pdfpaticka->zarovnani=="L")
                    $this->SetX($pdfpaticka->Odsazeni+$this->lMargin);

            $this->Cell($sirkaTextu, $vyskaCislovaniPaticky, "- ".$pdfpaticka->cisloStranky." -", 1, 1, $pdfpaticka->zarovnani, true, '', $pdfpaticka->radkovani);
        }
    }


    /**
     * Funkce tiskne objekt třídy SadaBunek
     * @param Projektor2_PDF_SadaBunek $sadaBunek
     * @param integer $pocetMezer , počet mezer vkládaných mezi jednotlivé buňky v řádku
     * @param unknown_type $tiskniVzdy
     * @param unknown_type $tiskniJenNeprazdnou
     * @param unknown_type $rozdelujSadu
     * @return unknown_type
     */
    public function TiskniSaduBunek(Projektor2_PDF_SadaBunek $sadaBunek, $pocetMezer=1, $tiskniVzdy=false, $tiskniJenSpustenou=false, $rozdelujSadu=false) {
        $pdfdebug = Projektor2_PDFContext::getDebug();

        if($sadaBunek->sadaNeniPrazdna AND !$tiskniJenSpustenou OR $tiskniVzdy OR $sadaBunek->sadaSpustena OR $pdfdebug->debug > 0) {
            // určení výšky sady buněk
            $vyskaRadku = 0;
            $vyskaSady = 0;
            if ($sadaBunek->bunky) {
                foreach ($sadaBunek->bunky as $bunka) {
                    $vyskaRadku = max($vyskaRadku, $bunka->vyska);
                    $bunkyVRadku[] = $bunka;
                    if($bunka->odradkovani) {
                        $vyskaRadku = max($vyskaRadku, $sadaBunek->vyskaPismaBunek);
                        $radky[] = array('vyska' => $vyskaRadku/2, 'bunky' => $bunkyVRadku  );
                        $bunkyVRadku = null;
                        $vyskaSady = $vyskaSady + $vyskaRadku/2;
                        $vyskaRadku = 0;
                    }
                }
            }
        	// automatické odstránkování, pokud se sada buněk nevejde na stránku
            $y = $this->y;          //aktuální pozice na stránce
            if($y+$vyskaSady > ($this->h - $this->bMargin) AND !$rozdelujSadu) {
                $this->AddPage();
            }
            // nastav barvy
            $this->SetDrawColor(255,255,255);
            $this->SetFillColor(255,255,255);
            $this->SetTextColor($sadaBunek->barvaPisma);

            if ($sadaBunek->nadpis) {
                $this->SetFont('Times','B',$vyskaNadpisu);
                $this->Ln(1);
                $this->DebugCell(0,$sadaBunek->vyskaPismaNadpisu/$this->k,$sadaBunek->nadpis,0,1,'L');
                $this->Ln(1);
            } elseif ($pdfdebug->debug > 1) {
                $this->DebugCell(0, $sadaBunek->vyskaPismaNadpisu/$this->k, "Text nadpisu sady buněk je prázdný" , 0, 1, "L");
            }

            $this->SetFont('Times','',$sadaBunek->vyskaPismaBunek);
            if ($radky) {
                foreach ($radky as $radek) {
                    $bunky = $radek['bunky'];
                    foreach ($bunky as $bunka) {
                        $text = $bunka->textUTF8." ".$bunka->promennaUTF8;
                        if ($bunka->sirka) {
                                $sirka = $bunka->sirka;
                        } else {
                            if ($bunka->odradkovani) {
                                $sirka = $this->AutoSirka($text);
                            } else {
                                $sirka = $this->AutoSirka($text.str_repeat(" ", $pocetMezer));
                            }
                        }
                        $this->DebugCell($sirka, $radek['vyska'], $text, $bunka->ohraniceni, $bunka->odradkovani, $bunka->zarovnani, $bunka->vypln);
                    }
                    if (!$bunka->odradkovani) $this->Ln(1);
                }
            } elseif ($pdfdebug->debug > 1) {
                $this->DebugCell(0, $sadaBunek->vyskaPismaBunek/$this->k, "Sada buňek je prázdná" , 0, 1, "L");
            }
        }
    }

    /**
     *
     * @param type $blok
     * @param type $rozdelujOdstavec
     */
    public function TiskniBlok(Projektor2_PDF_Blok $blok) {
//        $pdfdebug = Projektor2_PDFContext::getDebug();
        $this->SetDrawColor("255,255,255");
        $this->SetFillColor("255,255,255");
        $this->SetTextColor($blok->barvaPisma);
        if ($blok->nadpis) {
            $vyskaNadpisu = $blok->vyskaPismaNadpisu/$this->k;
        } else {
            $vyskaNadpisu = 0;
        }

        $paragrafy = $blok->text ? preg_split('/\R/', $blok->text) : '';      //Svoboda (varianta: '/\n|\r\n?/')
        if ($paragrafy) {
            $prvniParagraf = TRUE;
            $this->SetFont('Times','',$blok->vyskaPismaTextu ); // pro správnou funkčnost $this->GetStringWidth
            foreach ($paragrafy as $paragraf) {
                $slova = explode(" ", $paragraf);
                $textRadku = "";
                $delkaRadku = 0;
                $zalomenyText = '';
                $radky = array();
                $sirkaOdstavce = $this->w - $this->lMargin - $this->rMargin - $blok->odsazeniZleva - $blok->odsazeniZprava-2*$this->cMargin;
                $prvniRadek = TRUE;
                foreach ($slova as $slovo) {
                    $token = $slovo ? $slovo." " : "";
                    $delkaTokenu = $this->GetStringWidth(iconv("UTF-8","windows-1250",$token));

                    if ($delkaRadku+$delkaTokenu <= $sirkaOdstavce) {
                        $textRadku .= $token;
                        $delkaRadku += $delkaTokenu;
                    } else {
                        if ($prvniRadek) {
                            $prvniRadek = FALSE;
                            $zalomenyText = $textRadku;
                            $radky[] = $textRadku;
                            $sirkaOdstavce = $sirkaOdstavce-$blok->predsazeni;                            
                        } else {
                            $zalomenyText .=  chr(13).chr(10).$textRadku; 
                            $radky[] = $textRadku;
                        }
                        $textRadku = $token;
                        $delkaRadku = $delkaTokenu;
                    }
                }
                if ($textRadku) {
                    if ($prvniRadek) {
                        $zalomenyText = $textRadku;
                    } else {
                        $zalomenyText .=  chr(13).chr(10).$textRadku; 
                    }
                    $radky[] = $textRadku;                    
                }
                $pocetRadku = count($radky);
                if ($prvniParagraf) {
                    $pocetRadekParagrafuKtereSeVejdouNaStranku = floor(($this->PageBreakTrigger - $this->y - $blok->mezeraPredNadpisem - $vyskaNadpisu - $blok->mezeraPredOdstavcem)/($blok->vyskaPismaTextu*$blok->radkovani/$this->k));
                } else {
                    $pocetRadekParagrafuKtereSeVejdouNaStranku = floor(($this->PageBreakTrigger - $this->y - $blok->mezeraPredOdstavcem)/($blok->vyskaPismaTextu*$blok->radkovani/$this->k));
                }
                $odstrankovat = FALSE;
                if ($pocetRadku>$pocetRadekParagrafuKtereSeVejdouNaStranku 
                    AND 
                    (!$blok->povoleniRozdeleniOdstavce
                     OR 
                     $pocetRadku>1 AND $pocetRadekParagrafuKtereSeVejdouNaStranku == 1 
                     OR 
                     $pocetRadekParagrafuKtereSeVejdouNaStranku-$pocetRadku == 1
                    )
                    OR
                    $blok->nadpis AND $pocetRadekParagrafuKtereSeVejdouNaStranku == 0
                   ) $odstrankovat = TRUE;
                if ($odstrankovat AND !$this->InHeader AND !$this->InFooter AND $this->AcceptPageBreak()) {
                    $this->AddPage();
                    if ($prvniParagraf AND $blok->nadpis) {
                        $this->SetFont('Times','B',$blok->vyskaPismaNadpisu);
                        $this->x = $this->lMargin + $blok->odsazeniZleva;
                        $this->DebugCell(0, $vyskaNadpisu, $blok->nadpis, 0, 1, $blok->zarovnaniNadpisu, FALSE, '', 0);                
                        $this->Ln($blok->mezeraPredOdstavcem);
                    }
                    $this->SetFont('Times','',$blok->vyskaPismaTextu );
                    $this->x = $this->lMargin + $blok->odsazeniZleva;
                    $this->DebugCell(0, $pocetRadku*$blok->vyskaPismaTextu*$blok->radkovani/$this->k, $zalomenyText , 0, 1, $blok->zarovnaniTextu, FALSE, '', $blok->radkovani, $blok->predsazeni);
                } else {
                    if ($prvniParagraf AND $blok->nadpis) {
                        $this->SetFont('Times','B',$blok->vyskaPismaNadpisu);
                        $this->Ln($blok->mezeraPredNadpisem);                    
                        $this->x = $this->lMargin + $blok->odsazeniZleva;
                        $this->DebugCell(0, $vyskaNadpisu, $blok->nadpis, 0, 1, $blok->zarovnaniNadpisu, FALSE, '', 0);                
                    }
                    $this->SetFont('Times','',$blok->vyskaPismaTextu );
                    $this->Ln($blok->mezeraPredOdstavcem);
                    $this->x = $this->lMargin + $blok->odsazeniZleva;
                    $this->DebugCell(0, $pocetRadku*$blok->vyskaPismaTextu*$blok->radkovani/$this->k, $zalomenyText , 0, 1, $blok->zarovnaniTextu, FALSE, '', $blok->radkovani, $blok->predsazeni);
                }
                $prvniParagraf = FALSE;
            } 
        } else {
            if ($blok->nadpis) {
                $this->SetFont('Times','B',$blok->vyskaPismaNadpisu);
                if (($this->y+$blok->mezeraPredNadpisem+$vyskaNadpisu > $this->PageBreakTrigger) AND !$this->InHeader AND !$this->InFooter AND $this->AcceptPageBreak()) {
                    $this->AddPage();
                    $this->x = $this->lMargin + $blok->odsazeniZleva;
                    $this->DebugCell(0, $vyskaNadpisu, $blok->nadpis, 0, 1, $blok->zarovnaniNadpisu, FALSE, '', 0);                
                } else {
                    $this->Ln($blok->mezeraPredNadpisem);                    
                    $this->x = $this->lMargin + $blok->odsazeniZleva;
                    $this->DebugCell(0, $vyskaNadpisu, $blok->nadpis, 0, 1, $blok->zarovnaniNadpisu, FALSE, '', 0);                
                }
            }            
        }
    }

    public function TiskniTabulku($tabulka) {
        //Šířka sloupců
        $pocet_sloupcu[] = count($tabulka->zahlavi);
        foreach ($tabulka->data as $radek) {
            $pocet_sloupcu[] = count($radek);
        }
        $pocet = max($pocet_sloupcu);
        $sirkaSloupce = floor($tabulka->sirka/$pocet);	//všechny sloupce stejně široké

        //Nadpis
        if ($tabulka->controllerName) {
            $this->SetFont('Times','BU',$tabulka->vyskaPismaNadpisu);
            $h = (($tabulka->sirka)/2) - $this->GetStringWidth($tabulka->controllerName)/2;
            $this->SetX(($tabulka->odsazeniDole)+$h);
            $this->Cell(0,$tabulka->vyskaPismaNadpisu/$this->k,"$tabulka->controllerName",0,1,'L');
            $this->Ln(2);
        }

        //Záhlaví
        $this->SetX($tabulka->odsazeniZleva);
        $this->SetLineWidth(0.4);
        $this->SetFont('Times','B',($tabulka->vyskaPismaZahlavi));
        for($i=0; $i<($pocet); $i++) {
            if ($this->GetStringWidth($tabulka->zahlavi[$i])>$sirkaSloupce) $this->SetTextColor(255,0,0);
            $this->Cell($sirkaSloupce,$tabulka->vyskaPismaZahlavi/$this->k,$tabulka->zahlavi[$i],1,0,'C');
            $this->SetTextColor(0,0,0);
        }
        $this->Ln();

        //Data
        $this->SetLineWidth(0.2);
        $this->SetFont('Times','',$tabulka->vyskaPismaDat);
        foreach($tabulka->data as $radek) {
            $this->SetX($tabulka->odsazeniZleva);
            foreach ($radek as $bunka) {
                if ($this->GetStringWidth($bunka)>$sirkaSloupce) $this->SetTextColor(255,0,0);
                $this->Cell($sirkaSloupce,$tabulka->vyskaPismaDat/$this->k,$bunka,1,0,'C');
                $this->SetTextColor(0,0,0);
            }
            $this->Ln();
        }
    }
}
