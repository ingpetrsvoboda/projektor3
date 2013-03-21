<?php
class Projektor_Stranka_Akce_Seznam_Prihlasovaci extends Projektor_Stranka_Akce_Seznam
{
    const TYP_STRANKY = Projektor_Stranka_Generator::TYP_SEZNAM;
    const SABLONA = "seznam.xhtml";
    const TRIDA_DATA_COLLECTION = "Projektor_Data_Auto_AkceCollection";

	protected function potomekNeni()
	{
                /* Nadpis stranky */
                $this->novaPromenna("nadpis", "Výběr akce pro přihlášení");
                $nazevHlavihoObjektu = str_replace("Projektor_Data_", "", $this->strankaRodic->tridaData);
                $akcem = Projektor_Data_Auto_AkceItem::vypisVseProObjekt($nazevHlavihoObjektu, $this->filtr->generujSQL(), $this->parametry["razeniPodle"], $this->parametry["razeni"]);
                $this->generujSeznamSTlacitky($akcem);
        }

	protected function potomek°Projektor_Stranka_Ucastnik_Prihlaseni(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
        }

	protected function prihlasovaci°potomek°Projektor_Stranka_Zajemce_Prihlaseni(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
        }

        protected function prihlasovaci°potomek°Projektor_Stranka_Ucastnik_Prihlaska(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
//            $this->akceObjektu();
//            $this->akceObjektu°potomekNeni();
//            $this->akceObjektu°vzdy();
        }

        protected function prihlasovaci°potomek°Projektor_Stranka_Zajemce_Prihlaska(Projektor_Dispatcher_Uzel $uzelPotomek = null)
        {
//            $this->akceObjektu();
//            $this->akceObjektu°potomekNeni();
//            $this->akceObjektu°vzdy();
        }

	protected function prihlasovaci°potomek°Projektor_Stranka_Ucastnik_Detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
	}

	protected function prihlasovaci°potomek°Projektor_Stranka_Zajemce_Detail(Projektor_Dispatcher_Uzel $uzelPotomek = null)
	{
                $this->generujPolozku($uzelPotomek);
	}
//------ privátní funkce třídy ---------------------------------------------------------------------------------------------------------------

        protected function generujTlacitkaProSeznam($akcej)
        {
            $prihlasovaciStranka = "Projektor_Stranka_".$akcej->nazevHlavnihoObjektu."_Prihlasovaci";
            $prihlaseniStranka = "Projektor_Stranka_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Akce_Detail", array("id" => $akcej->id, "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Přihlásit na akci", $this->uzel->potomekUri($prihlasovaciStranka, array("id" => $akcej->id))),
                            new Projektor_Stranka_Element_Tlacitko("Seznam přihlášených", $this->uzel->potomekUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }

	protected function generujTlacitkaProPolozku($akcej)
        {
            $prihlasovaciStranka = "Projektor_Stranka_".$akcej->nazevHlavnihoObjektu."_Prihlasovaci";
            $prihlaseniStranka = "Projektor_Stranka_".$akcej->nazevHlavnihoObjektu."_Prihlaseni";
            $tlacitka = array
                (
                            new Projektor_Stranka_Element_Tlacitko("Detail", $this->uzel->potomekUri("Projektor_Stranka_Akce_Detail", array("id" => $akcej->id, "zmraz" => 1))),
                            new Projektor_Stranka_Element_Tlacitko("Seznam přihlášených", $this->uzel->potomekUri($prihlaseniStranka, array("id" => $akcej->id)))
                );
            return $tlacitka;
        }
}