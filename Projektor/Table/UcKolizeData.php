<?php


class Projektor_Table_UcKolizeData {

  public $id_uc_kolize_table;

  public $id_ucastnik;
  public $id_s_typ_kolize_FK;
  public $revidovano;
  public $revidovano_pozn;
  public $date_vzniku;
  public $kolize_nastala;
 // public $valid;

  public $nazev_kolize;     // z tabulky s_typ_kolize
  public $text;
  public $uroven;
  public $lokace;

  static $zjistovane_kolize=array();   //array
  static $nastava_kolize_ve_zjistovanych;




public function __construct($iducastnik, $idskolizeFK,
                            $revidovano, $revidovanopozn,
                            $datevzniku, $kolize_nastala, /*$valid=null,*/
                            $nazev_kolize,
                            $text, $uroven, $lokace,
                            $id )  {

                $this->id_uc_kolize_table = $id;

		$this->id_ucastnik = $iducastnik;
		$this->id_s_typ_kolize_FK = $idskolizeFK;
		$this->revidovano = $revidovano;
		$this->revidovano_pozn = $revidovanopozn;
                $this->date_vzniku = $datevzniku;
                $this->kolize_nastala = $kolize_nastala;
                //$this->valid = $valid;

                $this->nazev_kolize = $nazev_kolize;
                $this->text = $text;
                $this->uroven = $uroven;
                $this->lokace = $lokace;


                //echo "<br>contruct Projektor_Table_UcKolizeTable"; var_dump($this);
} //construct






/**
 * Z dodaných komponent vyrobí sql dotaz.
 *
 * Predpoklada vzdy vyber z tabulky ucastnik zjoinovane dale (dle dodaneho $fromtext).
 * a predpripravi vyberovy argument id_ucastnik v klauzuli where.
 *
 * V RecordSetu vraci: id_ucastnik, zobrazovany_text_kolize, kolize_nastala
 *
 * @param string  $zobrazovanytext
 * @param string  $selecttext
 * @param string  $fromtext
 * @param string  $wheretext
 */
private function Sestav_dotaz_na_kolizi ($zobrazovanytext="", $selecttext="", $fromtext="", $wheretext="" ) {
  $q = "  SELECT ucastnik.id_ucastnik, ";

  if ($zobrazovanytext){
    $q .=   $zobrazovanytext .  " AS zobrazovany_text_kolize ";
  }


  if ($selecttext) {
      $q .= ", " . $selecttext . " as kolize_nastala ";
  }

  if ($fromtext) {
    $q .= " FROM ucastnik JOIN " . $fromtext . " ";
  }

  if ($wheretext) {
      $q .= "WHERE  (ucastnik.id_ucastnik=:1) and " . $wheretext . " ";
  }
  else{
      $q .= "WHERE  (ucastnik.id_ucastnik=:1)";
  }

  //}
  //else { $q="";
  //}

  return $q ;
}//function Sestav_dotaz_na_kolizi





//---------------------------------------------------------------
/**
 * Proveri kolize ucastnika ve vsech formularich, zda nastavaji.
 * Vraci pole objektu proverovanych kolizi.
 *
 * @param integer $id_ucast id ucastnika
*/
public static function Najdi_kolize_vsechny ($iducast) {

    $query= "SELECT * FROM s_typ_kolize WHERE  valid" ;
    $kolize_pole = self::Najdi_kolize ($query,$iducast)  ;

    return $kolize_pole;
}



/**
 * Proveri kolize ucastnika vzadanem formulari, zda nastavaji.
 * Vraci pole objektu proverovanych kolizi.
 *
 * @param integer $id_ucast id ucastnika
 * @param string $formular jmeno formulare
*/
public static function Najdi_kolize_pro_formular ($iducast, $formular ) {
    $query= "SELECT * FROM s_typ_kolize WHERE  formular='" . $formular . "' and valid" ;
    $kolize_pole = self::Najdi_kolize ($query,$iducast)  ;

      //echo("<br>" . $formular . "<br>");
      //var_dump ($kolize_pole);
    return $kolize_pole;
}




/**
 * Proveri kolize ucastnika v zadane lokaci zadaneho formulare, zda nastavaji.
 * Vraci pole objektu proverovanych kolizi.
 *
 *
 * @param integer $id_ucast id ucastnika
 * @param string $formular jmeno formulare
 * @param string $lokacekolize lokace
*/
public static function Najdi_kolize_pro_formular_a_lokaci ($iducast, $formular, $lokacekolize ) {
  $query= "SELECT * FROM s_typ_kolize WHERE lokace_kolize='" . $lokacekolize . "' and formular='" . $formular . "' and valid" ;
  //echo "<br>" . $query;
  $kolize_pole = self::Najdi_kolize ($query,$iducast)  ;
  //var_dump ($kolize_pole);

  return $kolize_pole;
}


/**
 * Proveri kolize ucastnika, zda nastavaji. Vyber, ktere bude proverovat, se provadi dodanym  query.
 * Vraci pole objektu proverovanych kolizi.
 *
 * Ve vlastnosti tridy -zjistovane_kolize vraci pole idcek prave zjistovanych kolizi.
 * Ve vlastnosti tridy -nastava_kolize nastavi priznak, ze alespon jedna zjistovana kolize skutecne nastava,
 * je-li alespon jedna 'pozitivni'.
 *
 */

private static function Najdi_kolize ($query,$iducast){
  $kolize_pole = array();
  $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);

  //echo " <br>". $query . " " . $iducast;

  //--
  Projektor_Table_UcKolizeData::$zjistovane_kolize=array();
  Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych = false;
  //echo "NAJDI KOLIZE";

  $data= $dbh->prepare($query)->execute();
  while($zaznam_s_kolize = $data->fetch_assoc())  {    //pro  pozadovane kontroly (kolize) sestavuji dotazy
        //var_dump($zaznam_s_kolize);
        //--
        array_push (Projektor_Table_UcKolizeData::$zjistovane_kolize, $zaznam_s_kolize['id_s_typ_kolize']);
        //echo "Projektor_Table_UcKolizeData::zjistovane_kolize"; var_dump(Projektor_Table_UcKolizeData::$zjistovane_kolize);

        //ze zjistenych udaju sestavuji dotazy na kolize
        $query_kolize = self::Sestav_dotaz_na_kolizi($zaznam_s_kolize['zobrazovany_text_kolize'], $zaznam_s_kolize['select_text'], $zaznam_s_kolize['from_text'], $zaznam_s_kolize['where_text'] );
            //echo "<br>*id_s_typ_kolize a dotaz na kolizi*|" . $zaznam_s_kolize['id_s_typ_kolize'] ;
            //echo " |<br>". $query_kolize;

        if   ($query_kolize) {
          $data_kolize = $dbh->prepare($query_kolize)->execute($iducast);


          //while ($zaznam_kolize = $data_kolize->fetch_assoc()) {
           $zaznam_kolize = $data_kolize->fetch_assoc();
              //echo "<br>zaznam_kolize" ; var_dump( $zaznam_kolize );

           if  ( $zaznam_kolize['kolize_nastala'] ) {   // kolize nastava
                    // echo "<br>" . $zaznam_kolize['kolize_nastala'];
              //--/
              Projektor_Table_UcKolizeData::$nastava_kolize_ve_zjistovanych = true;       // nastavi priznak, ze alespon jedna zjistovana kolize skutecne nastava

            // ***minula*** - kolize nastava, je treba z uc_kolize_table precist ulozena data pokud uz kolize byla a !je! validni
            // hledat podle iducast, id s kolize , formular-nemusi, lokace-nemusi
            $query_minula = "SELECT * FROM uc_kolize_table left join s_typ_kolize on (s_typ_kolize.id_s_typ_kolize = uc_kolize_table.id_s_typ_kolize_FK) " .
                         "WHERE id_ucastnik=" . $iducast .
                         //" and s_typ_kolize.formular='" . $formular ."'" .
                         //" and  s_typ_kolize.lokace_kolize='" . $lokacekolize ."'" .
                         " and s_typ_kolize.id_s_typ_kolize=" . $zaznam_s_kolize['id_s_typ_kolize'] .
                         " and uc_kolize_table.valid" ;
            //echo  "<br>query_minula: "  .$query_minula;
            $data_minula = $dbh->prepare($query_minula)->execute();
            $zaznam_minula=$data_minula->fetch_assoc();
               //var_dump ($zaznam_minula);

            if ($zaznam_minula) {
              $kolize = new Projektor_Table_UcKolizeData($iducast, $zaznam_s_kolize['id_s_typ_kolize'],
                               $zaznam_minula['revidovano'], $zaznam_minula['revidovano_pozn'],
                               $zaznam_minula['date_vzniku'], $zaznam_kolize['kolize_nastala'],
                               $zaznam_s_kolize['nazev_kolize'],
                               $zaznam_kolize['zobrazovany_text_kolize'] , $zaznam_s_kolize['uroven_kolize'] , $zaznam_s_kolize['lokace_kolize'],
                               $zaznam_minula['id_uc_kolize_table']);
            }
            else {
              $kolize = new Projektor_Table_UcKolizeData($iducast, $zaznam_s_kolize['id_s_typ_kolize'],
                               null, null,
                               null, $zaznam_kolize['kolize_nastala'],
                               $zaznam_s_kolize['nazev_kolize'],
                               $zaznam_kolize['zobrazovany_text_kolize'] , $zaznam_s_kolize['uroven_kolize'] , $zaznam_s_kolize['lokace_kolize'],
                               null);
            }
            //var_dump($kolize);
            $kolize_pole[] = $kolize;



          } //- kolize nastava

          else {  // kolize nenastava      nevim zda i u kolize ktera nenastava  mam naplnovat informace z uc_kolize_table (revidovano....)
            $kolize = new Projektor_Table_UcKolizeData($iducast, $zaznam_s_kolize['id_s_typ_kolize'],
                               null, null,
                               null, $zaznam_kolize['kolize_nastala'],
                               $zaznam_s_kolize['nazev_kolize'],
                               $zaznam_kolize['zobrazovany_text_kolize'] , $zaznam_s_kolize['uroven_kolize'] , $zaznam_s_kolize['lokace_kolize'],
                               null);

            $kolize_pole[] = $kolize;

          }

        //}//while

        }//if query

  } // while - pro  pozadovane kontroly (kolize) sestavuji dotazy

//echo "<br>**Kolize_pole v Najdi_kolize..... **";
//var_dump($kolize_pole);

  return $kolize_pole;

}//Najdi_kolize




/**
 * Proveri kolize ucastnika zadaneho formulare, zda nastavaji.
 * Vraci pole objektu proverovanych kolizi,-ale jen ty, co jeste nebyly ve formulari ve skriptu volane-.
 *
 *
 * @param integer $id_ucast id ucastnika
 * @param string $formular jmeno formulare
 * @param string $pole_id_volanych pole id_s_typ_kolizi, ktere ve skriptu byly jiz zjistovane.
*/
public static function Najdi_kolize_pro_formular_dosud_nezavolane ($iducast, $formular, $pole_id_volanych ) {
    $query= "SELECT * FROM s_typ_kolize WHERE  formular='" . $formular . "' and valid" ;
    $kolize_pole = self::Najdi_kolize ($query,$iducast)  ;  //to jsou vsechny pro formular

    //ty, co uz volal, z pole vypustit
    $kolize_pole_redukovane = array();

    foreach ($kolize_pole as $kprvek) {
      if ( in_array( $kprvek->id_s_typ_kolize_FK, $pole_id_volanych) ) {
      }
      else {
        array_push ($kolize_pole_redukovane, $kprvek );  //$kprvek->id_s_typ_kolize_FK);
      }
    }

    return $kolize_pole_redukovane;
}




/**
 * Proveri kolize dosud ve formulari nevolane
 * a u nastalych kolizi zobrazi hlaseni a policka kolizi ve formulari.
 *
 * @param integer $iducast id ucastnika
 * @param string $formular jmeno formulare
 * @param string $pole_id_volanych - pole id_s_typ_kolizi, ktere ve skriptu byly jiz zjistovane.
 */
public static function Vypis_kolize_formulare_dosud_nezavolane($iducast, $formular, $pole_id_volanych)   {
  $kolize_pole = self::Najdi_kolize_pro_formular_dosud_nezavolane($iducast, $formular, $pole_id_volanych);

  // zobrazeni hlaseni a policek kolizi ve formulari
  foreach ($kolize_pole as $kprvek) {
        //var_dump($kprvek);
        if ($kprvek->kolize_nastala) {
          self::Vypis_jednu_kolizi_do_formulare($kprvek);
        }
  }

} //function Vypis_kolize_formular_dosud_nezavolane






//------------------------------------------------------




/**
 * Proveri kolize ve formulari pro lokaci
 * a u nastalych kolizi zobrazi hlaseni a policka kolizi ve formulari.
 *
 * @param integer $iducast id ucastnika
 * @param string $formular jmeno formulare
 * @param string $lokacekolize lokace
 */
public static function Vypis_kolize_pro_formular_a_lokaci($iducast, $formular, $lokacekolize )   {
  $kolize_pole = self::Najdi_kolize_pro_formular_a_lokaci($iducast, $formular, $lokacekolize);

  // zobrazeni hlaseni a policek kolizi ve formulari
  foreach ($kolize_pole as $kprvek) {
    //var_dump($kprvek);

        if ($kprvek->kolize_nastala) {
          self::Vypis_jednu_kolizi_do_formulare($kprvek);
        }
  }

} //function Vypis_kolize_pro_formular_a_lokaci



/**
 *
 *
 */
private static function Vypis_jednu_kolizi_do_formulare($kprvek){

        echo "<DIV>";

        if ($kprvek->uroven=="W") {
             echo "<br><span class='kolize-W'> Varování - "  . $kprvek->text . "</span>";
        }
        if ($kprvek->uroven=="E") {
            echo "<br><span class='kolize-E'> Chyba - "  . $kprvek->text . "</span>";
        }
        echo "<span style='display:none'><input ID='" . "uc_kolize_table§" . str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "_" . "text'
                                    type='text' name='" .  "uc_kolize_table§" .  str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "_" . "text'
                                    size='120' maxlength='500' readonly  value='". $kprvek->text . "'></span>"  ;

        echo "<span class='kolize-rev'>";
        echo "<br>Revidováno: ";
        echo "<input ID='" .  "uc_kolize_table§" .   str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "revidovano'
                     type='radio'  name='" .  "uc_kolize_table§" .  str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "_" . "revidovano' value='Ne'" ;
                       if ($kprvek->revidovano != 'Ano') {echo "checked ";} echo ">Ne";
        echo "<input ID='" .  "uc_kolize_table§" .  str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "revidovano'
                     type='radio'  name='" .  "uc_kolize_table§" .  str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "_" . "revidovano' value='Ano'";
                       if ($kprvek->revidovano == 'Ano') {echo 'checked';} echo ">Ano";

        echo "<br>Revidováno-pozn.: <input ID='" . "uc_kolize_table§" . str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "_" . "revidovano_pozn'
                                    type='text' name='" .  "uc_kolize_table§" .  str_pad($kprvek->id_s_typ_kolize_FK , 5, "0", STR_PAD_LEFT) . "_" . "revidovano_pozn'
                                    size='120' maxlength='500' value='". $kprvek->revidovano_pozn . "'>";
        echo "</span>";
        echo "</DIV>";


}




/**
 * Znevalidni vsechny dosud zname kolize ucastnika,
 * ktere jsou zapsane(ulozene) v tabulce uc_kolize_table.
 *
 * @param integer $iducast id ucastnika
*/
public static function Znevalidni_kolize_ucastnika_vsechny($iducast) {
//echo "<hr><br>*Znevalidnuji vsechny kolize ucastnika , formular=" . $formular;

    $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);

    //vyberu vsechny ulozene kolize  z tabulky uc_kolize_table
    $query= "SELECT * FROM uc_kolize_table " .
              "WHERE id_ucastnik=" . $iducast . " and uc_kolize_table.valid" ;

    //echo "<br>*dotaz na kolize v Znevalidni_kolize_ucastnika: " . $query;

    $data= $dbh->prepare($query)->execute();
    while($zaznam_kolize = $data->fetch_assoc())  {
       //echo "<br>*Znevalidnuji kolizi: "  .  $zaznam_kolize[id_uc_kolize_table];

       $query1 = "UPDATE uc_kolize_table SET " .
                 "valid=0 WHERE uc_kolize_table.id_uc_kolize_table=" . $zaznam_kolize['id_uc_kolize_table'] ;
       $data1= $dbh->prepare($query1)->execute();
    }

//echo "<hr>";
}//function Znevalidni_kolize_ucastnika_vsechny




/**
 * Znevalidni vsechny dosud zname kolize pro ucastnika a formular,
 * ktere jsou zapsane(ulozene) v tabulce uc_kolize_table.
 *
 *
 * @param integer $iducast id ucastnika
 * @param string $formular jmeno formulare
*/
public static function Znevalidni_kolize_ucastnika_formulare($iducast,$formular) {
//echo "<hr><br>*Znevalidnuji vsechny kolize ucastnika , formular=" . $formular;

    $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);

    //vyberu vsechny ulozene kolize  z tabulky uc_kolize_table
    $query= "SELECT * FROM uc_kolize_table left join s_typ_kolize on (s_typ_kolize.id_s_typ_kolize = uc_kolize_table.id_s_typ_kolize_FK) " .
              "WHERE id_ucastnik=" . $iducast . " and s_typ_kolize.formular='" . $formular . "' and uc_kolize_table.valid" ;

    //echo "<br>*dotaz na kolize v Znevalidni_kolize_ucastnika_formulare: " . $query;

    $data= $dbh->prepare($query)->execute();

    while($zaznam_kolize = $data->fetch_assoc())  {
       //echo "<br>*Znevalidnuji kolizi: "  .  $zaznam_kolize[id_uc_kolize_table];

       $query1 = "UPDATE uc_kolize_table SET " .
                 "valid=0 WHERE uc_kolize_table.id_uc_kolize_table=" . $zaznam_kolize['id_uc_kolize_table'] ;
       $data1= $dbh->prepare($query1)->execute();
    }

//echo "<hr>";
}//function Znevalidni_kolize_ucastnika_formulare








/**
 * Zapise kolizi (jednu) do uc_kolize_table.
 *
 * Kdyz je v tabulce uc_kolize_table jiz prislusny zaznam (hleda ho podle id_s_typ_kolize a id_ucastnik), tak prepise nalezeny radek(zaznam),
 * kdyz neni, tak vlozi.
*/
private function  Zapis_jednu_kolizi() {
  $dbh = Projektor_Container::getDbh(Framework_Config::DATABAZE_PROJEKTOR);

//echo "<hr><br>* v Zapis_jednu_kolizi:";

    //vyberu  kolizi z uc_kolize_table pokud jiz existuje
  $query= "SELECT * FROM uc_kolize_table WHERE id_ucastnik ="  .  $this->id_ucastnik . " and  id_s_typ_kolize_FK=" . $this->id_s_typ_kolize_FK ;
      //echo "<br>*dotaz  v Zapis_jednu_kolizi: " . $query;
  $data = $dbh->prepare($query)->execute();
      //var_dump($data);

  if ($data) {
    $zaznam_kolize = $data->fetch_assoc()  ;  //vemu prvni (je predpoklad ze je jen jedna)
    if ($zaznam_kolize) {
      //echo "<br>kolize je  - budu prepisovat";    //budu prepisovat
      $query1 = "UPDATE uc_kolize_table set " .
              "revidovano='" . $this->revidovano . "', " .
              "revidovano_pozn='" . $this->revidovano_pozn . "', " .
              "valid=1 "  .
              "WHERE id_uc_kolize_table =" . $zaznam_kolize['id_uc_kolize_table'];
      //echo "<br>" . $query1;
      $data1 = $dbh->prepare($query1)->execute();

    }
    else {
      //echo "<br>kolize neni - budu vkladat";   //budu vkladat
      $query1 = "INSERT uc_kolize_table set " .
              "id_ucastnik= " . $this->id_ucastnik .  ", " .
              "id_s_typ_kolize_FK=" . $this->id_s_typ_kolize_FK .  ", " .
              "revidovano='" . $this->revidovano . "', " .
              "revidovano_pozn='" . $this->revidovano_pozn . "', " .
              "valid=1, date_vzniku=now() "  ;
      //echo "<br>" . $query1;
      $data1 = $dbh->prepare($query1)->execute();

    }
  }

//echo "<hr>";
}//function  Zapis_jednu_kolizi()




/**
 * Zapise nastale kolize z formulare jednoho ucastnika.
 *
 * Data z formulare vyzvedne podle uschovanych id-cek ($idcka_skolizi).
 *
 * @param array $pole pole hodnot z formulare
 * @param array $idcka_skolizi id do s_typ_kolize
 * @param integer $iducast id ucastnika
 * @param string $formular jmeno formulare
*/
private function  Zapis_kolize_formulare($pole, $idcka_skolizi, $iducast, $formular) {
//znevalidneni vsech kolizi pro ucastnika a tento formular
              self::Znevalidni_kolize_ucastnika_formulare($iducast, $formular);

//zapis do uc_kolize_table pro kazdou nastalou s_kolizi
              foreach ($idcka_skolizi as $id_skolize) {    //zapisovana policka jsou v $pole
                         //echo "policko: " . $pole['uc_kolize_table§' . $id_skolize . '_revidovano'];
                $kolize = new Projektor_Table_UcKolizeData ($iducast, (int)$id_skolize,
                                                  $pole['uc_kolize_table§' . $id_skolize . '_revidovano'],
                                                  $pole['uc_kolize_table§' . $id_skolize . '_revidovano_pozn'],
                                                  null, 1,
                                                  null,null,null,null,null) ;
                        // echo "v Zapis_kolize_temp" . var_dump ($kolize);
                $kolize->Zapis_jednu_kolizi();  //kdyz je v tabulce uc_kolize_table, tak prepsat, kdyz neni, tak insert
              }

}//function Zapis_kolize_formulare







/**
 * Zapise vsechny kolize daneho formulare jednoho ucastnika, a pak
 * zjisti pro ucastnika pro vsechny formulare vsechny kolize, znevalidni kolize ucastnika stavajici  a zapise zjistene.
 *
 * @param array $pole pole hodnot z formulare
 * @param array $idcka_skolizi id do s_typ_kolize
 * @param integer $iducast id ucastnika
 * @param string $formular jmeno formulare
*/
public function   Zapis_vsechny_kolize_v_zaveru_formulare ($pole, $idcka_skolizi, $iducast, $formular){
  //zapise kolize formulare
  self::Zapis_kolize_formulare($pole,$idcka_skolizi, $iducast, $formular);
  //-----------------------------------------------------------------------------


  //a zjisti a zapise kolize  ucastnika pro vsechny formulare
  $vsechny_kolize_ucastnika_pole = self::Najdi_kolize_vsechny($iducast);
      //echo "<br>**Vsechny kolize_pole v Zapis_vsechny_kolize..... **";
      //var_dump($vsechny_kolize_ucastnika_pole);

  //znevalidneni vsech kolizi pro ucastnika
  self::Znevalidni_kolize_ucastnika_vsechny($iducast);
  foreach($vsechny_kolize_ucastnika_pole as $jedna_kolize){
    if ($jedna_kolize->kolize_nastala) {
       $jedna_kolize->Zapis_jednu_kolizi();
    }
  }

}// function Zapis_vsechny_kolize_v_zaveru_formulare




public static function Vyhodnot_kolizi($nazev_kolize,$iducast)  {
  $nastava=false;

  $query= "SELECT * FROM s_typ_kolize WHERE  nazev_kolize = '" . $nazev_kolize . "'";
  $kolize_pole = self::Najdi_kolize ($query,$iducast)  ;

 // echo "<br>";
 // var_dump($kolize_pole);


  //$query_kolize = self::Sestav_dotaz_na_kolizi($zaznam_s_kolize['zobrazovany_text_kolize'], $zaznam_s_kolize['select_text'], $zaznam_s_kolize['from_text'], $zaznam_s_kolize['where_text'] );

   //if   ($query_kolize) {
   //       $data_kolize = $dbh->prepare($query_kolize)->execute($iducast);


}


}//class









?>