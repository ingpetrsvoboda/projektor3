<?php
/**
 * Description of Filtr
 *
 * @author Marek Petko
 */
class Projektor_Controller_Page_Element_Filtr
{
    public $pravidla;

    const EQUALS = "=";
    const LIKE = "LIKE";

    /**
     * Konstruktor tridy Filtr.
     * @param mixed Pole objektu typu Filtr_pravidlo.
     */
    public function  __construct($pravidla = null)
    {
        $this->pravidla = $pravidla;
    }

    /**
     * Vytvori seznam filtrovacich pravidel, ktera budou vsechna porovnavat pomoci SQL: =
     * @param mixed Pole nazvu sloupcu tabulky v DB jako klic a filtrovacich hodnot.
     */
    public static function equals($sloupceHodnoty)
    {
        $filtr = new Projektor_Controller_Page_Element_Filtr();
        foreach($sloupceHodnoty as $sloupec => $hodnota)
            if($hodnota)
                $filtr->pravidla[] = new Projektor_Controller_Page_Element_Filtr_Pravidlo($sloupec, Projektor_Controller_Page_Element_Filtr::EQUALS, $hodnota);

        return $filtr;
    }

    /**
     * Vytvori seznam filtrovacich pravidel, ktera budou vsechna porovnavat pomoci SQL: LIKE
     * s tim ze vyhledavana hodnota je otevrena zleva i zprava. Standardne vytvari otevrena
     * filtrovaci pravidla tj. %.
     *
     * @param mixed Pole nazvu sloupcu tabulky v DB jako klic a filtrovacich hodnot.
     */
    public static function like($sloupceHodnoty = NULL)
    {
        $filtr = new Projektor_Controller_Page_Element_Filtr();
        if ($sloupceHodnoty) {
            foreach($sloupceHodnoty as $sloupec => $hodnota)
            if($hodnota)
                $filtr->pravidla[] = new Projektor_Controller_Page_Element_Filtr_Pravidlo($sloupec, Projektor_Controller_Page_Element_Filtr::LIKE, $hodnota, true, true);            
        }

        return $filtr;
    }

    /**
     * Nastavi u sloupce uzavrene vyhledavani hodnoty (%)
     * @param <type> $sloupec Nazev sloupce
     */
    public function striktni($sloupec)
    {
        if($this->pravidla)
            foreach($this->pravidla as $pravidlo)
                if($pravidlo->sloupec == $sloupec)
                    $pravidlo->otevri(false);
    }

    /**
     * Vygeneruje SQL kod filtru.
     * @return <type>
     */
    public function generujSQL()
    {
        if(!$this->pravidla)
            return null;

        foreach($this->pravidla as $pravidlo)
            $pravidlaSQL[] = $pravidlo->generujSQL();

        return implode(" AND ", $pravidlaSQL);
    }


}
?>
