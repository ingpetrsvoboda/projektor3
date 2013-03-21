<?php
class Projektor_Dispatcher_Uzel  //TODO: uzel není dispatcher - nějak přerovnat cestu a uzel
{
    public $uzelRodic;
    public $uzlyPotomci;
    public $trida;
    public $parametry;
    public $vraciHodnoty;

    public function __construct($trida, $uzelRodic, $parametry=null, $vraciHodnoty = FALSE)
    {
        $this->trida = $trida;
        $this->uzelRodic = $uzelRodic;
        $this->parametry = $parametry;
        $this->vraciHodnoty = $vraciHodnoty;
    }

    public function pridejPotomka($trida, $parametry=null)
    {
        $this->uzlyPotomci[] = new Projektor_Dispatcher_Uzel($trida, $this, $parametry, FALSE);
        return end($this->uzlyPotomci);          //metoda vrací právě přidaného potomka
    }

    public function setParametr($parametr, $hodnota)
    {
        if (isset($hodnota))
        {
            $this->parametry[$parametr] = $hodnota;
        } else {
            unset($this->parametry[$parametr]);
        }
        return $this;
    }

    public function getParametr($parametr)
    {
        return $this->parametry[$parametr];
    }

//    public function najdiPotomka($trida, $metoda)
//    {
//        if ($this->uzlyPotomci)
//        {
//            foreach ($this->uzlyPotomci as $uzelPotomek)
//            {
//                if ($uzelPotomek->trida == $trida AND $uzelPotomek->metoda == $metoda) return $uzelPotomek;
//            }
//        }
//        return FALSE;
//    }

    /**
        * Metoda vrací uri do stranky, ve ktere je volana (odkaz na "tuto" stranku)
        * mohla by se jmenovet semUri, ale je vždy užita v akci formuláře, proto název formAction
        * @return string
        */
    public function formAction()
    {
        $prefix = Projektor_Dispatcher_Cesta::getPrefixCesty();
        $koren = Projektor_Dispatcher_Cesta::getKoren();
        $uri = $prefix.serialize($koren);
        return $uri;
    }

    /**
        * Metoda vraci uri do stranky potomka
        * @param string $trida
        * @param string $metoda
        * @param array $parametry
        * @return string
        */
    public function potomekUri($trida, $parametry=null)
    {
        $p = $this->pridejPotomka($trida, $parametry);
        $uri = $this->formAction();
        $p = array_pop($this->uzlyPotomci);
        return $uri;
    }

    /**
        * Metoda vraci uri do stranky rodice
        * @return type
        */
    public function zpetUri()
    {
        // uschová potomky
        $uzlyPotomci = $this->uzelRodic->uzlyPotomci;
        //smaže potomky z potomků rodičovského uzlu a vygeneruje uri
        $this->uzelRodic->uzlyPotomci = $this->najdiASmazUzel($uzlyPotomci);
        $koren = Projektor_Dispatcher_Cesta::getKoren();
        $prefix = Projektor_Dispatcher_Cesta::getPrefixCesty();
        $uri = $prefix.serialize($koren);
        // vrátí uschované potomky
        $this->uzelRodic->uzlyPotomci = $uzlyPotomci;
        return $uri;
    }
//    public function zpetUri()
//    {
//        // přidání značky do rodiče slouří k tomu, aby byl v klonu při odstraňování uzlu smazán uzel - potomek správného rodiče
//        // identický uzel (stejná třída, metody i parametry) se ve stromě může nacházet vícekrát, stačí mít dvě identické stránky v různých místech stromu
//        $this->uzelRodic->znacka = "rodic";     // přidání značky do rodiče
//        $klon = $this->klonuj(Projektor_App_Kontext::getKoren());
//        unset($this->uzelRodic->znacka);    // odstranění značky z rodiče
//        $klon->najdiASmazUzel($klon, $this);
//        $prefix = Projektor_Generator::getPrefixCesty();
//        $uri = $prefix.serialize($klon);
//        return $uri;
//    }

    public function drobeckovaNavigace(Projektor_Dispatcher_Uzel $uzel = null, $navigace = "")
    {
        if (!$uzel) $uzel = $this;

        if ($uzel->uzelRodic)
        {
            if ($navigace) $navigace = " - ".$navigace;
            $navigace = $uzel->uzelRodic->trida . $navigace;
            $navigace = $this->drobeckovaNavigace($uzel->uzelRodic, $navigace);
        }

        return $navigace;

    }


    /**
     * Metoda traverzuje okolo stromu a generuje objekty stránek
     * @param Projektor_Router_Strom_Uzel $uzel
     * @return type
     */
    public function traverzuj(Projektor_App_StatusInterface $appStatus)
    {
//        $generator = new Projektor_Stranka_Generator($this);
//        $generator->generuj();
//        $generator->volejMetoduVychozi();
        $trida = $this->trida;
        $stranka = new $trida($appStatus, $this);
        $stranka->vychozi();

        if ($this->uzlyPotomci)
        {
            foreach($this->uzlyPotomci as $uzelPotomek)
            {
                if ( !$uzelPotomek->vraciHodnoty)
                {
                    $strankaPotomekHtml = $uzelPotomek->traverzuj($appStatus);
                    $strankyPotomciHtml[] = $strankaPotomekHtml;
                }
                //volám metodu rodičovské stránky pro potomka
//                $generator->volejMetoduPotomkovskou($strankaPotomek);
                    $potomkovskaMetoda = "potomek°".$uzelPotomek->trida;
//                    $stranka->strankaPotomek = $strankaPotomek;
                    $stranka->$potomkovskaMetoda();
            }
        } else {
                // není žádný potomek - volám metodu potomek není
//                $generator->volejMetoduPotomekNeni();
                $stranka->potomekNeni();
        }
//        $generator->volejMetoduVzdy();
        $stranka->vzdy();

        if (isset($strankyPotomciHtml))
        {
            $potomciHtml = "";
            foreach ($strankyPotomciHtml as $strankaPotomekHtml)
            {
                $potomciHtml .= $strankaPotomekHtml;
            }
            $stranka->novaPromenna('potomek', $potomciHtml);        // pripojime html kod potomkovskych stranek
        }
        $phptal = Projektor_App_Container::getPhptal();
        $view = new Projektor_View_Phptal($stranka->promenne, $phptal, $trida::SABLONA);
        return $view->render();
    }

    public static function debugUri($uri)
    {
        $cesta = unserialize(str_replace(Projektor_Dispatcher_Cesta::getPrefixCesty(), "", $uri));
        return print_r($cesta, True);
    }


// ################################# PRIVÁTNÍ FUNKCE ########################################################

    private function najdiASmazUzel($uzlyPotomci)
    {
        if (count($uzlyPotomci) == 0)
        {
            $uzlyPotomci = array();
            return $uzlyPotomci;
        }
        foreach($uzlyPotomci as $key=>$uzelPotomek)
        {
//            if ($this->shodaUzlu($uzelPotomek, $this))
            if ($uzelPotomek === $this)
            {
                    $potomciPred = array();
                    $potomciPost = array();
                    if ($key > 0)
                    {
                        $potomciPred = array_slice($uzlyPotomci, 0, $key);
                    }
                    if ($key+1 <  count($uzlyPotomci))
                    {
                        $potomciPost = array_slice($uzlyPotomci, $key+1);
                    }
                    $uzlyPotomci = array_merge($potomciPred, $potomciPost);
                    return $uzlyPotomci;
            }
        }
    }

    private function shodaUzlu(Projektor_Dispatcher_Uzel $uzel1, Projektor_Dispatcher_Uzel $uzel2)
    {
        // When using the comparison operator (==), object variables are compared in a simple manner,
        // namely: Two object instances are equal if they have the same attributes and values, and are instances of the same class.
        // On the other hand, when using the identity operator (===), object variables are identical if and only if they refer to the same
        // instance of the same class.
        if (($uzel1->trida == $uzel2->trida)) return TRUE;
        return FALSE;
    }
}