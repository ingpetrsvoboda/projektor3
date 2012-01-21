<?php
/**
 * Kontejner na statickou hlavičku a patičku PDF stránky
 * @author Petr Svoboda
 *
 */
abstract class PDF_Kontext
{
	private static $hlava;
	private static $pata;
	private static $deb;
	
	/**
	 * Statická funkce, pči prvním volání vytvoří nový objekt PDF_Hlavicka, při každém dalším volání vrací již jednou vytvořený objekt
	 * @return PDF_Hlavicka
	 */
	public static function dejHlavicku()
	{
		if(!self::$hlava)
			self::$hlava = new PDF_Hlavicka();
		
		return self::$hlava;
	}

    /**
     * Statická funkce, pči prvním volání vytvoří nový objekt PDF_Paticka, při každém dalším volání vrací již jednou vytvořený objekt
     * @return PDF_Paticka
     */
	public static function dejPaticku()
	{
		if(!self::$pata)
			self::$pata = new PDF_Paticka();
		
		return self::$pata;
	}
	
    public static function dejDebug()
    {
        if(!self::$deb)
            self::$deb = new PDF_Debug();
        
        return self::$deb;
    }	
}
