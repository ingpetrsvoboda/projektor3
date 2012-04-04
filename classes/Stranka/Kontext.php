<?php
//require_once("classes/PHPTAL.PHP");

class Stranka_Kontext extends Stranka implements Stranka_Interface
{
	const JMENO = "Stranka_Kontext";
	const MAIN = "main";
	const SABLONA_MAIN = "kontext.xhtml";

	public $html;
	public $promenne;

	public static function priprav($cesta, $tridaKoren = NULL)
	{
            		return new self($cesta, __CLASS__, $tridaKoren);
	}

	public function main($parametry = null)
	{
            /* Vygenerovani filtrovaciho formulare */
            $kontextSelectFormular = $this->kontextSelect();            
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $kontextSelectFormular->toHtml());
	}

	protected function main°vzdy()
	{
            $this->novaPromenna("nadpis", $this->parametry["nadpis"]);
            $this->novaPromenna("zprava", $this->parametry["zprava"]);
                $this->novaPromenna("debuguj", $this->cesta->generujUri()."&debug=".($this->parametry["debugpovolen"] ? "0" : "1"));
                $this->novaPromenna("debugujtext", ($this->parametry["debugpovolen"] ? "UKONČI DEBUGOVÁNÍ" : "DEBUGUJ"));
        }

	protected function main°potomekNeni()
	{

	}

private function kontextSelect()
        {
            $form = new HTML_QuickForm("kontext", "post", $this->cestaSem->generujUri());
            $kontextUser = App_Kontext::getUserKontext();

            /* element select Projekt a běh */
            if ($kontextUser->povoleneProjekty) 
            {
                $projektySelect[""] = "";
                    $behySelect[""][""] = "";
                foreach($kontextUser->povoleneProjekty as $projekt) 
                {
                    $projektySelect[$projekt->id] = $projekt->text;
                    $behySelect[$projekt->id][""] = "";
                    $filtr = $projekt->id . " = " . Data_Seznam_SBehProjektu::ID_C_PROJEKT_FK;
                    $behy = Data_Seznam_SBehProjektu::vypisVse($filtr);   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
                    if ($behy)
                    {
                        foreach($behy as $beh) $behySelect[$projekt->id][$beh->id] = $beh->text;
                    }
                }
                // Create the Element
                $sel =& $form->addElement('hierselect', 'projektbeh', 'Projekt a běh:');

                // And add the selection options
                $sel->setOptions(array($projektySelect, $behySelect));
//                $form->addElement("select", Data_Ucastnik::ID_C_PROJEKT_FK, "Projekt", $projektySelect);
            }
            /* element select Kanceláře */
            if ($kontextUser->povoleneKancelare) {
                $kancelareSelect[""] = "";
                foreach($kontextUser->povoleneKancelare as $kancelar) $kancelareSelect[$kancelar->id] = $kancelar->text;
                $form->addElement("select", "kancelar", "Kancelář", $kancelareSelect);
            }
            /* element Běhy */
//            if ($kontextUser->projekt) 
//            {
//                $behySelect[""] = "";
//                $behy = Data_Seznam_SBehProjektu::vypisVse();   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
//                if ($behy)
//                {
//                    foreach($behy as $beh) $behySelect[$beh->id] = $beh->text;
//                    $form->addElement("select", Data_Ucastnik::ID_S_BEH_PROJEKTU_FK, "Běh", $behySelect);
//                }
//            }
            /* element submit */
            $form->addElement("submit", "Vyber", "Vyber");
            if($form->validate())
            {
                $data = $form->exportValues();
                if ($data["Vyber"]) {
                    unset($data["Vyber"]);
                    $projekt = Data_Ciselnik::najdiPodleId(App_Config::DATABAZE_PROJEKTOR, "projekt", $data["projektbeh"][0]);
                    $beh = Data_Seznam_SBehProjektu::najdiPodleId($data["projektbeh"][1]); 
                    $kancelar = Data_Ciselnik::najdiPodleId(App_Config::DATABAZE_PROJEKTOR, "kancelar", $data["kancelar"]);
                    $kontextUser->projekt = $projekt;
                    $kontextUser->beh = $beh;
                    $kontextUser->kancelar = $kancelar;
                    $kontextUser = App_Kontext::setUserKontext($kontextUser);
                }            
            }
//            $kontextUser = App_Kontext::getKontextUser();

            
            if ($kontextUser->projekt) {
                $elm = $form->getElement('projektbeh');
                $elm->setValue(array(0 => $kontextUser->projekt->id, 1 =>$kontextUser->beh->id));
//                $elm->setValue($kontextUser->projekt->id);
            }
            if ($kontextUser->kancelar) $form->getElement("kancelar")->setValue($kontextUser->kancelar->id);
//            if ($kontextUser->beh) $form->getElement(Data_Ucastnik::ID_S_BEH_PROJEKTU_FK)->setValue($kontextUser->beh->id);            
            return $form;
        }        
        
}