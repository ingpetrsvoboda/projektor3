<?php
class Stranka_CRMFirmy extends Stranka_FlatTableM
{
	const NAZEV_FLAT_TABLE = "s_crm_firma";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "CRMFirma";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "CRMFirmy";
	
        public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba jako parametr databázi - stačí vybrat konstantu z App_Kontext
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vsechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            $stranka = new self($cesta, __CLASS__);
            $stranka->databaze = App_Config::DATABAZE_CRM;
            $stranka->nazev_flattable = self::NAZEV_FLAT_TABLE;
            $stranka->nazev_jednotne = self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE;
            $stranka->nazev_mnozne = self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE;
            $stranka->vsechny_radky = TRUE;
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
                $hlavickaTabulky->pridejSloupec("nazev", "Název firmy", "nazev");
                return $hlavickaTabulky;
        }
                    
}