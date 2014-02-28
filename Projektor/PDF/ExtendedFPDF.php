<?php
/**
 * Vytvoří pdf objekt
 * Potomek třídy fpdf, implementuje funkce Header a Footer volané zevnitř z třídy fpdf
 * a obsahuje funkce pro tisk objektů vytvořených třídami package PDF
 * @author Petr Svoboda
 *
 */
class Projektor2_PDF_ExtendedFPDF extends FPDF
{
    /**
     * Konstruktor - přetěžuje konstruktor třídy FPDF
     * @param character $orientation, default 'P'
     * @param string $unit, default 'mm'
     * @param string $size, default 'A4'
     * @return
     */
    public function __construct($orientation='P', $unit='mm', $size='A4')
    {
	// Some checks
	$this->_dochecks();
	// Initialization of properties
	$this->page = 0;
	$this->n = 2;
	$this->buffer = '';
	$this->pages = array();
	$this->PageSizes = array();
	$this->state = 0;
	$this->fonts = array();
	$this->FontFiles = array();
	$this->diffs = array();
	$this->images = array();
	$this->links = array();
	$this->InHeader = false;
	$this->InFooter = false;
	$this->lasth = 0;
	$this->FontFamily = '';
	$this->FontStyle = '';
	$this->FontSizePt = 12;
	$this->underline = false;
	$this->DrawColor = '0 G';
	$this->FillColor = '0 g';
	$this->TextColor = '0 g';
	$this->ColorFlag = false;
	$this->ws = 0;
	// Font path
	if(defined('FPDF_FONTPATH'))
	{
		$this->fontpath = FPDF_FONTPATH;
		if(substr($this->fontpath,-1)!='/' && substr($this->fontpath,-1)!='\\')
			$this->fontpath .= '/';
	}
	elseif(is_dir(dirname(__FILE__).'/font'))
		$this->fontpath = dirname(__FILE__).'/font/';
	else
		$this->fontpath = '';
	// Core fonts
	$this->CoreFonts = array('courier', 'helvetica', 'times', 'symbol', 'zapfdingbats');
	// Scale factor
	if($unit=='pt')
		$this->k = 1;
	elseif($unit=='mm')
		$this->k = 72/25.4;
	elseif($unit=='cm')
		$this->k = 72/2.54;
	elseif($unit=='in')
		$this->k = 72;
	else
		$this->Error('Incorrect unit: '.$unit);
	// Page sizes
	$this->StdPageSizes = array('a3'=>array(841.89,1190.55), 'a4'=>array(595.28,841.89), 'a5'=>array(420.94,595.28),
		'letter'=>array(612,792), 'legal'=>array(612,1008));
	$size = $this->_getpagesize($size);
	$this->DefPageSize = $size;
	$this->CurPageSize = $size;
	// Page orientation
	$orientation = strtolower($orientation);
	if($orientation=='p' || $orientation=='portrait')
	{
		$this->DefOrientation = 'P';
		$this->w = $size[0];
		$this->h = $size[1];
	}
	elseif($orientation=='l' || $orientation=='landscape')
	{
		$this->DefOrientation = 'L';
		$this->w = $size[1];
		$this->h = $size[0];
	}
	else
		$this->Error('Incorrect orientation: '.$orientation);
	$this->CurOrientation = $this->DefOrientation;
	$this->wPt = $this->w*$this->k;
	$this->hPt = $this->h*$this->k;
	// Page margins (1 cm)
	$margin = 28.35/$this->k;
	$this->SetMargins($margin,$margin);
	// Interior cell margin (1 mm)
	$this->cMargin = $margin/10;
	// Line width (0.2 mm)
	$this->LineWidth = .567/$this->k;
	// Automatic page break
//	$this->SetAutoPageBreak(true,2*$margin);  // původní kód
	$this->SetAutoPageBreak(true);
        // Default display mode
	$this->SetDisplayMode('default');
	// Enable compression
	$this->SetCompression(true);
	// Set default PDF version number
	$this->PDFVersion = '1.3';
    }

    /**
     * Metoda vrací předpokládaný počet řádek textu, počat řádek určí jako číslo o jednu větší než počet výskytů znaků, které při tisku mají za následek odřádkování
     * (samotný znak CR nebo dvojice znaků CR+LF).
     * @param type $txt
     * @return int
     */
    public function LineCount($txt) {
//       http://www.pcre.org/pcre.txt
//       WHAT \R MATCHES
//       By default, the sequence \R in a pattern matches  any  Unicode  newline
//       sequence,  whatever  has  been selected as the line ending sequence.
        $breaks = preg_match('/\R/', $txt);
        if (strlen($txt)) {
            return $breaks+1;
        } else {
            return 0;
        }
    }

    /**
     * Metoda nastaví svislou pozici na stránce, kde dojde k automatickému odstránkování
     * @param type $yTriggerPosition
     */
    public function SetPageBreakTrigger($yTriggerPosition=NULL) {
        if ($yTriggerPosition!==NULL) {
            $this->PageBreakTrigger = $yTriggerPosition;
        }
    }

    /**
     * Metoda přetěžuje metodu SetMargins třídy FPDF, metoda nastavuje šířku okrajů stránky v uživatelských jednotkách
     * @param type $left
     * @param type $top
     * @param type $right
     * @param type $bottom
     */
    public function SetMargins($left, $top, $right=NULL, $bottom=NULL) {
    // Set left, top and right margins
//        $this->lMargin = $left;
//        $this->tMargin = $top;
//        if($right===null)
//            $right = $left;
//        $this->rMargin = $right;

        //Nastavuji left, top, right i bottom margins
        $this->SetLeftMargin($left);
        $this->SetTopMargin($top);
        if ($right===NULL) {
            $this->SetRightMargin($left);
        } else {
            $this->SetRightMargin($right);
        }
        if ($bottom===NULL) {
            $this->SetBottomMargin($top);
        } else {
            $this->SetBottomMargin($bottom);
        }    }

    /**
     * Metoda přetěžuje metodu SetLeftMargin třídy FPDF, metoda nastavuje šířku levého okraje stránky v uživatelských jednotkách
     * @param type $margin
     */
    public function SetLeftMargin($margin)
    {
    // Set left margin
        $this->lMargin = $margin;
        if($this->page>0 && $this->x<$margin) $this->x = $margin; //var $page;               // current page number
    }

    /**
     * Metoda přetěžuje metodu SetTopMargin třídy FPDF
     * @param type $margin
     */
    public function SetTopMargin($margin)
    {
    // Set top margin
        $this->tMargin = $margin;
    }

    /**
     * Metoda přetěžuje metodu SetRightMargin třídy FPDF, metoda nastavuje šířku pravého okraje stránky v uživatelských jednotkách
     * @param type $margin
     */
    public function SetRightMargin($margin)
    {
    // Set right margin
        $this->rMargin = $margin;
    }

    /**
     * Metoda nastavuje šířku dolního okraje stránky v uživatelských jednotkách
     * @param type $margin
     */
    public function SetBottomMargin($margin)
    {
    // Set bottom margin
        $this->bMargin = $margin;
    }
    /**
     * Metoda přetěžuje metodu SetAutoPageBreak třídy FPDF, zapíná/vypíná automatické stránkování
     * @param type $auto
     * @param type $margin
     */
    public function SetAutoPageBreak($auto, $margin=0) {
    // Set auto page break mode and triggering margin
        $this->AutoPageBreak = $auto;
//        $this->bMargin = $margin;  //původní kód
//        $this->PageBreakTrigger = $this->h-$margin;   //původní kód
        $this->PageBreakTrigger = $this->h;
    }

    /** Funkce přetěžuje metodu Cell rodičovské třídy FPDP
     * http://www.fpdf.org/ Extended Cell functions
     * @author: Pivkin Vladimir, úprava Petr Svoboda
     * @param char $width | šířka buňky, pokud je 0, šířka buňky je nastavena od aktuální vodorovné pozice až k pravému okraji stránky
     * @param char $height Výška buňky, Default: 0. Pokud se buňka s takto zadanou výškou nevejde na stránku, metoda provede automatické zalomení
     *                     stránky. Zalamování se provádí pouze pokud $this->AcceptPageBreak() vrací TRUE.
     * @param string $txt Text k vytištění, default prázdný řetězec. Metoda podoruje víceřádkový text v buňce, vytvoří nový řádek
     *                    v místě výskytu "\n" nebo dvojice znaků CR a LF (chr(13) a chr(10)). Pokud text je jednořádkový a jeho
     *                    délka přesahuje šířku buňky je komprimován na šířku a přizpůsoben šířce buňky.
     * @param char $border Ohraničení buňky, 0: bez ohraničení, >0: s ohraničením. Parametr je řetězec složený z libovolné posloupnosti
     *                    znaků L: left, T: top, R: right, B: bottom pro tenké ohraničení nebo řetězec složený z libovolné posloupnosti
     *                    znaků l: left, t: top, r: right, b: bottom pro tlusté ohraničení. Default: 0.
     * @param integer $ln Určuje pozici po volání metody Cell, 0: napravo od buňky, 1: na počátku nového rádku, 2: dole.
     * @param char $align Zarovnání textu, L nebo prázdný řetězec: doleva (default), C: centrovaně, R: doprava.
     * @param boolean $fill Výplň, true: buňka bude vyplněna, false: buňka bude bez výplně, default: false.
     * @param string $link URL nebo indentifikátor vracený metodou AddLink()
     * @param float $lineSpacing Řádkování (vzdálenosti účaří v násobcích velikosti písma)
     * @param float $hangingIndent Předsazení prního řádku zalomeného víceřádkového textu (odstavce). Předsazení je funkční pouze pro zarovnání vlevo $align='L'
     *  (non-PHPdoc)
     * @see __fpdf16/FPDF#Cell($w, $h, $txt, $border, $ln, $align, $fill, $link)
     */
    function Cell($width, $height=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $lineSpacing=1.5, $hangingIndent=0)
    {
        //Output a cell
        $k=$this->k;
        if($this->y+$height>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
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
        if($width==0)
            $width=$this->w-$this->rMargin-$this->x;
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
                        $this->x*$k,($this->h-$this->y)*$k,$width*$k,-$height*$k,$op);
            }
            else
                $s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$width*$k,-$height*$k,$op);
        }
        if(is_string($border))
        {
            $x=$this->x;
            $y=$this->y;
            if(is_int(strpos($border,'L')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$height))*$k);
            else if(is_int(strpos($border,'l')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$height))*$k);

            if(is_int(strpos($border,'T')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$width)*$k,($this->h-$y)*$k);
            else if(is_int(strpos($border,'t')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-$y)*$k,($x+$width)*$k,($this->h-$y)*$k);

            if(is_int(strpos($border,'R')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$width)*$k,($this->h-$y)*$k,($x+$width)*$k,($this->h-($y+$height))*$k);
            else if(is_int(strpos($border,'r')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',($x+$width)*$k,($this->h-$y)*$k,($x+$width)*$k,($this->h-($y+$height))*$k);

            if(is_int(strpos($border,'B')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$height))*$k,($x+$width)*$k,($this->h-($y+$height))*$k);
            else if(is_int(strpos($border,'b')))
                $s.=sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ',$x*$k,($this->h-($y+$height))*$k,($x+$width)*$k,($this->h-($y+$height))*$k);
        }
        if (trim($txt)!='')
        {
//          $cr=substr_count($txt,"\n");       //původní - řádkuje na /n
//       http://www.pcre.org/pcre.txt
//       WHAT \R MATCHES
//       By default, the sequence \R in a pattern matches  any  Unicode  newline
//       sequence,  whatever  has  been selected as the line ending sequence.
            $txts = $txt ? preg_split('/\R/', $txt) : '';      //Svoboda (varianta: '/\n|\r\n?/')
            $lines = count($txts);
//            if ($cr>0)       //původní
            if ($lines>1)
            { // Multi line
//              $txts = explode("\n", $txt);       //původní

                for($l=0;$l<$lines;$l++)
                {
                    $txt=$txts[$l];
                    $w_txt=$this->GetStringWidth($txt);
                    if($align=='R') {
                        $dx=$width-$w_txt-$this->cMargin;
                    } elseif($align=='C') {
                            $dx=($width-$w_txt)/2;
                        } else {
                            $dx=$this->cMargin;
                            if ($l>0) $dx = $dx + $hangingIndent;
                        }

                    $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
                    if($this->ColorFlag) $s.='q '.$this->TextColor.' ';
                    $s.=sprintf('BT %.2F %.2F Td (%s) Tj ET ',
                            ($this->x+$dx)*$k,
//                          ($this->h-($this->y+.5*$height+(.7+$l-$lines/2)*$this->FontSize))*$k,             // původní kd bez řádkování
                            ($this->h-($this->y+.5*$height+(.7+$l-$lines/2)*$this->FontSize*$lineSpacing))*$k,       // Svoboda
                            $txt);
                    if($this->underline) $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$height+.3*$this->FontSize,$txt);
                    if($this->ColorFlag) $s.=' Q ';
                    if($link) $this->Link($this->x+$dx,$this->y+.5*$height-.5*$this->FontSize,$w_txt,$this->FontSize,$link);
                }
            }
            else { // Single line
                $w_txt=$this->GetStringWidth($txt);
                $Tz=100;
                if ($w_txt>$width-2*$this->cMargin) { // Need compression
                    $Tz=($width-2*$this->cMargin)/$w_txt*100;
                    $w_txt=$width-2*$this->cMargin;
                }
                if($align=='R')
                    $dx=$width-$w_txt-$this->cMargin;
                elseif($align=='C')
                    $dx=($width-$w_txt)/2;
                else
                    $dx=$this->cMargin;
                $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
                if($this->ColorFlag)
                    $s.='q '.$this->TextColor.' ';
                $s.=sprintf('q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ',
                            ($this->x+$dx)*$k,
                            ($this->h-($this->y+.5*$height+.3*$this->FontSize))*$k,
                            $Tz,$txt);
                if($this->underline)
                    $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$height+.3*$this->FontSize,$txt);
                if($this->ColorFlag)
                    $s.=' Q ';
                if($link)
                    $this->Link($this->x+$dx,$this->y+.5*$height-.5*$this->FontSize,$w_txt,$this->FontSize,$link);
            }
        }
//  end change Cell function
        if($s)
            $this->_out($s);
        $this->lasth=$height;
        if($ln>0)
        {
        //  Go to next line
            $this->y+=$height;
            if($ln==1)
                $this->x=$this->lMargin;
        }
        else
            $this->x+=$width;
    }

}