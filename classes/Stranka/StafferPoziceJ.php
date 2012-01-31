 <?php
class Stranka_StafferPoziceJ extends Stranka_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "staffer_pozice";
        const NAZEV_JENOTNE = "StafferPoziceJ";
        const NAZEV_MNOZNE = "StafferPoziceM";
        
	public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba vytvořit databázový handler a předat ho jako parametr $dbh
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vesechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            $dbh = App_Kontext::getDbMySQLPersonalService();
            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_JENOTNE, self::NAZEV_MNOZNE, TRUE, $dbh);            
//            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_JENOTNE, self::NAZEV_MNOZNE);                
	}
}