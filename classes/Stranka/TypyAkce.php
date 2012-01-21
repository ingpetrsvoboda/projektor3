<?php
class Stranka_TypyAkce extends Stranka implements Stranka_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

	/*
         *  ~~~~~~~~MAIN~~~~~~~~
         */
	public function main($parametry = null)
	{ 
		return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry);
	}

	protected function main°vzdy()
	{
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Typy akce");

                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Stranka_Element_Tlacitko("Zpět", $this->cestaSem->generujUriZpet()),
			new Stranka_Element_Tlacitko("Nový typ akce", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail")),
		);
                $this->novaPromenna("tlacitka", $tlacitka);

                /* Hlavicka tabulky */
		$hlavickaTabulky = new Stranka_Element_Hlavicka($this->cestaSem);
		$hlavickaTabulky->pridejSloupec("ID", Data_Seznam_STypAkce::ID);
		$hlavickaTabulky->pridejSloupec("Název", Data_Seznam_STypAkce::NAZEV);
                $hlavickaTabulky->pridejSloupec("Trvání dní", Data_Seznam_STypAkce::TRVANI_DNI);
                $hlavickaTabulky->pridejSloupec("Hodiny za den", Data_Seznam_STypAkce::HODINY_ZA_DEN);
		$hlavickaTabulky->pridejSloupec("Minimální počet účastníků", Data_Seznam_STypAkce::MIN_POCET_UC);
		$hlavickaTabulky->pridejSloupec("Maximální počet účastníků", Data_Seznam_STypAkce::MAX_POCET_UC);
		$this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
	}

	protected function main°potomekNeni()
	{
		$typyakce = Data_Seznam_STypAkce::vypisVse("", $this->parametry["razeniPodle"], $this->parametry["razeni"]);
		foreach($typyakce as $typakce)
		{
			$typakce->odkaz = $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "zmraz" => 1));
			$typakce->tlacitka = array
			(
				new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "zmraz" => 1))),
				new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id))),
                                new Stranka_Element_Tlacitko("Smazat", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typakce->id, "smaz" => 1))),
                                new Stranka_Element_Tlacitko("Předpoklady", $this->cestaSem->generujUriDalsi("Stranka_Predpoklady.proTypAkce", array("id_typ_akce" => $typakce->id))),
			);
                            $aa = $aa;
		}

                $this->novaPromenna("seznam", $typyakce);

	}

	protected function main°potomek°Stranka_TypAkce°detail()
	{ 
		$this->kompaktniZobrazeni("id");
	}

        protected function main°potomek°Stranka_Predpoklady°proTypAkce()
	{
		$this->kompaktniZobrazeni("id_typ_akce");
	}
	
	private function kompaktniZobrazeni($nazevID)
	{
		if($this->dalsi->parametry[$nazevID])
		{
			$typyakce[] = Data_Seznam_STypAkce::najdiPodleId($this->dalsi->parametry[$nazevID]);
			$typyakce[0]->tlacitka = array
			(
				new Stranka_Element_Tlacitko("Detail", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typyakce[0]->id, "zmraz" => 1))),
				new Stranka_Element_Tlacitko("Upravit", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typyakce[0]->id))),
                                new Stranka_Element_Tlacitko("Smazat", $this->cestaSem->generujUriDalsi("Stranka_TypAkce.detail", array("id" => $typyakce[0]->id, "smaz" => 1))),
                                new Stranka_Element_Tlacitko("Předpoklady", $this->cestaSem->generujUriDalsi("Stranka_Predpoklady.proTypAkce", array("id_typ_akce" => $typyakce[0]->id))),
			);
			
			
			$this->novaPromenna("seznam", $typyakce);
		}
	}
	
	

}