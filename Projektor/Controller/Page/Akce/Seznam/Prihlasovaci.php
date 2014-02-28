<?php
class Projektor_Controller_Page_Akce_Seznam_Prihlasovaci extends Projektor_Controller_Page_Akce_Seznam
{
    const TYP_STRANKY = Projektor_Controller_Page_Generator::TYP_SEZNAM;
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_AkceCollection";

	protected function potomekNeni()
	{
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Výběr akce pro přihlášení");
                $nazevHlavihoObjektu = str_replace("Projektor_Model_", "", $this->strankaRodic->tridaData);
                $akcem = Projektor_Model_Auto_AkceItem::vypisVseProObjekt($nazevHlavihoObjektu, $this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($akcem);
        }

	protected function potomek°Projektor_Controller_Page_Ucastnik_Prihlaseni(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
        }

	protected function prihlasovaci°potomek°Projektor_Controller_Page_Zajemce_Prihlaseni(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
        }

        protected function prihlasovaci°potomek°Projektor_Controller_Page_Ucastnik_Prihlaska(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
//            $this->akceObjektu();
//            $this->akceObjektu°potomekNeni();
//            $this->akceObjektu°vzdy();
        }

        protected function prihlasovaci°potomek°Projektor_Controller_Page_Zajemce_Prihlaska(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
        {
//            $this->akceObjektu();
//            $this->akceObjektu°potomekNeni();
//            $this->akceObjektu°vzdy();
        }

	protected function prihlasovaci°potomek°Projektor_Controller_Page_Ucastnik_Detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
	}

	protected function prihlasovaci°potomek°Projektor_Controller_Page_Zajemce_Detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
	}
//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------

        protected function generujTlacitkaProSeznam($akcej)
        {
            $prihlasovaciStranka = "Projektor_Controller_Page_".$akcej->nazevHlavnihoObjektu."_Prihlasovaci";
            $prihlaseniStranka = "Projektor_Controller_Page_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Akce_Detail", array("id" => $akcej->id, "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Přihlásit na akci", $this->vertex->childUri($prihlasovaciStranka, array("id" => $akcej->id))),
                            new Projektor_Controller_Page_Element_Tlacitko("Seznam přihlášených", $this->vertex->childUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }

	protected function generujTlacitkaProPolozku($akcej)
        {
            $prihlasovaciStranka = "Projektor_Controller_Page_".$akcej->nazevHlavnihoObjektu."_Prihlasovaci";
            $prihlaseniStranka = "Projektor_Controller_Page_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Controller_Page_Element_Tlacitko("Detail", $this->vertex->childUri("Projektor_Controller_Page_Akce_Detail", array("id" => $akcej->id, "zmraz" => 1))),
                            new Projektor_Controller_Page_Element_Tlacitko("Seznam přihlášených", $this->vertex->childUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }
}