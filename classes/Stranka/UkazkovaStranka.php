<?php
/**
 * Template pro vytvareni novych stranek.
 *
 * Zakomentovane casti jsou urcene k doplneni vlastnim kodem. Zivy priklad viz. AkceM.php
 *
 * @author Marek Petko
 *
 */

class Stranka_/*jmeno tridy*/ extends Stranka implements Stranka_Interface
{
	const JMENO = "Stranka_/*jmeno tridy*/";

	/* Zde doplnit konstanty se jmeny metod tridy */
	const MAIN = "main";

	/* Zde doplnit konstanty s cestami k sablonam */
	const SABLONA_MAIN = "/*jmeno tridy*/./*jmeno metody*/.xhtml";

	public static function priprav($cesta)
	{
		return new self($cesta);
	}

	public function main($parametry = null)
	{
		/* <NESAHAT> */
		$this->sablona(self::SABLONA_MAIN); // nacteni template
		$this->parametry = $parametry;   	  // globalizujeme parametry
		if($this->dalsi)					// pokud mame nejakeho potomka
		$this->pripojPotomka();			// tak ho pripojime

		/* Volame privatni metodu, ktera nam generuje obsah, ktery zobrazujeme vzdy, bez ohledu na potomka
		 * do teto metody doplnime kod ktery je treba provadet vzdy
		 */
		$this->vzdy();
		/* </NESAHAT> */

		/* Tento switch rozhoduje pouze o tom co bude stranka obsahovat pro ruzne potomky. */
		switch($this->dalsi->trida)  // jakou tridu potomka sme pripojili
		{
			case nazev_tridy_potomka_jako_konstanta :
				switch($this->dalsi->metoda) // jakou metodu potomka sme pripojili
				{
					case nazev_metody_potomka_jako_konstanta :
						$this->nazev_privatni_metody_teto_tridy_ktera_popisuje_jaka_data_zobrazim_na_teto_strance_pokud_je_potomek_tento();
						break;
				}
				break;

				//neni zadny dalsi potomek
			default:
				$this->potomekNeni();
				break;
		}

		return $this;
	}

	/**
	 * Privatni metoda, ktera se provede vzdy bez ohledu na potomka.
	 */
	private function vzdy()
	{

	}

	/**
	 * privatni metoda ktera se provede pokud potomek neni
	 */
	private function potomekNeni()
	{
		$akcem = Data_Akce::vypisVse();
		foreach($akcem as $akcej)
		$akcej->odkaz = $this->cesta->generujUriDalsi("Stranka_AkceJ.detail", array("id" => $akcej->id))/*"test.php?cesta=akcem~akcej.detail({$akcej->id})"*/;
		$this->promenne["akcem_seznam"] = $akcem;
	}

	/**
	 *
	 */
	private function potomek/**/()
	{
		$akcem[] = Data_Akce::najdiPodleId($this->dalsi->parametry["id"]);
		$this->promenne["akcem_seznam"] = $akcem;
	}

}