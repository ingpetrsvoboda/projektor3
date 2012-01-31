<?php
class Stranka_StafferPoziceM extends Stranka_FlatTableM
{
	const NAZEV_FLAT_TABLE = "staffer_pozice";
        const NAZEV_JENOTNE = "StafferPoziceJ";
        const NAZEV_MNOZNE = "StafferPoziceM";
	
        public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba vytvořit databázový handler a předat ho jako parametr $dbh
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vesechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            $dbh = App_Kontext::getDbMySQLPersonalService();
            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_JENOTNE, self::NAZEV_MNOZNE, TRUE, $dbh);
	}

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main($parametry = null)
	{             
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
//            $filtrovaciFormular = $this->filtrovani(self::NAZEV_MNOZNE, $hlavickaTabulky);
//            $formularHTML = $filtrovaciFormular->toHtml();
            return parent::main($parametry, "", $this->filtrovani(self::NAZEV_MNOZNE, $hlavickaTabulky)->toHtml());
	}
        
        public function main°potomek°Stranka_StafferPoziceJ°detail()
        {
            
        }

        protected function generujHlavickuTabulky() 
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                $hlavickaTabulky->pridejSloupec("id", "ID", "id");
                $hlavickaTabulky->pridejSloupec("nazev", "Název", "nazev");
                $hlavickaTabulky->pridejSloupec("aktiv", "Aktivní pozice", "aktiv");
                return $hlavickaTabulky;
                
        }
                    
}