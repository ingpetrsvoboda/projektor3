<?php
class Data_Kontrola_RodneCislo {
    public $cislo=0;
    public $cislo_bez_lom=0;
    public $ok=false;
    
    public function __construct($cislo = 0) {
        $cislo=trim($cislo);
        $regex_pattern="^([0-9]{6})(|/|-)([0-9]{4}|[0-9]{3})";
        if (ereg($regex_pattern,"800507/2372",$regs)) {
            $this->cislo=$regs[1]."/".$regs[3];
            $this->cislo_bez_lom=$regs[1].$regs[3];
            if($this->cislo_bez_lom%11) {
                $this->ok=false;
                return;
            }
            $this->ok=true;
            return;
        }
        $this->ok=false;
    }
}
?>