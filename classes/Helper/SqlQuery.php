<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Helper_SqlQuery
{
    public static function getShowTablesQueryMySQL() 
    {
        return "SELECT TABLE_NAME AS Nazev FROM information_schema.TABLES WHERE TABLE_SCHEMA = :1 AND TABLE_NAME=:2";
    }

    public static function getShowTablesQueryMSSQL() 
    {
        return "SELECT tab.[name] AS Nazev FROM  ~1.[sys].[tables] AS tab WHERE tab.[name]= :2";
    }
    
    public static function getShowColumnsQueryMySQL() 
    {
        return "SELECT COLUMN_NAME AS Nazev, DATA_TYPE AS Typ, CHARACTER_MAXIMUM_LENGTH AS Delka, if(COLUMN_KEY='PRI', 1, 0) AS PK  FROM information_schema.`COLUMNS` WHERE TABLE_SCHEMA = :1 AND TABLE_NAME=:2";
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
