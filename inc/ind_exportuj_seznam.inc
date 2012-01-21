<?php
// Docasne udelatko pro export pro Excel (Calc).
// Exportuje seznam úřastníků běhu ve formátu tabulky HTML a uloží ho do souboru
// s příponou ".xls" . To proto, že i neznalý uřivatel na takový soubor klikne 
// a ten se mu správně otevře v Excelu nebo Calcu. Jen se musí dát pozor při ukládání.
//

$excel_file = "D:\Data\Seznam zarazenych do projektu.xls";
//*
//echo "!!! Zapisuji na disk C: ";
//$excel_file = "C:\Data\Seznam zarazenych do projektu.xls"; 
//*/

//$excel_file = $cesta_spzpdata ."\Seznam zarazenych do projektu.xls";
@$fp=FOpen("$excel_file","w") ;


if ($fp) {

  setlocale(LC_ALL,"cs_CZ.UTF-8") ;

// HTML generované OOo - 1.část začátek
  FPutS($fp,iconv("UTF-8","Windows-1250",  "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Transitional//EN\">
  <HTML>
  <HEAD>
	<META HTTP-EQUIV=\"CONTENT-TYPE\" CONTENT=\"text/html; charset=windows-1250\">
  	<TITLE></TITLE>
  	<META NAME=\"GENERATOR\" CONTENT=\"OpenOffice.org 3.1  (Win32)\">
  	<META NAME=\"AUTHOR\" CONTENT=\"projektor\">
  	<STYLE>
		  <!-- 
		  BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-family:\"Arial\"; font-size:x-small }
		  -->
 </STYLE>
 </HEAD>
 <BODY TEXT=\"#000000\">
 <TABLE FRAME=VOID CELLSPACING=0 COLS=5 RULES=NONE BORDER=0>
	<COLGROUP><COL WIDTH=29><COL WIDTH=150><COL WIDTH=188><COL WIDTH=155><COL WIDTH=128></COLGROUP>
	<TBODY>
		<TR>
			<TD COLSPAN=4 WIDTH=522 HEIGHT=24 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4>S pomocí za prací CZ.1.04/2.1.00/13.00011</FONT></B></TD>
			<TD WIDTH=128 ALIGN=RIGHT VALIGN=MIDDLE><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 HEIGHT=27 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4>                SEZNAM   ZAŘAZENÝCH </FONT></B></TD>
			<TD COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4><BR></FONT></B></TD>
			</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=1><BR></FONT></B></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 HEIGHT=20 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=3>okresní kancelář &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..</FONT></B></TD>
			<TD COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=3>list č. &hellip;. z&hellip;.</FONT></B></TD>
			</TR>
		<TR>
			<TD HEIGHT=16 ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4><BR></FONT></B></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><B><FONT SIZE=4><BR></FONT></B></TD>
		</TR>

   "));

// HTML g enerované OOo - 1.část konec

  FPutS($fp,iconv("UTF-8","Windows-1250",  "  
  <TR>
			<TD STYLE=\"border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 1px solid #000000\" HEIGHT=34 ALIGN=CENTER VALIGN=MIDDLE><B><BR></B></TD>
			<TD STYLE=\"border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000\" ALIGN=CENTER VALIGN=MIDDLE><B>Identifikační číslo účastníka</B></TD>
			<TD STYLE=\"border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000\" ALIGN=CENTER VALIGN=MIDDLE><B>Příjmení a jméno účastníka</B></TD>
			<TD STYLE=\"border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000\" ALIGN=CENTER VALIGN=MIDDLE><B>Datum narození</B></TD>
			<TD STYLE=\"border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 1px solid #000000; border-right: 3px solid #000000\" ALIGN=CENTER VALIGN=MIDDLE><B>Datum podpisu dohody</B></TD>
		</TR>
  "));   
  $query = "SELECT ucastnik.identifikator AS identifikator,
              concat_ws(_utf8' ',uc_flat_table.prijmeni, ' ', uc_flat_table.jmeno,' ', uc_flat_table.titul) AS cele_jmeno,
              uc_flat_table.datum_narozeni AS datum_nar, uc_flat_table.datum_reg AS datum_reg
              FROM ucastnik, uc_flat_table 
              WHERE ucastnik.id_ucastnik=uc_flat_table.id_ucastnik AND ucastnik.id_s_beh_projektu_FK = :1
                    AND ucastnik.id_c_kancelar_FK= :2";
                    
 //echo "behid" . ($beh_id)  ;                  
  $data = $dbh->prepare($query)->execute($beh_id,$Kancelar->id);
  $row_cnt = 0;
  while ($zaznam = $data->fetch_assoc()) {
    $row_cnt = $row_cnt + 1;
    FPutS($fp,iconv("UTF-8","Windows-1250", "
     <TR>
			<TD STYLE=\"border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 1px solid #000000\" HEIGHT=33 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=3>".$row_cnt."</FONT></TD>
			<TD STYLE=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000\" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=3>".$zaznam['identifikator']."</FONT></TD>
			<TD STYLE=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000\" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=3>".$zaznam['cele_jmeno']."</FONT></TD>
			<TD STYLE=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000\" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=3>".$zaznam['datum_nar']."</FONT></TD>
			<TD STYLE=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 3px solid #000000\" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=3>".$zaznam['datum_reg']."</FONT></TD>
		</TR>
    
    "));
  }
  
  
  
// HTML generované OOo - 2.část začátek
  FPutS($fp,iconv("UTF-8","Windows-1250",   "
  		<TR>
			<TD COLSPAN=2 HEIGHT=37 ALIGN=LEFT VALIGN=MIDDLE>Datum:</TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><BR></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE>Vyhotovil:</TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><BR></TD>
		</TR>
	</TBODY>
</TABLE>
<!-- ************************************************************************** -->
  </BODY>
  </HTML>  "));

// HTML generované OOo - 2.část konec

  Fclose($fp);   // Zavřeme soubor

} else {
echo ("<hr>Do souboru ".$excel_file." pro export seznamu nelze zapsat. <br>") ;
echo ("Pravděpodobně složka neexistuje nebo je soubor otevřen v nějakém programu - používán. Export seznamu neproběhl.<hr>") ;
}

?>
