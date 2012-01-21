

function Zobraz(uc_identif,akce,filepathprefix) {
  //alert ('zobraz ...' + uc_identif + ' ' + akce);
  
  //var nn= window.open("","souborpdf") ;
  //nn.document.write("<H1>NADPIS</H1>"); 
  //nn.document.write('<p><a href="doc.pdf">ukaz mi soubor</a></p>'); 
  //**var nn=
  
  if (akce==('reg_dot')) {
   //jmenosouboru= '.\/doku\/' + 'dotaznik' + uc_identif + '.pdf';
   jmenosouboru= filepathprefix   + uc_identif + '.pdf';
   //alert ('jmenosouboru ' +  jmenosouboru);
   var nn=window.open(jmenosouboru,"dotaznikpdf");    //soubor z file systemu , jmeno okna
  }
  
  if (akce==('sml_uc')) {
   //jmenosouboru= '.\/doku\/' + 'smlouva' + uc_identif+ '.pdf';
   jmenosouboru= filepathprefix   + uc_identif + '.pdf';
   //alert ('jmenosouboru ' +  jmenosouboru);
   var nn=window.open(jmenosouboru,"smlouvapdf");
  }
  
  if (akce==('plan')) {
   //jmenosouboru= '.\/doku\/' + 'plan' + uc_identif+ '.pdf';
   jmenosouboru= filepathprefix + uc_identif+ '.pdf';
   //alert ('jmenosouboru: ' +  jmenosouboru);
   var nn=window.open(jmenosouboru,"planpdf");
  }
  
   if (akce==('ukonc')) {
   //jmenosouboru= '.\/doku\/' + 'ukonceni' + uc_identif+ '.pdf';
   jmenosouboru= filepathprefix  + uc_identif+ '.pdf';
   //alert ('jmenosouboru ' +  jmenosouboru);
   var nn=window.open(jmenosouboru,"ukoncenipdf");
  }
  
}  
 