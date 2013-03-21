<?php
class Projektor_Stranka_Akce_Seznam_AkceObjektu extends Projektor_Stranka_Akce_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_AkceCollection";

	protected function potomekNeni()
	{
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Seznam akcí na které je přihlášen/a:");
                // seznam je generován pro objekt, se kterým pracuje rodičovská stránka
                $tridaStrankyRodice = $this->uzel->uzelRodic->trida;
                $tridaDataItem = $tridaStrankyRodice::TRIDA_DATA_COLLECTION;
                $item = new $tridaDataItem($this->uzel->parametry["id"]);
                $akceUcastnikaCollection = new Projektor_Data_Auto_AkceCollection();
                $akceUcastnikaCollection->akceObjektu($item);
                $this->generujSeznam($akceUcastnikaCollection);
        }

        protected function potomek°Projektor_Stranka_Ucastnici_Prihlaseni()
        {
        }

        protected function potomek°Projektor_Stranka_Zajemci_Prihlaseni()
        {
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Akce zájemce");
        }

//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------

        protected function generujTlacitkaProSeznam($akcej)
        {
            $prihlaseniStranka = "Projektor_Stranka_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Stranka_Element_Tlacitko("Seznam přihlášených", $this->uzel->potomekUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }

	protected function generujTlacitkaProPolozku($akcej)
        {
            $prihlaseniStranka = "Projektor_Stranka_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Stranka_Element_Tlacitko("Seznam přihlášených", $this->uzel->potomekUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }
}