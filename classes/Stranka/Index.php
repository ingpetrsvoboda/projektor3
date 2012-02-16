<?php
//require_once("classes/PHPTAL.PHP");

class Stranka_Index extends Stranka implements Stranka_Interface
{
	const JMENO = "Stranka_Index";
	const MAIN = "main";
	const SABLONA_MAIN = "index.xhtml";

	public $html;
	public $promenne;

	public static function priprav($cesta)
	{
            		return new self($cesta, __CLASS__);
	}

	public function main($parametry = null)
	{
		return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry);
	}

	protected function main°vzdy()
	{
            $dbh = App_Kontext::getDbMySQLProjektor();
            $kontextUser = App_Kontext::getUserKontext();
            $this->novaPromenna("con",  "Přihlášen uživatel ".$kontextUser->user->username.", což je ".$kontextUser->user->name.".");
            $this->novaPromenna("nadpis", $this->parametry["nadpis"]);
            $this->novaPromenna("zprava", $this->parametry["zprava"]);

            /* Ovladaci tlacitka stranky */
            if ($kontextUser->projekt->kod == "SPZP" OR $kontextUser->projekt->kod == "RNH")
            {    
                $tlacitka = array
                (
                    new Stranka_Element_Tlacitko("Index", "index.php"),
                    new Stranka_Element_Tlacitko("Akce", $this->cestaSem->generujUriDalsi("Stranka_AkceM.main")),
                    new Stranka_Element_Tlacitko("Účastníci", $this->cestaSem->generujUriDalsi("Stranka_Ucastnici.main")),
                    new Stranka_Element_Tlacitko("Typy akce", $this->cestaSem->generujUriDalsi("Stranka_TypyAkce.main")),
                    new Stranka_Element_Tlacitko("Předpoklady", $this->cestaSem->generujUriDalsi("Stranka_Predpoklady.main")),
                    new Stranka_Element_Tlacitko("Prezentace", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceM.main")),
                );
            };
            if ($kontextUser->projekt->kod == "AGP")
            {    
                $tlacitka = array
                (
                    new Stranka_Element_Tlacitko("Index", "index.php"),
                    new Stranka_Element_Tlacitko("Akce", $this->cestaSem->generujUriDalsi("Stranka_AkceM.main")),
                    new Stranka_Element_Tlacitko("Zájemci", $this->cestaSem->generujUriDalsi("Stranka_Zajemci.main", array("hlavniObjekt" => "Zajemci"))),
                    new Stranka_Element_Tlacitko("Typy akce", $this->cestaSem->generujUriDalsi("Stranka_TypyAkce.main")),
                    new Stranka_Element_Tlacitko("Předpoklady", $this->cestaSem->generujUriDalsi("Stranka_Predpoklady.main")),
                    new Stranka_Element_Tlacitko("ISCO", $this->cestaSem->generujUriDalsi("Stranka_ISCOM.main")),
                    new Stranka_Element_Tlacitko("Prezentace", $this->cestaSem->generujUriDalsi("Stranka_PrezentaceM.main")),
                    new Stranka_Element_Tlacitko("Firmy", $this->cestaSem->generujUriDalsi("Stranka_Firmy.main")),
                    new Stranka_Element_Tlacitko("Staffer pozice", $this->cestaSem->generujUriDalsi("Stranka_StafferPoziceM.main")),
                    new Stranka_Element_Tlacitko("Přihlášky zájemců", $this->cestaSem->generujUriDalsi("Stranka_PrihlaskyZajemcu.main"))                    
                );
            };            
            $this->novaPromenna("tlacitka", $tlacitka);
	}

	protected function main°potomekNeni(){}
        
        protected function main°potomek°Stranka_AkceM°main(){}

	protected function main°potomek°Stranka_Ucastnici°main(){}

	protected function main°potomek°Stranka_Zajemci°main(){}
        
        protected function main°potomek°Stranka_TypyAkce°main(){}

	protected function main°potomek°Stranka_Predpoklady°main(){}

	protected function main°potomek°Stranka_ISCOM°main(){}

	protected function main°potomek°Stranka_PrezentaceM°main(){}

	protected function main°potomek°Stranka_Firmy°main(){}        

	protected function main°potomek°Stranka_StafferPoziceM°main(){}  

	protected function main°potomek°Stranka_PrihlaskyZajemcu°main(){} 

	protected function main°potomek°Stranka_PrihlaskaZajemce°detail(){}        
}
        