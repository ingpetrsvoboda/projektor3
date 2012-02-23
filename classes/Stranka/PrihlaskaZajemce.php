 <?php
class Stranka_PrihlaskaZajemce extends Stranka_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "prihlasky_zajemce";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "PrihlaskaZajemce";
        const NAZEV_MNOZNE = "PrihlaskyZajemce";
        
	public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba jako parametr databázi - stačí vybrat konstantu z App_Kontext
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vesechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE, self::NAZEV_MNOZNE, TRUE, App_Kontext::PERSONAL_SERVICE);            
//            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_JENOTNE, self::NAZEV_MNOZNE);                
	}
}