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
            $filtrovaciFormular = $this->filtrovani();            
            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, "", $filtrovaciFormular->toHtml());
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

private function filtrovani()
        {
            $form = new HTML_QuickForm("kontext", "post", $this->cestaSem->generujUri());
           $kontextUser = App_Kontext::getUserKontext();
            /* Projekty */
            if ($kontextUser->povoleneProjekty) 
            {
                    $projektySelect[""] = "";
                    foreach($kontextUser->povoleneProjekty as $projekt) $projektySelect[$projekt->id] = $projekt->text;
                    $form->addElement("select", Data_Ucastnik::ID_C_PROJEKT_FK, "Projekt", $projektySelect);
            }
            /* Kanceláře */
            if ($kontextUser->povoleneKancelare) {
                    $kancelareSelect[""] = "";
                    foreach($kontextUser->povoleneKancelare as $kancelar) $kancelareSelect[$kancelar->id] = $kancelar->text;
                    $form->addElement("select", Data_Ucastnik::ID_C_KANCELAR_FK, "Kancelář", $kancelareSelect);
            }
            /* Běhy */
            if ($kontextUser->projekt) 
            {
                    $behySelect[""] = "";
                    $behy = Data_Seznam_SBehProjektu::vypisVse();   //diky kontext filtru vraci jen behy pro $kontextUser->projekt
                    foreach($behy as $beh) $behySelect[$beh->id] = $beh->text;
                    $form->addElement("select", Data_Ucastnik::ID_S_BEH_PROJEKTU_FK, "Běh", $behySelect);
            }
            
            $form->addElement("submit", "Vyber", "Vyber");
            
            if($form->validate())
            {
                $data = $form->exportValues();
                if ($data["Vyber"]) {
                    unset($data["Vyber"]);
                    $projekt = Data_Ciselnik::najdiPodleId("projekt", $data[Data_Ucastnik::ID_C_PROJEKT_FK]);
                    $kancelar = Data_Ciselnik::najdiPodleId("kancelar", $data[Data_Ucastnik::ID_C_KANCELAR_FK]);
                    $beh = Data_Seznam_SBehProjektu::najdiPodleId($data[Data_Ucastnik::ID_S_BEH_PROJEKTU_FK]); 
                    $kontextUser->projekt = $projekt;
                    $kontextUser->kancelar = $kancelar;
                    $kontextUser->beh = $beh;
                    $kontextUser = App_Kontext::setUserKontext($kontextUser);
                }
            }
            if ($kontextUser->projekt) $form->getElement(Data_Ucastnik::ID_C_PROJEKT_FK)->setValue($kontextUser->projekt->id);
            if ($kontextUser->kancelar) $form->getElement(Data_Ucastnik::ID_C_KANCELAR_FK)->setValue($kontextUser->kancelar->id);
            if ($kontextUser->beh) $form->getElement(Data_Ucastnik::ID_S_BEH_PROJEKTU_FK)->setValue($kontextUser->beh->id);            
            return $form;
        }        
        
}