 <?php
class Stranka_Firma extends Stranka_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "s_firma";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "Firma";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "Firmy";
        
	public static function priprav($cesta)
	{
            $stranka = new self($cesta, __CLASS__);
            $stranka->databaze = App_Config::DATABAZE_PROJEKTOR;
            $stranka->nazev_flattable = self::NAZEV_FLAT_TABLE;
            $stranka->nazev_jednotne = self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE;
            $stranka->nazev_mnozne = self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE;
            $stranka->vsechny_radky = TRUE;
        }
}