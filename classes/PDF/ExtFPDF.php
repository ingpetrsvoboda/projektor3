<?php
/**
 * Vytvoří pdf objekt
 * Potomek třídy fpdf, implementuje funkce Header a Footer volané zevnitř z třídy fpdf
 * a obsahuje funkce pro tisk objektů vytvořených třídami package PDF
 * @author Petr Svoboda
 *
 */
class PDF_ExtFPDF extends FPDF
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
    }

    /* Funkce přetěžuje metodu Cell rodičovské třídy FPDP
    * http://www.fpdf.org/ Extended Cell functions 
    * @author: Pivkin Vladimir, úprava Petr Svoboda
    * @param char w | šířka buňky, pokud je 0, šířka buňky je nastavena až k pravému okraji stránky
    * @param char h | výška buňky, Default: 0
    * @param string txt | text k vytištění, default prázdný řetězec
    *                   | metoda podoruje víceřádkový text v buňce, vytvoří nový řádek v místě výskytu "\n" nebo dvojice znaků CR a LF (chr(13) a chr(10))
    *                   | pokud text je jednořádkový a jeho délka přesahuje šířku buňky je komprimovín na šířku a přizpůsoben šířce buňky
    * @param char border | 0: bez ohraničení, >0: ohraničení, 
    *                    | řetězec složený z libovolné posloupnosti znaků L: left, T: top, R: right, B: bottom
    *                    | tlusté ohraničení - řetězec složený z libovolné posloupnosti znaků l: left, t: top, r: right, b: bottom
    *                    | Default: 0.
    * @param integer ln | uřčuje pozici po volání metody Cell, 0: napravo od buňky, 1: na počátku nového rádku, 2: dole
    * @param char align | zarovnání textu, L nebo prázdný řetězec: L: doleva (default), C: centrovaně, R: doprava
    * @param boolean fill | výplň, true: buňka bude vyplněna, false: buňka bude bez výplně, Default: false
    * @param string link | URL nebo indentifikátor vracený metodou AddLink()
    *  (non-PHPdoc)
    * @see __fpdf16/FPDF#Cell($w, $h, $txt, $border, $ln, $align, $fill, $link)
    */
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Output a cell
        $k=$this->k;
        if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
        {
            //Automatic page break
            $x=$this->x;
            $ws=$this->ws;
            if($ws>0)
            {
                $this->ws=0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation,$this->CurPageFormat);
            $this->x=$x;
            if($ws>0)
            {
                $this->ws=$ws;
                $this->_out(sprintf('%.3F Tw',$ws*$k));
            }
        }
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $s='';
    // begin change Cell function
        if($fill || $border>0)
        {
            if($fill)
                $op=($border>0) ? 'B' : 'f';
            else
                $op='S';
            if ($border>1) {
                $s=sprintf('q %.2F w %.2F %.2F %.2F %.2F re %s Q ',$border,
                        $this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
            }
            else
                $s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
        }
        if(is_string($border))
        {
            $x=$this->x;
            $y=$this->y;
            if(is_int(strpos($border,'L')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
            else if(is_int(strpos($border,'l')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
            
            if(is_int(strpos($border,'T')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
            else if(is_int(strpos($border,'t')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
        
            if(is_int(strpos($border,'R')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
            else if(is_int(strpos($border,'r')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
        
            if(is_int(strpos($border,'B')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
            else if(is_int(strpos($border,'b')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
        }
        if (trim($txt)!='') 
        {
//          $cr=substr_count($txt,"\n");       //původní - řádkuje na /n
            $crlf = chr(13).chr(10);             //Svoboda - řádkuje na /n i na dvojznak CR+LF !!! neodladěno
            $cr=substr_count($txt,"\n")+substr_count($txt,$crlf);   //Svoboda
            if ($cr>0) 
            { // Multi line
//              $txts = explode("\n", $txt);       //původní
                $pattern = "\\n|".$crlf;      //Svoboda
                $txts = preg_split($pattern, $txt);      //Svoboda
                $lines = count($txts);
                for($l=0;$l<$lines;$l++) 
                {
                    $txt=$txts[$l];
                    $w_txt=$this->GetStringWidth($txt);
                    if($align=='R')
                        $dx=$w-$w_txt-$this->cMargin;
                    elseif($align=='C')
                        $dx=($w-$w_txt)/2;
                    else
                        $dx=$this->cMargin;

                    $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
                    if($this->ColorFlag)
                        $s.='q '.$this->TextColor.' ';
                    $s.=sprintf('BT %.2F %.2F Td (%s) Tj ET ',
                        ($this->x+$dx)*$k,
                        ($this->h-($this->y+.5*$h+(.7+$l-$lines/2)*$this->FontSize))*$k,
                        $txt);
                    if($this->underline)
                        $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
                    if($this->ColorFlag)
                        $s.=' Q ';
                    if($link)
                        $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$w_txt,$this->FontSize,$link);
                }
            }
            else { // Single line
                $w_txt=$this->GetStringWidth($txt);
                $Tz=100;
                if ($w_txt>$w-2*$this->cMargin) { // Need compression
                    $Tz=($w-2*$this->cMargin)/$w_txt*100;
                    $w_txt=$w-2*$this->cMargin;
                }
                if($align=='R')
                    $dx=$w-$w_txt-$this->cMargin;
                elseif($align=='C')
                    $dx=($w-$w_txt)/2;
                else
                    $dx=$this->cMargin;
                $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
                if($this->ColorFlag)
                    $s.='q '.$this->TextColor.' ';
                $s.=sprintf('q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ',
                            ($this->x+$dx)*$k,
                            ($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,
                            $Tz,$txt);
                if($this->underline)
                    $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
                if($this->ColorFlag)
                    $s.=' Q ';
                if($link)
                    $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$w_txt,$this->FontSize,$link);
            }
        }
//  end change Cell function
        if($s)
            $this->_out($s);
        $this->lasth=$h;
        if($ln>0)
        {
        //  Go to next line
            $this->y+=$h;
            if($ln==1)
                $this->x=$this->lMargin;
        }
        else
            $this->x+=$w;
    }

}