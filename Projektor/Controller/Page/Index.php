<?php
class Projektor_Controller_Page_Index extends Projektor_Controller_Page_AbstractPage
{
    const SABLONA = "index.xhtml";

	protected function vychozi()
	{
            /* Vygenerovani filtrovaciho formulare */
            $form = $this->kontextSelect3();
            if ($form) {
                $this->setViewContextValue("formular", $form->toHtml());
            }
	}
        
        protected function potomekNeni() {}
        
        protected function vzdy()
	{
            $user = Projektor_Container::getUser();
            $appStatus = Framework_Application_AbstractApplication::getInstance()->getAppStatus();
            $request = Framework_Application_AbstractApplication::getInstance()->getRequest();
            $appStatus->originating_uri = $this->vertex->formAction();
            if (!$user->identity->getIdentity()) {
                $this->setViewContextValue("loginlogout", new Projektor_Controller_Page_Element_Tlacitko("Login", $request->getScriptName()."?controller=login"));
            } else {
                $this->setViewContextValue("con", "\$user->identity->getIdentity(): ".print_r($user->identity->getIdentity(), TRUE)."\n" ."Přihlášen uživatel ".$user->item->dbField°username.", což je ".$user->item->dbField°name.".");
                $this->setViewContextValue("loginlogout", new Projektor_Controller_Page_Element_Tlacitko("Logout", $request->getScriptName()."?controller=logout"));
                
                $this->setViewContextValue("nadpis", "Index");
                $this->setViewContextValue("debuguj", $this->vertex->formAction()."&debug=".(Projektor_Container::getDebug() ? "0" : "1"));
                $this->setViewContextValue("debugujtext", ($this->vertex->params["debugpovolen"] ? "UKONČI DEBUGOVÁNÍ" : "DEBUGUJ"));

                $this->setViewContextValue("nadpis", "Výběr projektu a kanceláře");

                /* Ovladaci tlacitka stranky */
                if (isset($appStatus->userKontext->projekt)) {
                    switch ($appStatus->userKontext->projekt->kod) {
                        case "SPZP":
                        case "RNH":
                            $tlacitka = array
                            (
                                new Projektor_Controller_Page_Element_Tlacitko("Zavři všechny stránky", "index.php"),
                                new Projektor_Controller_Page_Element_Tlacitko("Akce", $this->vertex->childUri("Projektor_Controller_Page_Akce_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Účastníci", $this->vertex->childUri("Projektor_Controller_Page_Ucastnik_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Typy akce", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Předpoklady", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Prezentace", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Seznam")),
                            );
                            $this->setViewContextValue("tlacitka", $tlacitka);
                            break;

                        case "AGP":
                            $tlacitka = array
                            (
                                new Projektor_Controller_Page_Element_Tlacitko("Zavři všechny stránky", "index.php"),
                                new Projektor_Controller_Page_Element_Tlacitko("Akce", $this->vertex->childUri("Projektor_Controller_Page_Akce_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Zájemci", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Seznam", array("hlavniObjekt" => "Zajemci"))),
                                new Projektor_Controller_Page_Element_Tlacitko("Typy akce", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Předpoklady", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("ISCO", $this->vertex->childUri("Projektor_Controller_Page_ISCO_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Prezentace", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Firmy", $this->vertex->childUri("Projektor_Controller_Page_Firma_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Staffer pozice", $this->vertex->childUri("Projektor_Controller_Page_StafferPoziceM_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Přihlášky zájemců", $this->vertex->childUri("Projektor_Controller_Page_PrihlaskyZajemcu_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("CRMFirmy", $this->vertex->childUri("Projektor_Controller_Page_CRMFirmy_Seznam"))
                            );
                            $this->setViewContextValue("tlacitka", $tlacitka);
                            break;
                        case "HELP":
                            $tlacitka = array
                            (
                                new Projektor_Controller_Page_Element_Tlacitko("Zavři všechny stránky", "index.php"),
                                new Projektor_Controller_Page_Element_Tlacitko("Akce", $this->vertex->childUri("Projektor_Controller_Page_Akce_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Zájemci", $this->vertex->childUri("Projektor_Controller_Page_Zajemce_Seznam", array("hlavniObjekt" => "Zajemci"))),
                                new Projektor_Controller_Page_Element_Tlacitko("Typy akce", $this->vertex->childUri("Projektor_Controller_Page_TypAkce_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Předpoklady", $this->vertex->childUri("Projektor_Controller_Page_Predpoklad_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("ISCO", $this->vertex->childUri("Projektor_Controller_Page_ISCO_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Prezentace", $this->vertex->childUri("Projektor_Controller_Page_Prezentace_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Firmy", $this->vertex->childUri("Projektor_Controller_Page_Firma_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Staffer pozice", $this->vertex->childUri("Projektor_Controller_Page_StafferPoziceM_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("Přihlášky zájemců", $this->vertex->childUri("Projektor_Controller_Page_PrihlaskyZajemcu_Seznam")),
                                new Projektor_Controller_Page_Element_Tlacitko("CRMFirmy", $this->vertex->childUri("Projektor_Controller_Page_CRMFirmy_Seznam"))
                            );
                            $this->setViewContextValue("tlacitka", $tlacitka);
                            break;

                        default:
                            break;
                    }
                }
            }
        }


    /**
     * pracuje s objektem Projektor_Model_Auto_CKancelar3Collection - tedy nad upravenou db tabulkou c_kancelar3
     * @return \HTML_QuickForm|null
     */
    private function kontextSelect3()
    {
        $user = Projektor_Container::getUser();
        $appStatus = Framework_Application_AbstractApplication::getInstance()->getAppStatus();
        if ($user->identity->getIdentity()) {
            $form = new HTML_QuickForm("kontext", "post", $this->vertex->formAction());
            
            /* přidání elementu hierselect Projekt a kancelář */
            $povoleneProjektyCollection = $user->getPovoleneProjektyCollection();
                $projektySelect[""] = "";
                $kancelareSelect[""][""] = "";
                foreach($povoleneProjektyCollection as $projektItem) {
                    $zobrazovanaVlastnost = $projektItem::NAZEV_ZOBRAZOVANE_VLASTNOSTI;
                    $projektySelect[$projektItem->id] = $projektItem->$zobrazovanaVlastnost; 
                    $kancelareSelect[$projektItem->id][""] = "";
                    $povoleneKancelareVProjektuCollection = $user->getPovoleneKancelare3VProjektuCollection($projektItem->id); // kancelar3
                    if ($povoleneKancelareVProjektuCollection) {
                        foreach($povoleneKancelareVProjektuCollection as $kancelarItem) {
                            $kancelareSelect[$projektItem->id][$kancelarItem->id] = $kancelarItem->text;
                        }
                    }
                }
                $hierarchicalSelect = $form->addElement('hierselect', 'projektkancelar', 'Projekt a kancelář:');
                $hierarchicalSelect->setOptions(array($projektySelect, $kancelareSelect));

            /* přidání elementu submit */
            $form->addElement("submit", "Vyber", "Vyber");
            if($form->validate())
            {
                $data = $form->exportValues();
                if ($data["Vyber"]) {
                    unset($data["Vyber"]);
                    $projektItem = new Projektor_Model_Auto_CProjektItem($data["projektkancelar"][0]);
                    $kancelarItem = new Projektor_Model_Auto_CKancelar3Item($data["projektkancelar"][1]);
                    $userKontext = new Projektor_User_Kontext();
                    $userKontext->projekt = $projektItem;
                    $userKontext->kancelar = $kancelarItem;
                    $appStatus->userKontext = $userKontext;
                }
            }
            
            // nastavení výchozích (default) hodnot selectů
            $elm = $form->getElement('projektkancelar');
            $projektId = isset($appStatus->userKontext->projekt) ? $appStatus->userKontext->projekt->id : NULL;
            $kancelarId = isset($appStatus->userKontext->kancelar) ? $appStatus->userKontext->kancelar->id : NULL;
            $elm->setValue(array(0 => $projektId, 1 => $kancelarId));
            return $form;
        } else {
            $appStatus->userKontext = NULL;
            return NULL;
        }
    }
    
private function kontextSelect()
    {
        $user = Projektor_Container::getUser();
        $appStatus = Framework_Application_AbstractApplication::getInstance()->getAppStatus();
        if ($user->identity->getIdentity()) {
            $form = new HTML_QuickForm("kontext", "post", $this->vertex->formAction());
            
            /* přidání elementu hierselect Projekt a běh */
            $povoleneProjektyCollection = $user->getPovoleneProjektyCollection();
            if ($povoleneProjektyCollection) {
                $projektySelect[""] = "";
                $behySelect[""][""] = "";
                foreach($povoleneProjektyCollection as $projektItem) {
                    $projektySelect[$projektItem->id] = $projektItem->text;
                    $behySelect[$projektItem->id][""] = "";
                    $behyCollection = new Projektor_Model_Auto_SBehProjektuCollection();
                    $behyCollection->where("dbField°id_c_projekt", "=", $projektItem->id);
                    if ($behyCollection) {
                        foreach($behyCollection as $behItem) $behySelect[$projektItem->id][$behItem->id] = $behItem->text;
                    }
                }
                $hierarchicalSelect = $form->addElement('hierselect', 'projektbeh', 'Projekt a běh:');
                $hierarchicalSelect->setOptions(array($projektySelect, $behySelect));
            }
            
            /* přidání elementu select Kanceláře */
            $povoleneKancelareCollection = $user->getPovoleneKancelareVProjektuCollection();
            if ($povoleneKancelareCollection) {
                $kancelareSelect[""] = "";
                foreach($povoleneKancelareCollection as $kancelarItem) {
                    $kancelareSelect[$kancelarItem->id] = $kancelarItem->text;
                }
                $form->addElement("select", "kancelar", "Kancelář", $kancelareSelect);
            }

            /* přidání elementu submit */
            $form->addElement("submit", "Vyber", "Vyber");
            if($form->validate())
            {
                $data = $form->exportValues();
                if ($data["Vyber"]) {
                    unset($data["Vyber"]);
                    $projektItem = new Projektor_Model_Auto_CProjektItem($data["projektbeh"][0]);
                    $behItem = new Projektor_Model_Auto_SBehProjektuItem($data["projektbeh"][1]);
                    $kancelarItem = new Projektor_Model_Auto_CKancelarItem($data["kancelar"]);
                    $userKontext = new Projektor_User_Kontext();
                    $userKontext->projekt = $projektItem;
                    $userKontext->beh = $behItem;
                    $userKontext->kancelar = $kancelarItem;
                    $appStatus->userKontext = $userKontext;
                }
            }
            
            // nastavení výchozích (default) hodnot selectů
            if ($appStatus->userKontext->projekt) {
                $elm = $form->getElement('projektbeh');
                $elm->setValue(array(0 => $appStatus->userKontext->projekt->id, 1 => $appStatus->userKontext->beh->id));
            }
            if ($appStatus->userKontext->kancelar) $form->getElement("kancelar")->setValue($appStatus->userKontext->kancelar->id);
            return $form;
        } else {
            $appStatus->userKontext = NULL;
            return NULL;
        }
    }
    
}
