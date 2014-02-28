<?php
class Projektor_Controller_ExportExcel extends Projektor_Controller_Page_AbstractPage implements Projektor_Controller_Page_Interface
{
	const SABLONA_MAIN = "seznam.xhtml";
	const NAZEV_FLAT_TABLE = "s_firma";

        public static function priprav($cesta)
	{
		return new self($cesta, __CLASS__);
	}

        /*
         *  ~~~~~~~~MAIN~~~~~~~~~~
         */
	public function main($parametry = null)
	{
            $form = print_r('
                            <form method="POST" action="index.php?akce=export_excel" name="vyber_tabulky">
                                Databázové tabulky: <br>
                                <select ID="dbtabulka" size="1" name="dbtabulka">
                                <option >------------</option>
                                <option >v_mi_vstoupily</option>
                                <option >v_zamestnani</option>
                                </select><br>
                                <input type="submit" value="Export" name="submit">
                            </form>
                        ' ,1);

            return $this->vytvorStranku("main", self::SABLONA_MAIN, $parametry, $form, "");
	}

	protected function main°vzdy()
	{
	}

	protected function main°potomekNeni()
	{
                /* Nadpis stranky */
                $this->setViewContextValue("nadpis", "Firmy");
                /* Ovladaci tlacitka stranky */
		$tlacitka = array
		(
			new Projektor_Controller_Page_Element_Tlacitko("Zpět", $this->cesta->backUri()),
			new Projektor_Controller_Page_Element_Tlacitko("Export do Excelu", $this->cesta->childUri("Projektor_Controller_Page_ExportExcel"))		);
                $this->setViewContextValue("tlacitka", $tlacitka);
        }

	protected function main°potomek°Projektor_Controller_Page_ExportExcel°exportExcel()
	{
            if(isset($_POST['dbtabulka'])) {
                if(substr($_POST['dbtabulka'],0,3)<>"---") {
                    $tabulka = $_POST['dbtabulka'];
                    $exportExcel = new ExportExcel($tabulka);
                    $souborSExportem = $exportExcel->export(NULL, 1);
                    Projektor_VynucenyDownload::download($souborSExportem);
                }
            }
        }

}
?>
