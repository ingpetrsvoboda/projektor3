<?php
class Projektor_Stranka_CRMFirmy extends Projektor_Stranka_FlatTable_Seznam
{
	const NAZEV_FLAT_TABLE = "s_crm_firma";
        const NAZEV_DATOVEHO_OBJEKTU_JEDNOTNE = "CRMFirma";
        const NAZEV_DATOVEHO_OBJEKTU_MNOZNE = "CRMFirmy";

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main()
	{
            //tato třida stranka používá data z jine databáze, je třeba jako parametr databázi - stačí vybrat konstantu z Projektor_App_Config
            //používaná tabulka v databázi self::NAZEV_FLAT_TABLE nemá sloupec valid, je třeba jako parametr vsechny_radky zadat hodnoty TRUE,
            //pak se ve filtru nepoužije klauzule WHERE valid=1, která by způsobila chybu
            $this->databaze = Projektor_App_Config::DATABAZE_CRM;
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

        public function main°potomek°Projektor_Stranka_Firma°detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
            //použij univrzální metodu třídy FlatTableM pro potomky Projektor_Stranka_FlatTableJ°detail
            parent::main°potomek°Projektor_Stranka_FlatTableJ°detail($uzelPotomek);
        }

        protected function generujHlavickuTabulky()
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($this->uzel);
                $hlavickaTabulky->pridejSloupec("id", "ID", "id");
                $hlavickaTabulky->pridejSloupec("nazev", "Název firmy", "nazev");
                return $hlavickaTabulky;
        }

}