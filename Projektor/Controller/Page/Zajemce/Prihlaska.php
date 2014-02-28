 <?php
class Projektor_Controller_Page_Zajemce_Prihlaska extends Projektor_Controller_Page_HlavniObjekt_Detail
{
    const SABLONA = "detail.xhtml";
    const TRIDA_Model_ITEM = "Projektor_Model_Auto_ZajemceItem";

	protected function vzdy()
	{
            $this->uzel->returnsValues = TRUE; //TODO: nevím na co jsem měl udělánu vlastnost returnValue (vraciHodnoty), ale bylo to něco důležitého
	}
}
