<?php
class Projektor_Stranka_Element_Hlavicka_Sloupec
{
	public $nazevVlastnosti;
        public $titulek;
        public $nazevVlastnostiReferencovanehoObjektu;
	public $sestupne;
        public $razeniDescPouzito;
	public $vzestupne;
        public $razeniAscPouzito;

	public function __construct($nazevVlastnosti, $titulek=NULL, $nazevVlastnostiReferencovanehoObjektu = NULL, $uriSestupne=NULL, $razeniDescPouzito=NULL, $uriVzestupne=NULL, $razeniAscPouzito=NULL)
	{
            $this->nazevVlastnosti = $nazevVlastnosti;
            $this->titulek = $titulek;
            $this->nazevVlastnostiReferencovanehoObjektu = $nazevVlastnostiReferencovanehoObjektu;
            $this->sestupne = $uriSestupne;
            $this->razeniDescPouzito = $razeniDescPouzito;
            $this->vzestupne = $uriVzestupne;
            $this->razeniAscPouzito = $razeniAscPouzito;
	}
}