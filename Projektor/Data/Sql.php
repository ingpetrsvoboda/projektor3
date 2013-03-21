<?php
/**
 * Description of Sql
 *
 * @author pes2704
 */
class Projektor_Data_Sql {
    const DEFAULT_VALID_FILTER = "valid=1";

    public $sql;

    public $select;
    public $from;
    public $where;
    public $order;

    public $filter = "";
    public $validFilter = "";
    public $kontextFilter = "";

    public $params = array();

    public function __construct()
    {
        unset($this->sql);      //unsetování způsobí volání getteru a zachová napovídání v IDE
    }

    public function __get($name) {
        if ($name=="sql")
        {
            $sql = "SELECT ".  $this->select." FROM ".$this->from;

            if ($this->where)
            {
                $w = $this->where;
            }
            if ($this->filter)
            {
                $w .= $w ? " AND ":"";
                $w .= $this->filter;
            }
            if ($this->validFilter)
            {
                $w .= $w ? " AND ":"";
                $w .= $this->validFilter;
            }
            if ($this->kontextFilter)
            {
                $w .= $w ? " AND ":"";
                $w .= $this->kontextFilter;
            }

            if ($w) $sql .=" WHERE ".$w;

            if ($this->order) $sql.= " ORDER BY ".$this->order;
            return $sql;
        }
    }

    /**
     * Metoda vytvoří řetězec do klauzule WHERE s pojmenovanými parametry pro prepare a doplní pole parametrů pro bindParams
     * Příklady:
     * where("vek", "=", 2) => "(~vek = :vek)" a parametry ~vek="vek", :vek=2
     * where("jmeno", "LIKE", "Adam", FALSE, TRUE) => "(~jmeno LIKE :jmeno)" a parametry ~jmeno="jmeno", :jmeno="Adam%"
     * where("pismeno", "IV", array("A","B")) => "(~pismeno IN (:pismeno1, :pismeno2))" a parametry ~pismeno="pismeno", :pismeno1="A", :pismeno2="B"
     * @param type $nazev
     * @param type $podminka
     * @param type $hodnota
     * @param type $otevreneZleva
     * @param type $otevreneZprava
     */
    public function where($nazev, $podminka, $hodnota, $otevreneZleva=NULL, $otevreneZprava=NULL)
    {
        $podminka = strtoupper(str_replace(" ", "", $podminka));  //vynechá mezery a převede na velká písmena
        switch ($podminka) {
            case "LIKE":
                if ($otevreneZleva) $hodnota = "%".$hodnota;
                if ($otevreneZprava) $hodnota = $hodnota."%";
                $identifikatorHodnoty = ":".$nazev;
                $this->params[$identifikatorHodnoty] = $hodnota;
                break;
            case "IN":
                if (!is_array($hodnota))
                {
                    $list[0] = $hodnota;
                } else {
                        $list = $hodnota;
                }
                $poprve = TRUE;
                foreach ($list as $key=>$val) {
                    if ($poprve)
                    {
                        $poprve = FALSE;
                        $identifikatorHodnoty = ":".$nazev.$key;
                    } else {
                        $identifikatorHodnoty .= ", :".$nazev.$key;
                    }
                    $this->params[":".$nazev.$key] = $val;

                }
                    if (isset($identifikatorHodnoty))
                    {
                        $identifikatorHodnoty = "(".$identifikatorHodnoty.")";
                    } else {
                        $identifikatorHodnoty = "(NULL)";  //bylo zadáno prázdné pole hodonot
                    }
                break;
            default:
                $identifikatorHodnoty = ":".$nazev;
                $this->params[$identifikatorHodnoty] = $hodnota;
                break;
        }
        if ($this->where)
        {
            $this->where .= " AND (~".$nazev." ".$podminka." ".$identifikatorHodnoty.")";
        } else {
            $this->where = "(~".$nazev." ".$podminka." ".$identifikatorHodnoty.")";
        }
        $this->params["~".$nazev] = $nazev;
    }
}

?>
