<?php
class Stranka_Element_Hlavicka_Sloupec
{
	public $nazevVlastnosti;
        public $popisek;
        public $nazevSloupceDb;
        public $prikazGenerujiciPoleReferencovanychObjektu;
        public $nazevVlastnostiReferencovanehoObjektu;
	public $sestupne;
	public $vzestupne;
	
	public function __construct($nazevVlastnosti, $popisek, $nazevSloupceDb=NULL, $prikazGenerujiciPoleReferencovanychObjektu = NULL, $nazevVlastnostiReferencovanehoObjektu = NULL, $sestupne=NULL, $vzestupne=NULL)
	{
            $this->nazevVlastnosti = $nazevVlastnosti;
            $this->popisek = $popisek;
            $this->nazevSloupceDb = $nazevSloupceDb;
            $this->prikazGenerujiciPoleReferencovanychObjektu = $prikazGenerujiciPoleReferencovanychObjektu;
            $this->nazevVlastnostiReferencovanehoObjektu = $nazevVlastnostiReferencovanehoObjektu;
            $this->sestupne = $sestupne;
            $this->vzestupne = $vzestupne;		
	}
}