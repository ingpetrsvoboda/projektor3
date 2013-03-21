<?php
class Projektor_Stranka_Index extends Projektor_Stranka_Base
{
    const SABLONA = "index.xhtml";

	protected function vychozi()
	{
            /* Vygenerovani filtrovaciho formulare */
            $form = $this->kontextSelect();
            if ($form) $this->novaPromenna("formular", $form->toHtml());
	}
        protected function vzdy()
	{
            $user = Projektor_App_Container::getUser();
            $this->appStatus->originating_uri = $this->uzel->formAction();

            if ($user->identity->getIdentity()) {
                $this->novaPromenna("con",  "Přihlášen uživatel ".$user->item->dbField°username.", což je ".$user->item->dbField°name.".");
                $this->novaPromenna("loginlogout", new Projektor_Stranka_Element_Tlacitko("Logout", $_SERVER["SCRIPT_NAME"]."?route=logout"));
            } else {
                $this->novaPromenna("loginlogout", new Projektor_Stranka_Element_Tlacitko("Login", $_SERVER["SCRIPT_NAME"]."?route=login"));
            }
            $this->novaPromenna("nadpis", "Index");
            $this->novaPromenna("debuguj", $this->uzel->formAction()."&debug=".(Projektor_App_Container::getDebug() ? "0" : "1"));
            $this->novaPromenna("debugujtext", ($this->uzel->parametry["debugpovolen"] ? "UKONČI DEBUGOVÁNÍ" : "DEBUGUJ"));

            $this->novaPromenna("nadpis", "Výběr projektu, běhu a kanceláře");

            /* Ovladaci tlacitka stranky */

            if ($this->appStatus->userKontext->projekt->kod == "SPZP" OR $this->appStatus->userKontext->projekt->kod == "RNH")
            {
                $tlacitka = array
                (
                    new Projektor_Stranka_Element_Tlacitko("Zavři všechny stránky", "index.php"),
                    new Projektor_Stranka_Element_Tlacitko("Akce", $this->uzel->potomekUri("Projektor_Stranka_Akce_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Účastníci", $this->uzel->potomekUri("Projektor_Stranka_Ucastnik_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Typy akce", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Předpoklady", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Prezentace", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Seznam")),
                );
                $this->novaPromenna("tlacitka", $tlacitka);
            };
            if ($this->appStatus->userKontext->projekt->kod == "AGP")
            {
                $tlacitka = array
                (
                    new Projektor_Stranka_Element_Tlacitko("Zavři všechny stránky", "index.php"),
                    new Projektor_Stranka_Element_Tlacitko("Akce", $this->uzel->potomekUri("Projektor_Stranka_Akce_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Zájemci", $this->uzel->potomekUri("Projektor_Stranka_Zajemce_Seznam", array("hlavniObjekt" => "Zajemci"))),
                    new Projektor_Stranka_Element_Tlacitko("Typy akce", $this->uzel->potomekUri("Projektor_Stranka_TypAkce_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Předpoklady", $this->uzel->potomekUri("Projektor_Stranka_Predpoklad_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("ISCO", $this->uzel->potomekUri("Projektor_Stranka_ISCO_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Prezentace", $this->uzel->potomekUri("Projektor_Stranka_Prezentace_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Firmy", $this->uzel->potomekUri("Projektor_Stranka_Firma_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Staffer pozice", $this->uzel->potomekUri("Projektor_Stranka_StafferPoziceM_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("Přihlášky zájemců", $this->uzel->potomekUri("Projektor_Stranka_PrihlaskyZajemcu_Seznam")),
                    new Projektor_Stranka_Element_Tlacitko("CRMFirmy", $this->uzel->potomekUri("Projektor_Stranka_CRMFirmy_Seznam"))
                );
                $this->novaPromenna("tlacitka", $tlacitka);
            };
	}

    private function kontextSelect()
    {
        $user = Projektor_App_Container::getUser();
        if ($user->identity->getIdentity()) {
            $form = new HTML_QuickForm("kontext", "post", $this->uzel->formAction());
            /* element select Projekt a běh */
             $povoleneProjektyCollection = $user->getPovoleneProjektyCollection();
            if ($povoleneProjektyCollection)
            {
                $projektySelect[""] = "";
                $behySelect[""][""] = "";
                foreach($povoleneProjektyCollection as $projektItem)
                {
                    $projektySelect[$projektItem->id] = $projektItem->text;
                    $behySelect[$projektItem->id][""] = "";
                    $behyCollection = new Projektor_Data_Auto_SBehProjektuCollection();
                    $behyCollection->where("dbField°id_c_projekt", "=", $projektItem->id);
//                    $filtr = $projekt->id . " = " . Projektor_Data_Seznam_SBehProjektu::ID_C_PROJEKT_FK;
//                    $behy = Projektor_Data_Seznam_SBehProjektu::vypisVse($filtr);   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
                    if ($behyCollection)
                    {
                        foreach($behyCollection as $behItem) $behySelect[$projektItem->id][$behItem->id] = $behItem->text;
                    }
                }
                // Create the Element
                $sel = $form->addElement('hierselect', 'projektbeh', 'Projekt a běh:');

                // And add the selection options
                $sel->setOptions(array($projektySelect, $behySelect));
//                $form->addElement("select", Projektor_Data_Ucastnik::ID_C_PROJEKT_FK, "Projekt", $projektySelect);
            }
            /* element select Kanceláře */
            $povoleneKancelareCollection = $user->getPovoleneKancelareCollection();
            if ($povoleneKancelareCollection) {
                $kancelareSelect[""] = "";
                foreach($povoleneKancelareCollection as $kancelarItem) $kancelareSelect[$kancelarItem->id] = $kancelarItem->text;
                $form->addElement("select", "kancelar", "Kancelář", $kancelareSelect);
            }
            /* element Běhy */
//            if ($user->kontext->projekt)
//            {
//                $behySelect[""] = "";
//                $behy = Projektor_Data_Seznam_SBehProjektu::vypisVse();   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
//                if ($behy)
//                {
//                    foreach($behy as $beh) $behySelect[$beh->id] = $beh->text;
//                    $form->addElement("select", Projektor_Data_Ucastnik::ID_S_BEH_PROJEKTU_FK, "Běh", $behySelect);
//                }
//            }
            /* element submit */
            $form->addElement("submit", "Vyber", "Vyber");
            if($form->validate())
            {
                $data = $form->exportValues();
                if ($data["Vyber"]) {
                    unset($data["Vyber"]);
                    $projektItem = new Projektor_Data_Auto_CProjektItem($data["projektbeh"][0]);
                    $behItem = new Projektor_Data_Auto_SBehProjektuItem($data["projektbeh"][1]);
                    $kancelarItem = new Projektor_Data_Auto_CKancelarItem($data["kancelar"]);
                    $userKontext = new Projektor_User_Kontext();
                    $userKontext->projekt = $projektItem;
                    $userKontext->beh = $behItem;
                    $userKontext->kancelar = $kancelarItem;
                    $this->appStatus->userKontext = $userKontext;
                }
            }
            if ($this->appStatus->userKontext->projekt) {
                $elm = $form->getElement('projektbeh');
                $elm->setValue(array(0 => $this->appStatus->userKontext->projekt->id, 1 => $this->appStatus->userKontext->beh->id));
            //                $elm->setValue($kontextUser->projekt->id);
            }
            if ($this->appStatus->userKontext->kancelar) $form->getElement("kancelar")->setValue($this->appStatus->userKontext->kancelar->id);
            //            if ($userKontext->beh) $form->getElement(Projektor_Data_Ucastnik::ID_S_BEH_PROJEKTU_FK)->setValue($userKontext->beh->id);
            return $form;
        } else {
            $this->appStatus->userKontext = NULL;
            return NULL;
        }
    }

}
