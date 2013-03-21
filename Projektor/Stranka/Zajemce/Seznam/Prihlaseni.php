<?php
class Projektor_Stranka_Zajemce_Seznam_Prihlaseni extends Projektor_Stranka_Zajemce_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_ZajemceCollection";

    protected function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->novaPromenna("nadpis", "Zájemci přihlášení na akci");
    }

    public function dejCollection()
    {
        // seznam je generován pro objekt, se kterým pracuje rodičovská stránka
        $tridaStrankyRodice = $this->uzel->uzelRodic->trida;
        $tridaDataItem = $tridaStrankyRodice::TRIDA_DATA_ITEM;
        $item = new $tridaDataItem($this->uzel->parametry["id"]);
        $zajemciCollection = new Projektor_Data_Auto_ZajemceCollection();
        $zajemciCollection->zajemciPrihlaseniNaAkci($item);
        return $zajemciCollection;
    }

    protected function potomek°Projektor_Stranka_Zajemce°detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
    {
        $this->generujPolozkuSTlacitky($uzelPotomek);
    }

    protected function potomek°Projektor_Stranka_AkceM°akceObjektu(Projektor_Dispatcher_Uzel $uzelPotomek = null)
    {
        $this->generujPolozkuSTlacitky($uzelPotomek);
    }
}