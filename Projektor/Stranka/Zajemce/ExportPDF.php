 <?php
class Projektor_Stranka_Zajemce_ExportPDF extends Projektor_Stranka_HlavniObjekt_Detail
{
        public function vychozi()
        {
            // !!! netvoří se stránka
            if (!$this->uzel->parametry["pdfDokument"])
                throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Není zadán parametr pdfDokument: ".  print_r($this->uzel->parametry, TRUE));
            $zajemce = Projektor_Data_Zajemce::najdiPodleId($this->uzel->parametry["id"]);
            $pdfDokument = new Projektor_Pdf_Dokument_AGPSouhlas($zajemce);
            $pdfDokument->vytvor();
            $soubor = $pdfDokument->uloz();
            if (!$soubor)
                throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Nepodařilo se uložit pdfDokument ". Projektor_Pdf_Dokument_AGPSouhlas::FILENAME_PREFIX);
            Projektor_VynucenyDownload::download($soubor);
        }
}
