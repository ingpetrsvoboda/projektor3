<?php
class Projektor_Controller_Page_ISCO_Seznam extends Projektor_Controller_Page_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_SISCOCollection";

	protected function vzdy()
	{
                $this->setViewContextValue("jsFilePath1", "js/ajax_request.js");
                $this->setViewContextValue("jsFilePath2", "js/isco_vyhledani.js");
                parent::vzdy();
	}

	protected function potomekNeni()
	{
                /* Nadpis stranky */
                $uroven = strlen($this->vertex->params["prefixKodu"]) + 1;
                $this->setViewContextValue("nadpis", "Seznam ISCO úrovně ".$uroven);
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
                        new Projektor_Controller_Page_Element_Tlacitko("Vyhledání ISCO", $this->vertex->childUri("Projektor_Controller_Page_ISCOM_Seznam_Vyhledani"))
		);
                $this->setViewContextValue("tlacitka", $tlacitka);
                $this->generujSeznam();
	}

        public function potomek°Projektor_Controller_Page_ISCO_Seznam(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
//                        $kod = str_replace(" ", "", substr($jednoisco->kod, 0, 4));
//            $jednoisco->odkaz = $this->uzel->potomekUri("Projektor_Controller_Page_ISCOM_Seznam", array("id" => $jednoisco->id, "prefixKodu" => $kod));
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Položka seznamu ISCO úrovně ".strlen($uzelPotomek->params["prefixKodu"]));
		$this->generujPolozku($uzelPotomek);
        }

        public function potomek°Projektor_Controller_Page_ISCO_Detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Detail položky seznamu ISCO");
		$this->generujPolozku($uzelPotomek);
	}

        protected function potomek°Projektor_Controller_Page_ISCO_Seznam_Vyhledani()
        {
                $this->setViewContextValue("nadpis", "Vyhledávání v seznamu ISCO");
        }


        protected function generujSeznam()
        {
                $hlavickaTabulky = $this->generujHlavickuTabulky();
                $this->setViewContextValue("hlavickaTabulky", $hlavickaTabulky);
                $prefix = $this->vertex->params["prefixKodu"];
                // vyhledává všechny kódy začínající na prefix a mající kód o jeden znak delší než prefix
                $delkaPrefixu = strlen($prefix);
                if ($delkaPrefixu < 4)
                {
                    $delkaKodu = $delkaPrefixu + 1;
                    // odkaz na tlčítku je vytvořen tak, že se volá metoda s názvem "ISCOx", kde x je číslo o jednu větší než $delkaKodu
                    // nadpis na tlačítku je vytvořen z textu Úroveň a čísla $delkaKodu
                    if ($prefix)
                    {
                        $seznamisco = Projektor_Model_Seznam_SISCO::vypisVse("LENGTH(`".Projektor_Model_Seznam_SISCO::KOD."`)=".$delkaKodu." AND LEFT(`".Projektor_Model_Seznam_SISCO::KOD."`, ".$delkaPrefixu.")='".$prefix."'", $this->vertex->params["razeniPodle"], $this->vertex->params["razeni"]);
                    } else {
                        $seznamisco = Projektor_Model_Seznam_SISCO::vypisVse("LENGTH(".Projektor_Model_Seznam_SISCO::KOD.")=1", $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                    }
                    if ($seznamisco)
                    {
                        foreach($seznamisco as $jednoisco)
                        {
                            $this->dejSeznamItemZHlavicky($jednoisco, $hlavickaTabulky);
                            $this->generujTlacitkaProSeznam($jednoisco);
                        }
                        $this->setViewContextValue("seznam", $seznamisco);
                    } else {
                        $this->setViewContextValue("zprava", "Nic nenalezeno!");
                    }
                } else {
                    $seznamisco = Projektor_Model_Seznam_SISCO::vypisVse("LENGTH(`".Projektor_Model_Seznam_SISCO::KOD."`)=5 AND LEFT(`".Projektor_Model_Seznam_SISCO::KOD."`, 4)='".$prefix."'", $this->vertex->params["razeniPodle"], $this->vertex->params["razeni"]);
                    if ($seznamisco)
                    {
                        foreach($seznamisco as $jednoisco)
                        {
                            $this->dejSeznamItemZHlavicky($jednoisco, $hlavickaTabulky);
                            $this->generujTlacitkaProPolozku($jednoisco);
                        }
                        $this->setViewContextValue("seznam", $seznamisco);
                    } else {
                        $this->setViewContextValue("zprava", "Nic nenalezeno!");
                    }
                }
        }

        protected function generujTlacitkaProSeznam($jednoisco)
        {
            $kod = str_replace(" ", "", substr($jednoisco->kod, 0, 4));
            $jednoisco->odkaz = $this->vertex->childUri("Projektor_Controller_Page_ISCO_Seznam", array("id" => $jednoisco->id, "prefixKodu" => $kod));
            $jednoisco->tlacitka = array
                (
                    new Projektor_Controller_Page_Element_Tlacitko("Úroveň ".(strlen($kod)+1), $jednoisco->odkaz),
                );
        }

	protected function generujTlacitkaProPolozku($jednoisco)
        {
            $jednoisco->tlacitka = array
                (
                        new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_ISCO_Detail", array("id" => $jednoisco->id, "zmraz" => 1))),
                );
        }

        protected function generujHlavickuTabulky($tridaData)
        {
                /* Hlavicka tabulky */
		$hlavickaTabulky = new Projektor_Controller_Page_Element_Hlavicka($tridaData, $this);
//		$hlavickaTabulky->pridejSloupec("id", "ID", Projektor_Model_Seznam_SISCO::ID);
		$hlavickaTabulky->pridejSloupec("kod", "kód");
                $hlavickaTabulky->pridejSloupec("nazev", "Název");

                return $hlavickaTabulky;
        }

}