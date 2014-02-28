 <?php
class Projektor_Controller_Page_Zajemce_ExportPDF extends Projektor_Controller_Page_HlavniObjekt_Detail
{
        public function vychozi()
        {
            // !!! netvoří se stránka
            if (!$this->uzel->controllerParams["pdfDokument"])  //TODO: tohle je po předělání kontroleru na Page určitě špatně - udělat znova
                throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Není zadán parametr pdfDokument: ".  print_r($this->uzel->controllerParams, TRUE));
            $zajemce = Projektor_Model_Zajemce::najdiPodleId($this->uzel->controllerParams["id"]);
            $pdfDokument = new Projektor_Pdf_Dokument_AGPSouhlas($zajemce);
            $pdfDokument->vytvor();
            $soubor = $pdfDokument->uloz();
            if (!$soubor)
                throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Nepodařilo se uložit pdfDokument ". Projektor_Pdf_Dokument_AGPSouhlas::FILENAME_PREFIX);
            Projektor_VynucenyDownload::download($soubor);
        }
}
