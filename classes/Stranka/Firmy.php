<?php
class Stranka_Firmy extends Stranka_FlatTableM
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
            $stranka->vsechny_radky = FALSE;
            
            return $stranka;
	}

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main($parametry = null)
	{
            
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani(self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE, $hlavickaTabulky);
            $formularHTML = $filtrovaciFormular->toHtml();
            return parent::main($parametry, "", $formularHTML);
	}
        
        public function main°potomek°Stranka_Firma°detail()
        {
            //použij univrzální metodu třídy FlatTableM pro potomky Stranka_FlatTableJ°detail
            parent::main°potomek°Stranka_FlatTableJ°detail();
        }

        protected function generujHlavickuTabulky() 
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                $hlavickaTabulky->pridejSloupec("id", "ID", "id");
                $hlavickaTabulky->pridejSloupec("ico", "IČO", "ico");
                $hlavickaTabulky->pridejSloupec("nazev_firmy", "Název firmy", "nazev_firmy");
                $hlavickaTabulky->pridejSloupec("obec", "Obec", "obec");
                return $hlavickaTabulky;
        }
                    
}