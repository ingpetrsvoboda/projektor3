<?php
//---Definice---
$domena = 'personalservice.cz'; //Definice domeny
$adresar = 'backup'; //Definice adresare, ve kterem jsou spoustene soubory
//--------------

//$cesta = $domena.'/rs/data.inc.php';
$cesta = '../rs/data.inc.php';
include $cesta;

$date = getdate();
//$name = $domena."/".$adresar."/db-personalservicecz-".$date['mday']."-".$date['mon']."-".$date['year'].".txt";
$filename = "db-personalservicecz-".$date['mday']."-".$date['mon']."-".$date['year'].".txt";
$date = $date['mday'].".".$date['mon']."."." ".$date['year']." ".$date['hours'].":".$date['minutes'];


//  Cast pripojeni na DB a zjisteni tabulek
//------------------------------------------------------------------------------

 $JmenoDB = $mysqldb;               
   //mysql_select_db($JmenoDB) or
   //    die("Nelze vybrat databázi " .$JmenoDB . " " . mysql_error()) ;

// $JmenoDB = "opendoor";    
// mysql_pconnect("localhost","root","spravce") 
//             or die("Nelze se pøipojit k DB serveru: " . mysql_error());        
// mysql_select_db($JmenoDB) or
//       die("Nelze vybrat databázi " .$JmenoDB . " " . mysql_error()) ;
// $name = "FO";        
  
  
  $query = "SET NAMES 'utf8'";
  $result = mysql_query($query) or die ("Neprovedlo se SET NAME."  . mysql_error() );
  
  //$queryT = "show tables from ".$JmenoDB;  //dotaz na tabulky
  $queryT = "show tables";  //dotaz na tabulky
  $resultT = mysql_query($queryT) ;


//  Cast - zaznamenani novych souboru do zaloha-soubory
//------------------------------------------------------------------------------
  
  $i=0;
  while ($dataT =mysql_fetch_array($resultT)) {    // vsechny tabulky - v tabulky[] 
    $tabulky[$i] = $dataT[0];
    $i++;
  }
  
     //print_r ($tabulky);
  if (in_array ("soubory", $tabulky) ) {              // tab.soubory existuje
    if (in_array ("zaloha-soubory", $tabulky) ) {     // tab.zaloha-soubory existuje
      //echo "<br>tabulka zaloha-soubory existuje<br>";
      $queryJ = "select `soubory`.`IDfile`, `soubory`.`pripona` 
              from `soubory` left join `zaloha-soubory`
              using (`IDfile`,`pripona`)
              where (`zaloha-soubory`.`IDfile` is null)";
              //on `soubory`.`IDfile` = `zaloha-soubory`.`IDfile`
    }
    else {
      //echo "<br>tabulka zaloha-soubory neexistuje<br>";
      $queryJ="select `soubory`.`IDfile`, `soubory`.`pripona` from `soubory`" ;
    }
    
    $resultJ = mysql_query($queryJ) ;
    $i=0;
    while ($souborrow =  mysql_fetch_array($resultJ)) {    //nove soubory - v $novesoubory[$i][]
       $novesoubory[$i][1] = $souborrow['IDfile'];
       $novesoubory[$i][2] = $souborrow['pripona'];
       $i++;
    }
   
    if (isset($novesoubory)) { 
      // echo "Nove soubory jsou : "; print_r($novesoubory);
      
       $queryZ="CREATE TABLE IF NOT EXISTS `zaloha-soubory` (
            `IDfile` varchar(40) collate cp1250_czech_cs NOT NULL,
            `pripona` varchar(4) collate cp1250_czech_cs NOT NULL,
            UNIQUE KEY `IDfile` (`IDfile`)
            ) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;" ;
       $resultZ = mysql_query($queryZ) 
                or die("Zalozeni tabulky `zaloha-soubory` nelze provest: " . mysql_error());
    
       foreach ($novesoubory as $hodn) {
         $queryI = "INSERT INTO `zaloha-soubory` VALUES ('". $hodn[1] . "','". $hodn[2]. "')" ;
              //echo $queryI;
         $resultI = mysql_query($queryI) or 
            die("Insert do tabulky nelze provést: " . mysql_errno() . " : " . mysql_error());
       }
                                    
    }
  }
   



// Cast - export struktury a dat z DB
//-------------------------------------------------------------
  $obsahDB="";
  foreach ($tabulky as $tab) {
  //while ($dataT =  mysql_fetch_array($resultT))  {  // tabulka 
    //echo "<br><br>-- TABULKA " . $tab ;          //
    $queryC = "show create table `". $tab. "`";
    $resultC = mysql_query($queryC);
    $CreateTableString =  mysql_fetch_array($resultC); 
    //echo "<br>" ; var_dump($CreateTableString);       //
    $obsahDB .=  $CreateTableString [1]  . ";" ."\r\n" ;
    
    $queryS = "select * from `" . $tab. "`";
    $resultS = mysql_query($queryS) ;
    //echo "<br>" ; 
    while ($radek = mysql_fetch_row($resultS) )
    { 
     $radecek= "";
     $radecek .= 
     "INSERT INTO `" . $tab . "` VALUES (" ;
     foreach ($radek as $hodn) {
          $radecek .=  "'". $hodn . "',"  ;
     }
     $radecek = substr($radecek,0,-1);
     $radecek .=  ");" ;
     
     //echo $radecek . "<br>" ;                   // 
     $obsahDB .=  $radecek  . "\r\n";
    }
    
  }

      echo $obsahDB;

 MySQL_CLOSE ($connect);
?>
