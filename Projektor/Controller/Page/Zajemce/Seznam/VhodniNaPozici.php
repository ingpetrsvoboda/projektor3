<?php
class Projektor_Controller_Page_Zajemce_Seznam_VhodniNaPozici extends Projektor_Controller_Page_Zajemce_Seznam
{
    const SABLONA = "seznam.xhtml";
    const TRIDA_Model_COLLECTION = "Projektor_Model_Auto_ZajemceCollection";

    protected function potomekNeni()
    {
        $iscoKod = $this->vertex->params["iscoKod"];
        $zajemci = Projektor_Model_Zajemce::vypisVhodneNaPozici($iscoKod);
        $this->generujSeznam($zajemci);
        /* Nadpis stranky */
        $this->setViewContextValue("nadpis", "Zájemci vhodní na pozici");
    }

    protected function potomek°Projektor_Controller_Page_Zajemce°detail(Projektor_Dispatcher_TreeDispatcher_Vertex $uzelPotomek = null)
    {
        $this->generujPolozkuSTlacitky($uzelPotomek);
    }


}