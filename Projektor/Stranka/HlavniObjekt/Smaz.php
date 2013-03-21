 <?php
abstract class Projektor_Stranka_HlavniObjekt_Smaz extends Projektor_Stranka_Base implements Projektor_Stranka_Interface
{
        protected function vychozi()
        {
                if ($this->uzel->parametry["id"] AND $this->tridaData) {
                    $trida = $this->tridaData;
                    $hlavniObjekt = $trida::najdiPodleId($this->uzel->parametry["id"]);
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