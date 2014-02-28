<?php
/**
 * Description of Sql
 *
 * @author pes2704
 */
class Projektor_Model_Sql_Select extends Projektor_Model_Sql_Base {

    // Následující vlastnosti jsou deklarovány public pro našeptávání, ale jsou unsetovány v konstruktoru (voláním reset()), 
    // tím je zajištěno volání setteru __set() pro tyto vlastnosti.
    public $select;
    public $from;
    public $where;
    public $filter = "";
    public $validFilter = "";
    public $kontextFilter = "";
    public $order;

    /**
     * Setter - metoda má specifickou funkci - 
     * @param type $sqlPartName
     * @param type $sqlPartString
     * @return boolean
     */
    public function __set($sqlPartName, $sqlPartString) {
        // where a order se mění jen příslušnou metodou
        switch ($sqlPartName) {
            case 'select':
                $this->select = $sqlPartString;
                return $this->select;
                break;
            case 'from':
                $this->from = $sqlPartString;
                return $this->select;
                break;
            case 'filter':
                $this->filter = $sqlPartString;
                return $this->filter;
                break;
            case 'validFilter':
                $this->validFilter = $sqlPartString;
                return $this->validFilter;
                break;
            case 'kontextFilter':
                $this->kontextFilter = $sqlPartString;
                return $this->kontextFilter;
                break;        
            default:
                return FALSE;
                break;
        }
    }
    
    protected function reset() {
        unset($this->select);
        unset($this->from);
        unset($this->where);
        unset($this->filter);
        unset($this->validFilter);
        unset($this->kontextFilter);
        unset($this->order);
    }
    
    /**
     * Vytvoří a vrátí řetězec SQL SELECT.
     * @return string
     */
    protected function getSql() {
        $sql = "SELECT ".  $this->select." FROM ".$this->from;

        if ($this->where) {
            $w = $this->where;
        }
        if ($this->filter) {
            $w .= $w ? " AND ":"";
            $w .= $this->filter;
        }
        if ($this->validFilter) {
            $w .= $w ? " AND ":"";
            $w .= $this->validFilter;
        }
        if ($this->kontextFilter) {
            $w .= $w ? " AND ":"";
            $w .= $this->kontextFilter;
        }
        if ($w) $sql .=" WHERE ".$w;
        if ($this->order) $sql.= " ORDER BY ".$this->order;
        if ($this->identificators) {
            foreach ($this->identificators as $slot => $identificator) {
                $formattedIdentificator = $this->dbh->getFormattedIdentificator($identificator);
                $sql = str_replace($slot, $formattedIdentificator, $sql);
            }
        }   
        return $sql;
    }
}

?>
