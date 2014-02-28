<?php
class Projektor_Controller_Page_CRMFirmy extends Projektor_Controller_Page_FlatTable_Seznam
{
	const NAZEV_FLAT_TABLE = "s_crm_firma";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "CRMFirma";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "CRMFirmy";

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main()
	{
            //tato třida stranka používá data z jine databáze, je třeba jako parametr databázi - stačí vybrat konstantu z Framework_Config
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vsechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            $this->databaze = Framework_Config::DATABAZE_CRM;
            $this->nazev_flattable = self::NAZEV_FLAT_TABLE;
            $this->nazev_jednotne = self::NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE;
            $this->nazev_mnozne = self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE;
            $this->vsechny_radky = TRUE;
            /* Vygenerovani filtrovaciho formulare */
            $hlavickaTabulky = $this->generujHlavickuTabulky();
            $filtrovaciFormular = $this->filtrovani(self::NAZEV_DATOVEHO_OBJEKTU_MNOZNE, $hlavickaTabulky);
            $this->novaPromenna("filtrovaciFormular", $filtrovaciFormular->toHtml());
            return parent::main();
	}

        public function main°potomek°Projektor_Controller_Page_Firma°detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
            //použij univrzální metodu třídy FlatTableM pro potomky Projektor_Controller_Page_FlatTableJ°detail
            parent::main°potomek°Projektor_Controller_Page_FlatTableJ°detail($uzelPotomek);
        }

        protected function generujHlavickuTabulky($tridaData)
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
                $hlavickaTabulky->pridejSloupec("id", "ID", "id");
                $hlavickaTabulky->pridejSloupec("nazev", "Název firmy", "nazev");
                return $hlavickaTabulky;
        }

}