 <?php
class Stranka_Firma extends Stranka_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "s_firma";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "Firma";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "Firmy";
        
	public static function priprav($cesta)
	{
            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE, self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE);                
	}
}