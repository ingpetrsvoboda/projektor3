<?php

$xml = <<<XML
<root>
 <export>
      <pdf>
          <directory>C:/_Export Projektor/PDF/</directory>
      </pdf>
 </export>
<db databaze='Projektor' blaf='blaf'>
    <databaze>Projektor</databaze>
    <user>root</user>
    <pass>spravce</pass>
    <dbhost>localhost</dbhost>
    <dbname>projektor_3_00_centrala_vyvoj</dbname>
    <dbtype>MySQL</dbtype>
</db>
<db databaze='InformationSchema'>
    <databaze>InformationSchema</databaze>
    <user>root</user>
    <pass>spravce</pass>
    <dbhost>localhost</dbhost>
    <dbname>information_schema</dbname>
    <dbtype>MySQL</dbtype>
</db>
<db databaze='PersonalService'>
    <databaze>PersonalService</databaze>
    <user>root</user>
    <pass>spravce</pass>
    <dbhost>localhost</dbhost>
    <dbname>personalservice_web</dbname>
    <dbtype>MySQL</dbtype>
</db>
<db databaze='test_projektor'>
    <databaze>test_projektor</databaze>
    <user>root</user>
    <pass>spravce</pass>
    <dbhost>NB-SVOBODA\SQLEXPRESS2008</dbhost>
    <dbname>test_projektor</dbname>
    <dbtype>MSSQL</dbtype>
</db>     
</root>
XML;

$xmlE = new SimpleXMLElement($xml);

$sekceNazev = 'export';
$se = $xmlE->$sekceNazev;

$sekceNazev = 'db';
$se = $xmlE->$sekceNazev;
$atributNazev = 'databaze';
$atributHodnota = 'PersonalService';
//for( $xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next() ) {
if (property_exists($xmlE, $sekceNazev)){

    foreach ($xmlE->$sekceNazev as $jmeno => $dataSekce) {
        foreach($dataSekce->attributes() as $jmenoAtributu => $hodnotaAtributu) {
            $h = (string)$hodnotaAtributu;
            if ($jmenoAtributu==$atributNazev AND $h==$atributHodnota) $sekce = $dataSekce;
        }
    }

}
?>
