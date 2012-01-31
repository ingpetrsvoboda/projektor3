 <?php
class Stranka_Firma extends Stranka_FlatTableJ
{
	const NAZEV_FLAT_TABLE = "s_firma";
        const NAZEV_JENOTNE = "Firma";
        const NAZEV_MNOZNE = "Firmy";
        
	public static function priprav($cesta)
	{
            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_JENOTNE, self::NAZEV_MNOZNE);                
	}
}