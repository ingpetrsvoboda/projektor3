<?php
/**
 * Kontejner na statickou hlavičku a patičku PDF stránky
 * @author Petr Svoboda
 *
 */
abstract class Projektor_Pdf_Kontext
{
	private static $hlava;
	private static $pata;
	private static $deb;
	
	/**
	 * Statická funkce, pči prvním volání vytvoří nový objekt Projektor_Pdf_Hlavicka, při každém dalším volání vrací již jednou vytvořený objekt
	 * @return Projektor_Pdf_Hlavicka
	 */
	public static function dejHlavicku()
	{
		if(!self::$hlava)
			self::$hlava = new Projektor_Pdf_Hlavicka();
		
		return self::$hlava;
	}

    /**
     * Statická funkce, pči prvním volání vytvoří nový objekt Projektor_Pdf_Paticka, při každém dalším volání vrací již jednou vytvořený objekt
     * @return Projektor_Pdf_Paticka
     */
	public static function dejPaticku()
	{
		if(!self::$pata)
			self::$pata = new Projektor_Pdf_Paticka();
		
		return self::$pata;
	}
	
    public static function dejDebug()
    {
        if(!self::$deb)
            self::$deb = new Projektor_Pdf_Debug();
        
        return self::$deb;
    }	
}
