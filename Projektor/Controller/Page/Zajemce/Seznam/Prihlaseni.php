<?php
class Projektor_Controller_Page_Zajemce_Seznam_Prihlaseni extends Projektor_Controller_Page_Zajemce_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_ZajemceCollection";

    protected function potomekNeni()
    {
        parent::potomekNeni();
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Zájemci přihlášení na akci");
    }

    public function dejCollection()
    {
        // seznam je generován pro objekt, se kterým pracuje rodičovská stránka
        $tridaStrankyRodice = $this->vertex->parentVertexDispatcher->controllerClassName;
        $tridaDataItem = $tridaStrankyRodice::TRIDA_Model_ITEM;
        $item = new $tridaDataItem($this->vertex->params["id"]);
        $zajemciCollection = new Projektor_Model_Auto_ZajemceCollection();
        $zajemciCollection->zajemciPrihlaseniNaAkci($item);
        return $zajemciCollection;
    }

    protected function potomek°Projektor_Controller_Page_Zajemce°detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
    {
        $this->generujPolozkuSTlacitky($uzelPotomek);
    }

    protected function potomek°Projektor_Controller_Page_AkceM°akceObjektu(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
    {
        $this->generujPolozkuSTlacitky($uzelPotomek);
    }
}