 <?php
class Projektor_Controller_Page_StafferPoziceJ extends Projektor_Controller_Page_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "staffer_pozice";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "StafferPoziceJ";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "StafferPoziceM";
        
	public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba vytvořit databázový handler a předat ho jako parametr $dbh
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