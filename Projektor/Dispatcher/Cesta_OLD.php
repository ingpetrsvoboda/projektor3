<?php
class Projektor_Router_Cesta_OLD
{
	public $cesta;  //TODO: zde jen public jen pro debugování, má být private
//	public $ukazatel = -1;

        /**
         * Metoda vytvoří objekt Projektor_Router_Cesta, vlastnost cesta (array) naplní jednotlivými kroky cesty a ukazatel nastaví na poslední vygenerovaný krok
         * @param type $uri
         */
	public function __construct($uri)
	{
		if($uri)
		{
                        $koren = new Projektor_Router_Cesta_Stranka(null, null, null, null, null);

			$tildy = explode("~", $uri);
			foreach($tildy as $tilda)
			{
                            $potomci = new Projektor_Router_Cesta_Stranky($koren);
                            $uri = $potomci->rodic->uri.$ampersand;
                            $ampersandy = explode("&", $tilda);
                            foreach ($ampersandy as $ampersand)
                            {
                                $tecky = explode(".", $ampersand);
				$trida = $tecky[0];
				if($tecky[1])
				{
					$zavorky = explode("(", $tecky[1]);
					$metoda = $zavorky[0];
					if($zavorky[1])
					{
                                                $zavorky[1] = str_replace(")", "", $zavorky[1]);
                                        }
                                        if($zavorky[1])
                                        {
						$apostrofy = explode("'", $zavorky[1]);
						foreach($apostrofy as $apostrof)
						{
							$vykricniky = explode("!", $apostrof);
							$parametry[$vykricniky[0]] = $vykricniky[1];
						}
					}
					else
					$parametry = null;
				}
                                $potomci->pridejStranku($trida, $metoda, $parametry, $rodic, $uri);
                            }

                            $this->cesta[$this->ukazatel] = new Projektor_Router_Cesta_Stranka($trida, $metoda, $parametry, rodic);
                            $this->cesta[$this->ukazatel]->uri = $this->generujUri(); //přidí do uzlu uri
			}
                        $this->ukazatel=0;
		}

	}

        protected function prodluzUri($uri)
        {
                    foreach($this->cesta as $ukaz => $krokCesty)
                    {
                        if($krokCesty->parametry)
                            foreach($krokCesty->parametry as $jmenoParametru => $parametr)
                                    $apostrofy[] = $jmenoParametru."!".$parametr;
                        $tildy[] = $krokCesty->trida . "." . $krokCesty->metoda . "(" . ($apostrofy ? implode("'", $apostrofy) : "") . ")";
                        /* Uklid */
                        $apostrofy = "";
                        // krokuje až k ukazateli
                        if ($ukaz == $this->ukazatel)  return $_SERVER["SCRIPT_NAME"]."?" . Projektor_Dispatcher_Cesta::getPrefixCesty () . "_cesta=".implode("~", $tildy);
                    }
        }

//	public static function nova()
//	{
//		return new self("");
//	}

        /**
         * Metoda vrací aktuální krok cesty, krok na který ukazuje ukazatel
         * @return Projektor_Router_Cesta_Krok
         */
        public function dejKrokSem()
        {
		return $this->cesta[$this->ukazatel];
        }

	/**
         * Metoda zjišťuje, jestli cesta ma krok za ukazatelem
         * @return boolean
         */
        public function maDalsi()
	{
		return ($this->cesta && array_key_exists($this->ukazatel+1, $this->cesta));
	}

	/**
         * Metoda vrací další krok cesty, krok o jednu pozici za ukazatelem
         * @return Projektor_Router_Cesta_Krok
         */
        public function dejDalsiKrok()
	{
		if($this->maDalsi())
                    return $this->cesta[$this->ukazatel+1];
		else
			return null;
	}

	/**
         * Metoda zjišťuje, jestli cesta ma krok před ukazatelem
         * @return boolean
         */
        public function maPredchozi()
	{
		return ($this->cesta && array_key_exists($this->ukazatel-1, $this->cesta));
	}

	/**
         * Metoda vrací předchozí krok cesty, krok o jednu pozici před ukazatelem
         * @return Projektor_Router_Cesta_Krok
         */
        public function dejPredchoziKrok()
	{
		if($this->maPredchozi())
                    return $this->cesta[$this->ukazatel-1];
		else
			return null;
	}

	public function dejPosledniKrok()
	{
		return end($this->cesta);
	}

	/**
         * Metoda vytvoří klon (kopii) cesty
         * @return Projektor_Dispatcher_Cesta
         */
        public function klonuj()
	{
		$klon = clone $this;					// naklonujeme objekt, reference zustanou
		$poleCesta = $klon->cesta;				// ulozime si pole (teoreticky referenci na nej)
		$klon->cesta = array();					// v klonu vytvorime nove pole
		$klon->ukazatel = -1;
		if($poleCesta)
			foreach($poleCesta as $krokCesty)       // projdeme puvodni pole
                        {
                            $klon->ukazatel++;
                            $klon->cesta[$klon->ukazatel] = clone $krokCesty;	// a do noveho pole vkladame klony objektu v poli puvodnim
                        }
		$klon->ukazatel = $this->ukazatel;
		return $klon;
	}

	private function pridej(Projektor_Router_Cesta_Krok $krok)
	{
                $this->ukazatel++;
		$this->cesta[$this->ukazatel] = $krok;
                $this->cesta[$this->ukazatel]->uri = $this->generujUri();
		return $this;
	}

	/**
         * Metoda přídá krok k cestě (nevytváří kopii, klon) na pozici za ukazatelem a posune ukazatel na přidaný krok
         * @param type $trida
         * @param type $metoda
         * @param type $parametry
         * @return type
         */
        public function pridejKrok($trida, $metoda = "main", $parametry = null)
	{
		return $this->pridej(new Projektor_Router_Cesta_Krok($trida, $metoda, $parametry));
	}

	/**
         * Metoda vygeneruje uri z cesty až k ukazateli, nemění cestu
         * @return string
         */
        public function generujUri()
	{ //echo("<pre>"); var_dump($this); echo("</pre>");
		if($this->cesta == null)
			return  $_SERVER["SCRIPT_NAME"]."?" . Projektor_Dispatcher_Cesta::getPrefixCesty () . "_cesta=";
		if ($this->cesta[$this->ukazatel]->uri)
                {
                    return $this->cesta[$this->ukazatel]->uri;
                } else {
                    foreach($this->cesta as $ukaz => $krokCesty)
                    {
                        if($krokCesty->parametry)
                            foreach($krokCesty->parametry as $jmenoParametru => $parametr)
                                    $apostrofy[] = $jmenoParametru."!".$parametr;
                        $tildy[] = $krokCesty->trida . "." . $krokCesty->metoda . "(" . ($apostrofy ? implode("'", $apostrofy) : "") . ")";
                        /* Uklid */
                        $apostrofy = "";
                        // krokuje až k ukazateli
                        if ($ukaz == $this->ukazatel)  return $_SERVER["SCRIPT_NAME"]."?" . Projektor_Dispatcher_Cesta::getPrefixCesty () . "_cesta=".implode("~", $tildy);
                    }
                }
        }

	/**
         * Metoda  vytvoří kopii cesty (klon) a přidá jeden parametr k poli parametrů v posledním kroku cesty,
         *  poslední krok cesty je poslední položka v cestě, nikoli krok, ne který ukazuje ukazatel
         * @param string $parametr Název parametru
         * @param mixed $hodnota Hodnota parametru
         * @return Projektor_Dispatcher_Cesta
         */
        public function pridejParametr($parametr, $hodnota)
	{
		$nova = $this->klonuj();
		$nova->dejPosledniKrok()->setParametr($parametr, $hodnota);
		return $nova;
	}

	/**
         * Metoda generuje uri k předchozímu kroku cesty
         * @return string
         */
        public function generujUriZpet()
	{
            if($this->cesta[$this->ukazatel-1]->uri)
            {
                return $this->cesta[$this->ukazatel-1]->uri;
            } else {
                $tildy = explode("~", $this->generujUri());
		if(sizeof($tildy)==1) $tildy = explode("?", $tildy[0]);
		array_pop($tildy);
		return implode("~", $tildy);
            }
	}

	/**
         * Metoda generuje generuje uri k dalšímu kroku zadanému ve formátu $tridametoda a $parametry (uri budoucí cesty), ale nemění cestu
         * @param string $tridaMetoda Budoucí třída a metoda ve formátu Trida.metoda
         * @param array $parametry Parametry budoucí třídy a metody
         * @return Projektor_Dispatcher_Cesta
         */
        public function generujUriDalsi($tridaMetoda, $parametry = null)
	{
		$tridaMetoda = explode(".", $tridaMetoda);
		$budouciCesta = $this->klonuj();
		$budouciCesta->pridejKrok($tridaMetoda[0], $tridaMetoda[1], $parametry);
                return $budouciCesta->dejKrokSem()->uri;
	}

	/**
         * Metoda ořízne cestu tak, že odřízne všechny kroky za ukazatelem
         * @return boolean
         */
        private function orez()
	{
		if($this->cesta == null)
			return false;

		$this->cesta = array_slice($this->cesta, 0, $this->ukazatel + 1);
	}

	/**
         * Metoda vytvoří kopii cesty (klon) a ořízne novou cestu tak, že poslední krok je krok, na který ukazuje ukazatel
         * @return Projektor_Dispatcher_Cesta
         */
        public function sem()
	{
		$cestaSem = $this->klonuj();
		$cestaSem->orez();
		return $cestaSem;
	}

	/**
         * Metoda vytvoří kopii cesty (klon), pusune ukazatel na další krok
         * @return Projektor_Dispatcher_Cesta
         */
        public function dalsi()
	{
		$cestaDalsi = $this->klonuj();
                $cestaDalsi->ukazatel++;
		return $cestaDalsi;
	}
}