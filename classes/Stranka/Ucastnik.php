 <?php
class Stranka_Ucastnik extends Stranka_HlavniObjekt
{
        const HLAVNI_OBJEKT = "Ucastnik";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

        public function detail($parametry)
        {
            return parent::detail(self::HLAVNI_OBJEKT, $parametry);
        }
        
    /* prihlaska */
	public function prihlaska($parametry = null)
	{
		return $this->vytvorStranku("prihlaska", self::SABLONA_DETAIL, $parametry);
	}

	protected function prihlaska°vzdy()
	{
		$akce = Data_Akce::najdiPodleId($this->parametry["id_akce"]);
                $this->novaPromenna("nadpis", "Přihlášení účastníka na akci");

                try
		{
                    //TODO: Nedodělek - pevně zadaný parametr 2!
			$akce->prihlas(Data_Ucastnik::najdiPodleId($this->parametry["id"]), Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));
                        $this->novaPromenna("hlaseni", "Prihlaseni bylo uspesne!");
		}
		catch(Exception $e)
		{
                        $this->novaPromenna("hlaseni", $e->getMessage());
		}

		$this->detail°vzdy();
	}

	protected function prihlaska°potomekNeni()
	{
		$this->detail°potomekNeni();
	}
                

}