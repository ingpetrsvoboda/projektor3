<?php
class Projektor_Controller_Page_Akce_Seznam_AkceObjektu extends Projektor_Controller_Page_Akce_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_AkceCollection";

	protected function potomekNeni()
	{
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Seznam akcí na které je přihlášen/a:");
                // seznam je generován pro objekt, se kterým pracuje rodičovská stránka
                $tridaStrankyRodice = $this->vertex->parentVertexDispatcher->controllerClassName;
                $tridaDataItem = $tridaStrankyRodice::TRIDA_Model_COLLECTION;
                $item = new $tridaDataItem($this->vertex->params["id"]);
                $akceUcastnikaCollection = new Projektor_Model_Auto_AkceCollection();
                $akceUcastnikaCollection->vyberAkceObjektu($item);
                $this->generujSeznam($akceUcastnikaCollection);
        }

        protected function potomek°Projektor_Controller_Page_Ucastnici_Prihlaseni()
        {
        }

        protected function potomek°Projektor_Controller_Page_Zajemci_Prihlaseni()
        {
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Akce zájemce");
        }

//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------

        protected function generujTlacitkaProSeznam($akcej)
        {
            $prihlaseniStranka = "Projektor_Controller_Page_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Controller_Page_Element_Tlacitko("Seznam přihlášených", $this->vertex->childUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }

	protected function generujTlacitkaProPolozku($akcej)
        {
            $prihlaseniStranka = "Projektor_Controller_Page_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Controller_Page_Element_Tlacitko("Seznam přihlášených", $this->vertex->childUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }
}