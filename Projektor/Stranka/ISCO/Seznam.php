<?php
class Projektor_Stranka_ISCO_Seznam extends Projektor_Stranka_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_SISCOCollection";

	protected function vzdy()
	{
                $this->novaPromenna("jsFilePath1", "js/ajax_request.js");
                $this->novaPromenna("jsFilePath2", "js/isco_vyhledani.js");
                parent::vzdy();
	}

	protected function potomekNeni()
	{
                /* Nadpis stranky */
                $uroven = strlen($this->uzel->parametry["prefixKodu"]) + 1;
                $this->novaPromenna("nadpis", "Seznam ISCO úrovně ".$uroven);
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
                        new Projektor_Stranka_Element_Tlacitko("Vyhledání ISCO", $this->uzel->potomekUri("Projektor_Stranka_ISCOM_Seznam_Vyhledani"))
		);
                $this->novaPromenna("tlacitka", $tlacitka);
                $this->generujSeznam();
	}

        public function potomek°Projektor_Stranka_ISCO_Seznam(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
//                        $kod = str_replace(" ", "", substr($jednoisco->kod, 0, 4));
//            $jednoisco->odkaz = $this->uzel->potomekUri("Projektor_Stranka_ISCOM_Seznam", array("id" => $jednoisco->id, "prefixKodu" => $kod));
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Položka seznamu ISCO úrovně ".strlen($uzelPotomek->parametry["prefixKodu"]));
		$this->generujPolozku($uzelPotomek);
        }

        public function potomek°Projektor_Stranka_ISCO_Detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Detail položky seznamu ISCO");
		$this->generujPolozku($uzelPotomek);
	}

        protected function potomek°Projektor_Stranka_ISCO_Seznam_Vyhledani()
        {
                $this->novaPromenna("nadpis", "Vyhledávání v seznamu ISCO");
        }


        protected function generujSeznam()
        {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->novaPromenna("hlavickaTabulky", $hlavickaTabulky);
                $prefix = $this->uzel->parametry["prefixKodu"];
                // vyhledává všechny kódy začínající na prefix a mající kód o jeden znak delší než prefix
                $delkaPrefixu = strlen($prefix);
                if ($delkaPrefixu < 4)
                {
                    $delkaKodu = $delkaPrefixu + 1;
                    // odkaz na tlčítku je vytvořen tak, že se volá metoda s názvem "ISCOx", kde x je číslo o jednu větší než $delkaKodu
                    // nadpis na tlačítku je vytvořen z textu Úroveň a čísla $delkaKodu
                    if ($prefix)
                    {
                        $seznamisco = Projektor_Data_Seznam_SISCO::vypisVse("LENGTH(`".Projektor_Data_Seznam_SISCO::KOD."`)=".$delkaKodu." AND LEFT(`".Projektor_Data_Seznam_SISCO::KOD."`, ".$delkaPrefixu.")='".$prefix."'", $this->uzel->parametry["razeniPodle"], $this->uzel->parametry["razeni"]);
                    } else {
                        $seznamisco = Projektor_Data_Seznam_SISCO::vypisVse("LENGTH(".Projektor_Data_Seznam_SISCO::KOD.")=1", $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                    }
                    if ($seznamisco)
                    {
                        foreach($seznamisco as $jednoisco)
                        {
                            $this->dejSeznamItemZHlavicky($jednoisco, $hlavickaTabulky);
                            $this->generujTlacitkaProSeznam($jednoisco);
                        }
                        $this->novaPromenna("seznam", $seznamisco);
                    } else {
                        $this->novaPromenna("zprava", "Nic nenalezeno!");
                    }
                } else {
                    $seznamisco = Projektor_Data_Seznam_SISCO::vypisVse("LENGTH(`".Projektor_Data_Seznam_SISCO::KOD."`)=5 AND LEFT(`".Projektor_Data_Seznam_SISCO::KOD."`, 4)='".$prefix."'", $this->uzel->parametry["razeniPodle"], $this->uzel->parametry["razeni"]);
                    if ($seznamisco)
                    {
                        foreach($seznamisco as $jednoisco)
                        {
                            $this->dejSeznamItemZHlavicky($jednoisco, $hlavickaTabulky);
                            $this->generujTlacitkaProPolozku($jednoisco);
                        }
                        $this->novaPromenna("seznam", $seznamisco);
                    } else {
                        $this->novaPromenna("zprava", "Nic nenalezeno!");
                    }
                }
        }

        protected function generujTlacitkaProSeznam($jednoisco)
        {
            $kod = str_replace(" ", "", substr($jednoisco->kod, 0, 4));
            $jednoisco->odkaz = $this->uzel->potomekUri("Projektor_Stranka_ISCO_Seznam", array("id" => $jednoisco->id, "prefixKodu" => $kod));
            $jednoisco->tlacitka = array
                (
                    new Projektor_Stranka_Element_Tlacitko("Úroveň ".(strlen($kod)+1), $jednoisco->odkaz),
                );
        }

	protected function generujTlacitkaProPolozku($jednoisco)
        {
            $jednoisco->tlacitka = array
                (
                        new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_ISCO_Detail", array("id" => $jednoisco->id, "zmraz" => 1))),
                );
        }

        protected function generujHlavickuTabulky($tridaData)
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Stranka_Element_Hlavicka($tridaData, $this->uzel);
//		$hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Data_Seznam_SISCO::ID);
		$hlavickaTabulky->pridejSloupec("kod", "kód");
                $hlavickaTabulky->pridejSloupec("nazev", "Název");

                return $hlavickaTabulky;
        }

}