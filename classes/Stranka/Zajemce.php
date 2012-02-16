 <?php
class Stranka_Zajemce extends Stranka_HlavniObjekt
{
        const HLAVNI_OBJEKT = "Zajemce";

//TODO: Stranka_Zajemce a Stranka_Ucastnik -> upravit na jednu univerzální třídu Stranka_Osoba (??)        
	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

        public function detail($parametry)
        {
            return parent::detail(self::HLAVNI_OBJEKT, $parametry);
        }
        
    /* prihlaseni */
	public function prihlaseni($parametry = null)
	{
		return $this->vytvorStranku("prihlaseni", self::SABLONA_DETAIL, $parametry);
	}

	protected function prihlaseni°vzdy()
	{
		$akce = Data_Akce::najdiPodleId($this->parametry["id_akce"]);
                $this->novaPromenna("nadpis", "Přihlášení na akci");

                try
		{
                    //TODO: Nedodělek - pevně zadaný parametr 2!
			$akce->prihlas(Data_Zajemce::najdiPodleId($this->parametry["id"]), Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));
                        $this->novaPromenna("prihlaseni_na_akci", "Prihlaseni bylo uspesne!");
		}
		catch(Exception $e)
		{
                        $this->novaPromenna("prihlaseni_na_akci", $e->getMessage());
		}

		$this->detail°vzdy();
	}

	protected function prihlaseni°potomekNeni()
	{
		$this->detail°potomekNeni();
	}

}