<?php
// 
//
// echo "projekt-id: " . $Projekt->id ;

$dbh = App_Kontext::getDbMySQLProjektor();

//******************* vyplnit sloupce id_s_typ_kurzu_rk_FK_1...3 podle nazvu typu kurzu a  id projektu
// v behu (tj. v behu projektu)
//   echo "<br>provedeno DOPLNENI uc_doporucenirk_flat_table SLOUPCE id_s_typ_kurzu_rk_FK_1...3 PODLE NAZVU typu kurzu<br>"; 

   $query="
       UPDATE uc_doporucenirk_flat_table INNER JOIN ucastnik ON  (uc_doporucenirk_flat_table.id_ucastnik=ucastnik.id_ucastnik)

       SET   uc_doporucenirk_flat_table.id_s_typ_kurzu_rk_FK_1 =  
	(SELECT  id_s_typ_kurzu_rk FROM   s_typ_kurzu_rk  WHERE      s_typ_kurzu_rk .nazev_kurzu= uc_doporucenirk_flat_table.nazev_RK_1 and id_c_projekt_FK =" . $Projekt->id ." ),
             uc_doporucenirk_flat_table.id_s_typ_kurzu_rk_FK_2 =  
	(SELECT  id_s_typ_kurzu_rk FROM   s_typ_kurzu_rk  WHERE      s_typ_kurzu_rk .nazev_kurzu= uc_doporucenirk_flat_table.nazev_RK_2 and id_c_projekt_FK =" . $Projekt->id ." ),
             uc_doporucenirk_flat_table.id_s_typ_kurzu_rk_FK_3 =  
	(SELECT  id_s_typ_kurzu_rk FROM   s_typ_kurzu_rk  WHERE      s_typ_kurzu_rk .nazev_kurzu= uc_doporucenirk_flat_table.nazev_RK_3 and id_c_projekt_FK =" . $Projekt->id ." )

       WHERE
        (
          (ucastnik.id_s_beh_projektu_FK=" .   $beh_id   .")  
        )  
    ";
             //(ucastnik.id_c_projekt_FK="  . $Projekt->id .   ")    
             //(ucastnik.id_c_kancelar_FK ="   . $Kancelar->id .   ")
       //echo $query . "<br>gg<br>";      
   $dbh->prepare($query)->execute();

//******************* vyplnit sloupce id_s_typ_kurzu_rk_FK_1...3 podle nazvu typu kurzu
//*******************  konec *********************



//if (1)  {   //cvicne
//}//if cvicne





//***********************************************************************************************
//***************** pro kazdou kancelar**********************************************************

$query0="
       SELECT  kod , id_c_kancelar FROM c_kancelar WHERE kod!='CE' and   kod!='TST'  
    ";
    //echo "<br>" . $query0 . "<br><br>";
$data0= $dbh->prepare($query0)->execute();
  
while($zaznam0 = $data0->fetch_assoc()) {                    // ****     pro kazdou kancelar zvlast ****
     
     if ( ($User->username == "sys_admin" ))  {
      echo " <br>**************************** zaznam0 - " . $zaznam0['kod'] ." ".  $zaznam0['id_c_kancelar']   ."************************** <br>";
     }
     
   $Id_kancelar0 = $zaznam0['id_c_kancelar'];



//******************* zjisteni  min a max u kurzu 1-3 , v  behu projektu v kancelari
 $query = "
          SELECT
  max( CAST( uc_doporucenirk_flat_table.ucastnik_pozaduje_1 as binary))   AS max_ucastnik_pozaduje1 ,
  min( CAST( uc_doporucenirk_flat_table.ucastnik_pozaduje_1 as binary))   AS min_ucastnik_pozaduje1 ,

  max( CAST( uc_doporucenirk_flat_table.ucastnik_pozaduje_2 as binary))   AS max_ucastnik_pozaduje2 ,
  min( CAST( uc_doporucenirk_flat_table.ucastnik_pozaduje_2 as binary))   AS min_ucastnik_pozaduje2 ,

  max( CAST( uc_doporucenirk_flat_table.ucastnik_pozaduje_3 as binary))   AS max_ucastnik_pozaduje3 ,
  min( CAST( uc_doporucenirk_flat_table.ucastnik_pozaduje_3 as binary))   AS min_ucastnik_pozaduje3 ,

  max( CAST( uc_doporucenirk_flat_table.poradce_doporucuje_1 as binary))   AS max_poradce_doporucuje1 ,
  min( CAST( uc_doporucenirk_flat_table.poradce_doporucuje_1 as binary))   AS min_poradce_doporucuje1 ,

  max( CAST( uc_doporucenirk_flat_table.poradce_doporucuje_2 as binary))   AS max_poradce_doporucuje2 ,
  min( CAST( uc_doporucenirk_flat_table.poradce_doporucuje_2 as binary))   AS min_poradce_doporucuje2 ,

  max( CAST( uc_doporucenirk_flat_table.poradce_doporucuje_3 as binary))   AS max_poradce_doporucuje3 ,
  min( CAST( uc_doporucenirk_flat_table.poradce_doporucuje_3 as binary))   AS min_poradce_doporucuje3 
          
  FROM  
  uc_doporucenirk_flat_table   JOIN  
        ucastnik    ON  (uc_doporucenirk_flat_table.id_ucastnik = ucastnik.id_ucastnik)   JOIN 
         c_kancelar ON (ucastnik.id_c_kancelar_FK =  c_kancelar.id_c_kancelar)
  WHERE
    ( 
      (ucastnik.id_s_beh_projektu_FK = ".  $beh_id   .")   and
      (ucastnik.id_c_kancelar_FK ="   . $Id_kancelar0 .   ")
   )
   
  ";
    // echo $query;     
              
 $data = $dbh->prepare($query)->execute()->fetch_assoc();
  
  unset($max_ucastnik_pozaduje1) ;   unset($min_ucastnik_pozaduje1) ;
  unset($max_ucastnik_pozaduje2) ;   unset($min_ucastnik_pozaduje2) ;
  unset($max_ucastnik_pozaduje3) ;   unset($min_ucastnik_pozaduje3) ;
  
  unset($max_poradce_doporucuje1) ;   unset($min_poradce_doporucuje1) ;
  unset($max_poradce_doporucuje2) ;   unset($min_poradce_doporucuje2) ;
  unset($max_poradce_doporucuje3) ;   unset($min_poradce_doporucuje3) ; 
  
       
 if ($data['max_ucastnik_pozaduje1']) {$max_ucastnik_pozaduje1=$data['max_ucastnik_pozaduje1'];}
      else {
        /*if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  max_ucastnik_pozaduje1!*" );}*/ }
 if ($data['max_ucastnik_pozaduje2']) {$max_ucastnik_pozaduje2=$data['max_ucastnik_pozaduje2'];}
       else {
        /*if ( ($User->username == "sys_admin" ))  {echo(" <hr>*Neexistuje  max_ucastnik_pozaduje2!*" );}*/ }
 if ($data['max_ucastnik_pozaduje3']) {$max_ucastnik_pozaduje3=$data['max_ucastnik_pozaduje3'];}
      else {
        /*if ( ($User->username == "sys_admin" ))  {echo(" <hr>*Neexistuje  max_ucastnik_pozaduje3!*" );}*/ }
 if ($data['min_ucastnik_pozaduje1']) {$min_ucastnik_pozaduje1=$data['min_ucastnik_pozaduje1'];}
       else {
        /*if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  min_ucastnik_pozaduje1!*" );}*/ }
 if ($data['min_ucastnik_pozaduje2']) {$min_ucastnik_pozaduje2=$data['min_ucastnik_pozaduje2'];}
      else {
        /*if ( ($User->username == "sys_admin" ))  {echo(" <hr>*Neexistuje  min_ucastnik_pozaduje2!*" );}*/ }
 if ($data['min_ucastnik_pozaduje3']) {$min_ucastnik_pozaduje3=$data['min_ucastnik_pozaduje3'];}
       else {
        /*if ( ($User->username == "sys_admin" ))  {echo(" <hr>*Neexistuje  min_ucastnik_pozaduje3!*" );}*/  }    
    
    if ( ($User->username == "sys_admin" ))  {
    echo   "<br>max_ucastnik_pozaduje1: " . $max_ucastnik_pozaduje1;
    echo   "<br>max_ucastnik_pozaduje2: " . $max_ucastnik_pozaduje2;
    echo   "<br>max_ucastnik_pozaduje3: " . $max_ucastnik_pozaduje3;
    echo   "<br>min_ucastnik_pozaduje1: " . $min_ucastnik_pozaduje1;
    echo   "<br>min_ucastnik_pozaduje2: " . $min_ucastnik_pozaduje2;
    echo   "<br>min_ucastnik_pozaduje3: " . $min_ucastnik_pozaduje3;
    }
    
 if ($data['max_poradce_doporucuje1']) {$max_poradce_doporucuje1=$data['max_poradce_doporucuje1'];}
     else {
      /*if ( ($User->username == "sys_admin" ))  {echo(" <hr>*Neexistuje  max_poradce_doporucuje1!*" ); }*/ }
 if ($data['max_poradce_doporucuje2']) {$max_poradce_doporucuje2=$data['max_poradce_doporucuje2'];}
       else {
        /*if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  max_poradce_doporucuje2!*" );}*/ }
 if ($data['max_poradce_doporucuje3']) {$max_poradce_doporucuje3=$data['max_poradce_doporucuje3'];}
      else {
        /*if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  max_poradce_doporucuje3!*" );}*/ }
 if ($data['min_poradce_doporucuje1']) {$min_poradce_doporucuje1=$data['min_poradce_doporucuje1'];}
       else {
        /*if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  min_poradce_doporucuje1!*" );}*/ }
 if ($data['min_poradce_doporucuje2']) {$min_poradce_doporucuje2=$data['min_poradce_doporucuje2'];}
      else {
        /*if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  min_poradce_doporucuje2!*" );}*/ }
 if ($data['min_poradce_doporucuje3']) {$min_poradce_doporucuje3=$data['min_poradce_doporucuje3'];}
       else {
       /* if ( ($User->username == "sys_admin" ))  {echo (" <hr>*Neexistuje  min_poradce_doporucuje3!*" );}*/ }
  
  if ( ($User->username == "sys_admin" ))  {     
    echo   "<br>max_poradce_doporucuje1 :" . $max_poradce_doporucuje1;
    echo   "<br>max_poradce_doporucuje2 :" . $max_poradce_doporucuje2;
    echo   "<br>max_poradce_doporucuje3 :" . $max_poradce_doporucuje3;
    echo   "<br>min_poradce_doporucuje1 :" . $min_poradce_doporucuje1;
    echo   "<br>min_poradce_doporucuje2 :" . $min_poradce_doporucuje2;
    echo   "<br>min_poradce_doporucuje3 :" . $min_poradce_doporucuje3;
  }  
    
  //if ($max_ucastnik_pozaduje1-$min_ucastnik_pozaduje1 ==0) {die (" <hr>Nelze delit nulou (ucastnik_pozaduje_1)!" );}
  //if ($max_ucastnik_pozaduje2-$min_ucastnik_pozaduje2 ==0) {die (" <hr>Nelze delit nulou (ucastnik_pozaduje_2)!" );}
  //if ($max_ucastnik_pozaduje3-$min_ucastnik_pozaduje3 ==0) {die (" <hr>Nelze delit nulou (ucastnik_pozaduje_3)!" );}
  
  //if ($max_poradce_doporucuje1-$min_poradce_doporucuje1 ==0) {die (" <hr>Nelze delit nulou (poradce doporucuje_1)!" );}
  //if ($max_poradce_doporucuje2-$min_poradce_doporucuje2 ==0) {die (" <hr>Nelze delit nulou (poradce doporucuje_2)!" );}
  //if ($max_poradce_doporucuje3-$min_poradce_doporucuje3 ==0) {die (" <hr>Nelze delit nulou (poradce doporucuje_3)!" );}
    
  $min_ucastnik_pozaduje1=  $min_ucastnik_pozaduje1 *2 / 3 + (1/3);
  $min_ucastnik_pozaduje2=  $min_ucastnik_pozaduje2 *2 / 3 + (1/3);
  $min_ucastnik_pozaduje3=  $min_ucastnik_pozaduje3 *2 / 3 + (1/3);
  
  $min_poradce_doporucuje1=  $min_poradce_doporucuje1 *2 / 3 + (1/3);
  $min_poradce_doporucuje2=  $min_poradce_doporucuje2 *2 / 3 + (1/3);
  $min_poradce_doporucuje3=  $min_poradce_doporucuje3 *2 / 3 + (1/3);
  
  $max_ucastnik_pozaduje1=  $max_ucastnik_pozaduje1 *2 / 3 + 3;
  $max_ucastnik_pozaduje2=  $max_ucastnik_pozaduje2 *2 / 3 + 3;
  $max_ucastnik_pozaduje3=  $max_ucastnik_pozaduje3 *2 / 3 + 3;
  
  $max_poradce_doporucuje1=  $max_poradce_doporucuje1 *2 / 3 + 3;
  $max_poradce_doporucuje2=  $max_poradce_doporucuje2 *2 / 3 + 3;
  $max_poradce_doporucuje3=  $max_poradce_doporucuje3 *2 / 3 + 3;

//******************* zjisteni  min a max u kurzu 1-3 , v  behu projektu v kancelari - konrc




//********************* vypocet  vah a generovani poradi doporuceni kurzu********************************
//******************* vypocet a zapis vahy u kurzu 1-3
//echo "<br>**<br>";  
$query= " UPDATE uc_doporucenirk_flat_table INNER JOIN ucastnik ON  (uc_doporucenirk_flat_table.id_ucastnik=ucastnik.id_ucastnik)
       SET   uc_doporucenirk_flat_table.vaha_1 =
         
        100 * uc_doporucenirk_flat_table.korekce_1 * SQRT( 
            (uc_doporucenirk_flat_table.ucastnik_pozaduje_1 - " . $min_ucastnik_pozaduje1 . ")" .
         " / (" .$max_ucastnik_pozaduje1 . "-" . $min_ucastnik_pozaduje1 . ") " .
         " * "  .
        " (uc_doporucenirk_flat_table.poradce_doporucuje_1 - " . $min_poradce_doporucuje1 . ")" .
        " / (" . $max_poradce_doporucuje1 .  "-" . $min_poradce_doporucuje1 . ") " .
        "), " .
        
        "uc_doporucenirk_flat_table.vaha_2 =
         
        100 * uc_doporucenirk_flat_table.korekce_2  *  SQRT( 
            (uc_doporucenirk_flat_table.ucastnik_pozaduje_2 - " . $min_ucastnik_pozaduje2 . ")" .
         " / (" .$max_ucastnik_pozaduje2 . "-" . $min_ucastnik_pozaduje2 . ") " .
         " * "  .
        " (uc_doporucenirk_flat_table.poradce_doporucuje_2 - " . $min_poradce_doporucuje2 . ")" .
        " / (" . $max_poradce_doporucuje2 .  "-" . $min_poradce_doporucuje2 . ") " .
        "), "  .
        
        "uc_doporucenirk_flat_table.vaha_3 =
         
        100 *  uc_doporucenirk_flat_table.korekce_3 * SQRT( 
            (uc_doporucenirk_flat_table.ucastnik_pozaduje_3 - " . $min_ucastnik_pozaduje3 . ")" .
         " / (" .$max_ucastnik_pozaduje3 . "-" . $min_ucastnik_pozaduje3 . ") " .
         " * "  .
        " (uc_doporucenirk_flat_table.poradce_doporucuje_3 - " . $min_poradce_doporucuje3 . ")" .
        " / (" . $max_poradce_doporucuje3 .  "-" . $min_poradce_doporucuje3 . ") " .
        ") "  .
        
        
    
       " WHERE
        (
          (ucastnik.id_s_beh_projektu_FK=" .   $beh_id   .")    and
          (ucastnik.id_c_kancelar_FK ="   . $Id_kancelar0 .   ")
        )  
    ";
          //(ucastnik.id_c_projekt_FK="  . $Projekt->id .   ")
        // echo "<br>" . $query . "<br><br>";      
  $dbh->prepare($query)->execute();

	 
//******************* vypocet a zapis vahy u kurzu 1-3  - konec



//*******************  zapis doporucen kurz 1- 3  pro kazdou vetu v doporucovaci tabulce uc_doporucenirk_flat_table
$query="
       SELECT nazev_RK_1, nazev_RK_2, nazev_RK_3,
         vaha_1,  vaha_2,  vaha_3,
         uc_doporucenirk_flat_table.id_s_typ_kurzu_rk_FK_1,
          uc_doporucenirk_flat_table.id_s_typ_kurzu_rk_FK_2,
           uc_doporucenirk_flat_table.id_s_typ_kurzu_rk_FK_3,
            uc_doporucenirk_flat_table.id_ucastnik
       FROM
         uc_doporucenirk_flat_table INNER JOIN ucastnik ON  (uc_doporucenirk_flat_table.id_ucastnik=ucastnik.id_ucastnik)
       
       WHERE
        (
          (ucastnik.id_s_beh_projektu_FK=" .   $beh_id   .")    and
          (ucastnik.id_c_kancelar_FK ="   . $Id_kancelar0 .   ")
        )
        
    ";
    //echo "<br>" . $query . "<br><br>";
    
$data= $dbh->prepare($query)->execute();
  
while($zaznam = $data->fetch_assoc()) {   // *pro kazdou vetu v doporucovaci tabulce (- vety vybrane pro prislusne id behu a id kancelare)*
     // echo " <br><br>zaznam nazev_RK_1 nazev_RK_2 nazev_RK_3 - " .  $zaznam['nazev_RK_1'] ." ".  $zaznam['nazev_RK_2'] ." ". $zaznam['nazev_RK_3'] ." ".
     //      $zaznam['id_ucastnik'] .  " -ID<br>";
    
   $V_vaha = array();     
      //$V_vaha[] = $zaznam['vaha_1'];   //$V_vaha[] = $zaznam['vaha_2'];   //$V_vaha[] = $zaznam['vaha_3'];   
    
   $V_id_typukurzu = array();
      //$V_id_typukurzu[] = $zaznam['id_s_typ_kurzu_rk_FK_1'];  //$V_id_typukurzu[] = $zaznam['id_s_typ_kurzu_rk_FK_2'];  //$V_id_typukurzu[] = $zaznam['id_s_typ_kurzu_rk_FK_3'];
    
   $V_limit = array();
   //echo "<br>";
   
    if ($zaznam['id_s_typ_kurzu_rk_FK_1']) {
       $query=" SELECT * FROM  s_typ_kurzu_rk  WHERE (s_typ_kurzu_rk.id_s_typ_kurzu_rk  =" . $zaznam['id_s_typ_kurzu_rk_FK_1'] . " ) ";
          //echo " ***" . $query . "  ";
       $data1= $dbh->prepare($query)->execute();
       $zaznam1 = $data1->fetch_assoc();
      // echo  "limit1** "  . $zaznam1['limit'];
       
       if ($zaznam['vaha_1'] >= $zaznam1['limit']) {  //je-li vetsi nebo rovno limitu, tak si ho schovam
            $V_id_typukurzu[] = $zaznam['id_s_typ_kurzu_rk_FK_1']; 
            $V_vaha[] = $zaznam['vaha_1'];
            $V_limit[]= $zaznam1['limit'];
       }  
    }
    else {
      // $V_limit[]= "";
    }
    
    if ($zaznam['id_s_typ_kurzu_rk_FK_2']) {
       $query=" SELECT * FROM  s_typ_kurzu_rk  WHERE (s_typ_kurzu_rk.id_s_typ_kurzu_rk  =" . $zaznam['id_s_typ_kurzu_rk_FK_2'] . " ) ";
         //echo " ***" . $query . "  ";
       $data1= $dbh->prepare($query)->execute();
       $zaznam1 = $data1->fetch_assoc();
       //echo  "limit2** "  . $zaznam1['limit'];
       
       if ($zaznam['vaha_2'] >= $zaznam1['limit']) {
            $V_id_typukurzu[] = $zaznam['id_s_typ_kurzu_rk_FK_2']; 
            $V_vaha[] = $zaznam['vaha_2'];
            $V_limit[]= $zaznam1['limit'];
       }  
       
    }
    else {
      //$V_limit[]= "";
    }
    
    if ($zaznam['id_s_typ_kurzu_rk_FK_3']) {
        $query=" SELECT * FROM  s_typ_kurzu_rk  WHERE (s_typ_kurzu_rk.id_s_typ_kurzu_rk  =" . $zaznam['id_s_typ_kurzu_rk_FK_3'] . " ) ";
           //echo " ***" . $query . "  ";
        $data1= $dbh->prepare($query)->execute();
        $zaznam1 = $data1->fetch_assoc();
        //echo  "limit3** "  . $zaznam1['limit'];
        
        if ($zaznam['vaha_3'] >= $zaznam1['limit']) {
            $V_id_typukurzu[] = $zaznam['id_s_typ_kurzu_rk_FK_3']; 
            $V_vaha[] = $zaznam['vaha_3'];
            $V_limit[]= $zaznam1['limit'];
       }  
        
    }
    else {
       //$V_limit[]= "";
    }
     
  
  //  echo "<br> *vaha* " ;
  //  print_r ($V_vaha);
  //  echo "  *id* " ;
  //  print_r ($V_id_typukurzu);
  //  echo "  *limit* " ;
  //  print_r ($V_limit);
       


//*** seradit
array_multisort($V_vaha, SORT_DESC,
                $V_id_typukurzu,  SORT_DESC,
                $V_limit,SORT_DESC
                );
 //echo "<br> *vaha* " ;
 //   print_r ($V_vaha);
 //   echo "  *id* " ;
 //   print_r ($V_id_typukurzu);
 //   echo "  *limit* " ;
 //   print_r ($V_limit);


//*** zapsat serazene doporucene kurzy do vety ucastnika v doporucovaci tabulce

 $query="
       UPDATE uc_doporucenirk_flat_table INNER JOIN ucastnik ON  (uc_doporucenirk_flat_table.id_ucastnik=ucastnik.id_ucastnik) SET " ;

 //foreach ($V_id_typukurzu as $indexV => $idk) {
       if ($V_id_typukurzu[0]) {
          $query .= "id_doporucen_kurz_1 = " . $V_id_typukurzu[0] . "," ;
       }
       else {
          $query .= "id_doporucen_kurz_1 = null" . "," ;
       }
       
       if ($V_id_typukurzu[1]) {
          $query .= "id_doporucen_kurz_2 = " . $V_id_typukurzu[1] . "," ;
       }
       else {
        $query .= "id_doporucen_kurz_2 = null" . "," ;
      }
       if ($V_id_typukurzu[2]) {
          $query .= "id_doporucen_kurz_3 = " . $V_id_typukurzu[2] .  ",";
       }
       else {
        $query .= "id_doporucen_kurz_3 = null" . "," ;
      }
//}            

 $query=substr($query,0,strlen($query)-1);  
 $query .= "     WHERE
        ( uc_doporucenirk_flat_table.id_ucastnik =" . $zaznam['id_ucastnik']   .
        ")  
    ";          
    //echo "<br><br>"  . $query . "<br><br>";      
 $dbh->prepare($query)->execute();

}  //while
//******************* zapis doporucen kurz 1- 3  pro kazdou vetu v doporucovaci tabulce uc_doporucenirk_flat_table  - konec




} //while za kancelar  $Id_kancelar0
//***************** pro kazdou kancelar - konec **********************************************************
//***********************************************************************************************


//*********************konec vypoctu vah a generovani poradi doporuceni kurzu konec********************************



 
//**************  pro beh, je-li vyplneno datum_ukonceni  nebo zam_datum_vstupu ,-> zapis do ucastnik_vyrazen a duvod_vyrazeni
//**************  pro beh, je-li ucastnik_vyrazen_z_RK=ano, -> zapis null do id_doporucen_kurz_1,...2,...3
//  echo "<br>DOPLNENI uc_doporucenirk_flat_table V PRIPADE VYRAZENI: ano, duvod.  V pripade ano - null do doporucenkurz1-3."; 
 $query0="
   SELECT
    UCFLAT.jmeno as                 jmeno,
    UCFLAT.prijmeni as              prijmeni, 
    ZAM.zam_datum_vstupu as         zam_datum_vstupu,
    UKONC.datum_ukonceni  as        datum_ukonceni,
    UCAST.id_s_beh_projektu_FK  as  id_s_beh_projektu_FK,
    DOP.id_ucastnik  as             id_ucastnik,
    DOP.ucastnik_vyrazen_z_RK as    ucastnik_vyrazen_z_RK,
    DOP.duvod_vyrazeni_ucastnika as duvod_vyrazeni_ucastnika,
    DOP.id_doporucen_kurz_1 as      id_doporucen_kurz_1,
    DOP.id_doporucen_kurz_2 as      id_doporucen_kurz_2,
    DOP.id_doporucen_kurz_3 as      id_doporucen_kurz_3
  FROM  
    uc_doporucenirk_flat_table  as DOP 
  JOIN  
        ucastnik  as UCAST ON  ( UCAST.id_ucastnik=DOP.id_ucastnik) 
  JOIN 
         c_kancelar as KANC ON (KANC.id_c_kancelar=UCAST.id_c_kancelar_FK)
  JOIN 
         uc_flat_table as UCFLAT ON (UCFLAT.id_ucastnik=DOP.id_ucastnik)

  left join uc_zam_flat_table as ZAM  on (ZAM. id_ucastnik= DOP.id_ucastnik)
  left join uc_ukonc_flat_table as UKONC  on (UKONC. id_ucastnik= DOP.id_ucastnik)

  WHERE
     ( UCAST.id_s_beh_projektu_FK = " .   $beh_id   .")
  ";   
  
  $data0= $dbh->prepare($query0)->execute();
  
  while($zaznam0 = $data0->fetch_assoc()) {    //pro vsechny vety v doporucovaci tabulce
  
     if ($zaznam0[datum_ukonceni] or $zaznam0[zam_datum_vstupu]) {    //je-li vyplneno datum_ukonceni  nebo zam_datum_vstupu - zapis do ucastnik_vyrazen a duvod_vyrazeni
        
              if ( ($User->username == "sys_admin" ))  {
              echo "<BR>" . $zaznam0[prijmeni].  " ".$zaznam0[id_ucastnik]. " ucastnik_vyrazen = ano ";}
                                                                    //INNER JOIN ucastnik ON  (uc_doporucenirk_flat_table.id_ucastnik=ucastnik.id_ucastnik)
          $query="
            UPDATE uc_doporucenirk_flat_table 

            SET   uc_doporucenirk_flat_table.ucastnik_vyrazen_z_RK =  'ano',	
                  uc_doporucenirk_flat_table.duvod_vyrazeni_ucastnika =  'ukončil účast v projektu nebo se zaměstnal'
            WHERE
              (
                  uc_doporucenirk_flat_table.id_ucastnik =" .   $zaznam0[id_ucastnik] .   
              ")  
           ";
             //echo "<br>" .$query . "<br><br>";      
          $dbh->prepare($query)->execute();
     }//if
     else {
      
        if ($zaznam0[duvod_vyrazeni_ucastnika] == 'ukončil účast v projektu nebo se zaměstnal' ){
          $query="
            UPDATE uc_doporucenirk_flat_table 

            SET   uc_doporucenirk_flat_table.ucastnik_vyrazen_z_RK =  'ne',	
                  uc_doporucenirk_flat_table.duvod_vyrazeni_ucastnika =  null
            WHERE
              (
                  uc_doporucenirk_flat_table.id_ucastnik =" .   $zaznam0[id_ucastnik] .   
              ")  
           ";
             //echo "<br>" .$query . "<br><br>";      
          $dbh->prepare($query)->execute();
          
        }
     }
     
     //je-li vyplneno ucastnik_vyrazen_z_RK - tak zapis null do id_doporucen_kurz_1,...2,...3
     $query="
            UPDATE uc_doporucenirk_flat_table 

            SET   uc_doporucenirk_flat_table.id_doporucen_kurz_1 =  null,
                  uc_doporucenirk_flat_table.id_doporucen_kurz_2 =  null,
                  uc_doporucenirk_flat_table.id_doporucen_kurz_3 =  null
            WHERE
              (
                  uc_doporucenirk_flat_table.id_ucastnik =" .   $zaznam0[id_ucastnik] .
                  " and " .
                  " uc_doporucenirk_flat_table.ucastnik_vyrazen_z_RK =  'ano'" .
              ")  
           ";
             //echo "<br>" .$query . "<br><br>";      
          $dbh->prepare($query)->execute();
     
  }//while  pro vsechny vety v doporucovaci tabulce

//**************  pro beh, je-li vyplneno datum_ukonceni  nebo zam_datum_vstupu ,-> zapis do ucastnik_vyrazen a duvod_vyrazeni
//**************  pro beh, je-li ucastnik_vyrazen_z_RK=ano, -> zapis null do id_doporucen_kurz_1,...2,...3
//***************  konec **********************  
  



    
//echo ("<hr> Tady se vypocetlo to, co se melo. <br>") ;





?>
