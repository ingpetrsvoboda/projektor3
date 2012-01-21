<?php
class Router_Cesta
{
	private $cesta;  //TODO: zde jen public jen pro debugování, má být private
	public $ukazatel = -1;

	public function __construct($uri, $tridaKoren)
	{
		if($uri)
		{

			$tildy = explode("~", $uri);
			foreach($tildy as $tilda)
			{
				$tecky = explode(".", $tilda);
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
				else
				$metoda = "main";


					
				$this->cesta[] = new Router_Cesta_Krok($trida, $metoda, $parametry, $tridaKoren);
			}

		}

	}

	public static function nova()
	{
		return new self("");
	}

	public function maDalsi()
	{
		return ($this->cesta && array_key_exists($this->ukazatel+1, $this->cesta));
	}

	public function dalsi()
	{
		if($this->maDalsi())
			$this->ukazatel++; 
		else
			return null;
			
		return $this->cesta[$this->ukazatel];
	}

	public function maPredchozi()
	{
		return array_key_exists($this->ukazatel-1, $this->cesta);
	}

	public function predchozi()
	{
		if(array_key_exists($this->ukazatel-1, $this->cesta))
			$this->ukazatel++;
		else
			return null;
			
		return $this->cesta[$this->ukazatel];
	}
	
	public function posledni()
	{
		return end($this->cesta);
	}

	public function klonuj()
	{
		$klon = clone $this;					// naklonujeme objekt, reference zustanou
		$poleCesta = $klon->cesta;				// ulozime si pole (teoreticky referenci na nej)
		$klon->cesta = array();					// v klonu vytvorime nove pole
		
		if($poleCesta)
			foreach($poleCesta as $krokCesty)		// projdeme puvodni pole
				$klon->cesta[] = clone $krokCesty;	// a do noveho pole vkladame klony objektu v poli puvodnim
		
		return $klon;
	}

	public function pridejKrok(Router_Cesta_Krok $krok)
	{
		$this->cesta[] = $krok;
		return $this;
	}

	public function pridej($trida, $metoda = "main", $parametry = null)
	{
		return $this->pridejKrok(new Router_Cesta_Krok($trida, $metoda, $parametry));
	}

	public function generujUri()
	{ //echo("<pre>"); var_dump($this); echo("</pre>");
		if($this->cesta == null)
			return  $_SERVER["SCRIPT_NAME"]."?" . Generator::getPrefixCesty () . "_cesta=";
		
		foreach($this->cesta as $krokCesty)
		{ 
			if($krokCesty->parametry)
				foreach($krokCesty->parametry as $jmenoParametru => $parametr)
					$apostrofy[] = $jmenoParametru."!".$parametr;
				
			$tildy[] = $krokCesty->trida . "." . $krokCesty->metoda . "(" . ($apostrofy ? implode("'", $apostrofy) : "") . ")";
			
			/* Uklid */
			$apostrofy = "";
		}
	
		return $_SERVER["SCRIPT_NAME"]."?" . Generator::getPrefixCesty () . "_cesta=".implode("~", $tildy);
	}
	
	public function pridejParametr($parametr, $hodnota)
	{		
		$nova = $this->klonuj();
		$nova->posledni()->pridejParametr($parametr, $hodnota);
		return $nova;
	}

	public function generujUriZpet()
	{
		$tildy = explode("~", $this->generujUri());
		if(sizeof($tildy)==1)
			$tildy = explode("?", $tildy[0]);
		array_pop($tildy);
		return implode("~", $tildy);
	}

	public function generujUriDalsi($tridaMetoda, $parametry = null)
	{
		$tridaMetoda = explode(".", $tridaMetoda);
		$budouciCesta = $this->klonuj(); 
		return $budouciCesta->pridej($tridaMetoda[0], $tridaMetoda[1], $parametry)->generujUri();
	}
	
	private function orez()
	{ 
		if($this->cesta == null)
			return false;
			
		$this->cesta = array_slice($this->cesta, 0, $this->ukazatel + 1);
	}
	
	public function sem()
	{
		$cestaSem = $this->klonuj();
		$cestaSem->orez();
		return $cestaSem;
	}

}