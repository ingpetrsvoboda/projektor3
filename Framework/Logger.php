<?php
/**
 * Logger loguje do paměti (zapisuje do pole, které je svlastností instance).
 * @author Petr Svoboda
 */
class Framework_Logger
{
	private static $log = array();

        /**
         * Metoda vrací obsah logu ve formě pole. Každá položka pole obsahuje jeden zápis do logu. Každá položka obsahuje to, 
         * co do ní bylo zapsáno. Pokud byly zapsány proměnné různých typů, obsahují položky proměnné těch typů, které byly zapsány.
         * @return array
         */
	public static function getLogArray()
	{
		return self::$log;
	}
        
        /**
         * Metoda vrací obsah logu ve formě textu. Pro převod obsahu do textu je používána funkce print_r(). 
         * @return string
         */
	public static function getLogText()
	{
		return print_r(self::$log, TRUE);
	}        

        /**
         * Vymaže log.
         * @return array Vrací log ve formě pole, tato metoda vrací pole po smazání, tedy vždy prázdné pole.
         */
        public static function resetLog()
	{
		self::$log = NULL;
		return self::$log;
	}

        /**
         * Zápis jednoho záznamu do logu. Metoda přijímá argumenty, které lze převést do čitelné podoby.
         * @param mixed $zaznam Proměnná typu scalar nebo objekt nebo pole
         * @return boolean
         */
        public static function setLog($zaznam = NULL)
	{
            if ($zaznam AND (is_scalar($zaznam) OR is_object($zaznam) OR is_array($zaznam))) {
                self::$log[] = $zaznam;
                return TRUE;
            } else {
                return FALSE;
            }
	}

}
?>
