select 
`u`.`id_zajemce` AS `id_zajemce`,


if(length(right(`u`.`zamestnani_od1`,4))=4 AND length(right(`u`.`zamestnani_do1`,4))=4, concat_ws(' ',concat_ws(' - ',right(`u`.`zamestnani_od1`,4),right(`u`.`zamestnani_do1`,4)),`u`.`zamestnani_pozice1`), '') AS `zamestnani_1`,
if(length(right(`u`.`zamestnani_od2`,4))=4 AND length(right(`u`.`zamestnani_do2`,4))=4, concat_ws(' ',concat_ws(' - ',right(`u`.`zamestnani_od2`,4),right(`u`.`zamestnani_do2`,4)),`u`.`zamestnani_pozice2`), '') AS `zamestnani_2`,
if(length(right(`u`.`zamestnani_od3`,4))=4 AND length(right(`u`.`zamestnani_do3`,4))=4, concat_ws(' ',concat_ws(' - ',right(`u`.`zamestnani_od3`,4),right(`u`.`zamestnani_do3`,4)),`u`.`zamestnani_pozice3`), '') AS `zamestnani_3`,
if(length(right(`u`.`zamestnani_od4`,4))=4 AND length(right(`u`.`zamestnani_do4`,4))=4, concat_ws(' ',concat_ws(' - ',right(`u`.`zamestnani_od4`,4),right(`u`.`zamestnani_do4`,4)),`u`.`zamestnani_pozice4`), '') AS `zamestnani_4`,
if(length(right(`u`.`zamestnani_od5`,4))=4 AND length(right(`u`.`zamestnani_do5`,4))=4, concat_ws(' ',concat_ws(' - ',right(`u`.`zamestnani_od5`,4),right(`u`.`zamestnani_do5`,4)),`u`.`zamestnani_pozice5`), '') AS `zamestnani_5`,
concat_ws(' ',`u`.`rok_ukonceni_studia1`,`u`.`nazev_skoly1`) AS `skola_1`,
concat_ws(' ',`u`.`rok_ukonceni_studia2`,`u`.`nazev_skoly2`) AS `skola_2`,
concat_ws(' ',`u`.`rok_ukonceni_studia3`,`u`.`nazev_skoly3`) AS `skola_3`,
concat_ws(' ',`u`.`rok_ukonceni_studia4`,`u`.`nazev_skoly4`) AS `skola_4`,
concat_ws(' ',`u`.`rok_ukonceni_studia5`,`u`.`nazev_skoly5`) AS `skola_5`,

left(`u`.`KZAM_cislo1`,1) AS `k1`,
left(`u`.`KZAM_cislo2`,1) AS `k2`,
left(`u`.`KZAM_cislo3`,1) AS `k3`,
left(`u`.`KZAM_cislo4`,1) AS `k4`,
left(`u`.`KZAM_cislo5`,1) AS `k5`,
-- ###############################################
-- výpočet předpokladu pro výkon požadovaného zamestnání podle minulých zamestnání účastníka
-- POUŽÍVÁ TABULKU s_kzam_kvalifikacni_predpoklady !!
-- Tabulka s_kzam_kvalifikacni_predpoklady obsahuje (odhadnuté) hodnoty urcující zda minulá praxe účastníka s daným KZAM1
-- je vhodnou kvalifikací pro výkon zamestnání s jednotlivými KZAM1, tyto hodnoty jsou v intervalu od 0 do 100.
-- Výpočet predpokladu probíhá tak, že vypočte čtvrtou odmocninu ze součtu čtvrtých mocnin hodnot z tabulky s_kzam_kvalifikacni_predpoklady
-- podle minulých zamestnání účastníka. Uvažovaných minulých zamestnání je max. 5, ctvrtý stupenn mocniny a odmocniny je empiricky stanoven
-- tak, že hodnota výsledného předpokladu vyšší než 100 znamená, že zájemce již spče má předpoklady, maximilní hodnota je cca 150
POWER(
    -- pro dany kzam2 predpoklad plynouci ze zamestnani s KZAM_cislo1
    POWER(  
      if(left(`u`.`KZAM_cislo1`,1)=0, kp.predpoklad_kz0,
        if(left(`u`.`KZAM_cislo1`,1)=1,  kp.predpoklad_kz1,
          if(left(`u`.`KZAM_cislo1`,1)=2,  kp.predpoklad_kz2,
            if(left(`u`.`KZAM_cislo1`,1)=3,  kp.predpoklad_kz3,
              if(left(`u`.`KZAM_cislo1`,1)=4,  kp.predpoklad_kz4,  
                if(left(`u`.`KZAM_cislo1`,1)=5,  kp.predpoklad_kz5,
                  if(left(`u`.`KZAM_cislo1`,1)=6,  kp.predpoklad_kz6,
                    if(left(`u`.`KZAM_cislo1`,1)=7,  kp.predpoklad_kz7,
                      if(left(`u`.`KZAM_cislo1`,1)=8,  kp.predpoklad_kz8,
                        if(left(`u`.`KZAM_cislo1`,1)=9,  kp.predpoklad_kz9,
                           0
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )                                 
      )
      ,4) +
    -- pro dany kzam2 predpoklad plynouci ze zamestnani s KZAM_cislo2
    POWER(  
      if(left(`u`.`KZAM_cislo2`,1)=0, kp.predpoklad_kz0,
        if(left(`u`.`KZAM_cislo2`,1)=1,  kp.predpoklad_kz1,
          if(left(`u`.`KZAM_cislo2`,1)=2,  kp.predpoklad_kz2,
            if(left(`u`.`KZAM_cislo2`,1)=3,  kp.predpoklad_kz3,
              if(left(`u`.`KZAM_cislo2`,1)=4,  kp.predpoklad_kz4,  
                if(left(`u`.`KZAM_cislo2`,1)=5,  kp.predpoklad_kz5,
                  if(left(`u`.`KZAM_cislo2`,1)=6,  kp.predpoklad_kz6,
                    if(left(`u`.`KZAM_cislo2`,1)=7,  kp.predpoklad_kz7,
                      if(left(`u`.`KZAM_cislo2`,1)=8,  kp.predpoklad_kz8,
                        if(left(`u`.`KZAM_cislo2`,1)=9,  kp.predpoklad_kz9,
                           0
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )                                 
      )
      ,4) +
    -- pro dany kzam2 predpoklad plynouci ze zamestnani s KZAM_cislo3
    POWER(  
      if(left(`u`.`KZAM_cislo3`,1)=0, kp.predpoklad_kz0,
        if(left(`u`.`KZAM_cislo3`,1)=1,  kp.predpoklad_kz1,
          if(left(`u`.`KZAM_cislo3`,1)=2,  kp.predpoklad_kz2,
            if(left(`u`.`KZAM_cislo3`,1)=3,  kp.predpoklad_kz3,
              if(left(`u`.`KZAM_cislo3`,1)=4,  kp.predpoklad_kz4,  
                if(left(`u`.`KZAM_cislo3`,1)=5,  kp.predpoklad_kz5,
                  if(left(`u`.`KZAM_cislo3`,1)=6,  kp.predpoklad_kz6,
                    if(left(`u`.`KZAM_cislo3`,1)=7,  kp.predpoklad_kz7,
                      if(left(`u`.`KZAM_cislo3`,1)=8,  kp.predpoklad_kz8,
                        if(left(`u`.`KZAM_cislo3`,1)=9,  kp.predpoklad_kz9,
                           0
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )                                 
      )
  ,4) +
    -- pro dany kzam2 predpoklad plynouci ze zamestnani s KZAM_cislo4  
    POWER(  
      if(left(`u`.`KZAM_cislo4`,1)=0, kp.predpoklad_kz0,
        if(left(`u`.`KZAM_cislo4`,1)=1,  kp.predpoklad_kz1,
          if(left(`u`.`KZAM_cislo4`,1)=2,  kp.predpoklad_kz2,
            if(left(`u`.`KZAM_cislo4`,1)=3,  kp.predpoklad_kz3,
              if(left(`u`.`KZAM_cislo4`,1)=4,  kp.predpoklad_kz4,  
                if(left(`u`.`KZAM_cislo4`,1)=5,  kp.predpoklad_kz5,
                  if(left(`u`.`KZAM_cislo4`,1)=6,  kp.predpoklad_kz6,
                    if(left(`u`.`KZAM_cislo4`,1)=7,  kp.predpoklad_kz7,
                      if(left(`u`.`KZAM_cislo4`,1)=8,  kp.predpoklad_kz8,
                        if(left(`u`.`KZAM_cislo4`,1)=9,  kp.predpoklad_kz9,
                           0
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )                                 
      )
      ,4) +
    -- pro dany kzam2 predpoklad plynouci ze zamestnani s KZAM_cislo5
    POWER(  
      if(left(`u`.`KZAM_cislo5`,1)=0, kp.predpoklad_kz0,
        if(left(`u`.`KZAM_cislo5`,1)=1,  kp.predpoklad_kz1,
          if(left(`u`.`KZAM_cislo5`,1)=2,  kp.predpoklad_kz2,
            if(left(`u`.`KZAM_cislo5`,1)=3,  kp.predpoklad_kz3,
              if(left(`u`.`KZAM_cislo5`,1)=4,  kp.predpoklad_kz4,  
                if(left(`u`.`KZAM_cislo5`,1)=5,  kp.predpoklad_kz5,
                  if(left(`u`.`KZAM_cislo5`,1)=6,  kp.predpoklad_kz6,
                    if(left(`u`.`KZAM_cislo5`,1)=7,  kp.predpoklad_kz7,
                      if(left(`u`.`KZAM_cislo5`,1)=8,  kp.predpoklad_kz8,
                        if(left(`u`.`KZAM_cislo5`,1)=9,  kp.predpoklad_kz9,
                           0
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )                                 
      )
      ,4) 
, 1/4)  AS predpoklady,         -- ctvrta odmocnina ze souctu ctvrtych mocnin jednotlivých predpokladu (empiricky, simulovano)


-- #####################################################  
`s_kzam2`.`kod_s_kzam2` AS `pozaduje_kzam2_kod`,
`s_kzam2`.`nazev` AS `pozaduje_kzam2_nazev`,

concat(IF(`s_kzam2`.`kod_s_kzam2` = left(`u`.`pozadavky_KZAM1`,2), 
          nazev_pkz1.nazev, 
          ''),
       IF(`s_kzam2`.`kod_s_kzam2` = left(`u`.`pozadavky_KZAM2`,2), 
          concat(if(`s_kzam2`.`kod_s_kzam2` = left(`u`.`pozadavky_KZAM1`,2) , ' / ', ''), nazev_pkz2.nazev),
          ''),
       IF(`s_kzam2`.`kod_s_kzam2` = left(`u`.`pozadavky_KZAM3`,2), 
          concat(if(`s_kzam2`.`kod_s_kzam2` = left(`u`.`pozadavky_KZAM2`,2) , ' / ', ''), nazev_pkz3.nazev),
          '')
      ) AS `pozaduje_kzam5_nazev`,

nazev_kz1.nazev AS kzam_nazev1,
nazev_kz2.nazev AS kzam_nazev2,
nazev_kz3.nazev AS kzam_nazev3,
nazev_kz4.nazev AS kzam_nazev4,
nazev_kz5.nazev AS kzam_nazev5
 
from 
     -- zájemce pozaduje dany KZAM2 a je z projektu zadením v kriteriích
--     v_nabidka_kriteria AS kriteria
--     join 
    `s_kzam2`
     left join  `za_flat_table` AS `u` 
          on (`s_kzam2`.`kod_s_kzam2` in (left(`u`.`pozadavky_KZAM1`,2), left(`u`.`pozadavky_KZAM2`,2), left(`u`.`pozadavky_KZAM3`,2))

             ) 
     left join s_kzam_kvalifikacni_predpoklady AS kp on (kp.pozadovane_kzam1=left(`s_kzam2`.`kod_s_kzam2`,1))
     left join s_kzam5 AS nazev_pkz1 on (`u`.`pozadavky_KZAM1`=nazev_pkz1.kod_s_kzam5)
     left join s_kzam5 AS nazev_pkz2 on (`u`.`pozadavky_KZAM2`=nazev_pkz2.kod_s_kzam5)
     left join s_kzam5 AS nazev_pkz3 on (`u`.`pozadavky_KZAM2`=nazev_pkz3.kod_s_kzam5)
     left join s_kzam5 AS nazev_kz1 on (`u`.`KZAM_cislo1`=nazev_kz1.kod_s_kzam5)
     left join s_kzam5 AS nazev_kz2 on (`u`.`KZAM_cislo2`=nazev_kz2.kod_s_kzam5)
     left join s_kzam5 AS nazev_kz3 on (`u`.`KZAM_cislo3`=nazev_kz3.kod_s_kzam5)
     left join s_kzam5 AS nazev_kz4 on (`u`.`KZAM_cislo4`=nazev_kz4.kod_s_kzam5)
     left join s_kzam5 AS nazev_kz5 on (`u`.`KZAM_cislo5`=nazev_kz5.kod_s_kzam5)
