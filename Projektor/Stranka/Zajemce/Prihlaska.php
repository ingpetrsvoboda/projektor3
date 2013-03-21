 <?php
class Projektor_Stranka_Zajemce_Prihlaska extends Projektor_Stranka_HlavniObjekt_Detail
{
    const SABLONA = "detail.xhtml";
    const TRIDA_DATA_ITEM = "Projektor_Data_Auto_ZajemceItem";

	protected function vzdy()
	{
            $this->uzel->vraciHodnoty = TRUE;
	}
}
