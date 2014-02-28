<?php
class Projektor_Model_Auto_SAkcePredpokladCollection extends Projektor_Model_Collection
{
    const NAZEV_TRIDY_ITEM = "Projektor_Model_Auto_SAkcePredpokladItem";

###START_AUTOCODE

    /**
     * Factory metoda provádí type hinting pro našeptávání v IDE.
     * Metoda vrací objekt typu Projektor_Model_Auto_SAkcePredpokladItem, prvek kolekce Projektor_Model_Auto_SAkcePredpokladCollection.
     * @param $id $object Tento parametr při volání metody zadávejte.
     * @param Projektor_Model_Auto_SAkcePredpokladItem $object Tento parametr při volání metody nikdy NEZADÁVEJTE. Jedná se pouze o hack pro type hinting.
     * @return \Projektor_Model_Auto_SAkcePredpokladItem
     */
    public function Item($id, Projektor_Model_Auto_SAkcePredpokladItem &$object=NULL){
        $object = new Projektor_Model_Auto_SAkcePredpokladItem($id); //factory na Item
        return $object;
    }

###END_AUTOCODE

    /**
        * Vrati pole neslpnenych predpokladu.
        * @param Ucastnik $ucastnik Ucastnik projektu.
        * @param Projektor_Model_Seznam_STypAkce $sTypAkce Typ akce na kterou se ucastnik chce prihlasit.
        * @return array Vraci pole instanci predpokladu nebo nic pokud jsou vsechny predpoklady splneny.
        */

    public static function nesplnene($ucastnik, $sTypAkce)
    {
        /* Nema-li typ akce predpoklady, pak jsou vsechny splneny.*/
        if(!($predpoklady = self::vypisPro($sTypAkce)))
        return array();

        /* Neni-li ucastnik prihlasen na zadnou akci, potom nemel sanci
            * predpoklady splnit a tudiz ma nesplnene vsechny.	*/
        if(!($akceUcastnika = Akce::vsechnyUcastnika($ucastnik)))
        return $predpoklady;

        $stavyAkciUcastnika = Projektor_Model_Auto_VzbUcastnikAkceCollection::dejStavy($ucastnik, $akceUcastnika);

        $nesplnene = array();
        foreach($predpoklady as $predpoklad)
        {
            $pocitadlo = 0;
            $splnen = false;
            foreach($akceUcastnika as $akce)
            {
                if(($predpoklad->idSTypAkcePredFK == $akce->idSTypAkceFK) && ($predpoklad->idSStavUcastnikAkcePredFK == $stavyAkciUcastnika[$pocitadlo]->id))
                $splnen = true;

                $pocitadlo++;
            }

            if(!$splnen)
            $nesplnene[] =  $predpoklad;
        }

        return $nesplnene;
    }
    /**
        * Vrati vsechny predpoklady pro prihlaseni k akci.
        * @param Projektor_Model_Seznam_STypAkce Typ akce pro vypsani
        * @return array Pole instanci predpokladu
        */

    public static function vypisPro($sTypAkce, $orderBy, $order)
    {
        return self::vypisVse(self::ID_S_TYP_AKCE_FK." = {$sTypAkce->id}", $orderBy, $order);
    }
}
?>
