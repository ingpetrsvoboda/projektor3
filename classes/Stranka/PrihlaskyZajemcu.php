<?php
class Stranka_PrihlaskyZajemcu extends Stranka_FlatTableM
{
	const NAZEV_FLAT_TABLE = "prihlasky_zajemce";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "PrihlaskaZajemce";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "PrihlaskyZajemce";
	
        public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba vytvořit databázový handler a předat ho jako parametr $dbh
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vesechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            $dbh = App_Kontext::getDbMySQLPersonalService();
            return new self($cesta, __CLASS__, self::NAZEV_FLAT_TABLE, self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE, self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE, TRUE, $dbh);
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
            return parent::main($parametry, "", $this->filtrovani(self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE, $hlavickaTabulky)->toHtml());
	}
                
	protected function main°potomek°Stranka_PrihlaskaZajemce°detail()
        {
            parent::main°potomek°Stranka_FlatTableJ°detail();
        }        

        protected function generujHlavickuTabulky() 
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
                $hlavickaTabulky->pridejSloupec("id", "ID", "id");
                $hlavickaTabulky->pridejSloupec("jmeno", "Jméno", "jmeno");
                $hlavickaTabulky->pridejSloupec("prijmeni", "Příjmení", "prijmeni");
                $hlavickaTabulky->pridejSloupec("titul", "Titul", "titul");
                $hlavickaTabulky->pridejSloupec("obec", "Obec", "obec");
                $hlavickaTabulky->pridejSloupec("sdeleni", "Sdělení", "sdeleni");                
                $hlavickaTabulky->pridejSloupec("id_c_region_FK", "idCRegion", 
                            "id_c_region_FK", "Data_Ciselnik::vypisVse('region', '', '', '', '', TRUE, TRUE, 'PersonalService')",
                            "Data_Ciselnik::najdiPodleId('region', %ID%, TRUE, TRUE, 'PersonalService')","text");
                return $hlavickaTabulky;
                
        }
                    
}