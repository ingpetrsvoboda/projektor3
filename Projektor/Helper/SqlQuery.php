<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Projektor_Helper_SqlQuery
{
    public static function getShowTablesQueryMySQL()
    {
        return "SELECT TABLE_NAME AS Nazev FROM information_schema.TABLES WHERE TABLE_SCHEMA = :1 AND TABLE_NAME=:2";
    }

    public static function getShowTablesQueryMSSQL()
    {
        return "SELECT tab.[name] AS Nazev FROM  ~1.[sys].[tables] AS tab WHERE tab.[name]= :2";
    }

    public static function getShowStructureQueryMySQL()
    {
//SELECT Tabulka, Nazev, Typ, Delka, Def AS 'Default', if(KlicC='PRI', 'PK', if(KlicC='MUL' AND NOT(ISNULL(TabulkaK)), 'FK', NULL)) AS Klic, Extra, TabulkaK, Referencovana_tabulka, Referencovany_sloupec
//                FROM (SELECT * FROM
//                        (SELECT col.TABLE_NAME AS Tabulka, col.COLUMN_NAME AS Nazev, col.DATA_TYPE AS Typ, col.CHARACTER_MAXIMUM_LENGTH AS Delka, col.COLUMN_DEFAULT AS Def,
//                            col.COLUMN_KEY AS KlicC, col.EXTRA AS Extra FROM information_schema.`COLUMNS` AS col
//                        WHERE col.TABLE_SCHEMA = 'projektor_3'
//                        ) AS c
//                      JOIN
//                        (SELECT tab.TABLE_NAME AS TabulkaT, tab.TABLE_TYPE FROM  information_schema.`TABLES` AS tab
//                         WHERE tab.TABLE_SCHEMA =  'projektor_3' AND tab.TABLE_TYPE='BASE TABLE') AS t
//
//                      ON (t.TabulkaT=c.Tabulka)
//                      ) AS ct
//                LEFT JOIN (SELECT kcu.TABLE_NAME AS TabulkaK, kcu.COLUMN_NAME AS NazevK, kcu.REFERENCED_TABLE_NAME AS Referencovana_tabulka, kcu.REFERENCED_COLUMN_NAME AS Referencovany_sloupec FROM information_schema.KEY_COLUMN_USAGE AS kcu
//				WHERE kcu.TABLE_SCHEMA = 'projektor_3') AS k
//                ON (ct.Tabulka=k.TabulkaK AND ct.Nazev=k.NazevK)
        return "SELECT Tabulka, Nazev, Typ, Delka, Def AS 'Default', if(KlicC='PRI', 'PK', if(KlicC='MUL' AND NOT(ISNULL(TabulkaK)), 'FK', NULL)) AS Klic, Extra, TabulkaK, Referencovana_tabulka, Referencovany_sloupec
                FROM (SELECT * FROM
                        (SELECT col.TABLE_NAME AS Tabulka, col.COLUMN_NAME AS Nazev, col.DATA_TYPE AS Typ, col.CHARACTER_MAXIMUM_LENGTH AS Delka, col.COLUMN_DEFAULT AS Def,
                            col.COLUMN_KEY AS KlicC, col.EXTRA AS Extra FROM information_schema.`COLUMNS` AS col
                        WHERE col.TABLE_SCHEMA = :1
                        ) AS c
                      JOIN
                        (SELECT tab.TABLE_NAME AS TabulkaT, tab.TABLE_TYPE FROM  information_schema.`TABLES` AS tab
                         WHERE tab.TABLE_SCHEMA =  :1 AND tab.TABLE_TYPE='BASE TABLE') AS t

                      ON (t.TabulkaT=c.Tabulka)
                      ) AS ct
                LEFT JOIN (SELECT kcu.TABLE_NAME AS TabulkaK, kcu.COLUMN_NAME AS NazevK, kcu.REFERENCED_TABLE_NAME AS Referencovana_tabulka, kcu.REFERENCED_COLUMN_NAME AS Referencovany_sloupec FROM information_schema.KEY_COLUMN_USAGE AS kcu
				WHERE kcu.TABLE_SCHEMA = :1) AS k
                ON (ct.Tabulka=k.TabulkaK AND ct.Nazev=k.NazevK) ";
//        return "SELECT COLUMN_NAME AS Nazev, DATA_TYPE AS Typ, CHARACTER_MAXIMUM_LENGTH AS Delka, COLUMN_KEY AS Klic, if(COLUMN_KEY='PRI', 1, 0) AS PK  FROM information_schema.`COLUMNS` WHERE TABLE_SCHEMA = :1 AND TABLE_NAME=:2";
    }

    public static function getShowColumnsQueryMSSQL()
    {
        return "SELECT col.[name] AS Nazev, typ.name AS Typ, col.max_length AS Delka, col.is_identity AS PK FROM ~1.[sys].[tables] AS tab JOIN ~1.[sys].[columns] AS col ON col.object_id=tab.object_id JOIN ~1.[sys].[types] AS typ ON col.[user_type_id]=typ.[user_type_id] WHERE tab.[name]=:2";
    }

    public static function getPrimaryKeyQueryMySQL()
    {
        return "SELECT COLUMN_NAME AS Nazev FROM information_schema.`COLUMNS` WHERE TABLE_SCHEMA = :1 AND TABLE_NAME=:2 AND COLUMN_KEY='PRI'";
    }

    public static function getPrimaryKeyQueryMSSQL()
    {
        return "SELECT  COL_NAME(ic.OBJECT_ID,ic.column_id) AS Nazev FROM ~1.[sys].[indexes] AS i INNER JOIN ~1.[sys].[index_columns] AS ic ON  i.OBJECT_ID = ic.OBJECT_ID  AND i.index_id = ic.index_id WHERE   i.is_primary_key = 1 AND OBJECT_NAME(ic.OBJECT_ID)=:2";
    }

    }
?>
