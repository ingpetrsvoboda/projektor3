<?php
//PŘEDMĚT:
//Předmět e-mailové zprávy se přenáší hlavičkami, jejichž kódování je omezeno na US-ASCII (tedy sedmibitové kódování, které neobsahuje žádné znaky s diakritikou). Pokud chceme v předmětu přenést diakritiku, využijeme rozšíření MIME, které definuje, jakým způsobem je možno v hlavičkách používat obsáhlejší znakové sady.
//MIME určuje dva možné způsoby zápisu diakritiky do hlaviček. První je takzvaný “quoted-printable”, druhým pak “base64″. Obecný formát pro zápis je:
//=?znaková sada?X?kódovaný text?=
//kde “znaková sada” určuje znakovou sadu původního textu (utf-8, windows-1250 atd.), X označuje, která ze dvou uvedených metod kódování byla použita (Q = quoted-printable, B = base64), a poslední částí je samotný text, zakódovaný uvedenou metodou.
//Quoted-printable
//Toto kódování přiřazuje každému bajtu vstupního textu tři bajty výstupu. Prvním znakem je rovnítko (=), následuje hexadecimální reprezentace ASCII kódu ukládaného znaku. Písmeno A se tedy zakóduje jako =41, písmeno Z jako =5A, písmeno á jako =E1. Aby nebyl kódovaný text zbytečně nafouknutý, stačí takto kódovat pouze znaky mimo rozsah tisknutelných znaků US-ASCII (tedy znaky s ordinálními hodnotami od 33 do 126), pochopitelně s výjimkou rovnítka, které se kódovat musí.
//Vícebajtové znaky vícebajtových kódování (např. UTF-8) se zapisují po jednotlivých bajtech – český znak s diakritikou v UTF-8 tedy vyprodukuje =XY=XZ (tedy 6 znaků).
//V PHP se dá quoted-printable vyrobit pomocí funkce imap_8bit(), celý předmět pak například pomocí
//$predmet = "=?utf-8?Q?".imap_8bit($predmet)."?=";
//kde $predmet je řetězec v UTF-8.
//Base64
//Toto kódování převádí posloupnost tří osmibitových znaků na čtyři znaky s šestibitovým kódováním. Výsledný text tedy není jednoduše čitelný (na rozdíl od quoted-printable, které se většinou dá dekódovat pouhým pohledem). V PHP nám k tomuto účelu poslouží funkce base64_encode(). Celý kód pro řetězec v UTF-8:
//$predmet = "=?utf-8?B?".base64_encode($predmet)."?=";



//Text musíme posílat s uvedeným kódováním. Dříve se doporučovalo ISO-889-2, 
//dnes bych neměl strach z UTF-8 a vzhledem k předpokládanému vývoji ho doporučuji.
//Kódování předmětu (a dalších hlaviček) a zprávy samotné se zadávají samostatně.
//Předmět zakódovat musíme, zakódováním těla zprávy nic nezkazíme.
//Hlavičky by mohly vypadat nějak takhle:
//$head = "MIME-Version: 1.0".PHP_EOL;
//Zde je zadané kódování zprávy. Když budeme posílat html mail, zde změníme na text/html.
//$head .= "Content-Type: text/plain; charset=\"utf-8\"".PHP_EOL;
//Tuto hlavičku uvedeme, pokud kódujeme i tělo zprávy.
//$head .= "Content-Transfer-Encoding: base64".PHP_EOL;
//To by ze základních hlaviček mohlo být vše. Teď kódování předmětu:
//$predmet = "=?utf-8?B?".base64_encode(autoUTF($predmet))."?=";
//Všimněte si, že pracujeme s utf-8 a používáme funkci autoUTF(), viz následující.
//Pokud budeme chtít použít jméno, poskládáme hlavičku takto:
//"From: =?UTF-8?B?".base64_encode(autoUTF("Moje Jméno"))."?=<ja@email.cz>".PHP_EOL;
//Už zbývá jen zakódování zprávy samotné, opět použijeme base64_encode()
//$zprava = base64_encode(autoUTF($zprava));
//Abychom bez námahy získali text v utf, použijeme k tomu funkci od Davida Grudla. 
//Dokážete-li získat data ve správném kódování (např. máte vše v utf-8), lze tento krok vynechat.
class SVMail {
    
    public function cs_mail ($prijemce, $predmet, $zprava, $head = ""){  
        $predmet = "=?utf-8?B?".base64_encode($this->autoUTF ($predmet))."?=";
        $head .= "From:email@server.com\n";
        $head .= "MIME-Version: 1.0\n";
        $head .= "Content-Type: text/plain; charset=\"utf-8\"\n";
        $head .= "Content-Transfer-Encoding: base64\n";
        $zprava = base64_encode ($this->autoUTF ($zprava));
        return mail ($prijemce, $predmet, $zprava, $head); 
    }
    
    private function UTF8Mail( $to, $subject, $message, $from, $fromName, $html = false, $attach = null )
    { 
	/*
		$Attach = array
		(
			array( 'name' => 'file name', 'content' => 'file content', 'mime_type' => 'mime/type' )
			...
		);
	*/
        // uniqid(rand(), true)
	$boundary = strtoupper( "boundaty".md5( uniqid(time()) ) ); 
	$header = "From: $from\n"; 
	$header .= "MIME-version: 1.0\n"; 
	$header .= "Return-Path: <$from>\n"; 
	$header .= "Reply-To: $from\n"; 
	$header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n\n";
	$header .= "--" . $boundary . "\n"; 
	$header .= "Content-Type: text/" . ( ( $html ) ? ( 'html' ) : ( 'plain' ) ) . "; charset=\"UTF-8\"\n";
	$header .= "Content-Transfer-Encoding: base64\n\n"; 
	$header .=  base64_encode( $message ) . "\n\n";

        if ( is_array( $attach ) ) {
            reset( $attach );
            for ( $i = 0; $i < count( $attach ); ++ $i ) {	
                if ( $attach[ key( $attach ) ][ 'content' ] && $attach[ key( $attach ) ][ 'name' ] ) {
                    $header .= "--" . $boundary . "\n"; 
                    $header .= "Content-Type: " . ( ( $attach[ key( $attach ) ][ 'mime_type' ] ) ? ( $attach[ key( $attach ) ][ 'mime_type' ] ) : ( 'application/octet-stream' ) ) . ";\n";
                    $header .= "Content-Transfer-Encoding: base64\n"; 
                    $header .= "Content-Disposition: attachment; filename=\"" . $attach[ key( $attach ) ][ 'name' ] . "\"\n\n";
                    $header .=  chunk_split( base64_encode( $attach[ key( $attach ) ][ 'content' ] ) )  . "\n\n";
                } else {
                    return false;
                }
            next( $attach );
            }
        }
	$header .= "--" . $boundary . "--"; 
	return mail( $to, '=?UTF-8?B?' . base64_encode( $subject ) . '?=', "", $header ); 
    }

    private function autoUTF($s){
      if (preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $s))
            return $s;
      if (preg_match('#[\x7F-\x9F\xBC]#', $s))
            return iconv('WINDOWS-1250', 'UTF-8', $s);
      return iconv('ISO-8859-2', 'UTF-8', $s);
    }
}
$mail = 'jmeno@email.cz';
$predmet = 'ěščřžýáíé46';
$zprava = "Test, \r\n ěščřžýáíé123456789";

$mailer = new SVMail();
if ($mailer->cs_mail($mail, $predmet, $zprava, "From: vas@web.cz".PHP_EOL))
{
    echo 'E-mail byl úspěšně odeslán.<br>';
}
else
{
    echo 'E-mail se bohužel nepodařilo odeslat.<br>';
}
