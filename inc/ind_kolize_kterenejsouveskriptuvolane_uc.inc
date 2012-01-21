<?php

  //$f_zjistovane_kolize= array_merge($f_zjistovane_kolize,Table_UcKolizeData::$zjistovane_kolize);
  //echo "<br>zjistovane_kolize vdodatecnem ormulari ind_kolize_kterenejsouveskriptu" ; var_dump($f_zjistovane_kolize);  
    
  //$pole_kolize_dosud_nevolane=Table_UcKolizeData::Najdi_kolize_pro_formular_dosud_nezavolane ($id_ucastnik,FORMULAR_PLAN,$f_zjistovane_kolize );
  //echo "<br>kolize dosud nevolane" ; var_dump($pole_kolize_dosud_nevolane);
  
  Table_UcKolizeData::Vypis_kolize_formular_dosud_nezavolane($id_ucastnik,FORMULAR_PLAN,$f_zjistovane_kolize)  //$zavolane_kolize_v_plan
  
?>