<?php
class Stranka_PrihlaskyZajemcu extends Stranka_FlatTableM
{
	const NAZEV_FLAT_TABLE = "prihlasky_zajemce";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "PrihlaskaZajemce";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "PrihlaskyZajemce";
	
        public static function priprav($cesta)
	{
            //tato třida stranka používá data z jine databáze, je třeba vytvořit vlastnost databáze
            //používaná db tabulka self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako vlastnost vesechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu            
            $stranka = new self($cesta, __CLASS__);
            $stranka->databaze = App_Config::DATABAZE_PERSONAL_SERVICE;
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
                            "id_c_region_FK", "Data_Ciselnik::vypisVse(App_Config::DATABAZE_PERSONAL_SERVICE, 'region', '', '', '', '', TRUE, TRUE)",
                            "Data_Ciselnik::najdiPodleId(App_Config::DATABAZE_PERSONAL_SERVICE, 'region', %ID%, TRUE, TRUE)","text");
                return $hlavickaTabulky;
                
        }
                    
}