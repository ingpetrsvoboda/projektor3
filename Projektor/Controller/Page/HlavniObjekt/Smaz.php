 <?php
abstract class Projektor_Controller_Page_HlavniObjekt_Smaz extends Projektor_Controller_Page_AbstractPage implements Projektor_Controller_Page_Interface
{
        protected function vychozi()
        {
                if ($this->vertex->params["id"] AND $this->tridaData) {
                    $trida = $this->tridaData;
                    $hlavniObjekt = $trida::najdiPodleId($this->vertex->params["id"]);
                    if ($hlavniObjekt)
                    {
                        return $hlavniObjekt->smaz();
                    }
                    return FALSE;
                } else {
                    return FALSE;
                }
        }

        protected function vzdy() {}

	protected function potomekNeni() {}

}