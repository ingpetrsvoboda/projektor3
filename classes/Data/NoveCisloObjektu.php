<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Data_NoveCisloObjektu {
    
    
    public static function dejNoveCislo($hlavniObjekt) {
           $dbh = App_Kontext::getDbMySQLProjektor();

           //-----------------------novy ucastnik - nahrada za uloz proceduru-----------------
            $query = "SELECT Max(~1) AS maxU  FROM ~2
                      WHERE ((~3 = :4) AND (~5 = :6))";
            $data = $dbh->prepare($query)->execute($hlavniObjekt::CISLO_UCASTNIKA, $hlavniObjekt::TABULKA, 
                    $hlavniObjekt::ID_C_PROJEKT_FK, $hlavniObjekt->Projekt->id, $hlavniObjekt::ID_C_KANCELAR_FK, $hlavniObjekt->Kancelar->id)->fetch_assoc();
            if ($data['maxU']) {
                return $data['maxU'] + 1 ;
            }
            else {
                return 1;
            }
            //-----------------------novy ucastnik - nahrada za uloz proceduru - konec-----------------
    }
}

?>
