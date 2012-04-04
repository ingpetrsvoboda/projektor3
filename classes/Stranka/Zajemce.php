 <?php
class Stranka_Zajemce extends Stranka_HlavniObjekt
{
        const HLAVNI_OBJEKT = "Zajemce";

	public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

        public function detail($parametry=NULL)
        {
            return parent::detail(self::HLAVNI_OBJEKT, $parametry);
        }

        public function exportPDF($parametry=NULL)
        {
            // !!! netvoří se stránka
            if (!$parametry["pdfDokument"]) 
                throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Není zadán parametr pdfDokument: ".  print_r($parametry, TRUE));
            $zajemce = Data_Zajemce::najdiPodleId($parametry["id"]);  
            $pdfDokument = new PDF_Dokument_AGPSouhlas($zajemce);
            $pdfDokument->vytvor();
            $soubor = $pdfDokument->uloz();
            if (!$soubor) 
                throw new Exception('*** Chyba v '.__CLASS__."->".__METHOD__.': '."Nepodařilo se uložit pdfDokument ". PDF_Dokument_AGPSouhlas::FILENAME_PREFIX);
            VynucenyDownload::download($soubor);
        }
        
    /* prihlaseni */
	public function prihlaseni($parametry = null)
	{
		return $this->vytvorStranku("prihlaseni", self::SABLONA_DETAIL, $parametry);
	}

	protected function prihlaseni°vzdy()
	{
		$akce = Data_Akce::najdiPodleId($this->parametry["id_akce"]);
                $this->novaPromenna("nadpis", "Přihlášení na akci");

                try
		{
                    //TODO: Nedodělek - pevně zadaný parametr 2!
			$akce->prihlas(Data_Zajemce::najdiPodleId($this->parametry["id"]), Data_Seznam_SStavUcastnikAkce::najdiPodleId(2), Data_Seznam_SStavUcastnikAkceDen::najdiPodleId(2));
                        $this->novaPromenna("prihlaseni_na_akci", "Prihlaseni bylo uspesne!");
		}
		catch(Exception $e)
		{
                        $this->novaPromenna("prihlaseni_na_akci", $e->getMessage());
		}

		$this->detail°vzdy();
	}

	protected function prihlaseni°potomekNeni()
	{
		$this->detail°potomekNeni();
	}

        public function smaz($parametry=NULL)
        {
            return parent::smaz(self::HLAVNI_OBJEKT, $parametry);            
        }
     
        
        
}