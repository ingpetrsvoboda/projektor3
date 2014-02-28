 <?php
class Projektor_Controller_Page_PrihlaskaZajemce extends Projektor_Controller_Page_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "prihlasky_zajemce";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "PrihlaskaZajemce";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "PrihlaskyZajemce";
        
	public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba jako parametr databázi - stačí vybrat konstantu z Framework_Kontext
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vesechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu

            $stranka = new self($cesta, __CLASS__);
            $stranka->databaze = Framework_Config::DATABAZE_PERSONAL_SERVICE;
            $stranka->nazev_flattable = self::NAZEV_FLAT_TABLE;
            $stranka->nazev_jednotne = self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE;
            $stranka->nazev_mnozne = self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE;
            $stranka->vsechny_radky = TRUE;
            
            return $stranka;            
	}
}